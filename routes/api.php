<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('website-data/{lang?}', [WebsiteDataController::class, 'index']);
Route::get('slider', [WebsiteDataController::class, 'slider']);
Route::get('secondarySlider', [WebsiteDataController::class, 'secondarySlider']);
Route::post('contact', [ContactController::class, 'create']);
Route::get('rooms/{lang?}', [RoomController::class, 'index']);
Route::get('room/{id}/{lang?}', [RoomController::class, 'show']);
Route::get('get-available-rooms/{lang?}', [RoomController::class, 'getAvailableRooms']);
