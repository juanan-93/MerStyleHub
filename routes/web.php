<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AppointmentAvailabilityController;
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

//Create users
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
});

//Calendar
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');


//Dashboard Admin Route
Route::middleware('auth')->group(function () {
   Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
});

//Appointment Availability Routes
Route::middleware('auth')->group(function () {
    Route::get('/admin/appointments', [AppointmentAvailabilityController::class, 'index'])->name('admin_appointments.index');
    Route::get('/admin/appointments/create', [AppointmentAvailabilityController::class, 'create'])->name('admin_appointments.create');
});

require __DIR__.'/auth.php';
