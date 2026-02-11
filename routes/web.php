<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\ReportController as UserReportController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/reports', [UserReportController::class, 'index'])->name('user.report.index');
Route::get('/report/{code}', [UserReportController::class, 'show'])->name('user.report.show');

// Route::middleware(['auth'])->group(function () {

//     Route::get('/take-report', [UserReportController::class, 'take'])->name('user.report.take');
//     Route::get('/preview-report', [UserReportController::class, 'preview'])->name('user.report.preview');
//     Route::get('/create-report', [UserReportController::class, 'create'])->name('user.report.create');
//     Route::post('/create-report', [UserReportController::class, 'store'])->name('user.report.store');
//
// });

Route::middleware('auth')->group(function () {
    Route::get('/take-report', [UserReportController::class, 'take'])
        ->name('user.report.take');

    Route::get('/preview-report', [UserReportController::class, 'preview'])
        ->name('user.report.preview');

    Route::get('/create-report', [UserReportController::class, 'create'])
        ->name('user.report.create');

    Route::post('/create-report', [UserReportController::class, 'store'])
        ->name('user.report.store');

    Route::get('/report-success', [UserReportController::class, 'success'])->name('user.report.success');

    Route::get('/my-reports', [UserReportController::class, 'myReport'])->name('user.report.my-report');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('user/report')->name('user.report.')->group(function() {
    Route::patch('/{code}/status', [UserReportController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{code}', [UserReportController::class, 'destroy'])->name('destroy');
    Route::get('/{code}/edit', [UserReportController::class, 'edit'])->name('edit');
    Route::put('/{code}/update', [UserReportController::class, 'update'])
        ->name('update');
    Route::patch('/report/{code}/status', [UserReportController::class, 'updateStatus'])
    ->name('user.report.update-status');
});

Route::get('/reports/search', [UserReportController::class, 'search'])
    ->name('user.report.search');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
