<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // POST [name, email, password]
    public function register(Request $request){

        // Validation
        $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|min:8|confirmed"
        ]);

        // Create user
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        // Return JWT token
        // $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            "status" => 200,
            "message" => "User created successfully",
            // "token" => $token
            "data" => []
        ]);
    }

    // POST [name, email, password]
    public function login(Request $request){

        // Validation
        $request->validate([
            "email" => "required|string|email",
            "password" => "required|max:24"
        ]);

        // Email Existency Checking
        // User object
        $user = User::where("email", $request->email)->first();

        if(!empty($user)){
            // user exists
            if(Hash::check($request->password, $user->password)){
                // Password match
                // Auth Token genarate
                $token = $user->createToken('authToken')->accessToken;
                return response()->json([
                    "status" => 200,
                    "message" => "User logged in successfully",
                    "token" => $token,
                    "data" => []
                ]);
            }else{

                // \Log::info("Password mismatch for email: " . $request->email);

                return response()->json([
                    "status" => 401,
                    "message" => "Incorrect password",
                    "data" => []
                ]);
            }
        }else{
            return response()->json([
                "status" => 404,
                "message" => "User not found",
                "data" => []
            ]);
        }
    }

    // GET [Auth:token]
    public function profile(){

        // Authenticate user
        // Profile information
        $userData = auth()->user();

        return response()->json([
            "status" => 200,
            "message" => "User profile",
            "data" => $userData
        ]);
    }
    // POST [Auth:token]
    public function logout(Request $request){

        $token = auth()->user()->token();
        $token->revoke();

        return response()->json([
            "status" => 200,
            "message" => "User logged out successfully",
            "data" => []
        ]);
    }
}
