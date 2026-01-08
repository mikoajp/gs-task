<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReportController::class, 'dashboard'])->name('dashboard');
Route::post('/process', [ReportController::class, 'process'])->name('process');
Route::get('/inspections', [ReportController::class, 'inspections'])->name('inspections');
Route::get('/failures', [ReportController::class, 'failures'])->name('failures');
