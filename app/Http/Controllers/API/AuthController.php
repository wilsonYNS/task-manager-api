<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
    * @OA\Post(
    *     path="/api/register",
    *     summary="Register a new user",
    *     tags={"Auth"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"name","email","password"},
    *             @OA\Property(property="name", type="string", example="John Doe"),
    *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
    *             @OA\Property(property="password", type="string", format="password", example="password")
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="User registered"
    *     )
    * )
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    /**
    * @OA\Post(
    *     path="/api/login",
    *     summary="Log in a user",
    *     tags={"Auth"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"email","password"},
    *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
    *             @OA\Property(property="password", type="string", format="password", example="password")
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Login successful"
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Invalid credentials"
    *     )
    * )
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    /**
    * @OA\Post(
    *     path="/api/logout",
    *     summary="Log out the authenticated user",
    *     tags={"Auth"},
    *     security={{"sanctum":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Logged out successfully"
    *     )
    * )
    */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // revoke all tokens

        return response()->json(['message' => 'Logged out']);
    }
}

