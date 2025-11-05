<?php

use App\Http\Controllers\FaceVerificationController;
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
Route::get('/get-face-descriptor', [FaceVerificationController::class, 'getFaceDescriptor'])
    ->name('api.face.descriptor');

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// API version info
Route::get('/version', function () {
    return response()->json([
        'version' => '1.0.0',
        'app' => config('app.name'),
        'laravel' => app()->version()
    ]);
});