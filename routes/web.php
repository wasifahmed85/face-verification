<?php
/**
 * File: routes/web.php
 * 
 * Web routes for face verification system
 */

use App\Http\Controllers\FaceVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome/Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes (শুধুমাত্র logged out users access করতে পারবে)
Route::middleware('guest')->group(function () {
    
    // Registration with face verification
    Route::get('/register-face', [FaceVerificationController::class, 'showRegisterFace'])
        ->name('register.face');
    
    Route::post('/register-face', [FaceVerificationController::class, 'storeFaceData'])
        ->name('register.face.store');

    // Login with face verification
    Route::get('/login-face', [FaceVerificationController::class, 'showLoginFace'])
        ->name('login.face');
    
    Route::post('/verify-face', [FaceVerificationController::class, 'verifyFace'])
        ->name('verify.face');
});

// Authenticated routes (শুধুমাত্র logged in users access করতে পারবে)
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [FaceVerificationController::class, 'dashboard'])
        ->name('dashboard');
    
    // Logout
    Route::post('/logout', [FaceVerificationController::class, 'logout'])
        ->name('logout');
    
    // Reset face verification
    Route::post('/reset-face', [FaceVerificationController::class, 'resetFaceVerification'])
        ->name('reset.face');
});

// Redirect /login to /login-face
Route::redirect('/login', '/login-face');
Route::redirect('/register', '/register-face');