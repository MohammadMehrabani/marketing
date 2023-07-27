<?php

namespace App\Contracts;

interface UserAuthenticateServiceInterface
{
    public function authenticate(array $arguments);
    public function sendOtp(array $arguments);
    public function verifyOtp(array $arguments);
    public function register(array $arguments);
    public function login(array $arguments);
    public function refresh();
    public function changePassword(array $arguments);
    public function getTokenPasswordReset(array $arguments);
}
