<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\VoteController;

Route::prefix('v1')->group(function(){
  Route::apiResource('/category', CategoryController::class);
    Route::apiResource('/candidate', CandidateController::class); 
// middleware roleAdmin
  Route::middleware('auth:api', 'admin')->group(function(){
    Route::resource('role', RoleController::class);
  });
  //Auth
   Route::prefix('auth')->group(function (){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'currentuser'])->middleware('auth:api');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/account_verification', [AuthController::class, 'verifikasi'])->middleware('auth:api');
    Route::post('/generate_otp_code', [AuthController::class, 'generateOtp'])->middleware('auth:api');
  })->middleware('api');
  //middleware update profile
  Route::post('/profile', [ProfileController::class, 'updateProfile'])->middleware(['auth:api', 'verifiedAccount']);
  Route::post('/vote', [VoteController::class, 'updateCreateVote'])->middleware(['auth:api', 'verifiedAccount']);
  });
