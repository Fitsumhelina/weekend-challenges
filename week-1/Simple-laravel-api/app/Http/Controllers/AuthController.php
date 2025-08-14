<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $requiredFields = ['name', 'email', 'password', 'password_confirmation'];
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                $missingFields[] = $field;
            }
        }
        if (!empty($missingFields)) {
            return response()->json([
                'message' => 'Missing required fields.',
                'missing_fields' => $missingFields
            ], 422);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8',
        ]);

        if ($data['password'] !== $data['password_confirmation']) {
            return response()->json(['message' => 'Password and password confirmation do not match.'], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        // $request->session()->regenerate();
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
            
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not registered.'], 404);
        }

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        // $request->session()->regenerate();
        return response()->json(['message' => 'Login successful', 'user' => $user]);
            
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function user(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            return response()->json(['user' => $user]);
        }
        return response()->json(['error' => 'User not logged in.'], 401);
    }
}