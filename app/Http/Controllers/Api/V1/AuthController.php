<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController
 *
 * Handles authentication endpoints: login, register, logout, profile.
 */
class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login endpoint.
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->authenticate(
            $request->input('username'),
            $request->input('password')
        );

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Register endpoint.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $token = $user->createToken('api_token')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Registration successful', 201);
    }

    /**
     * Logout endpoint.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }

    /**
     * Profile endpoint.
     */
    public function profile(Request $request)
    {
        return $this->successResponse(
            new UserResource($request->user()),
            'Profile retrieved'
        );
    }
}
