<?php
namespace App\DTOs\Auth;


use App\Http\Requests\Auth\RegisterRequest;

class StoreAuthDto
{

    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    )
    {
    }



    public static function fromRequest(RegisterRequest $request)
    {

        $validated = $request->validated();

        return new self(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password'),
        );



    }

}
