<?php

namespace App\Contracts;

interface PasswordResetTokenRepositoryInterface
{
    public function create(string $mobile);
    public function findByMobile(string $mobile);
    public function findByMobileAndToken(string $mobile, string $resetPasswordToken);
    public function deleteByMobile(string $mobile);
}
