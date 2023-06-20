<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventary\Register_inventaries;
use App\Http\Controllers\Rol\RolController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\Submodeles\SubmodelesController;
use App\Http\Controllers\Rolpermiso\RolpermisoController;


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

Route::post('forget-password', [UserController::class, 'forgetPassword']);
Route::post('registerUser/{rol_id}', [UserController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('imprimir', [Register_inventaries::class, 'imprimirSelect']);

//VISTAS
Route::get('estados', [Register_inventaries::class, 'vista_estado']);
Route::get('condiciones', [Register_inventaries::class, 'vista_condicion']);
Route::get('area', [Register_inventaries::class, 'vista_area']);


Route::get('biencodigo/{codigo}', [Register_inventaries::class, 'bienid']);
Route::put('inventario/{codigo}', [Register_inventaries::class, 'update_inventario']);

Route::get('barras/{codigo}', [Register_inventaries::class, 'BarraCodigo']);


Route::post('reset-password', [UserController::class, 'resetPassword']);
// Rol CRUD inicio
Route::get('rol', [RolController::class, 'rolAll']);
Route::get('rol/{id}', [RolController::class, 'rolId']);
Route::post('rol', [RolController::class, 'rolRegister']);
Route::put('rol/{id}', [RolController::class, 'rolUpdate']);
Route::delete('rol/{id}', [RolController::class, 'rolDelete']);
//Rol CRUD end
// Module CRUD inicio
Route::get('module', [ModuleController::class, 'moduleAll']);
Route::get('module/{id}', [ModuleController::class, 'moduleId']);
Route::post('module', [ModuleController::class, 'moduleRegister']);
Route::put('module/{id}', [ModuleController::class, 'moduleUpdate']);
Route::delete('module/{id}', [ModuleController::class, 'moduleDelete']);
//Module CRUD end
// Submodule CRUD inicio
Route::get('submodule', [SubmodelesController::class, 'submoduleAll']);
Route::get('submodule/{id}', [SubmodelesController::class, 'submoduleId']);
Route::post('submodule', [SubmodelesController::class, 'submoduleRegister']);
Route::put('submodule/{id}', [SubmodelesController::class, 'submoduleUpdate']);
Route::delete('submodule/{id}', [SubmodelesController::class, 'submoduleDelete']);
// Submodule CRUD end
// Rolpermiso CRUD inicio
Route::get('rolpermiso', [RolpermisoController::class, 'rolpermisoAll']);
Route::get('rolpermiso/{id}', [RolpermisoController::class, 'rolpermisoId']);
Route::post('rolpermiso', [RolpermisoController::class, 'rolpermisoRegister']);
Route::put('rolpermiso/{id}', [RolpermisoController::class, 'rolpermisoUpdate']);
Route::delete('rolpermiso/{id}', [RolpermisoController::class, 'rolpermisoDelete']);
Route::get('rolpermisol/{name}', [RolpermisoController::class, 'permisosxUsuariosLogeado']);
// Rolpermiso CRUD end
Route::get('inventaryP', [Register_inventaries::class, 'PaginadoInvitario']);
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
