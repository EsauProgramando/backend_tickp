<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
  public function login(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'email|max:255',
      'password' => 'required|min:6'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }


    if (!$token = JWTAuth::attempt($validator->validated())) {
      return response()->json(['success' => false, 'msg' => 'Correo O Password incorecto']);
    }
    return  $this->respondWithToken($token);
  }

  protected function respondWithToken($token)
  {
    $expiration = Carbon::now()->addDays(7);
    $user =
      User::join('rols', 'rols.id', '=', 'users.rol_id')
      ->select('users.id', 'users.name', 'users.email', 'users.rol_id', 'rols.name as rol')
      ->where('users.id', auth()->user()->id)
      ->first();
    $newToken = JWTAuth::fromUser($user, ['exp' => $expiration->timestamp]);
    return response()->json([
      'success' => true,
      'user' => $user,
      'access_token' => $newToken,
      'token_type' => 'Bearer',
      'expires_in' => JWTAuth::factory()->getTTL() * 60

    ]);
  }
}
