<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\WebsiteDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', [WebsiteDataController::class, 'user']);

Route::post('/register', [WebsiteDataController::class, 'register']);
Route::post('/verify-code', [WebsiteDataController::class, 'verifyCode']);
Route::post('/resend-verification-code', [WebsiteDataController::class, 'resendVerificationCode']);


Route::post('register', [WebsiteDataController::class, 'register']);
Route::post('login', [WebsiteDataController::class, 'login']);
Route::post('forgot-password', [WebsiteDataController::class, 'sendResetLink']);
Route::post('reset-password', [WebsiteDataController::class, 'resetPassword']);

Route::get('website-data/{lang?}', [WebsiteDataController::class, 'index']);
Route::get('slider', [WebsiteDataController::class, 'slider']);
Route::get('secondarySlider', [WebsiteDataController::class, 'secondarySlider']);
Route::post('contact', [ContactController::class, 'create']);
Route::get('rooms/{lang?}', [RoomController::class, 'index']);
Route::get('room/{id}/{lang?}', [RoomController::class, 'show']);
Route::get('get-available-rooms/{lang?}', [RoomController::class, 'getAvailableRooms']);
Route::middleware('auth:sanctum')->post('confirm-booking', [RoomController::class, 'confirmReservation']);
