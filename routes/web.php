<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    // $user->token
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
// Route::get('/products/{id}', function ($id) {
//     return Inertia::render('Products/Details', [
//         'productId' => (int) $id,
//     ]);
// })->name('products.show');

Route::get('products/{id}', [ProductController::class,'show'])->name('products.show');
// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('checkout', function () {
        return Inertia::render('Checkout/Index');
    })->name('checkout');
    Route::get('products', [ProductController::class,'index'])->name('products.index');
    Route::get('products/create', [ProductController::class,'create'])->name('products.create');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
