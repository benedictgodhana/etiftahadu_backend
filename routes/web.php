<?php

use Illuminate\Support\Facades\Route;

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

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



Route::get('/send-test-email', function () {
    $toEmail = 'benedictgodhana7@gmail.com';
    $data = [
        'name' => 'Benedict Godhana',
        'messageContent' => 'This is a test email sent from the Laravel application.'
    ];

    Mail::to($toEmail)->send(new TestEmail($data));

    return "Test email sent to {$toEmail}!";
});


Route::get('/', function () {
    return view('welcome');
});
