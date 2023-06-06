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
// */

Route::post('forget-password',[UserController::class,'forgetPassword']);
Route::post('registerUser/{rol_id}', [UserController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::get('user', [UserController::class,'index'])->middleware('jwt.verify');
Route::post('imprimir', [Register_inventaries::class, 'imprimirSelect']);

//VISTAS
Route::get('estados', [Register_inventaries::class, 'vista_estado']);
Route::get('condiciones', [Register_inventaries::class, 'vista_condicion']);
Route::get('area', [Register_inventaries::class, 'vista_area']);


Route::get('biencodigo/{codigo}', [Register_inventaries::class, 'bienid']);
Route::put('inventario/{codigo}', [Register_inventaries::class, 'update_inventario']);

Route::get('barras/{codigo}', [Register_inventaries::class, 'BarraCodigo']);


Route::post('reset-password', [UserController::class, 'resetPassword']);
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('usersinfo', [UserController::class, 'index']); //http://127.0.0.1:8000/api/usersinfo
    Route::post('updateProfile', [UserController::class, 'updateProfile']); //actualizar perfil

    Route::get('users/{user}', [UserController::class, 'show']);
    Route::get('usersAll', [UserController::class, 'allUser']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    Route::get('inventaryAll', [Register_inventaries::class, 'index']);
    Route::post('inventary', [Register_inventaries::class, 'agregarBines']);
    
});

