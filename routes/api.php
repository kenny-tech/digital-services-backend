<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\PaymentController;
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
Route::get('activate_account/{id}/{token}', [UserController::class, 'activateAccount']);
Route::post('forgot_password', [UserController::class, 'forgotPassword']);
Route::get('reset_password/{id}/{token}', [UserController::class, 'reset_password']);
Route::post('reset_password', [UserController::class, 'resetPassword']);

Route::get('get_biller_categories', [ProviderController::class, 'getBillerCategories']);
Route::get('get_biller_by_categories', [ProviderController::class, 'getBillerByCategories']);
Route::get('get_biller_payment_items', [ProviderController::class, 'getBillerPaymentItems']);
Route::post('send_bill_payment_advice', [ProviderController::class, 'sendBillPaymentAdvice']);

// Flutterwave
// Route::get('validate_phone_number', [ProviderController::class, 'ValidateCustomerPhoneNumber']);

Route::middleware('auth:api')->group( function () {
    Route::get('get_bill_categories', [ProviderController::class, 'getBillCategories']);
    Route::post('buy_airtime', [ProviderController::class, 'buyAirtime']);

    Route::get('payment/get', [PaymentController::class, 'index']);
    Route::post('payment/create', [PaymentController::class, 'create']);
    Route::post('payment/get-payment', [PaymentController::class, 'getPayment']);
});
