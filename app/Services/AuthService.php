<?php

namespace App\Services;

use App\DTOs\Auth\StoreAuthDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{


    public function create(StoreAuthDto $dto)
    {

        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);

    }


    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email yoki parol noto‘g‘ri.'],
            ]);
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

}
