<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Memberships\MembershipController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Classes\ClassController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Payments\PaymentController;

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
    
    Route::resource('events', EventController::class)->names([
        'index' => 'events.index',
        'create' => 'events.create',
        'store' => 'events.store',
        'show' => 'events.show',
    ]);
    Route::get('classes/type/create', [ClassController::class, 'createType'])->name('classes.type.create');
    Route::post('classes/type', [ClassController::class, 'storeType'])->name('classes.type.store');
    Route::get('classes/create', [ClassController::class, 'createClass'])->name('classes.create');
    Route::get('classes/{class}/edit', [ClassController::class, 'editClass'])->name('classes.edit');
    Route::put('classes/{class}', [ClassController::class, 'updateClass'])->name('classes.update');
    Route::post('classes', [ClassController::class, 'storeClass'])->name('classes.store');
    Route::get('classes/{class}/schedules/create', [ClassController::class, 'createSchedule'])->name('classes.schedules.create');
    Route::post('classes/{class}/schedules', [ClassController::class, 'storeSchedule'])->name('classes.schedules.store');

    Route::resource('branches', BranchController::class)->names([
        'index' => 'admin.branches.index',
        'create' => 'admin.branches.create',
        'store' => 'admin.branches.store',
        'edit' => 'admin.branches.edit',
        'update' => 'admin.branches.update',
        'destroy' => 'admin.branches.destroy',
    ]);

    Route::resource('promotions', PromotionController::class)->names([
        'index' => 'admin.promotions.index',
        'create' => 'admin.promotions.create',
        'store' => 'admin.promotions.store',
        'edit' => 'admin.promotions.edit',
        'update' => 'admin.promotions.update',
        'destroy' => 'admin.promotions.destroy',
    ]);

    Route::resource('categories', CategoryController::class)->except('index')->names([
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    Route::resource('exercises', ExerciseController::class)->except('index')->names([
        'create' => 'admin.exercises.create',
        'store' => 'admin.exercises.store',
        'edit' => 'admin.exercises.edit',
        'update' => 'admin.exercises.update',
        'destroy' => 'admin.exercises.destroy',
    ]);

    Route::prefix('payments')->name('admin.payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::patch('/{payment}/refund', [PaymentController::class, 'refund'])->name('refund');
        Route::get('/export/csv', [PaymentController::class, 'export'])->name('export');
        Route::get('/reports/monthly', [PaymentController::class, 'monthlyReport'])->name('monthly-report');
    });
});