<?php

use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', UserController::class);
Route::apiResource('roles', UserController::class);
Route::get('download-pdf', [UserController::class, 'downloadPdf'])->name('download-pdf');
Route::get('push', [UserController::class, 'push']);

Route::get('asterisk/{numeroA}/{numeroB}',[UserController::class, 'ctc']);
