<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,parent,teacher',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_approved' => false,
                'is_active' => true,
            ]);

            // Create approval request
            Approval::create([
                'user_id' => $user->id,
                'approvable_type' => 'User',
                'approvable_id' => $user->id,
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'User registered successfully. Awaiting approval.',
                'user' => $user->only(['id', 'name', 'email', 'phone', 'role']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user is approved
        if (!$user->is_approved) {
            return response()->json([
                'message' => 'Your account is pending approval. Please wait for admin review.',
                'status' => 'pending_approval'
            ], 403);
        }

        // Check if user is active
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account has been deactivated.',
                'status' => 'inactive'
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'user' => $user->only(['id', 'name', 'email', 'phone', 'role', 'is_approved']),
        ], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Get authenticated user
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'is_approved' => $user->is_approved,
            'is_active' => $user->is_active,
        ];

        // Load role-specific data
        if ($user->role === 'student' && $user->student) {
            $data['student'] = $user->student->only(['id', 'admission_number', 'class_level_id', 'stream_id']);
        }

        if ($user->role === 'teacher' && $user->staff) {
            $data['staff'] = $user->staff->only(['id', 'staff_number', 'position']);
        }

        return response()->json($data, 200);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|unique:users,phone,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->only(['id', 'name', 'email', 'phone', 'role']),
        ], 200);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update(['password' => Hash::make($validated['new_password'])]);

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}

