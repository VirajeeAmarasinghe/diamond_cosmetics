<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::post('/login', [LoginController::class, 'login'])->name('api.login');
Route::post('/register', [RegisterController::class, 'register'])->name('api.register');

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('products', [ProductApiController::class, 'index'])->name('api.products');
Route::get('products/show/{id}', [ProductApiController::class, 'show'])->name('api.products.show');

Route::middleware('auth:sanctum')->get('/logout', [LoginController::class, 'logout'])->name('api.logout');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:admin,moderator'])->group(function () {
    Route::post('products/store', [ProductApiController::class, 'store'])->name('api.products.store');
    Route::put('products/update/{id}', [ProductApiController::class, 'update'])->name('api.products.update');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:admin'])->group(function () {
    Route::delete('products/delete/{id}', [ProductApiController::class, 'destroy'])->name('api.products.delete');
});