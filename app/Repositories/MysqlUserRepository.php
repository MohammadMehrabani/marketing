<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\DTO\UserDto;
use App\Models\User;

class MysqlUserRepository extends MysqlBaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }

    public function create(UserDto $arguments)
    {
        return User::create([
            'firstname' => $arguments->firstName,
            'lastname' => $arguments->lastName,
            'mobile' => $arguments->mobile,
            'password' => $arguments->password,
            'type' => $arguments->type,
            'name' => $arguments->name,
            'url' => $arguments->url
        ]);
    }

    public function update(User $user, UserDto $arguments)
    {
        $user->update([
            'firstname' => $arguments->firstName ?: $user->firstname,
            'lastname' => $arguments->lastName ?: $user->lastname,
            'type' => $arguments->type ?: $user->type,
            'name' => $arguments->name ?: $user->name,
            'url' => $arguments->url ?: $user->url,
            'password' => $arguments->password ?: $user->password,
            'mobile_verified_at' => $arguments->mobileVerifiedAt ?: $user->mobile_verified_at
        ]);

        return $user;
    }

    public function findByMobile($mobile)
    {
        return User::query()->where('mobile', $mobile)->first();
    }
}
