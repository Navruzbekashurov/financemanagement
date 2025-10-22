<?php

namespace App\Services;

use App\DTOs\Auth\StoreAuthDto;
use App\Models\User;

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
}
