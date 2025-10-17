<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; 

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;




class AuthController extends Controller
{
     public function register(Request $request)
    {

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);

        $token = $user->createToken('auth_token')->plainTextToken;



      /*  return response()->json(['token' => $token, 'user' => $user], 201);*/

       return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
        ], 201);
    }


        public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user_id' => $user->id,
            'token' => $token,
        ], 200);

    
    }


    /*
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    */
}
