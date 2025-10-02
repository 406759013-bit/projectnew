<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Manager\StaffController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Staff\MemberController as StaffMemberController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect ตาม role
    if ($user->isAdmin()) {
        return redirect()->route('admin.users.index');
    } elseif ($user->isManager()) {
        return redirect()->route('manager.reports.index');
    } elseif ($user->isStaff()) {
        return redirect()->route('staff.members.index');
    } else {
        return redirect()->route('member.profile.edit');
    }
})->middleware(['auth', 'approved'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'approved', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
});

// Manager Routes
Route::middleware(['auth', 'approved', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::resource('staffs', StaffController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// Staff Routes
Route::middleware(['auth', 'approved', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('members', [StaffMemberController::class, 'index'])->name('members.index');
    Route::get('members/{member}', [StaffMemberController::class, 'show'])->name('members.show');
    Route::post('members/{member}/approve', [StaffMemberController::class, 'approve'])->name('members.approve');
    Route::post('members/{member}/reject', [StaffMemberController::class, 'reject'])->name('members.reject');
    Route::get('members/{member}/edit', [StaffMemberController::class, 'edit'])->name('members.edit');
    Route::put('members/{member}', [StaffMemberController::class, 'update'])->name('members.update');
});

// Member Routes
Route::middleware(['auth', 'approved', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('profile', [MemberProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [MemberProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
