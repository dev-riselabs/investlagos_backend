<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginAdminRequest;
use App\Http\Requests\Admin\RegisterAdminRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new admin user. Signup is open — anyone with valid details
     * may create an admin account.
     */
    public function register(RegisterAdminRequest $request): JsonResponse
    {
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
