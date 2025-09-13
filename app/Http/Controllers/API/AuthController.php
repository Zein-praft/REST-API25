<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = user::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'Succes',
            "message"  => 'User berhasil mendaftar',
            'data' => [
                'user' => $user,
                'token' => $this->respondWithToken($token)
            ]
            ], 201);
    }

    public function login(Request $request)
    {

        // DI tambahkan

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|filled',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email dan password di isi',
                'errors' => $validator->errors()
            ], 422);
        }

        // Sampai sini

        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'invalid credential'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token gagal di buat',
                'error' => $e->getMessage()
            ], 500);
        }
        return response()->json([
            'status' => 'succes',
            'message' => 'Berhasil login',
            'data' => $this->respondWithToken($token)
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::Invalidate(JWTAuth::getToken());

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil logout'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal logout, token tidak valid',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            response()->json([
                'status' => 'succes',
                'message' => 'Token telah diperbarui',
                'data' => $this->respondWithToken($newToken)
            ]);
        } catch (JWTException $e) {
            return response ()->json([
                'status' => 'error',
                'message' => 'gagal memperbarui token',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            response()->json([
                'status' => 'succes',
                'message' => 'User profile',
                'data' => $user,
            ]);
        } catch (JWTException $e) {
            response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid atau expired token',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }
}
