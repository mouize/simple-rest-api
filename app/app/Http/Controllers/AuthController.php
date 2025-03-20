<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->createUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send email verification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Account created successfully. Please check your email to verify your account.',
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $user = $this->userRepository->findOrFail($request->route('id'));

        if (! hash_equals((string) $request->route('hash'), sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userRepository->getUserByEmail($request->email);

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
