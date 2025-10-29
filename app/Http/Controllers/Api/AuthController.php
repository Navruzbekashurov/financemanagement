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
use Laravel\Socialite\Facades\Socialite;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AuthResource",
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Navruzbek Ashurov"),
 *         @OA\Property(property="email", type="string", example="navruz@gmail.com")
 *     ),
 *     @OA\Property(property="token", type="string", example="1|XyzABC123...")
 * )
 *
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="Navruzbek"),
 *     @OA\Property(property="email", type="string", example="navruz@gmail.com"),
 *     @OA\Property(property="password", type="string", example="secret123")
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", example="navruz@gmail.com"),
 *     @OA\Property(property="password", type="string", example="secret123")
 * )
 */
class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->create(StoreAuthDto::fromRequest($request));
        return new AuthResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout user",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Successfully logged out")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Tizimdan chiqildi.']);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Get current authenticated user",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user info",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
     *     )
     * )
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * @OA\Get(
     *     path="/api/auth/google/url",
     *     summary="Get Google authentication redirect URL",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Google redirect URL",
     *         @OA\JsonContent(@OA\Property(property="url", type="string", example="https://accounts.google.com/o/oauth2/auth?..."))
     *     )
     * )
     */
    public function getGoogleAuthUrl()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return response()->json(['url' => $url]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/google/callback",
     *     summary="Handle Google login callback",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful Google login",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
     *     ),
     *     @OA\Response(response=500, description="Google Auth error")
     * )
     */
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
