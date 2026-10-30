<?php

use App\Http\Controllers\{
    DashboardController,
    CategoryController,
    MineralController,
    CustomerController,
    SupplierController,
    SaleController,
    PurchaseController,
    StockController,
    ReportController,
    ProfileController,
    UserController,
    BranchController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Branch switcher ----
    Route::post('switch-branch', function (\Illuminate\Http\Request $request) {
        $request->session()->put('current_branch_id', $request->input('branch_id') ?: null);
        return back();
    })->name('switch.branch');

    // ---- Inventory ----
    Route::resource('minerals',   MineralController::class);
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('customers',  CustomerController::class)->except('show');
    Route::resource('suppliers',  SupplierController::class)->except('show');

    // ---- Sales ----
    Route::resource('sales', SaleController::class)->except(['edit', 'update']);

    // ---- Purchases ----
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
    Route::post('purchases/{purchase}/receive', [PurchaseController::class, 'receive'])
        ->name('purchases.receive');

    // ---- Stock ----
    Route::get ('stock',                   [StockController::class, 'index'])  ->name('stock.index');
    Route::post('stock/{mineral}/adjust',  [StockController::class, 'adjust']) ->name('stock.adjust');
    Route::get ('stock/{mineral}/history', [StockController::class, 'history'])->name('stock.history');

    // ---- Reports ----
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // ---- Admin ----
    Route::resource('users',    UserController::class);
    Route::resource('branches', BranchController::class);
});

// ---- Profile ----
Route::middleware('auth')->group(function () {
    Route::get   ('/profile', [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch ('/profile', [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';