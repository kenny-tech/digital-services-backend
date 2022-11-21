<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('activate_account/{email}/{token}', [UserController::class, 'activateAccount']);
Route::post('forgot_password', [UserController::class, 'forgotPassword']);
Route::get('reset_password/{email}/{token}', [UserController::class, 'reset_password']);
Route::post('reset_password', [UserController::class, 'resetPassword']);
     
Route::middleware('auth:api')->group( function () {
    Route::resource('products', ProductController::class);
});
