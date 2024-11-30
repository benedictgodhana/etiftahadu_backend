<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTopupController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TicketTransactionController;

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


Route::get('/ticket-transactions', [TicketTransactionController::class, 'index']);
Route::post('/ticket', [TicketTransactionController::class, 'store']);

Route::get('/routes', [RouteController::class, 'index']);
Route::post('/addRoute', [RouteController::class, 'store']);
Route::delete('/routes/{id}', [RouteController::class, 'destroy']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login'); // Explicitly name the route
Route::post('/add-card', [CardController::class, 'store'])->name('add-card');
Route::get('/card-topups', [CardTopupController::class, 'index']);
Route::get('/card-topups/{id}', [CardTopupController::class, 'show']);
Route::put('/card-topups/{id}', [CardTopupController::class, 'update']);
Route::delete('/card-topups/{id}', [CardTopupController::class, 'destroy']);
Route::post('/fetch-card', [CardController::class, 'fetchCardData']);
Route::post('/top-up', [CardTopupController::class, 'topUpCard']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected Routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']); // Logout route
    Route::get('user', [AuthController::class, 'user']); // Get authenticated user details

});
