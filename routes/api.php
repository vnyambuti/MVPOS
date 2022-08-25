<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TellerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [ApiAuthController::class, 'Register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/reset', [ApiAuthController::class, 'reset']);
Route::post('/password-changed', [ApiAuthController::class, 'resetpass']);
Route::middleware('auth:api')->group( function () {
Route::resources([
    'roles'=>RolesController::class,
    'permissions'=>PermissionsController::class,
    'users'=>UserController::class,
    'shops'=>ShopController::class,
    'categories'=>CategoriesController::class,
    'products'=>ProductController::class,
    'tellers'=>TellerController::class
]);
Route::post('/add', [OrderController::class, 'add']);
});
