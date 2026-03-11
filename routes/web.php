<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/product/{product}/review', [\App\Http\Controllers\Front\ProductReviewController::class, 'store'])->name('reviews.store');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');

// Front Auth Routes
use App\Http\Controllers\Front\AuthController;
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/user/logout', [AuthController::class, 'logout'])->name('user.logout');

// User Dashboard Routes
use App\Http\Controllers\Front\UserController;
Route::middleware(['auth'])->prefix('my-account')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [UserController::class, 'viewOrder'])->name('orders.show');
    Route::get('/helpline', [UserController::class, 'helpline'])->name('helpline');
    Route::post('/helpline', [UserController::class, 'storeTicket'])->name('helpline.store');

    // Invoice Route
    Route::get('/orders/{id}/invoice', [\App\Http\Controllers\Front\InvoiceController::class, 'download'])->name('orders.invoice');

    // Address Routes
    Route::resource('addresses', \App\Http\Controllers\Front\AddressController::class);

    // Notification Routes
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\Front\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/mark-all', [\App\Http\Controllers\Front\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});

// Admin Routes
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\HomeSettingController;

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin', 'prevent-back-history'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders', [AdminDashboardController::class, 'getOrders'])->name('dashboard.orders');
    Route::resource('categories', AdminCategoryController::class);
    Route::delete('/products/image/{productImage}', [AdminProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::resource('products', AdminProductController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::get('/support', [App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::patch('/support/{id}/status', [App\Http\Controllers\Admin\SupportController::class, 'updateStatus'])->name('support.updateStatus');
    // Home Settings
    Route::get('/home-settings', [HomeSettingController::class, 'index'])->name('home-settings.index');
    Route::put('/home-settings', [HomeSettingController::class, 'update'])->name('home-settings.update');
});

// Temporary Route for Shared Hosting Optimization
Route::get('/optimize-server', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('optimize');
    \Illuminate\Support\Facades\Artisan::call('view:cache');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    return 'Server optimized successfully! Windows paths cleared and Linux cache generated.';
});
