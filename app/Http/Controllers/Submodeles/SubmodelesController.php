<?php

namespace App\Http\Controllers\Submodeles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submodeles;
use App\Models\Module;

class SubmodelesController extends Controller
{
  public function submoduleAll()
  {
    try {
      $submodules = Submodeles::join('modules', 'submodules.module_id', '=', 'modules.id')
        ->select(
          'submodules.id',
          'submodules.name',
          'submodules.route',
          'submodules.create',
          'submodules.read',
          'submodules.update',
          'submodules.delete',
          'submodules.module_id',
          'modules.name as module_name'

        )
        ->orderBy('modules.name', 'asc')
        ->get();

      return response()->json([
        'success' => true,
        'msg' => 'Listado de submodules',
        'data' =>  $submodules
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al listar los submodules',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function submoduleId($id)
  {
    try {

      $submodules = Submodeles::where('id', $id)->get();
      return response()->json([
        'success' => true,
        'msg' => 'Listado de submodules',
        'data' =>  $submodules
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al listar los submodules',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function submoduleRegister(Request $request)
  {
    try {
      $submodule = new Submodeles();
      $submodule->name = $request->name;
      $submodule->route = $request->route;
      $submodule->create = $request->create;
      $submodule->read = $request->read;
      $submodule->update = $request->update;
      $submodule->delete = $request->delete;
      $submodule->module_id = $request->module_id;
      $submodule->save();
      return response()->json([
        'success' => true,
        'msg' => 'Submodule registrado',
        'data' =>  $submodule
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al registrar el submodule',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function submoduleUpdate(Request $request, $id)
  {
    try {
      $submodule = Submodeles::find($id);

      $submodule->name = $request->name ? $request->name : $submodule->name;
      $submodule->route = $request->route ? $request->route : $submodule->route;
      $submodule->create = $request->create;
      $submodule->read = $request->read;
      $submodule->update = $request->update;
      $submodule->delete = $request->delete;
      $submodule->module_id = $request->module_id ? $request->module_id : $submodule->module_id;
      $submodule->save();
      return response()->json([
        'success' => true,
        'msg' => 'Submodule actualizado',
        'data' =>  $submodule
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al actualizar el submodule',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function submoduleDelete($id)
  {
    try {
      $submodule = Submodeles::find($id);
      $submodule->delete();
      return response()->json([
        'success' => true,
        'msg' => 'Submodule eliminado',
        'data' =>  $submodule
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al eliminar el submodule',
        'data' =>  $e->getMessage()
      ]);
    }
  }
}
