<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;

// Open Routes
Route::post("register", [AuthController::class, "register"]);
Route::post('verify-user', [AuthController::class, 'verifyEmail']);
Route::post("login", [AuthController::class, "login"]);

// Protected Routes
Route::group([
    "middleware" => ["auth:api"]
], function(){
    Route::get("refresh-token", [AuthController::class, "refreshToken"]);
    Route::get('user/{user_id}', [AuthController::class, 'getUser']);
    Route::get("logout", [AuthController::class, "logout"]);
    
    // Transaction Routes (RESTful style)
    Route::post("transactions", [TransactionController::class, "createTransaction"]);
    Route::get("transactions/{id}", [TransactionController::class, "getTransaction"]); 
    Route::put("transactions/{id}", [TransactionController::class, "updateTransaction"]);
    Route::delete("transactions/{id}", [TransactionController::class, "deleteTransaction"]); 
});
