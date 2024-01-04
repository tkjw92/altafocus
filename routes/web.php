<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('check')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard']);
    Route::post('/', [DashboardController::class, 'add']);
    Route::put('/', [DashboardController::class, 'edit']);
    Route::delete('/', [DashboardController::class, 'remove']);

    Route::get('/graph/{mac}/{day}', [DashboardController::class, 'graph']);
});

Route::get('/login', [DashboardController::class, 'login']);
Route::post('/login', [DashboardController::class, 'auth']);
Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});
