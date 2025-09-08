<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Memberships\MembershipController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::middleware(['auth',EnsureUserIsAdmin::class])->group(function () {
    Route::get('memberships-plan/create', [MembershipController::class, 'createMembershipPlan'])
        ->name('memberships-plan.create');
    Route::post('memberships-plan', [MembershipController::class, 'storeMembershipPlan'])->name('memberships-plan.store');
    Route::get('memberships-plan/{plan}/edit', [MembershipController::class, 'edit'])->name('memberships-plan.edit');
    Route::put('memberships-plan/{plan}', [MembershipController::class, 'update'])->name('memberships-plan.update');
});
