<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\RoleMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'] ?? null,
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        // Assign default role
        $user->assignRole(RoleMapper::normalizeSingle('Member'));

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'roles' => $user->getRoleNames(),
                ],
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact administrator.',
            ], 403);
        }

        // Revoke previous tokens
        $user->tokens()->delete();

        $deviceName = $request->device_name ?? 'mobile-app';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'cohort' => $user->cohort ? [
                        'id' => $user->cohort->id,
                        'name' => $user->cohort->name,
                        'year' => $user->cohort->year,
                    ] : null,
                    'division' => $user->division ? [
                        'id' => $user->division->id,
                        'name' => $user->division->name,
                    ] : null,
                    'roles' => $user->getRoleNames(),
                    'is_active' => $user->is_active,
                ],
                'token' => $token,
            ],
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['cohort', 'division']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'phone' => $user->phone,
                'address' => $user->address,
                'bio' => $user->bio,
                'blood_type' => $user->blood_type,
                'cohort' => $user->cohort ? [
                    'id' => $user->cohort->id,
                    'name' => $user->cohort->name,
                    'year' => $user->cohort->year,
                ] : null,
                'division' => $user->division ? [
                    'id' => $user->division->id,
                    'name' => $user->division->name,
                ] : null,
                'emergency_contact' => [
                    'name' => $user->emergency_contact_name,
                    'phone' => $user->emergency_contact_phone,
                    'relation' => $user->emergency_contact_relation,
                ],
                'medical_info' => [
                    'allergies' => $user->allergies,
                    'conditions' => $user->medical_conditions,
                ],
                'roles' => $user->getRoleNames(),
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:100'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()->load(['cohort', 'division']),
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Revoke all tokens except current
        $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }
}
