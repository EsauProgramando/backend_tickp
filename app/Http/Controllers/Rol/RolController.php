<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
class RolController extends Controller
{

      public function rolAll(){
          try{
            $rols = Rol::all();
            return response()->json([
                'success' => true,
                'msg' => 'Listado de roles',
                'data' =>  $rols
            ]);
          } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al listar los roles',
                'data' =>  $e->getMessage()
            ]);
          }

        
      }

       public function rolId($id){
          try{
            $rols = Rol::find($id);
            return response()->json([
                'success' => true,
                'msg' => 'Listado de rol',
                'data' =>  $rols
            ]);
          } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al listar los roles',
                'data' =>  $e->getMessage()
            ]);
          }
        }

        public function rolRegister(Request $request){
          try{
            $rol = new Rol();
            $rol->name = $request->name;       
            $rol->save();
            return response()->json([
                'success' => true,
                'msg' => 'Rol registrado',
                'data' =>  $rol
            ]);
          } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al registrar el rol',
                'data' =>  $e->getMessage()
            ]);
          }
        }

        public function rolUpdate(Request $request, $id){
          try{
            $rol = Rol::find($id);
            $rol->name = $request->name;       
            $rol->save();
            return response()->json([
                'success' => true,
                'msg' => 'Rol actualizado',
                'data' =>  $rol
            ]);
          } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al actualizar el rol',
                'data' =>  $e->getMessage()
            ]);
          }
        }

      public function rolDelete($id){
          try{
            $rol = Rol::find($id);
            $rol->delete();
            return response()->json([
                'success' => true,
                'msg' => 'Rol eliminado',
                'data' =>  $rol
            ]);
          } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al eliminar el rol',
                'data' =>  $e->getMessage()
            ]);
          }
        }


}
