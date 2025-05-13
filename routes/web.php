<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\MaterialApprovalController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;

// Rute publik yang tidak memerlukan autentikasi
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);

// Semua rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute logout
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/project/store', [ProjectController::class, 'store'])->name('user.project.store');

    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Stock In Routes
    Route::get('/stock-ins', [StockInController::class, 'index'])->name('stock-ins.index');
    Route::post('/stock-ins', [StockInController::class, 'store'])->name('stock-ins.store');
    Route::delete('/stock-ins/{stockIn}', [StockInController::class, 'destroy'])->name('stock-ins.destroy');
    Route::get('/stockin/filter', [StockInController::class, 'filter'])->name('stockin.filter');
    Route::get('/stock-ins/export', [StockInController::class, 'exportExcel'])->name('stock-ins.export');

    // Stock Out Routes
    Route::get('/stock-outs', [StockOutController::class, 'index'])->name('stock-outs.index');
    Route::post('/stock-outs', [StockOutController::class, 'store'])->name('stock-outs.store');
    Route::delete('/stock-outs/{stockOut}', [StockOutController::class, 'destroy'])->name('stock-outs.destroy');
    Route::get('/stockout/filter', [StockOutController::class, 'filter'])->name('stockout.filter');
    Route::get('/stock-outs/export', [StockOutController::class, 'exportExcel'])->name('stock-outs.export');

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Supplier Routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Material Request Routes
    Route::get('/request', [MaterialRequestController::class, 'request'])->name('user.material_request');
    Route::get('/approval', [MaterialApprovalController::class, 'approval'])->name('user.material_approval');
    Route::get('/material-request/{id}/details', [MaterialApprovalController::class, 'getRequestDetails'])->name('user.material_request.details');

    // Approval actions
    Route::post('/material-request/{id}/checker/approve', [MaterialApprovalController::class, 'checkerApprove'])->name('user.material_request.checker.approve');
    Route::post('/material-request/{id}/checker/reject', [MaterialApprovalController::class, 'checkerReject'])->name('user.material_request.checker.reject');
    Route::post('/material-request/{id}/approver/approve', [MaterialApprovalController::class, 'approverApprove'])->name('user.material_request.approver.approve');
    Route::post('/material-request/{id}/approver/reject', [MaterialApprovalController::class, 'approverReject'])->name('user.material_request.approver.reject');

    Route::get('/history', [MaterialRequestController::class, 'history'])->name('user.material_history');
    Route::post('/request', [MaterialRequestController::class, 'store'])->name('user.material_request.store');
    Route::delete('/request/{id}', [MaterialRequestController::class, 'destroy'])->name('user.material_request.destroy');
    Route::post('/request/{id}/approve-checker', [MaterialRequestController::class, 'approveByChecker'])->name('user.material_request.approve_checker');
    Route::post('/request/{id}/approve-approver', [MaterialRequestController::class, 'approveByApprover'])->name('user.material_request.approve_approver');
    Route::get('/material-request/details/{id}', [MaterialRequestController::class, 'getRequestDetails'])->name('user.material_request.details');
    Route::get('material-request/export/{id}', [MaterialRequestController::class, 'exportToExcel'])->name('user.material_request.export');

    // Purchase Order Routes
    Route::get('/purchase/request', [PurchaseOrderController::class, 'request'])->name('user.purchase_request');
    Route::post('/purchase-request/store', [PurchaseOrderController::class, 'store'])->name('user.purchase_request.store');
    Route::get('/purchase/approval', [PurchaseOrderController::class, 'approval'])->name('user.purchase_approval');
    Route::get('/purchase/history', [PurchaseOrderController::class, 'history'])->name('user.purchase_history');
    Route::get('/purchase/request/{id}/details', [PurchaseOrderController::class, 'getRequestDetails'])->name('user.purchase_request.details');

    Route::post('/purchase/request/{id}/checker-checker', [PurchaseOrderController::class, 'checkerApprove'])->name('user.purchase_request.checker.approve');
    Route::post('/purchase/request/{id}/checker-reject', [PurchaseOrderController::class, 'checkerReject'])->name('user.purchase_request.checker.reject');
    Route::post('/purchase/request/{id}/approver-approve', [PurchaseOrderController::class, 'approverApprove'])->name('user.purchase_request.approver.approve');
    Route::post('/purchase/request/{id}/approver-reject', [PurchaseOrderController::class, 'approverReject'])->name('user.purchase_request.approver.reject');
});