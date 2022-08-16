<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
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
Route::resources([
    'roles'=>RolesController::class,
    'permissions'=>PermissionsController::class
]);
