<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\UserController;
use App\Models\PostCategory;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
    Route::prefix('category')->group(function () {
        Route::get('/', [PostCategoryController::class, 'index']);
        Route::get('/{id}', [PostCategoryController::class, 'show']);
        Route::post('/', [PostCategoryController::class, 'store']);
        Route::put('/{id}', [PostCategoryController::class, 'update']);
        Route::delete('/{id}', [PostCategoryController::class, 'destroy']);
    });
    Route::prefix('bookmark')->group(function () {
        Route::get('/', [BookmarkController::class, 'index']);
        Route::get('/{id}', [BookmarkController::class, 'show']);
        Route::post('/', [BookmarkController::class, 'store']);
        Route::put('/{id}', [BookmarkController::class, 'update']);
        Route::delete('/{id}', [BookmarkController::class, 'destroy']);
    });
});
