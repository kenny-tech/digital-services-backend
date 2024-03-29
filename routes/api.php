<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AirtimeController;
use App\Http\Controllers\API\BillController;
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
Route::middleware('auth:api')->group( function () {
    Route::get('get_bill_categories', [ProviderController::class, 'getBillCategories']);
    Route::post('buy_airtime', [ProviderController::class, 'buyAirtime']);
    Route::post('pay_bills', [ProviderController::class, 'payBills']);

    Route::get('payment/get', [PaymentController::class, 'index']);
    Route::post('payment/create', [PaymentController::class, 'create']);
    Route::post('payment/user-payment', [PaymentController::class, 'getPayment']);
    Route::post('payment/verify_payment', [PaymentController::class, 'verifyPayment']);

    Route::get('airtime/get', [AirtimeController::class, 'index']);
    Route::post('airtime/user-airtime', [AirtimeController::class, 'getAirtime']);

    Route::get('bills/get', [BillController::class, 'index']);
    Route::post('bills/user-bills', [BillController::class, 'getBills']);

    Route::get('get_bill_categories/cable', [ProviderController::class, 'getBillCategoriesForCableTv']);
    Route::post('validate_customer', [ProviderController::class, 'ValidateCustomer']);

});
