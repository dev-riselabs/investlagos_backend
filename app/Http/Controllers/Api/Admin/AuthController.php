<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginAdminRequest;
use App\Http\Requests\Admin\RegisterAdminRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new admin user.
     *
     * Bootstrap rules:
     *  - If no admin exists yet, anyone with the configured signup token may
     *    create the first admin (or signup is open if no token is configured).
     *  - Otherwise the request must be authenticated as an existing admin.
     */
    public function register(RegisterAdminRequest $request): JsonResponse
    {
        $hasAdmin = User::where('is_admin', true)->exists();
        $configuredToken = config('services.admin.signup_token');

        if ($hasAdmin) {
            // Resolve the bearer token manually since this route is not behind
            // auth:sanctum (so the bootstrap call can still succeed unauthenticated).
            $user = Auth::guard('sanctum')->user();
            if (! $user || ! $user->isAdmin()) {
                return response()->json([
                    'message' => 'Only existing admins can create new admin accounts.',
                ], 403);
            }
        } elseif ($configuredToken) {
            $provided = $request->header('X-Admin-Signup-Token') ?? $request->input('signup_token');
            if (! hash_equals((string) $configuredToken, (string) $provided)) {
                throw ValidationException::withMessages([
                    'signup_token' => ['Invalid or missing admin signup token.'],
                ]);
            }
        }

        $data = $request->validated();

        $admin = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'is_admin' => true,
        ]);

        $token = $admin->createToken('admin-access')->plainTextToken;

        return response()->json([
            'message' => 'Admin account created successfully.',
            'token'   => $token,
            'user'    => $admin->only(['id', 'name', 'email', 'is_admin', 'created_at']),
        ], 201);
    }

    public function login(LoginAdminRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => ['This account does not have admin access.'],
            ]);
        }

        // Revoke previous tokens for this device label so the latest one wins.
        $user->tokens()->where('name', 'admin-access')->delete();
        $token = $user->createToken('admin-access')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully.',
            'token'   => $token,
            'user'    => $user->only(['id', 'name', 'email', 'is_admin']),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => $user?->only(['id', 'name', 'email', 'is_admin', 'created_at']),
        ]);
    }
}
