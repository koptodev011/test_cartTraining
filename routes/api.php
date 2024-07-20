<?php
use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\VideoUploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Allocatedstdent;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/login', [Api\AuthController::class, 'login']);




Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [Api\AuthController::class, 'logout']);
    Route::post('/delete-account', [Api\AuthController::class, 'deleteAccount']);
    Route::post('/change-password', [Api\AuthController::class, 'changePassword']);


    Route::get('/profile', [Api\UserController::class, 'profile']);
    Route::post('/edit-profile', [Api\UserController::class, 'editProfile']);
    Route::post('/set-fcm', [Api\UserController::class, 'setFcm']);
    

});

//Videos routes
Route::get('/getstudentvideo', [VideoUploadController::class, 'getstudentvideo']);
Route::get('/getTrainervideo', [VideoUploadController::class, 'getTrainervideo']);

//Student Section
Route::middleware(['auth:sanctum'])->group(function () { 
    Route::get('/Allocatedstdent', [Api\Allocatedstdent::class, 'Allocatedstdents']);
});
Route::get('/Studentdetails', [Api\Allocatedstdent::class, 'Studentdetails']);
Route::post('/feedback', [Api\Allocatedstdent::class, 'feedback']);



//Expense section route
Route::post('/addExpense', [Api\Allocatedstdent::class, 'feedback']);

