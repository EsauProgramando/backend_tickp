<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|min:2', //TODO:CAMBIAR NAME
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
        return response()->json([
            'success' => true,
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60

        ]);
    }
}
