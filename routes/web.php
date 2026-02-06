<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AppointmentAvailabilityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\UserQuestionnaireController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InfoUserAdminController;
use App\Http\Controllers\ChatAdminController;
use App\Http\Controllers\ChatUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta de prueba temporal para debug
Route::post('/test-questionnaire-submit/{id}', function(\Illuminate\Http\Request $request, $id) {
    \Log::info('TEST ROUTE CALLED - ID: ' . $id);
    \Log::info('Request data:', $request->all());
    return response()->json(['success' => true, 'data' => $request->all()]);
})->middleware('web')->name('test.questionnaire.submit');

//Authenticated Routes
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Create users Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

//Calendar
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
//Ruta para reservar citas
Route::get('/calendar/available-dates', [CalendarController::class, 'getAvailableDates'])->name('calendar.available_dates');
Route::get('/calendar/available-slots/{date}', [CalendarController::class, 'getAvailableSlots'])->name('calendar.available_slots');
Route::post('/calendar/book', [CalendarController::class, 'book'])->name('calendar.book');
//Rutas para cancelar citas
Route::get('/calendar/cancel/{token}', [CalendarController::class, 'showCancelPage'])->name('calendar.cancel');
Route::post('/calendar/cancel/{token}', [CalendarController::class, 'cancelAppointment'])->name('calendar.cancel.process');
Route::post('/calendar/check-appointment', [CalendarController::class, 'checkExistingAppointment'])->name('calendar.check_appointment');

//Dashboard Admin Route
Route::middleware('auth','role:admin')->group(function () {
   Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
   
   // Gestión de citas del calendario
   Route::get('/dashboardAdmin/appointment/{id}', [DashboardAdminController::class, 'getAppointment'])->name('dashboardAdmin.getAppointment');
   Route::put('/dashboardAdmin/appointment/{id}/status', [DashboardAdminController::class, 'updateAppointmentStatus'])->name('dashboardAdmin.updateStatus');
   Route::put('/dashboardAdmin/appointment/{id}/move', [DashboardAdminController::class, 'moveAppointment'])->name('dashboardAdmin.moveAppointment');
   Route::delete('/dashboardAdmin/appointment/{id}', [DashboardAdminController::class, 'deleteAppointment'])->name('dashboardAdmin.deleteAppointment');
   Route::get('/dashboardAdmin/appointments/month', [DashboardAdminController::class, 'getMonthAppointments'])->name('dashboardAdmin.getMonthAppointments');
   Route::get('/dashboardAdmin/appointments/slots', [DashboardAdminController::class, 'getAvailableSlots'])->name('dashboardAdmin.getAvailableSlots');
   Route::get('/dashboardAdmin/appointments/dates', [DashboardAdminController::class, 'getAvailableDates'])->name('dashboardAdmin.getAvailableDates');
   Route::post('/dashboardAdmin/appointments/block', [DashboardAdminController::class, 'blockSlot'])->name('dashboardAdmin.blockSlot');
   Route::delete('/dashboardAdmin/appointments/unblock/{id}', [DashboardAdminController::class, 'unblockSlot'])->name('dashboardAdmin.unblockSlot');
});

//Appointment Availability  Admin Routes
Route::middleware('auth','role:admin')->group(function () {
    Route::get('/admin/appointments', [AppointmentAvailabilityController::class, 'index'])->name('admin_appointments.index');
    Route::get('/admin/appointments/create', [AppointmentAvailabilityController::class, 'create'])->name('admin_appointments.create');
    Route::post('/admin/appointments', [AppointmentAvailabilityController::class, 'store'])->name('admin_appointments.store');
    Route::get('/admin/appointments/batch/{batch_id}/edit', [AppointmentAvailabilityController::class, 'edit'])->name('admin_appointments.edit');
    Route::put('/admin/appointments/batch/{batch_id}', [AppointmentAvailabilityController::class, 'updateBatch'])->name('admin_appointments.updateBatch');
    Route::delete('/admin/appointments/batch/{batch_id}', [AppointmentAvailabilityController::class, 'destroyBatch'])->name('admin_appointments.destroyBatch');
    Route::post('/admin/appointments/check-conflicts', [AppointmentAvailabilityController::class, 'checkTimeConflicts'])->name('admin_appointments.checkConflicts');
});

