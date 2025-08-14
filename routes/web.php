<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Memberships\MembershipController;
use App\Http\Controllers\Classes\ClassController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Payments\OpenPayController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::post('memberships', [MembershipController::class, 'store'])->name('memberships.store');

    Route::get('classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('classes/{class}/schedules', [ClassController::class, 'schedules'])->name('classes.schedules');

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::post('payments/openpay/charge', [OpenPayController::class, 'charge'])->name('openpay.charge');

    // Ruta de prueba UI
    Route::get('payments/openpay', function () { return Inertia::render('payments/OpenPayTest'); })->name('openpay.test');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
