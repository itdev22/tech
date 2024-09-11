<?php

namespace App\Http\Controllers;

use App\Events\LoginEvent;
use App\Helper\ResponseFormat;
use App\Http\Requests\StoreAuthRequest;
use App\Mail\Mailgun;
use App\Models\User;
use App\Utils\MailSender;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator as Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            ])->validate();

            $random = Str::random(10);

            MailSender::SendEmailText($validated['email'], 'Email Verification', 'Click this link to verify your email: ' . url('/api/verification-email/' . $random));

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'remember_token' => $random,
            ]);

            return response()->json(ResponseFormat::success('', 'User registered successfully', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                // return response()->json(['message' => 'Unauthorized'], 401);

                return response()->json(ResponseFormat::BadRequest('Unauthorized', 401), 401);
            }

            $user = User::where('email', $request['email'])->where('email_verified_at', '!=', NULL)->first();
            if (!$user) {
                return response()->json(ResponseFormat::BadRequest('Email Not Verif', 401), 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            event(new LoginEvent($user));
            // sleep(1);

            return response()->json(ResponseFormat::success(['access_token' => $token, 'token_type' => 'Bearer'], 'User registered successfully', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(ResponseFormat::success(null, 'Logged out', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function verification(Request $request, $token)
    {
        try {
            $user = User::where('remember_token', $token)->where('email_verified_at', NULL)->first();
            if (!$user) {
                return response()->json(ResponseFormat::BadRequest('Token not found', 404), 404);
            }

            $user->update([
                'email_verified_at' => Carbon::now(),
                'remember_token' => NULL
            ]);

            return response()->json(ResponseFormat::success('', 'Email verified', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $random=Str::random(10);
            $validated = Validator::make($request->all(), [
                'email' => 'required|email',
                ])->validate();

                $user = User::where('email', $validated['email'])->first();
                if (!$user) {
                    return response()->json(ResponseFormat::BadRequest('Email not found', 404), 404);
                }

                $user->update([
                    'remember_token' =>$random
                ]);
                MailSender::SendEmailText($validated['email'], 'Reset Password', 'this is your token: ' . $random);

                return response()->json(ResponseFormat::success('', 'Token sent to email', 200), 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error',
                    'error' => $th->getMessage()
                ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'token' => 'required|string',
                'password' => 'required|string',
            ])->validate();

            $user = User::where('remember_token', $validated['token'])->first();
            if (!$user) {
                return response()->json(ResponseFormat::BadRequest('Email not found', 404), 404);
            }

            $user->update([
                'password' => Hash::Make($validated['password']),
                'remember_token' => NULL
            ]);

            return response()->json(ResponseFormat::success('', 'Success Update Password', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
