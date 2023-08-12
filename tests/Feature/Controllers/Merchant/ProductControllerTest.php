<?php

namespace Tests\Feature\Controllers\Merchant;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_access_personal_products_list_without_merchant_user_api_token(): void
    {
        $this->json('get','/api/merchant/products')
            ->assertExactJson(['success' => false, "errors" => "Authorization Token not found"]);
    }

    public function test_access_personal_products_list_with_merchant_user_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->merchantToken)
            ->json('get','/api/merchant/products')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll('data.items', 'data.pagination', 'success')
                    ->missing('errors')
            );
    }

    public function test_not_access_personal_products_list_with_marketer_user_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->marketerToken)
            ->json('get','/api/merchant/products')
            ->assertStatus(401)
            ->assertExactJson(['success' => false, "errors" => "Token is Invalid!"]);
    }

    public function test_not_access_personal_products_list_with_expire_api_token(): void
    {
        // in the jwt.php file, ttl is set to 60 minutes by default
        $this->travel(5)->hours();

        $this->withHeader('Authorization', 'Bearer '.$this->merchantToken)
            ->json('get','/api/merchant/products')
            ->assertStatus(401)
            ->assertExactJson(['success' => false, "errors" => "Token is Expired"]);
    }

    public function test_not_access_personal_products_list_with_invalid_api_token(): void
    {
        $this->withHeader('Authorization', 'Bearer test')
            ->json('get','/api/merchant/products')
            ->assertStatus(401)
            ->assertExactJson(['success' => false, "errors" => "Token is Invalid"]);
    }
}
