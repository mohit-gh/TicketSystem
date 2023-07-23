<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

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

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/', [LoginController::class, 'showLoginForm'])->name('showLogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    // Routes that require authentication
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('tickets/change-status', [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');
    Route::post('tickets/mark-notification', [TicketController::class, 'markNotification'])->name('tickets.markNotification');
    Route::resource('tickets', TicketController::class);
});
