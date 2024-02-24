<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //Register API
    public function register(Request $request) {
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        // Data Save
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        // Response
        return response()->json([
            "status" => true,
            "message" => "User created successfully"
        ]);
    }

    //Login API  
    public function login(Request $request) {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){

            return response()->json([
                "status" => true,
                "message" => "User Logged In Sucessfully!",
                "token" => $token
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid Login Details"
        ]);       
    }

    //Profile API
    public function profile() {
        $userData = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile Data",
            "user" => $userData
        ]);
    }

    //Refresh Token API
    public function refreshToken() {
        $newToken = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New Access Token Generated",
            "token" => $newToken
        ]);
    }

    //Logout API
    public function logout() {
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User Logged Out Successfully"
        ]);
    }
}
