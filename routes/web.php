<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/shop', function (Request $request) {
    $category = $request->string('category')->toString();
    $query = Product::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name');

    if ($category !== '') {
        $query->where('category', $category);
    }

    return view('shop.index', [
        'products' => $query->get(),
        'categories' => Product::query()->where('is_active', true)->orderBy('category')->distinct()->pluck('category'),
        'activeCategory' => $category,
    ]);
})->name('shop');

Route::get('/shop/{product:slug}', function (Product $product) {
    abort_unless($product->is_active, 404);

    return view('shop.show', compact('product'));
})->name('shop.show');

Route::view('/about', 'about')->name('about');
Route::view('/community', 'community')->name('community');
Route::view('/cart', 'cart')->name('cart');

Route::get('/login', fn () => Auth::check() ? redirect()->route('home') : view('auth.login'))->name('login');
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

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');
