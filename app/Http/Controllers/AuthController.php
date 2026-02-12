<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{  public function register()
    {
        $validate = request()->validate([
            "name" => "required",
            "email" => "required|unique:users,email|string",
            "password" => "required|min:6"
        ]);
        $create = User::create($validate);
        $create->assignRole("customer");
        
        return response()->json([
            "user" => $create
        ]);
    }
    public function login()
    {
        $validate = request()->validate([
            "email" => "required",
            "password" => "required"
        ]);
        if (!Auth::attempt($validate)) {
            return response()->json("unauthorized");
        }
        $user = Auth::user();
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json([
            "message" => "Logged in succesfully",
            "user" => $user,
            "token" => $token
        ],201);
    }
    public function logout(){
        request()->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
    }
   
}
