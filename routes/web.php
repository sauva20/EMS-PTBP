<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('data-entry.index');
});

// Temporarily removed auth middleware
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/data-entry', [DataEntryController::class, 'index'])->name('data-entry.index');
Route::post('/data-entry', [DataEntryController::class, 'store'])->name('data-entry.store');
Route::post('/data-entry/quota', [DataEntryController::class, 'storeQuota'])->name('data-entry.quota');

Route::get('/reports/accumulative', [ReportController::class, 'accumulative'])->name('reports.accumulative');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
