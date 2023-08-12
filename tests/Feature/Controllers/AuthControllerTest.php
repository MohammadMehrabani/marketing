<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_access_user_info_without_api_token(): void
    {
        $this->json('get','/api/user/me')
            ->assertExactJson(['success' => false, "errors" => "Authorization Token not found"]);
    }

    public function test_not_access_user_info_with_expire_api_token(): void
    {
        // in the jwt.php file, ttl is set to 60 minutes by default
        $this->travel(5)->hours();

        $this->withHeader('Authorization', 'Bearer '.$this->merchantToken)
            ->json('get','/api/user/me')
            ->assertStatus(401)
            ->assertExactJson(['success' => false, "errors" => "Token is Expired"]);

        $this->withHeader('Authorization', 'Bearer '.$this->marketerToken)
            ->json('get','/api/user/me')
            ->assertStatus(401)
            ->assertExactJson(['success' => false, "errors" => "Token is Expired"]);
    }

    public function test_not_access_user_info_with_invalid_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer test')
            ->json('get','/api/user/me')
            ->assertStatus(401)
            ->assertExactJson(['success' => false, "errors" => "Token is Invalid"]);
    }

    public function test_access_merchant_user_info_with_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->merchantToken)
            ->json('get','/api/user/me')
            ->assertStatus(200)
            ->assertJsonPath('data.mobile', $this->merchant->mobile);
    }

    public function test_access_marketer_user_info_with_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->marketerToken)
            ->json('get','/api/user/me')
            ->assertStatus(200)
            ->assertJsonPath('data.mobile', $this->marketer->mobile);
    }

    public function test_not_access_merchant_user_info_with_marketer_user_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->marketerToken)
            ->json('get','/api/user/me')
            ->assertStatus(200)
            ->assertJsonMissing(['mobile' => $this->merchant->mobile])
            ->assertJsonPath('data.mobile' ,$this->marketer->mobile);
    }

    public function test_not_access_marketer_user_info_with_merchant_user_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->merchantToken)
            ->json('get','/api/user/me')
            ->assertStatus(200)
            ->assertJsonMissing(['mobile' => $this->marketer->mobile])
            ->assertJsonPath('data.mobile' ,$this->merchant->mobile);
    }
}
