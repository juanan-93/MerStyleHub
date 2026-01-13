<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Dashboard Admin Route
Route::middleware('auth')->group(function () {
   Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
});

require __DIR__.'/auth.php';
