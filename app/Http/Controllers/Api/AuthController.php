<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\StoreAuthDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService)
    {

    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->create(StoreAuthDto::fromRequest($request));

        return new AuthResource($user);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Error'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Tizimdan chiqildi.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function googleLogin(Request $request)
    {
        return response()->json(['message' => 'Google login hali qo‘shilmagan.']);
    }
}
