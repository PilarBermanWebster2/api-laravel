<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|unique:users",
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        return response()->json(
            [
                "success" => true,
                "message" => "User Successfully Added",
                "data" => $user,
            ],
            201
        );
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json(
                [
                    "message" => "Unauthorized",
                ],
                401
            );
        }

        $user = User::where("email", $request->email)->firstOrFail();
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json(
            [
                "message" => "Successfully Logged In!",
                "access_token" => $token,
                "type" => "Bearer",
            ],
            200
        );
    }
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json(
            [
                "message" => "Successfully Logged Out",
            ],
            200
        );
    }
}
