<?php

use Illuminate\Support\Facades\Route;
use Artisan; 

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
    // return view('welcome');
    return redirect('/admin');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/git-pull', function () {
    // Ensure the environment is correct (optional)
    $output = null;
    $resultCode = null;

    // Execute the git pull command
    exec('git pull', $output, $resultCode);

    // Return the output to the browser
    return response()->json([
        'output' => $output,
        'resultCode' => $resultCode,
    ]);
});

Route::get('/migrate', function () {
    // Running the migrate command
    $output = null;
    $resultCode = null;

    exec('php artisan migrate', $output, $resultCode);

    // Return the output and result code
    return response()->json([
        'output' => $output,
        'resultCode' => $resultCode,
    ]);
});
