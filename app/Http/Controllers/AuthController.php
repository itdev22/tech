<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormat;
use App\Http\Requests\StoreAuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ])->validate();


        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(ResponseFormat::success([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'User registered successfully', 200), 200);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            // return response()->json(['message' => 'Unauthorized'], 401);

            return response()->json(ResponseFormat::BadRequest('Unauthorized', 401), 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(ResponseFormat::success(['access_token' => $token, 'token_type' => 'Bearer'], 'User registered successfully', 200), 200);
    }

    public function logout(Request $request)
    {
        // dd($request->user()->currentAccessToken());
        $request->user()->currentAccessToken()->delete();

        return response()->json(ResponseFormat::success(null, 'Logged out', 200), 200);
    }
}
