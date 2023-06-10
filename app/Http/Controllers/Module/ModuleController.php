<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{

  public function moduleAll()
  {
    try {
      $modules = Module::Select('id', 'name', 'icon')->orderBy('name', 'asc')->get();
      return response()->json([
        'success' => true,
        'msg' => 'Listado de modules',
        'data' =>  $modules
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al listar los modules',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function moduleId($id)
  {
    try {
      $modules = Module::find($id);
      return response()->json([
        'success' => true,
        'msg' => 'Listado de modules',
        'data' =>  $modules
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al listar los modules',
        'data' =>  $e->getMessage()
      ]);
    }
  }
  public function moduleRegister(Request $request)
  {
    try {
      $module = new Module();
      $module->name = $request->name;
      $module->icon = $request->icon;
      $module->save();
      return response()->json([
        'success' => true,
        'msg' => 'Module registrado',
        'data' =>  $module
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al registrar el module',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function moduleUpdate(Request $request, $id)
  {
    try {
      $module = Module::find($id);
      $module->name = $request->name;
      $module->icon = $request->icon;
      $module->save();
      return response()->json([
        'success' => true,
        'msg' => 'Module actualizado',
        'data' =>  $module
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al actualizar el module',
        'data' =>  $e->getMessage()
      ]);
    }
  }

  public function moduleDelete($id)
  {
    try {
      $module = Module::find($id);
      $module->delete();
      return response()->json([
        'success' => true,
        'msg' => 'Module eliminado',
        'data' =>  $module
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'msg' => 'Error al eliminar el module',
        'data' =>  $e->getMessage()
      ]);
    }
  }
}
