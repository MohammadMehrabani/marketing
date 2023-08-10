<?php

namespace App\Contracts;

use App\DTO\UserDto;

interface UserAuthenticateServiceInterface
{
    public function authenticate(UserDto $userDto);
    public function sendOtp(UserDto $userDto);
    public function verifyOtp(UserDto $userDto);
    public function register(UserDto $userDto);
    public function login(UserDto $userDto);
    public function refresh();
    public function resetPassword(UserDto $userDto);
    public function getTokenPasswordReset(UserDto $userDto);
}
