<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestsController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\ReservationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

route::get('/', [HomeController::class, 'index']);

route::get('/redirect', [HomeController::class, 'redirect'])->middleware('auth','verified');

route::get('/rooms', [HomeController::class, 'rooms']);

route::get('/choose', [HomeController::class, 'choose']);

// route::get('/generate_pdf', [HomeController::class, 'generate_PDF']);

route::post('/details', [HomeController::class, 'details']);

route::post('/add_reservation', [HomeController::class, 'add_reservation']);

route::get('/confirmation', [HomeController::class, 'view_confirmation']);  

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
/*---------------------------------------GUEST ACCOUNT-------------------------------------*/
route::get('/account', [HomeController::class, 'account']);
route::post('/check_available', [HomeController::class, 'check_available']);
route::post('/download_pdf', [HomeController::class, 'download_pdf']);
route::post('/cancel_reservation', [HomeController::class, 'cancel_reservation']);
route::post('/modify_dates', [HomeController::class, 'modify_dates']);

/*---------------------------------------DASHBOARD---------------------------------------*/
route::get('/dashboard', function () {return view('admin.dashboard');});
route::get('/get_reservations', [AdminController::class, 'get_reservations']);
route::get('/get_all_rooms', [AdminController::class, 'get_all_rooms']);

/*---------------------------------------GUESTS---------------------------------------*/
route::get('/guests', [GuestsController::class, 'view_guests']);
route::post('/add_member', [GuestsController::class, 'add_member']);
route::post('/update_member/{id}', [GuestsController::class, 'update_member']);
route::get('/get_guest/{id}', [GuestsController::class, 'get_guest']);

/*---------------------------------------RESERVATIONS---------------------------------------*/
route::get('/all_reservations', [ReservationsController::class, 'view_reservations']);
route::post('/admin_add_reservation', [ReservationsController::class, 'add_reservation']);
route::post('/filter_available', [ReservationsController::class, 'filter_available']);
route::get('/get_members', [ReservationsController::class, 'get_members']);
route::get('/get_reservations/{id}', [ReservationsController::class, 'get_reservations']);
route::post('/add_extra_guest/{id}', [ReservationsController::class, 'add_extra_guest']);
route::post('/change_booking_payer/{id}', [ReservationsController::class, 'change_booking_payer']);
route::get('/remove_booking_guest/{id}', [ReservationsController::class, 'remove_booking_guest']);
route::get('/move_booking_guest/{id}', [ReservationsController::class, 'move_booking_guest']);
route::post('/reschedule/{id}', [ReservationsController::class, 'reschedule']);
route::post('/change_room/{id}', [ReservationsController::class, 'change_room']);
route::post('/edit_booking_time/{id}', [ReservationsController::class, 'edit_booking_time']);
route::post('/update_reservation_room_no/{id}', [ReservationsController::class, 'update_reservation_room_no']);
route::post('/update_reservation_status/{id}', [ReservationsController::class, 'update_reservation_status']);
route::post('/additional_charges/{id}', [ReservationsController::class, 'additional_charges']);
route::get('/remove_charges/{id}', [ReservationsController::class, 'remove_charges']);

/*---------------------------------------HOUSEKEEPING---------------------------------------*/
route::get('/housekeeping', [HousekeepingController::class, 'view_housekeeping']);
route::post('/update_housekeeping_status/{id}', [HousekeepingController::class, 'update_housekeeping_status']);
route::post('/update_housekeeping_priority/{id}', [HousekeepingController::class, 'update_housekeeping_priority']);

/*---------------------------------------PAYMENTS----------------------------------------*/
route::get('/payments', [PaymentsController::class, 'view_payments']);
route::post('/add_booking_payment/{id}', [PaymentsController::class, 'add_booking_payment']);
route::get('/get_payment/{id}', [PaymentsController::class, 'get_payment']);

/*---------------------------------------REPORTS----------------------------------------*/
route::get('/reports', [ReportsController::class, 'view_reports']);

/*---------------------------------------ROOMS-------------------------------------------*/
route::get('/all_rooms', [AdminController::class, 'view_rooms']);
route::post('/add_room', [AdminController::class, 'add_room']);
route::post('/add_multiple_rooms', [AdminController::class, 'add_multiple_rooms']);
route::get('/get_room/{id}', [AdminController::class, 'get_room']);
route::get('/get_rooms', [AdminController::class, 'get_rooms']);
route::get('/delete_room/{id}', [AdminController::class, 'delete_room']);
route::post('/update_room/{id}', [AdminController::class, 'update_room']);

/*---------------------------------------ROOM TYPES-------------------------------------------*/
route::get('/room_types', [AdminController::class, 'view_room_types']);
route::post('/add_room_type', [AdminController::class, 'add_room_type']);
route::get('/get_room_type/{id}', [AdminController::class, 'get_room_type']);
route::get('/delete_room_type/{id}', [AdminController::class, 'delete_room_type']);
route::post('/update_room_type_status/{id}', [AdminController::class, 'update_room_type_status']);
route::post('/update_room_type/{id}', [AdminController::class, 'update_room_type']);

/*---------------------------------------EMPLOYEES---------------------------------------*/
route::get('/employees', [EmployeesController::class, 'view_employees']);
route::post('/add_employee', [EmployeesController::class, 'add_employee']);
route::get('/delete_employee/{id}', [EmployeesController::class, 'delete_employee']);
route::post('/update_employee/{id}', [EmployeesController::class, 'update_employee']);
});