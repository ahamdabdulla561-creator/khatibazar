<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderTrackingController;
use App\Http\Controllers\Frontend\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Frontend\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\ComboOfferController as AdminComboOfferController;
use App\Http\Controllers\Admin\CourierServiceController as AdminCourierServiceController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| FRONTEND PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', function ($slug) {
    return redirect()->route('products.index', ['category' => $slug]);
})->name('category.show');
Route::get('/api/search-suggestions', [ProductController::class, 'searchSuggestions'])->name('api.search_suggestions');
Route::get('/api/products/{id}/quick-view', [ProductController::class, 'quickView'])->name('api.product_quick_view');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Order Tracking Route
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('order.track');
Route::post('/track-order/{id}/message', [OrderTrackingController::class, 'sendOrderMessage'])->name('order.track.message');

/*
|--------------------------------------------------------------------------
| CUSTOMER AUTHENTICATION & DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

Route::middleware('customer')->prefix('account')->name('customer.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [CustomerDashboardController::class, 'orderDetail'])->name('orders.show');
    Route::post('/orders/{id}/message', [CustomerDashboardController::class, 'sendOrderMessage'])->name('orders.message');
    Route::post('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [CustomerDashboardController::class, 'changePassword'])->name('password.change');
});

/*
|--------------------------------------------------------------------------
| ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/
// Redirect Aliases
Route::redirect('/admin', '/admin/login');
Route::redirect('/amin', '/admin/login');
Route::redirect('/amin/login', '/admin/login');

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest Auth
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Categories Management
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        // Brands Management
        Route::resource('brands', AdminBrandController::class)->except(['show']);

        // Product Management
        Route::resource('products', AdminProductController::class);
        Route::post('products/{id}/toggle-super-offer', [AdminProductController::class, 'toggleSuperOffer'])->name('products.toggle_super_offer');
        Route::post('products/{id}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle_featured');

        // Hero Banners & Slider Management
        Route::resource('banners', AdminBannerController::class);

        // Combo Offer Cards Management
        Route::resource('combo-offers', AdminComboOfferController::class);
        Route::post('combo-offers/{id}/toggle-status', [AdminComboOfferController::class, 'toggleStatus'])->name('combo_offers.toggle_status');

        // Courier Services Management
        Route::resource('courier-services', AdminCourierServiceController::class);
        Route::post('courier-services/{id}/toggle-status', [AdminCourierServiceController::class, 'toggleStatus'])->name('courier_services.toggle_status');

        // Orders & Reports Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
        Route::post('/orders/{id}/message', [AdminOrderController::class, 'sendOrderMessage'])->name('orders.message');
        Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');

        // Customers Management
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/{id}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle_status');

        // Site Settings Management
        Route::get('/settings', [AdminSiteSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');

        // Audit Logs
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit_logs.index');
    });
});

/*
|--------------------------------------------------------------------------
| DYNAMIC UPLOADED STORAGE IMAGE SERVING FALLBACK
|--------------------------------------------------------------------------
| Guarantees 100% image display on live hosting/cPanel regardless of symlinks
*/
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        $fullPath = public_path('storage/' . $path);
    }
    if (file_exists($fullPath) && !is_dir($fullPath)) {
        $mime = mime_content_type($fullPath) ?: 'image/jpeg';
        return response()->file($fullPath, ['Content-Type' => $mime]);
    }
    abort(404);
})->where('path', '.*');

