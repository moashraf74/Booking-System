<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BusinessController;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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

Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    SubstituteBindings::class,
])->group(function () {
    // تعريف route الخاص بالمستخدم
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Password Reset Routes
Route::post('/password/forgot', [AuthController::class, 'sendResetLink']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    
    // User Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // عرض بيانات المستخدم
        Route::get('/user/{id}', [UserController::class, 'show']);

        // تعديل بيانات المستخدم
        Route::put('/user/{id}', [UserController::class, 'update']);

        // تغيير كلمة السر
        Route::put('/user/{id}/password', [UserController::class, 'changePassword']);
    });

    // Admin-only Routes
    // الادمن بعمل كل حاجة سيبك من الراوتات دي
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        // Business Routes
        Route::post('/business', [BusinessController::class, 'store']);
        Route::put('/business/{id}', [BusinessController::class, 'update']);
        Route::delete('/business/{id}', [BusinessController::class, 'destroy']);

        // Service Routes
        Route::post('/service', [ServiceController::class, 'store']);
        Route::put('/service/{id}', [ServiceController::class, 'update']);
        Route::delete('/service/{id}', [ServiceController::class, 'destroy']);

        // Booking Routes
        Route::put('/booking/{id}', [BookingController::class, 'update']);
        Route::delete('/booking/{id}', [BookingController::class, 'destroy']);

        // Review Routes
        Route::put('/review/{id}', [ReviewController::class, 'update']);
        Route::delete('/review/{id}', [ReviewController::class, 'destroy']);
    });

    // General Routes (accessible by all authenticated users)
    Route::middleware('auth:sanctum')->group(function () {
        // Business
        Route::get('/business', [BusinessController::class, 'index']);

        // Services
        Route::get('/service', [ServiceController::class, 'index']);

        // Bookings
        Route::post('/booking', [BookingController::class, 'store']);
        Route::put('/booking/{id}', [BookingController::class, 'update']);
        Route::get('/booking', [BookingController::class, 'index']);

        // Reviews
        Route::post('/review', [ReviewController::class, 'store']);
        Route::put('/review/{id}', [ReviewController::class, 'update']);
        Route::get('/review', [ReviewController::class, 'index']);
    });
}); 