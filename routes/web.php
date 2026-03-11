<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminPurchaseOrderController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminSupplierController;
use Illuminate\Support\Facades\Route;

// ── Storefront ───────────────────────────────────────────────
Route::get('/',         [HomeController::class,    'index'])->name('home');
Route::get('/shop',     [ProductController::class, 'index'])->name('shop');
Route::get('/shop/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/about',    [HomeController::class,    'about'])->name('about');
Route::get('/contact',  [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// ── Cart (AJAX + page) ───────────────────────────────────────
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',             [CartController::class, 'index'])->name('index');
    Route::post('/add',         [CartController::class, 'add'])->name('add');
    Route::post('/update',      [CartController::class, 'update'])->name('update');
    Route::post('/remove',      [CartController::class, 'remove'])->name('remove');
    Route::post('/clear',       [CartController::class, 'clear'])->name('clear');
    Route::get('/count',        [CartController::class, 'count'])->name('count');
});

// ── Checkout ─────────────────────────────────────────────────
Route::get('/checkout',             [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout',            [CheckoutController::class, 'submit'])->name('checkout.submit');
Route::get('/order-confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// ── Admin auth ───────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');

    // ── Protected admin routes ───────────────────────────────
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', AdminProductController::class)
            ->except(['show'])
            ->names('products');

        // Categories
        Route::resource('categories', AdminCategoryController::class)
            ->except(['show'])
            ->names('categories');

        // Orders
        Route::get('orders',                    [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}',            [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status',   [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('orders/{order}/payment',  [AdminOrderController::class, 'updatePayment'])->name('orders.payment');

        // Stock management
        Route::get('stock',                             [AdminStockController::class, 'index'])->name('stock.index');
        Route::get('stock/{product}/history',           [AdminStockController::class, 'history'])->name('stock.history');
        Route::post('stock/{product}/adjust',           [AdminStockController::class, 'adjust'])->name('stock.adjust');

        // Purchase orders
        Route::resource('purchase-orders', AdminPurchaseOrderController::class)
            ->names('purchase_orders');
        Route::post('purchase-orders/{purchaseOrder}/receive', [AdminPurchaseOrderController::class, 'receive'])
            ->name('purchase_orders.receive');

        // Suppliers
        Route::resource('suppliers', AdminSupplierController::class)
            ->except(['show'])
            ->names('suppliers');

        // Reports
        Route::get('reports',              [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales',        [AdminReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/products',     [AdminReportController::class, 'products'])->name('reports.products');
        Route::get('reports/categories',   [AdminReportController::class, 'categories'])->name('reports.categories');
        Route::get('reports/profit',       [AdminReportController::class, 'profit'])->name('reports.profit');
        Route::get('reports/export/pdf',   [AdminReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('reports/export/excel', [AdminReportController::class, 'exportExcel'])->name('reports.export.excel');

        // Messages
        Route::get('messages',              [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}',    [AdminMessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        // Settings
        Route::get('settings',  [AdminSettingController::class, 'index'])->name('settings');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});