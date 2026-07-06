<?php

use App\Http\Controllers\Admin\CatalogueModerationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\CatalogueBrowseController;
use App\Http\Controllers\CatalogueShowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\BranchController;
use App\Http\Controllers\Shop\CatalogueController;
use App\Http\Controllers\Shop\DashboardController as ShopDashboardController;
use App\Http\Controllers\Shop\ShopProfileController;
use App\Http\Controllers\Shop\ShopRegistrationController;
use App\Http\Controllers\Shop\ShopUserController;
use App\Http\Controllers\ShopShowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogues', [CatalogueBrowseController::class, 'index'])->name('catalogues.index');
Route::get('/catalogues/{shop:slug}/{catalogue:slug}', [CatalogueShowController::class, 'show'])->name('catalogues.show');
Route::get('/shops/{shop:slug}', [ShopShowController::class, 'show'])->name('shops.show');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('shop.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/shop/register', [ShopRegistrationController::class, 'create'])->name('shop.register');
    Route::post('/shop/register', [ShopRegistrationController::class, 'store'])->name('shop.register.store');
    Route::get('/shop/dashboard', [ShopDashboardController::class, 'index'])->name('shop.dashboard');
    Route::get('/shop/profile', [ShopProfileController::class, 'edit'])->name('shop.profile.edit');
    Route::put('/shop/profile', [ShopProfileController::class, 'update'])->name('shop.profile.update');
    Route::resource('/shop/branches', BranchController::class)->names('shop.branches');
    Route::resource('/shop/catalogues', CatalogueController::class)->names('shop.catalogues');
    Route::post('/shop/catalogues/{catalogue}/submit', [CatalogueController::class, 'submit'])->name('shop.catalogues.submit');
    Route::post('/shop/catalogues/{catalogue}/publish', [CatalogueController::class, 'publish'])->name('shop.catalogues.publish');
    Route::post('/shop/catalogues/{catalogue}/archive', [CatalogueController::class, 'archive'])->name('shop.catalogues.archive');
    Route::resource('/shop/users', ShopUserController::class)->except(['show'])->names('shop.users');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('/shops', AdminShopController::class)->only(['index', 'show'])->names('shops');
        Route::post('/shops/{shop}/approve', [AdminShopController::class, 'approve'])->name('shops.approve');
        Route::post('/shops/{shop}/reject', [AdminShopController::class, 'reject'])->name('shops.reject');
        Route::post('/shops/{shop}/suspend', [AdminShopController::class, 'suspend'])->name('shops.suspend');
        Route::resource('/catalogues', CatalogueModerationController::class)->only(['index', 'show', 'update'])->names('catalogues');
    });
});

require __DIR__.'/auth.php';
