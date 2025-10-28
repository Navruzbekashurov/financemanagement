<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\StoreAuthDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->authService->login($credentials['email'], $credentials['password']);

        return response()->json([
            'token' => $token,
            'user'  => User::where('email', $credentials['email'])->first(),
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

    public function getGoogleAuthUrl()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return response()->json(['url' => $url]);
    }

    public function handleGoogleCallback(Request $request)
    {
        $token = $request->input('token');

        $googleUser = Socialite::driver('google')->stateless()->userFromToken($token);

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->id],
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now()
            ]
        );

        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Google orqali tizimga kirdingiz!',
            'user' => $user,
            'token' => $token
        ]);
    }
}
