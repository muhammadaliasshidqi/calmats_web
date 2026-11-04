<?php

use App\Models\CommunityPost;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

Route::view('/', 'home')->name('home');

Route::get('/shop', function (Request $request) {
    $category = $request->string('category')->toString();
    $search = trim($request->string('q')->toString());
    $sort = $request->string('sort')->toString();
    $allowedSorts = ['default', 'latest', 'name', 'price_asc', 'price_desc'];
    $sort = in_array($sort, $allowedSorts, true) ? $sort : 'default';
    $limit = min(max($request->integer('limit', 6), 6), 24);

    $query = Product::query()->where('is_active', true);

    if ($category !== '') {
        $query->where('category', $category);
    }

    if ($search !== '') {
        $query->where(function ($query) use ($search) {
            $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('material', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    match ($sort) {
        'latest' => $query->latest(),
        'name' => $query->orderBy('name'),
        'price_asc' => $query->orderBy('price')->orderBy('name'),
        'price_desc' => $query->orderByDesc('price')->orderBy('name'),
        default => $query->orderBy('sort_order')->orderBy('name'),
    };

    $totalProducts = (clone $query)->count();

    return view('shop.index', [
        'products' => $query->limit($limit)->get(),
        'categories' => Product::query()->where('is_active', true)->orderBy('category')->distinct()->pluck('category'),
        'activeCategory' => $category,
        'search' => $search,
        'sort' => $sort,
        'limit' => $limit,
        'totalProducts' => $totalProducts,
    ]);
})->name('shop');

Route::get('/shop/{product:slug}', function (Product $product) {
    abort_unless($product->is_active, 404);

    return view('shop.show', compact('product'));
})->name('shop.show');

Route::view('/about', 'about')->name('about');
Route::get('/community', function (Request $request) {
    return view('community', [
        'communityPosts' => CommunityPost::query()
            ->approved()
            ->with('user')
            ->latest('published_at')
            ->limit(12)
            ->get(),
        'mySubmissions' => $request->user()
            ? $request->user()->communityPosts()->latest()->limit(6)->get()
            : collect(),
    ]);
})->name('community');
Route::view('/cart', 'cart')->name('cart');

$cartPayload = function ($user): array {
    return $user->cartItems()
        ->with('product')
        ->get()
        ->filter(fn ($item) => $item->product?->is_active)
        ->map(fn ($item): array => [
            'slug' => $item->product->slug,
            'name' => $item->product->name,
            'price' => $item->product->price,
            'image' => $item->product->image,
            'size' => $item->size,
            'color' => $item->color,
            'quantity' => $item->quantity,
            'stock' => $item->product->availableStock($item->size, $item->color),
        ])
        ->values()
        ->all();
};

Route::middleware('auth')->group(function () use ($cartPayload) {
    Route::get('/cart/data', fn (Request $request) => response()->json([
        'items' => $cartPayload($request->user()),
    ]))->name('cart.data');

    Route::put('/cart/data', function (Request $request) use ($cartPayload) {
        $validated = $request->validate([
            'items' => ['present', 'array', 'max:50'],
            'items.*.slug' => ['required', 'string', 'max:255'],
            'items.*.size' => ['required', 'string', 'max:40'],
            'items.*.color' => ['required', 'string', 'max:80'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $request->user()->cartItems()->delete();

            foreach ($validated['items'] as $item) {
                $product = Product::query()
                    ->where('slug', $item['slug'])
                    ->where('is_active', true)
                    ->first();

                if (! $product) {
                    continue;
                }

                $stock = $product->availableStock($item['size'], $item['color']);

                if ($stock !== null && $stock < 1) {
                    continue;
                }

                $quantity = min($item['quantity'], 20, $stock ?? 20);

                $request->user()->cartItems()->updateOrCreate([
                    'product_id' => $product->id,
                    'size' => $item['size'],
                    'color' => $item['color'],
                ], [
                    'quantity' => $quantity,
                ]);
            }
        });

        return response()->json(['items' => $cartPayload($request->user())]);
    })->name('cart.data.update');
});

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

    Route::get('/reset-password/{token}', fn (Request $request, string $token) => view('auth.reset-password', [
        'token' => $token,
        'email' => $request->string('email')->toString(),
    ]))->name('password.reset');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('status', 'Selamat datang kembali.');
    }

    return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
})->middleware('throttle:6,1')->name('login.store');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create($validated);
    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('profile')->with('status', 'Akun berhasil dibuat. Selamat datang di CALMATS.');
})->middleware(['guest', 'throttle:6,1'])->name('register.store');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => ['required', 'email']]);
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)])->onlyInput('email');
})->middleware(['guest', 'throttle:6,1'])->name('password.email');

Route::post('/reset-password', function (Request $request) {
    $credentials = $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $status = Password::reset($credentials, function (User $user, string $password) {
        $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
    });

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)])->withInput($request->only('email'));
})->middleware(['guest', 'throttle:6,1'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::post('/community', function (Request $request) {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['required', 'string', 'max:500'],
        ], [
            'image.required' => 'Pilih foto yang ingin dibagikan.',
            'image.image' => 'File yang dipilih harus berupa gambar.',
            'image.mimes' => 'Gunakan foto JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran foto maksimal 5 MB.',
            'caption.required' => 'Tuliskan cerita singkat untuk foto ini.',
            'caption.max' => 'Caption maksimal 500 karakter.',
        ]);

        $request->user()->communityPosts()->create([
            'image' => $request->file('image')->store('community', 'public'),
            'caption' => $validated['caption'],
            'status' => 'pending',
        ]);

        return redirect(route('community').'#share')
            ->with('status', 'Kiriman berhasil dikirim dan sedang menunggu moderasi admin.');
    })->middleware('throttle:5,1')->name('community.store');

    Route::view('/profile', 'auth.profile')->name('profile');

    Route::patch('/profile', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'Informasi profil berhasil diperbarui.');
    })->name('profile.update');

    Route::put('/profile/password', function (Request $request) {
        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update(['password' => $validated['password']]);

        return back()->with('status', 'Kata sandi berhasil diperbarui.');
    })->name('profile.password');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');
