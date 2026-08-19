<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

/* ----------------------------- Admin auth ----------------------------- */
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

/* ----------------------------- Client auth ----------------------------- */
Route::get('/tracker/login', [AuthController::class, 'showClientLogin'])->name('client.login');
Route::post('/tracker/login', [AuthController::class, 'clientLogin'])->name('client.login.post');
Route::post('/tracker/logout', [AuthController::class, 'clientLogout'])->name('client.logout');
