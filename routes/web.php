<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorklogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return to_route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('worklogs', WorklogController::class);
    Route::get('worklog-files/{worklogFile}/download', [WorklogController::class, 'downloadFile'])
        ->name('worklog-files.download');
});

require __DIR__.'/auth.php';
