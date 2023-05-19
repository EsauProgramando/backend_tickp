<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventary\Register_inventaries;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('inventaryPDF/{codigo}/{fecha}/{descripcion}', [Register_inventaries::class, 'ticketPDFExcel']);//TODO: ENVIAR NOMBRE
//http://127.0.0.1:8000/api/inventaryPDF
Route::get('reset-password', [UserController::class, 'resetPasswordLoad']);
Route::get('/resetinfo', function () {
    return view('resetinfo');
});
