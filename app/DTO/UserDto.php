<?php

namespace App\DTO;

class UserDto
{
    public function __construct(
        public $firstName,
        public $lastName,
        public $mobile,
        public $password,
        public $type,
        public $name,
        public $url,
        public $otp,
        public $newUser,
        public $resetPasswordToken,
        public $mobileVerifiedAt,
    ) {
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
            $array['newUser'] ?? null,
            $array['token'] ?? null,
            $array['mobileVerifiedAt'] ?? null,
        );
    }
}
