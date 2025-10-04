<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Memberships\MembershipController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Classes\ClassController;

Route::middleware(['auth',EnsureUserIsAdmin::class])->group(function () {
    Route::get('memberships-plan/create', [MembershipController::class, 'createMembershipPlan'])
        ->name('memberships-plan.create');
    Route::post('memberships-plan', [MembershipController::class, 'storeMembershipPlan'])->name('memberships-plan.store');
    Route::get('memberships-plan/{plan}/edit', [MembershipController::class, 'edit'])->name('memberships-plan.edit');
    Route::put('memberships-plan/{plan}', [MembershipController::class, 'update'])->name('memberships-plan.update');
    Route::post('membership', [MembershipController::class, 'store'])->name('memberships.store');

    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    Route::get('classes/type/create', [ClassController::class, 'createType'])->name('classes.type.create');
    Route::post('classes/type', [ClassController::class, 'storeType'])->name('classes.type.store');
    Route::get('classes/create', [ClassController::class, 'createClass'])->name('classes.create');
    Route::post('classes', [ClassController::class, 'storeClass'])->name('classes.store');
    Route::get('classes/{class}/schedules/create', [ClassController::class, 'createSchedule'])->name('classes.schedules.create');
    Route::post('classes/{class}/schedules', [ClassController::class, 'storeSchedule'])->name('classes.schedules.store');

});