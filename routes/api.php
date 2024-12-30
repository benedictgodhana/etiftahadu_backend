<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTopupController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TicketTransactionController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/roles', [RoleController::class, 'fetchRoles']);
    Route::get('/tickets/{id}/print', [TicketTransactionController::class, 'printReceipt']);

    Route::get('/offers', [OfferController::class, 'index']);

    // Show the form to create a new offer
    Route::post('/offers', [OfferController::class, 'store']);

    // Display the specified offer
    Route::get('/offers/{offer}', [OfferController::class, 'show']);

    // Show the form to edit the specified offer
    Route::put('/offers/{offer}', [OfferController::class, 'update']);

    // Remove the specified offer
    Route::delete('/offers/{offer}', [OfferController::class, 'destroy']);

    // Ticket transactions
    Route::get('/ticket-transactions', [TicketTransactionController::class, 'index']);
    Route::post('/ticket', [TicketTransactionController::class, 'store']);
    Route::get('/transactions/total/monthly', [TicketTransactionController::class, 'totalMonthlyTransactions']);
    Route::get('/transactions/total/today', [TicketTransactionController::class, 'totalTodaysTransactions']);

    // Routes management
    Route::get('/routes', [RouteController::class, 'index']);
    Route::post('/addRoute', [RouteController::class, 'store']);
    Route::put('/routes/{id}', [RouteController::class, 'update']);
    Route::delete('/routes/{id}', [RouteController::class, 'destroy']);
    Route::post('/findRoute', [RouteController::class, 'findRoute']);
    Route::get('/routes/count', [RouteController::class, 'getRoutesCount']);


    // Card management
    Route::post('/add-card', [CardController::class, 'store'])->name('add-card');
    Route::post('/fetch-card', [CardController::class, 'fetchCardData']);
    Route::get('/active-cards', [CardController::class, 'fetchActiveCards']);
    Route::get('/cards', [CardController::class, 'index']);

    // Card top-up management
    Route::get('/card-topups', [CardTopupController::class, 'index']);
    Route::get('/card-topups/{id}', [CardTopupController::class, 'show']);
    Route::put('/card-topups/{id}', [CardTopupController::class, 'update']);
    Route::delete('/card-topups/{id}', [CardTopupController::class, 'destroy']);
    Route::post('/top-up', [CardTopupController::class, 'topUpCard']);

    // User-related routes
    Route::post('logout', [AuthController::class, 'logout']); // Logout route
    Route::get('user', [AuthController::class, 'user']); // Get authenticated user details

    Route::get('users', [UserController::class, 'index']); // Display users
    Route::post('users', [UserController::class, 'store']); // Add user
    Route::put('users/{id}', [UserController::class, 'update']); // Update user
    Route::patch('users/{id}/deactivate', [UserController::class, 'deactivate']); // Deactivate user
    Route::get('/user-count', [UserController::class, 'getUserCount']);
});

// Authentication routes (no authentication required)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login'); // Explicitly name the route
