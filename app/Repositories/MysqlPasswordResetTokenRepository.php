<?php

namespace App\Repositories;

use App\Contracts\PasswordResetTokenRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MysqlPasswordResetTokenRepository implements PasswordResetTokenRepositoryInterface
{
    public function create(string $mobile)
    {
        return DB::table('password_reset_tokens')->insert([
            'mobile' => $mobile,
            'token' => Str::random(32),
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function findByMobile(string $mobile)
    {
        return DB::table('password_reset_tokens')
            ->where('mobile', $mobile)
            ->first();
    }

    public function findByMobileAndToken(string $mobile, string $resetPasswordToken)
    {
        return DB::table('password_reset_tokens')
            ->where('mobile', $mobile)
            ->where('token', $resetPasswordToken)
            ->first();
    }

    public function deleteByMobile(string $mobile)
    {
        return DB::table('password_reset_tokens')
            ->where('mobile', $mobile)
            ->delete();
    }
}
