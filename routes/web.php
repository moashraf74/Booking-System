<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});




Route::get('/test-email', function () {

    set_time_limit(120); 


    try {
        Mail::raw('This is a test email from Booking System Admin.', function ($message) {
            $message->to('5521f99530325e+1@inbox.mailtrap.io') // الإيميل الخاص بـ Mailtrap
                    ->subject('Test Email - Booking System'); // عنوان الرسالة
        });
        return 'Email sent successfully.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
