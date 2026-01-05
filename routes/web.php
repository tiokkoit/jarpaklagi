<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesReportPdfController;
use App\Http\Controllers\StockMovementPdfController;

Route::get('/', function () {
    return view('landing');
});

// Keep original welcome page for reference
Route::get('/welcome', function () {
    return view('welcome');
});

// PDF Export Routes (protected by auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/exports/sales-report/pdf', [SalesReportPdfController::class, 'export'])
        ->name('exports.sales-report.pdf');
    Route::get('/exports/stock-movements/pdf', [StockMovementPdfController::class, 'export'])
        ->name('exports.stock-movements.pdf');
});
