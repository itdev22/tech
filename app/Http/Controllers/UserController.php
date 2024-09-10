<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormat;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json(ResponseFormat::Success($users,'Get User',200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ];
        try {
            $validated = $request->validate($rules);
            $user = User::create($validated);
            return response()->json(ResponseFormat::Success($user,'User Created',201), 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(ResponseFormat::BadRequest('User not found',404), 404);
            }
            return response()->json(ResponseFormat::Success($user,'User Detail',200), 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'string',
            'email' => 'email|unique:users',
            'password' => 'string|min:6',
        ];
        try {
            $validated = $request->validate($rules);

            $user = User::find($id);
            if (!$user) {
                return response()->json(ResponseFormat::BadRequest('User not found',404), 404);
            }

            $user->update($validated);
            return response()->json(ResponseFormat::Success($user,'User Updated',200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(ResponseFormat::BadRequest('User not found',404), 404);
            }

            $user->delete();
            return response()->json(ResponseFormat::Success('','User Deleted',200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
