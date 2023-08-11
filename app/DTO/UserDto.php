<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\ValidatedInput;

class UserDto
{
    public function __construct(
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $mobile,
        public readonly ?string $password,
        public readonly ?string $type,
        public readonly ?string $name,
        public readonly ?string $url,
        public readonly ?string $otp,
        public readonly ?string $resetPasswordToken,
        public readonly ?string $mobileVerifiedAt,
    ) {}

    public static function fromRequest(Request|ValidatedInput $request)
    {
        return new self(
            $request->firstname ?? null,
            $request->lastname ?? null,
            $request->mobile ?? null,
            $request->password ?? null,
            $request->type ?? null,
            $request->name ?? null,
            $request->url ?? null,
            $request->code ?? null,
            $request->token ?? null,
            $request->mobileVerifiedAt ?? null,
        );
    }

    public static function fromArray(array $array)
    {
        return new self(
            $array['firstname'] ?? null,
            $array['lastname'] ?? null,
            $array['mobile'] ?? null,
            $array['password'] ?? null,
            $array['type'] ?? null,
            $array['name'] ?? null,
            $array['url'] ?? null,
            $array['code'] ?? null,
            $array['token'] ?? null,
            $array['mobileVerifiedAt'] ?? null,
        );
    }
}
