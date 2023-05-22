<?php

namespace App\Http\Controllers\Rolpermiso;

use App\Models\Rolpermiso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolpermisoController extends Controller
{
  public function rolpermisoAll()
  {
    try {
      $rolpermiso = Rolpermiso::all();
      return response()->json([
        'success' => true,
        'msg' => 'Listado de los rolpermiso',
        'data' =>  $rolpermiso
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al listar los rolpermiso',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function rolpermisoId($id)
  {

    try {
      $rolpermiso = Rolpermiso::find($id);
      return response()->json([
        'success' => true,
        'msg' => 'El rolpermiso',
        'data' =>  $rolpermiso
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al listar los rolpermiso',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function rolpermisoRegister(Request $request)
  {
    try {
      $rolpermiso = new Rolpermiso();
      $rolpermiso->rols_id = $request->rols_id;
      $rolpermiso->namepermido = $request->namepermido;
      $rolpermiso->create = $request->create;
      $rolpermiso->read = $request->read;
      $rolpermiso->update = $request->update;
      $rolpermiso->delete = $request->delete;
      $rolpermiso->submodules_id = $request->submodules_id;
      $rolpermiso->save();
      return response()->json([
        'success' => true,
        'msg' => 'Rolpermiso registrado',
        'data' =>  $rolpermiso
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al registrar el rolpermiso',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function rolpermisoUpdate(Request $request, $id)
  {
    try {
      $rolpermiso = Rolpermiso::find($id);
      $rolpermiso->rols_id = $request->rols_id ? $request->rols_id : $rolpermiso->rols_id;
      $rolpermiso->namepermido = $request->namepermido ? $request->namepermido : $rolpermiso->namepermido;
      $rolpermiso->create = $request->create;
      $rolpermiso->read = $request->read;
      $rolpermiso->update = $request->update;
      $rolpermiso->delete = $request->delete;
      $rolpermiso->submodules_id = $request->submodules_id ? $request->submodules_id : $rolpermiso->submodules_id;
      $rolpermiso->save();
      return response()->json([
        'success' => true,
        'msg' => 'Rolpermiso actualizado',
        'data' =>  $rolpermiso
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al actualizar el rolpermiso',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function  rolpermisoDelete($id)
  {
    try {
      $rolpermiso = Rolpermiso::find($id);
      $rolpermiso->delete();
      return response()->json([
        'success' => true,
        'msg' => 'Rolpermiso eliminado',
        'data' =>  $rolpermiso
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al eliminar el rolpermiso',
        'data' =>  $e->getMessage()
      ]);
    }
  }
}
