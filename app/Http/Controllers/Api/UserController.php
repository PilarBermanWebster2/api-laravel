<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return response()->json(
            [
                "success" => true,
                "message" => "Daftar pengguna",
                "data" => $users,
            ],
            200
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validation Failed",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password); // Menggunakan bcrypt untuk mengenkripsi password
            $user->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "User successfully created",
                    "data" => $user,
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "There was a problem",
                    "errors" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(
                [
                    "success" => true,
                    "message" => "User found",
                    "data" => $user,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "User Not Found",
                    "errors" => $e->getMessage(),
                ],
                404
            );
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" =>
                "required|string|email|max:255|unique:users,email," . $id,
            "password" => "nullable|string|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validation Failed",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled("password")) {
                $user->password = bcrypt($request->password); // Menggunakan bcrypt untuk mengenkripsi password
            }
            $user->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "User successfully updated",
                    "data" => $user,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "There was a problem",
                    "errors" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(
                [
                    "success" => true,
                    "message" => "User successfully deleted",
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "User Not Found or There was a problem",
                    "errors" => $e->getMessage(),
                ],
                404
            );
        }
    }
}
