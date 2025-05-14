<?php

use App\Http\Controllers\BusController;
use App\Http\Controllers\SystemManagementController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTopupController;
use App\Http\Controllers\CommuteScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TicketTransactionController;
use App\Http\Controllers\UserController;
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
Route::view('/', 'auth.login')->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware('auth')->group(function () {

    Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
    Route::get('/buses/create', [BusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [BusController::class, 'store'])->name('buses.store');
    Route::get('/buses/{bus}', [BusController::class, 'show'])->name('buses.show');
    Route::get('/buses/{bus}/edit', [BusController::class, 'edit'])->name('buses.edit');
    Route::put('/buses/{bus}', [BusController::class, 'update'])->name('buses.update');
    Route::delete('/buses/{bus}', [BusController::class, 'destroy'])->name('buses.destroy');
    Route::get('/buses/{bus}/assign', [BusController::class, 'assign'])->name('buses.assign');



    //Commute Schedule
    Route::get('/commute-schedules', [CommuteScheduleController::class, 'index'])
        ->name('commute.schedules.index')
        ->middleware('can:view schedules');

    Route::post('/commute-schedules', [CommuteScheduleController::class, 'store'])
        ->name('schedules.store')
        ->middleware('can:create schedules');


    Route::get('/system-management', [SystemManagementController::class, 'index'])
    ->name('system-management.index');

// Cache Management
Route::post('/cache/refresh', [SystemManagementController::class, 'refreshCache'])
    ->name('admin.cache.refresh');

// Role Management
Route::get('/roles', [SystemManagementController::class, 'rolesIndex'])
    ->name('admin.roles.index')
    ->middleware('can:manage roles');


    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])
    ->name('roles.permissions')
    ->middleware('permission:edit roles');

    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');


Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
    ->name('roles.permissions.update')
    ->middleware('permission:edit roles');

Route::get('roles/{role}/users', [RoleController::class, 'users'])
    ->name('roles.users')
    ->middleware('permission:view users');

// Clone an existing role
Route::post('roles/{role}/clone', [RoleController::class, 'clone'])
    ->name('roles.clone')
    ->middleware('permission:create roles');

// Bulk actions
Route::post('roles/bulk-action', [RoleController::class, 'bulkAction'])
    ->name('roles.bulk.action')
    ->middleware('permission:edit roles');

// Settings Management
Route::get('/settings', [SystemManagementController::class, 'settingsIndex'])
    ->name('admin.settings.index')
    ->middleware('can:configure settings');

// Notification Management
Route::get('/notifications', [SystemManagementController::class, 'notificationsIndex'])
    ->name('admin.notifications.index')
    ->middleware('can:manage notifications');

// Logs Management
Route::get('/logs', [SystemManagementController::class, 'logsIndex'])
    ->name('admin.logs.index')
    ->middleware('can:view logs');

// Database Management
Route::get('/database', [SystemManagementController::class, 'databaseIndex'])
    ->name('admin.database.index')
    ->middleware('can:manage database');

Route::post('/database/backup', [SystemManagementController::class, 'createBackup'])
    ->name('admin.database.backup')
    ->middleware('can:manage database');

    Route::get('/ticket-transactions', [TicketTransactionController::class, 'index']);
    Route::get('/ticket-transactions/create', [TicketTransactionController::class, 'create'])->name('ticket-transactions.create');
    Route::post('/ticket-transactions', [TicketTransactionController::class, 'store'])->name('tickets.store');
    Route::get('/ticket-transactions/{id}', [TicketTransactionController::class, 'show'])->name('tickets.show');
    Route::get('/ticket-transactions/{id}/edit', [TicketTransactionController::class, 'edit'])->name('tickets.edit');
    Route::put('/ticket-transactions/{id}', [TicketTransactionController::class, 'update'])->name('tickets.update');
    Route::delete('/ticket-transactions/{id}', [TicketTransactionController::class, 'destroy'])->name('tickets.destroy');



    Route::get('/all-offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/all-offers/create', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/all-offers', [OfferController::class, 'store'])->name('offers.store');
    Route::get('/all-offers/{offer}', [OfferController::class, 'show'])->name('offers.show');
    Route::get('/all-offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::put('/all-offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    Route::delete('/all-offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');
    Route::get('/all-offers/{offer}/print', [OfferController::class, 'printReceipt'])->name('offers.print');
    Route::get('/all-offers/{offer}/receipt', [OfferController::class, 'receipt'])->name('offers.receipt');

    Route::get('/all-card-topups', [CardTopupController::class, 'index'])->name('card-topups.index');
    Route::get('/all-card-topups/{id}', [CardTopupController::class, 'show'])->name('card-topups.show');
    Route::get('/all-card-topups/create', [CardTopupController::class, 'create'])->name('card-topups.create');
    Route::post('/all-card-topups', [CardTopupController::class, 'topUpCard'])->name('card-topups.store');
    Route::get('/all-card-topups/{id}/edit', [CardTopupController::class, 'edit'])->name('card-topups.edit');
    Route::put('/all-card-topups/{id}', [CardTopupController::class, 'update'])->name('card-topups.update');
    Route::delete('/all-card-topups/{id}', [CardTopupController::class, 'destroy'])->name('card-topups.destroy');
    Route::get('/all-card-topups/{id}/print', [CardTopupController::class, 'printReceipt'])->name('card-topups.print');
    Route::get('/all-card-topups/{id}/receipt', [CardTopupController::class, 'receipt'])->name('card-topups.receipt');

    Route::get('/all-routes', [RouteController::class, 'index'])->name('routes.index');

    Route::get('/all-routes', [RouteController::class, 'index'])->name('routes.index');

// Route to store a new route
Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');

// Route to edit an existing route
Route::get('/routes/{route}/edit', [RouteController::class, 'edit'])->name('routes.edit');

// Route to update an existing route
Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');

// Route to delete a route
Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');



    Route::post('/', [UserController::class, 'store'])
    ->name('users.store');


    Route::put('/{user}', [UserController::class, 'update'])
        ->name('users.update');

        Route::delete('/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

     // Special user operations
     Route::post('/{user}/assign-role', [UserController::class, 'assignRole'])
     ->name('users.assign-role');

 Route::post('/{user}/suspend', [UserController::class, 'suspend'])
     ->name('users.suspend');

 Route::post('/{user}/activate', [UserController::class, 'activate'])
     ->name('users.activate');

 Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
     ->name('users.reset-password');

    Route::get('/users', [UserController::class, 'index'])->name('user.index');

    Route::post('/cards/{card}/transfer', [CardController::class, 'transferCard'])->name('cards.transfer');

    Route::post('/cards/{card}/unblock', [CardController::class, 'unblockCard'])->name('cards.unblock');
    Route::post('/cards/{card}/block', [CardController::class, 'blockCard'])->name('cards.block');

    Route::post('/cards/top-up', [CardTopupController::class, 'topUpCard'])->name('cards.topup');

// Route to view the index of all card top-ups
Route::get('/cards/top-ups', [CardTopupController::class, 'index'])->name('cards.topups.index');

// Route to view a single card top-up by ID
Route::get('/cards/top-ups/{id}', [CardTopupController::class, 'show'])->name('cards.topups.show');


    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');

    // Store a new card
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');

    // Show a specific card details (if you need a separate page)
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');

    // Edit card form (if you have a separate form page, not using modals)
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('cards.edit');

    // Update a card
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');

    // Delete a card
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
