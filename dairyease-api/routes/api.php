<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\MilkDetailsController;
use App\Http\Controllers\Api\ExpensesDetailsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;

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
Route::post("register",[UserController::class,"register"]);
Route::post("login",[UserController::class,"login"]);

Route::post("verify-email", [UserController::class, "verifyOtp"]);

Route::post('forgot-password', [PasswordController::class, 'ForgotPassword']);
Route::post('verify-otp', [PasswordController::class, 'verifyOtp']);

Route::group(["middleware"=>["auth:sanctum"]],function(){
Route::get("profile",[UserController::class,"profile"]);
Route::get("logout",[UserController::class,"logout"]);

Route::post('change-password', [PasswordController::class, 'changePassword']);

Route::post("create",[MilkDetailsController::class,"create"]);
Route::get("list",[MilkDetailsController::class,"list"]);
Route::delete("delete/{id}",[MilkDetailsController::class,"delete"]);
Route::get('/milk', [MilkDetailsController::class, 'index']);

Route::post("create-expenses",[ExpensesDetailsController::class,"createExpenses"]);
Route::get("list-expenses",[ExpensesDetailsController::class,"listExpenses"]);
Route::put('expenses/{id}', [ExpensesDetailsController::class, 'update']);
Route::delete("delete-expenses/{id}",[ExpensesDetailsController::class,"deleteExpenses"]);
Route::get("expenses", [ExpensesDetailsController::class, "listShift"]);


Route::post("update-profile",[ProfileController::class,"update_profile"]);

Route::get("showDashboard",[HomeController::class,"showDashboard"]);

});


Route::post("create-product",[ProductController::class,"createProduct"]);
Route::get("list-product",[ProductController::class,"index"]);
Route::put('product/{id}', [ProductController::class, 'update']);
Route::delete("delete-product/{id}",[ProductController::class,"destroy"]);

Route::post("create-event",[EventController::class,"createEvent"]);
Route::get("list-event",[EventController::class,"index"]);
Route::delete("delete-event/{id}",[EventController::class,"destroy"]);


Route::post("create-category",[CategoryController::class,"createCategory"]);
Route::get("list-category",[CategoryController::class,"index"]);
Route::delete("delete-category/{id}",[CategoryController::class,"destroy"]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
