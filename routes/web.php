<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
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


/* OAuth routes*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/mfa', [AuthController::class, 'showMfaForm'])->name('mfa');
Route::post('/mfa', [AuthController::class, 'verifyMfa'])->name('mfa.verify');
Route::get('/mfa/resend', [AuthController::class, 'resendMfaCode'])->name('mfa.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
/* OAuth routes*/

/* Customer crud operations*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});
/* Customer crud operations*/
