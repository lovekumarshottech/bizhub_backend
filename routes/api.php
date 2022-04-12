<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\StripeUtilController;
use App\Http\Controllers\SupportController;
use App\Models\Support;

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
Route::get('test', function () {
    return ('test code');
});
Route::post('register', [AuthController::class, 'register']);
Route::post('validate-otp', [AuthController::class, 'validateOtp']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('contactUs', [ServiceController::class, 'contactUs']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [AuthController::class, 'updatePassword']);
    // Route::get('profile', [AuthController::class, 'profile']);
    Route::put('update-profile', [AuthController::class, 'updateProfile']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('apply-service', [ServiceController::class, 'applyService']);
    Route::put('start-interview', [ServiceController::class, 'startInterview']);

    Route::get('my-services', [ServiceController::class, 'getMyServices']);
    Route::put('services-edit', [ServiceController::class, 'servicesEdit']);
    Route::delete('services-delete/{id}', [ServiceController::class, 'ServicesDelete']);
    Route::get('my-service/{id}', [ServiceController::class, 'myServies']);
    Route::get('useroffer/{id}', [ServiceController::class, 'userOffer']);
    Route::post('offerAccept', [ServiceController::class, 'offerAccept']); //  Service Accept Offer For User
    Route::post('rating', [ServiceController::class, 'rating']);
    Route::post('profile', [ServiceController::class, 'getRating']);
    Route::get('jobComplete/{id}', [ServiceController::class, 'jobComplete']);
    Route::post('jobCancel', [ServiceController::class, 'jobCancel']);
    Route::get('show-service/{service}', [ServiceController::class, 'showService']);

    Route::post('create-account', [StripeUtilController::class, 'create']);

    Route::get('applied-services', [ServiceController::class, 'getAppliedServices']);
    Route::apiResources([
        'services' => ServiceController::class,
        'comments' => CommentController::class,
    ]);

    Route::post('create-dispute', [SupportController::class, 'store']);
});
Route::post('checkout-session', [StripeUtilController::class, 'checkoutSession']);

Route::get('update-account/{id}', [StripeUtilController::class, 'accountUpdate'])->name('update-account');
Route::get('account-payout-mannual/{id}', [StripeUtilController::class, 'accountPayoutMannual']);
Route::get('payout/{id}', [StripeUtilController::class, 'payout']);
Route::get('add-funds', [StripeUtilController::class, 'addFund']);
Route::get('promotional-coupon', [StripeUtilController::class, 'promotionalCode']);
Route::get('refund/{id}', [StripeUtilController::class, 'refund']);

Route::get('checkout-session-complete/{id}', [StripeUtilController::class, 'checkoutSessionComplete'])->name('checkout-session-complete');



Route::get('transfer/{id}', [StripeUtilController::class, 'tranferToSpecificAccount']);
