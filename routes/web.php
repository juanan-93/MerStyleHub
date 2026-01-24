<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AppointmentAvailabilityController;
use App\Http\Controllers\ProductController;
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
//Ruta para reservar citas
Route::get('/calendar/available-dates', [CalendarController::class, 'getAvailableDates'])->name('calendar.available_dates');
Route::get('/calendar/available-slots/{date}', [CalendarController::class, 'getAvailableSlots'])->name('calendar.available_slots');
Route::post('/calendar/book', [CalendarController::class, 'book'])->name('calendar.book');

//Dashboard Admin Route
Route::middleware('auth')->group(function () {
   Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
});

//Appointment Availability Routes
Route::middleware('auth')->group(function () {
    Route::get('/admin/appointments', [AppointmentAvailabilityController::class, 'index'])->name('admin_appointments.index');
    Route::get('/admin/appointments/create', [AppointmentAvailabilityController::class, 'create'])->name('admin_appointments.create');
    Route::post('/admin/appointments', [AppointmentAvailabilityController::class, 'store'])->name('admin_appointments.store');
    Route::get('/admin/appointments/batch/{batch_id}/edit', [AppointmentAvailabilityController::class, 'edit'])->name('admin_appointments.edit');
    Route::put('/admin/appointments/batch/{batch_id}', [AppointmentAvailabilityController::class, 'updateBatch'])->name('admin_appointments.updateBatch');
    Route::delete('/admin/appointments/batch/{batch_id}', [AppointmentAvailabilityController::class, 'destroyBatch'])->name('admin_appointments.destroyBatch');
});

//Product Routes
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


require __DIR__.'/auth.php';
