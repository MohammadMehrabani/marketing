<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected Model $merchant;
    protected string $merchantToken;
    protected Model $marketer;
    protected string $marketerToken;

    protected function setUp(): void
    {
        parent::setUp();
        list($marketer, $marketerToken) = $this->getUserToken(['type' => 'marketer']);
        list($merchant, $merchantToken) = $this->getUserToken(['type' => 'merchant']);
        $this->merchant = $merchant;
        $this->merchantToken = $merchantToken;
        $this->marketer = $marketer;
        $this->marketerToken = $marketerToken;
    }

    protected function getUserToken(array $attributes = []): array
    {
        $user = User::factory()->create($attributes);
        $token = $this->json('post', '/api/user/login', [
            'mobile' => $user->mobile,
            'password' => 'password'
        ]);
        $token = json_decode($token->getContent())->data->access_token;
        return [$user, $token];
    }
}
