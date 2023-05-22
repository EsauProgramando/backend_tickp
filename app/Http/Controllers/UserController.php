<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use  Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function allUser()
    {


        $usersRol = User::join('rols', 'users.rol_id', '=', 'rols.id')
            ->select('users.*', 'rols.name as rol_name') //NO QUIERO MOSTRAR UN CAMPO EN SELECT
            ->get();

        return response()->json([
            'success' => true,
            'msg' => 'Listado de usuarios',
            'data' =>  $usersRol
        ]);
    }
    public function index()
    {
        try {
            return response()->json(['success' => true, 'data' => auth()->user()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function updateProfile(Request $request)
    {
        if (auth()->user()) {
            $validator = Validator::make($request->all(), [
                'name' => 'max:100|min:2',
                'email' => 'max:255|unique:users',
                'password' => 'min:6|confirmed'

            ]);



            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $user = User::find(auth()->user()->id);
            $user->name = $request->name ? $request->name : $user->name;
            $user->email = $request->email ? $request->email : $user->email;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->save();
            return response()->json(['success' => true, 'data' => $user, 'msg' => 'User updated successfully']);
        } else {
            return response()->json(['success' => false, 'msg' => 'User is not logged in']);
        }
    }

    public function show($user_id)
    {
        $user = User::find($user_id);
        $user = User::join('rols', 'users.rol_id', '=', 'rols.id')
            ->select('users.*', 'rols.name as rol_name') //NO QUIERO MOSTRAR UN CAMPO EN SELECT
            ->where('users.id', '=', $user_id)
            ->get();
        if (is_null($user)) {
            return response()->json(['success' => false, 'msg' => 'User not found']);
        }
        return response()->json([
            'success' => true,
            'msg' => 'User found',
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if (is_null($user)) {
            return response()->json(['success' => false, 'msg' => 'User not found']);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'max:100|min:2',
            'email' => 'max:255|unique:users',
            'password' => 'min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // editar perfil de usuario auth
        if ($user_id != null  || $user_id != '') {
            $user->name = $request->name ? $request->name : $user->name;
            $user->email = $request->email ? $request->email : $user->email;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->save();
            return response()->json(['success' => true, 'data' => $user, 'msg' => 'User updated successfully']);
        }

        return response()->json(['success' => false, 'msg' => 'Algo salio mal']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        $user = User::find($user_id);
        if (is_null($user)) {
            return response()->json(['success' => false, 'msg' => 'User not found']);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'msg' => 'User deleted successfully'
        ]);
    }

    //Registro de usuario
    public function register(Request $request, $rol_id)
    {

        try {

            $rol = Rol::find($rol_id);
            if (is_null($rol)) {
                return response()->json(['success' => false, 'msg' => 'el rol no existe']);
            }
            // validar los datos que se reciben
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100|min:2',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => $rol_id,
            ]);

            $token = JWTAuth::fromUser($user);
            return response()->json([
                'user' => $user,
                '$token' => $token,
                'message' => 'Successfully created user!',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => $th->getMessage()]);
        }
    }

    // forget password api method
     public function forgetPassword(Request $request){
        try {
          $user = User::where('email', $request->email)->get();
          if(count($user) >0){
              $token = Str::random(40);
              $domain =URL::to('/');
              $url = $domain.'/reset-password?token='.$token;

              $data['url'] = $url;
              $data['email'] = $request->email;
              $data['title'] = 'Restablecer contraseña';
              $data['body'] = 'Click On the link below to reset your password';

              Mail::send('forgertPasswordMail', ['data' => $data], function ($message) use ($data) {
                  $message->to($data['email'])
                      ->subject($data['title']);
              });
              $datetime = Carbon::now()->format('Y-m-d H:i:s');
              PasswordReset::updateOrCreate(
                  ['email' => $request->email],
                  [
                      'email' => $request->email,
                      'token' => $token,
                      'created_at' => $datetime
                  ]
              );

              return response()->json(['success' => true, 'msg' => 'Email enviado']);

          }else{
            return response()->json(['success' => false, 'msg' => 'Email no encontrado']);
          }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }

     }

      // reset password view load
      public function resetPasswordLoad(Request $request){
        $token = $request->token;
        $passwordReset = PasswordReset::where('token', $token)->get();
        if(isset($passwordReset) && count($passwordReset) > 0){

            $user = User::where('email', $passwordReset[0]->email)->get();
            return view('resetPassword', compact('user'));
        }else{
            return view('404');
        }
      }

      //password reset functionality 
    public function resetPassword(Request $request){
      try {
        
        $validator = Validator::make($request->all(), [
          'password' => 'required|min:6',
      ]);

      if ($validator->fails()) {
          return  $validator->errors();
      }
      $user =User::find($request->id);
      $user->password = Hash::make($request->password);
      $user->save();

       PasswordReset::where('email', $user->email)->delete();
      return   response()->json(['success' => true, 'msg' => 'Password reset successfully']);

      } catch (\Throwable $th) {
        return response()->json(['success' => false, 'msg' => $th->getMessage()]);
      }
            
      
    }

}
