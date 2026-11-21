<?php

use App\Models\CommunityPost;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

Route::view('/', 'home')->name('home');

Route::get('/shop', function (Request $request) {
    $category = $request->string('category')->toString();
    $search = preg_replace('/\s+/u', ' ', trim($request->string('q')->toString())) ?? '';
    $availability = $request->string('availability')->toString();
    $allowedAvailability = ['ready', 'preorder', 'sold_out'];
    $availability = in_array($availability, $allowedAvailability, true) ? $availability : '';
    $sort = $request->string('sort')->toString();
    $allowedSorts = ['latest', 'oldest', 'price_asc', 'price_desc'];
    $sort = in_array($sort, $allowedSorts, true) ? $sort : 'latest';
    $sort = $search !== '' ? 'latest' : $sort;
    $limit = max($request->integer('limit', 6), 6);

    $query = Product::query()->published();

    if ($category !== '') {
        $query->where('category', $category);
    }

    if ($search !== '') {
        $term = '%'.addcslashes($search, '%_\\').'%';
        $query->where('name', 'like', $term);
    }

    match ($sort) {
        'oldest' => $query->orderBy('published_at')->orderBy('id'),
        'price_asc' => $query->orderBy('price')->orderBy('name'),
        'price_desc' => $query->orderByDesc('price')->orderBy('name'),
        default => $query->orderByDesc('published_at')->orderBy('id'),
    };

    $matchingProducts = $query->get()
        ->when($availability !== '', fn ($products) => $products
            ->filter(fn (Product $product): bool => $product->matchesAvailability($availability))
            ->values());
    $totalProducts = $matchingProducts->count();
    $products = $search !== '' ? $matchingProducts : $matchingProducts->take($limit);

    return view('shop.index', [
        'products' => $products,
        'categories' => Product::query()->published()->orderBy('category')->distinct()->pluck('category'),
        'activeCategory' => $category,
        'availability' => $availability,
        'search' => $search,
        'sort' => $sort,
        'limit' => $limit,
        'totalProducts' => $totalProducts,
    ]);
})->name('shop');

Route::get('/shop/{product:slug}', function (Product $product) {
    abort_unless($product->is_active && $product->published_at, 404);

    return view('shop.show', compact('product'));
})->name('shop.show');

Route::post('/shop/{product:slug}/validate-variant', function (Request $request, Product $product) {
    abort_unless($product->is_active && $product->published_at, 404);

    $validated = $request->validate([
        'size' => ['required', 'string', 'max:40'],
        'color' => ['required', 'string', 'max:80'],
        'quantity' => ['required', 'integer', 'min:1', 'max:20'],
    ]);
    $stock = $product->availableStock($validated['size'], $validated['color']);

    if ($stock !== null && $stock < 1) {
        return response()->json([
            'message' => $product->is_preorder
                ? 'Varian ini berstatus Pre-order dan belum dapat dimasukkan ke keranjang.'
                : 'Varian ini sudah Sold Out.',
        ], 409);
    }

    $color = collect($product->colors ?? [])->firstWhere('name', $validated['color']);

    return response()->json([
        'item' => [
            'slug' => $product->slug,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $color['image'] ?? $product->image,
            'stock' => $stock,
        ],
        'quantity' => min($validated['quantity'], $stock ?? 20, 20),
    ]);
})->middleware('throttle:60,1')->name('shop.variant.validate');

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
Route::get('/cart', fn () => view('cart', [
    'whatsappUrl' => SiteSetting::whatsappUrl(),
]))->name('cart');

$cartPayload = function ($user): array {
    return $user->cartItems()
        ->with('product')
        ->get()
        ->filter(fn ($item) => $item->product?->is_active && $item->product?->published_at)
        ->map(function ($item): array {
            $color = collect($item->product->colors ?? [])->firstWhere('name', $item->color);

            return [
                'slug' => $item->product->slug,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'image' => $color['image'] ?? $item->product->image,
                'size' => $item->size,
                'color' => $item->color,
                'quantity' => $item->quantity,
                'stock' => $item->product->availableStock($item->size, $item->color),
            ];
        })
        ->values()
        ->all();
};

Route::post('/cart/validate', function (Request $request) {
    $validated = $request->validate([
        'items' => ['required', 'array', 'min:1', 'max:50'],
        'items.*.slug' => ['required', 'string', 'max:255'],
        'items.*.size' => ['required', 'string', 'max:40'],
        'items.*.color' => ['required', 'string', 'max:80'],
        'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
        'items.*.price' => ['nullable', 'integer', 'min:0'],
    ]);

    $items = [];
    $messages = [];
    $canCheckout = true;
    $changed = false;

    foreach ($validated['items'] as $item) {
        $product = Product::query()->published()->where('slug', $item['slug'])->first();

        if (! $product) {
            $canCheckout = false;
            $messages[] = "{$item['slug']} sudah tidak dipublikasikan. Hapus item ini dari keranjang.";

            continue;
        }

        $stock = $product->availableStock($item['size'], $item['color']);

        if ($stock !== null && $stock < 1) {
            $canCheckout = false;
            $label = $product->is_preorder ? 'tersedia sebagai informasi Pre-order' : 'sudah Sold Out';
            $messages[] = "{$product->name} ({$item['color']} / {$item['size']}) {$label} dan tidak dapat di-checkout.";

            continue;
        }

        $quantity = min($item['quantity'], $stock ?? 20, 20);
        $color = collect($product->colors ?? [])->firstWhere('name', $item['color']);
        $changed = $changed || $quantity !== $item['quantity'] || ($item['price'] ?? null) !== $product->price;

        if ($quantity !== $item['quantity']) {
            $messages[] = "Jumlah {$product->name} disesuaikan menjadi {$quantity} karena stok berubah.";
        }

        if (($item['price'] ?? null) !== null && $item['price'] !== $product->price) {
            $messages[] = "Harga {$product->name} telah diperbarui.";
        }

        $items[] = [
            'slug' => $product->slug,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $color['image'] ?? $product->image,
            'size' => $item['size'],
            'color' => $item['color'],
            'quantity' => $quantity,
            'stock' => $stock,
        ];
    }

    return response()->json([
        'items' => $items,
        'changed' => $changed,
        'can_checkout' => $canCheckout,
        'messages' => $messages,
    ]);
})->middleware('throttle:30,1')->name('cart.validate');

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
                    ->published()
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

        $post = $request->user()->communityPosts()->create([
            'image' => $request->file('image')->store('community', 'public'),
            'caption' => $validated['caption'],
            'status' => 'pending',
        ]);

        Log::info('Postingan komunitas dikirim', [
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
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
