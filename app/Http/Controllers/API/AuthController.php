<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|email|unique:users,email',
           'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['password']),
        ]);

        // optionally create first company or not

        // return token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
           'user' => $user,
           'token' => $token,
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
           'email' => 'required|email',
           'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke existing tokens? depends on policy. We'll keep multi tokens.
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
           'user' => $user,
           'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        // If using sanctum tokens and token-based auth
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
