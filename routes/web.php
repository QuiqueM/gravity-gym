<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Memberships\MembershipController;
use App\Http\Controllers\Classes\ClassController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Payments\OpenPayController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Payments\MercadoPagoController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('memberships', [MembershipController::class, 'index'])->name('memberships.index');
    
    Route::get('classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('classes/{class}/schedules', [ClassController::class, 'schedules'])->name('classes.schedules');
    
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('attendance/list/{schedule}', [AttendanceController::class, 'show'])->name('attendance.show');
    
    Route::post('payments/openpay/charge', [OpenPayController::class, 'charge'])->name('openpay.charge');
    
    // Ruta de prueba UI
    Route::get('payments/openpay', function () { return Inertia::render('payments/OpenPayTest'); })->name('openpay.test');
    
    Route::post('classes/{schedule}/create/attend', [ClassController::class, 'storeClassAttendance'])->name('classes.schedules.attend');
    
    Route::get('profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('profile/avatar', [UserController::class, 'updateAvatarProfile'])->name('profile.avatar.update');
    Route::patch('profile/{user}', [UserController::class, 'update'])->name('profile.update');
    Route::delete('profile/{user}', [UserController::class, 'destroy'])->name('profile.destroy');

    Route::get('my-membership', [MembershipController::class, 'myMembership'])->name('membership.my.show');

    // Rutas de Mercado Pago
    Route::post('/payments/mercadopago/membership', [MercadoPagoController::class, 'createMembershipPayment'])->middleware('auth')->name('payments.mercadopago.membership');
    Route::post('/payments/mercadopago/webhook', [MercadoPagoController::class, 'handleWebhook'])->name('payments.mercadopago.webhook');
    Route::get('/payments/mercadopago/success', [MercadoPagoController::class, 'success'])->name('payments.mercadopago.success');
    Route::get('/payments/mercadopago/failure', [MercadoPagoController::class, 'failure'])->name('payments.mercadopago.failure');
    Route::get('/payments/mercadopago/pending', [MercadoPagoController::class, 'pending'])->name('payments.mercadopago.pending');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
