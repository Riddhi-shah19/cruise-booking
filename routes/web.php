<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\CruiseController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Passenger\PassengerAuthController;
use App\Http\Controllers\Passenger\HomeController;
use App\Http\Controllers\Passenger\PassengerController;
use App\Http\Controllers\Passenger\BookingController;
use App\Http\Controllers\Passenger\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login']);

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    // Route::get('/schedules', [AdminController::class, 'schedules'])->name('admin.schedules');
    Route::get('/routes', [AdminController::class, 'routes'])->name('admin.routes');
    Route::get('/cruise', [AdminController::class, 'cruise'])->name('admin.cruise');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    // Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::get('/feedbacks', [AdminController::class, 'feedbacks'])->name('admin.feedbacks');
    Route::get('/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/status/{id}/{status}', [UserController::class, 'toggleStatus'])->name('admin.passengers.toggleStatus');

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('admin.schedules');
    Route::put('/schedules/{id}/update', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::post('/schedules/range', [ScheduleController::class, 'storeRange'])->name('schedule.range.store');

    Route::get('/routes', [RouteController::class, 'index'])->name('admin.routes');
    Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
    Route::put('/routes/{id}', [RouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{id}', [RouteController::class, 'destroy'])->name('routes.destroy');

    Route::get('/cruise', [CruiseController::class, 'index'])->name('admin.cruise');
    Route::post('/cruise', [CruiseController::class, 'store'])->name('cruise.store');
    Route::put('/cruise/{id}', [CruiseController::class, 'update'])->name('cruise.update');
    Route::delete('/cruise/{id}', [CruiseController::class, 'destroy'])->name('cruise.destroy');

    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('admin.feedbacks');
    Route::post('/feedbacks/{id}/reply', [FeedbackController::class, 'reply'])->name('feedback.reply');

    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/report/{id}', [ReportController::class, 'downloadReport'])->name('admin.report.view');
   
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments');

});

Route::get('/', [HomeController::class, 'index'])->name('dashboard');
Route::get('/get-available-rooms', [BookingController::class, 'getAvailableRooms']);
   
Route::prefix('passenger')->group(function () {
    Route::get('/signup', [PassengerAuthController::class, 'showSignupForm'])->name('signup.form');
    Route::post('/signup', [PassengerAuthController::class, 'register'])->name('signup.register');
    Route::get('/signin', [PassengerAuthController::class, 'showSigninForm'])->name('login');
    Route::post('/signin', [PassengerAuthController::class, 'signin'])->name('signin');


    Route::match(['get', 'post'], '/dashboard', [PassengerController::class, 'index'])->name('passenger.dashboard');
   
    Route::get('booking', [BookingController::class, 'showBookingPage'])->name('passenger.booking');
    Route::get('bookings', [TicketController::class, 'index'])->name('ticket.paid');
    Route::get('feedback', [App\Http\Controllers\Passenger\FeedbackController::class, 'index'])->name('passenger.feedback');
    Route::post('logout', [PassengerController::class, 'logout'])->name('passenger.logout');

    Route::post('/initiate-payment', [BookingController::class, 'initiatePayment'])->name('payment.initiate');
    Route::post('/verify-payment', [BookingController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/print/{id}', [TicketController::class, 'printTicket'])->name('passenger.print');
    Route::post('/tickets/modify', [TicketController::class, 'modify'])->name('passenger.tickets.modify');

    Route::post('/feedbacks', [App\Http\Controllers\Passenger\FeedbackController::class, 'store'])->name('feedbacks.store');

});