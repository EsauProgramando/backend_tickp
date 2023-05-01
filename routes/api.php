<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventary\Register_inventaries;

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

Route::post('registerUser/{rol_id}', [UserController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::get('user', [UserController::class,'index'])->middleware('jwt.verify');


Route::post('inventary', [Register_inventaries::class, 'agregarBines']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('usersinfo', [UserController::class, 'index']); //http://127.0.0.1:8000/api/usersinfo
    Route::post('updateProfile', [UserController::class, 'updateProfile']); //actualizar perfil

    Route::get('users/{user}', [UserController::class, 'show']);
    Route::get('usersAll', [UserController::class, 'allUser']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::get('inventaryAll', [Register_inventaries::class, 'index']);
});

Route::get('inventario', [Register_inventaries::class, 'index']);