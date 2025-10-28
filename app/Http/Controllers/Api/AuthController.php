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

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            );

            Auth::login($user);

            return response()->json([
                'token' => $user->createToken('api')->plainTextToken,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Google Auth error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