//Product Routes Admin
Route::middleware('auth','role:admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

//Questionnaire Routes
Route::middleware('auth','role:admin')->group(function () {
    Route::get('/questionnaire', [QuestionnaireController::class, 'index'])->name('questionnaire.index');
    Route::get('/questionnaire/create', [QuestionnaireController::class, 'create'])->name('questionnaire.create');
    Route::post('/questionnaire', [QuestionnaireController::class, 'store'])->name('questionnaire.store');
    Route::get('/questionnaire/{id}/edit', [QuestionnaireController::class, 'edit'])->name('questionnaire.edit');
    Route::put('/questionnaire/{id}', [QuestionnaireController::class, 'update'])->name('questionnaire.update');
    Route::delete('/questionnaire/{id}', [QuestionnaireController::class, 'destroy'])->name('questionnaire.destroy');
    
    // Asignación de cuestionarios a usuarios
    Route::get('/questionnaire/{id}/assign', [QuestionnaireController::class, 'showAssign'])->name('questionnaire.assign');
    Route::post('/questionnaire/{id}/assign', [QuestionnaireController::class, 'assign'])->name('questionnaire.assign.store');
    Route::delete('/questionnaire/{id}/unassign/{userId}', [QuestionnaireController::class, 'unassign'])->name('questionnaire.unassign');
    
    // Ver respuestas
    Route::get('/questionnaire/{id}/responses', [QuestionnaireController::class, 'responses'])->name('questionnaire.responses');
    Route::get('/questionnaire/{id}/responses/{userId}', [QuestionnaireController::class, 'userResponses'])->name('questionnaire.user-responses');
});

//Dashboard DashboardUser
Route::middleware('auth','role:customer')->group(function () {
    Route::get('/dashboardUser', [DashboardUserController::class, 'index'])->name('dashboardUser.index');
    Route::get('/dashboardUser/appointment/{id}', [DashboardUserController::class, 'getAppointment'])->name('dashboardUser.getAppointment');
    Route::post('/dashboardUser/book', [DashboardUserController::class, 'bookAppointment'])->name('dashboardUser.book');
    Route::post('/dashboardUser/appointment/{id}/cancel', [DashboardUserController::class, 'cancelAppointment'])->name('dashboardUser.cancelAppointment');
    
    // Cuestionarios para usuarios
    Route::get('/my-questionnaires', [UserQuestionnaireController::class, 'index'])->name('user-questionnaire.index');
    Route::get('/my-questionnaires/{id}', [UserQuestionnaireController::class, 'show'])->name('user-questionnaire.show');
    Route::post('/my-questionnaires/{id}', [UserQuestionnaireController::class, 'store'])->name('user-questionnaire.store');
    Route::get('/my-questionnaires/{id}/responses', [UserQuestionnaireController::class, 'viewResponses'])->name('user-questionnaire.responses');
});

//Notifications Routes (para todos los usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/dropdown', [NotificationController::class, 'getDropdownNotifications'])->name('notifications.dropdown');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear/read', [NotificationController::class, 'destroyAllRead'])->name('notifications.destroyAllRead');
});

//Rutas Info User Admin 
Route::middleware('auth','role:admin')->group(function () {
    Route::get('/info-user-admin/{userId}', [InfoUserAdminController::class, 'show'])->name('info-user-admin.show');
    Route::get('/info-user-admin/{userId}/questionnaire/{questionnaireUserId}', [InfoUserAdminController::class, 'showQuestionnaireResponses'])->name('info-user-admin.questionnaire-responses');
    
    // Gestión de documentos
    Route::post('/info-user-admin/{userId}/documents/upload', [InfoUserAdminController::class, 'uploadDocument'])->name('info-user-admin.documents.upload');
    Route::delete('/info-user-admin/{userId}/documents/{documentId}', [InfoUserAdminController::class, 'deleteDocument'])->name('info-user-admin.documents.delete');
});



//Chat Admin Routes
Route::middleware('auth','role:admin')->group(function () {
    Route::get('/chat-admin', [ChatAdminController::class, 'index'])->name('chat-admin.index');
    Route::get('/chat-admin/{conversationId}', [ChatAdminController::class, 'show'])->name('chat-admin.show');
    Route::post('/chat-admin/start', [ChatAdminController::class, 'startConversation'])->name('chat-admin.start');
    Route::post('/chat-admin/{conversationId}/send', [ChatAdminController::class, 'sendMessage'])->name('chat-admin.send');
    Route::get('/chat-admin/{conversationId}/new-messages', [ChatAdminController::class, 'getNewMessages'])->name('chat-admin.new-messages');
    Route::get('/chat-admin-unread-total', [ChatAdminController::class, 'getUnreadTotal'])->name('chat-admin.unread-total');
    Route::delete('/chat-admin/{conversationId}/message/{messageId}', [ChatAdminController::class, 'deleteMessage'])->name('chat-admin.delete-message');
});

//Chat User Routes
Route::middleware('auth','role:customer')->group(function () {
    Route::get('/chat-user', [ChatUserController::class, 'index'])->name('chat-user.index');
    Route::get('/chat-user/{conversationId}', [ChatUserController::class, 'show'])->name('chat-user.show');
    Route::post('/chat-user/{conversationId}/send', [ChatUserController::class, 'sendMessage'])->name('chat-user.send');
    Route::get('/chat-user/{conversationId}/new-messages', [ChatUserController::class, 'getNewMessages'])->name('chat-user.new-messages');
    Route::get('/chat-user-unread-total', [ChatUserController::class, 'getUnreadTotal'])->name('chat-user.unread-total');
    Route::delete('/chat-user/{conversationId}/message/{messageId}', [ChatUserController::class, 'deleteMessage'])->name('chat-user.delete-message');
});

require __DIR__.'/auth.php';
