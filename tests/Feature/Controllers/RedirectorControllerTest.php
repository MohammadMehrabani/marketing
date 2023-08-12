<?php

namespace Tests\Feature\Controllers;

use App\Models\MarketerProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RedirectorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_increment_view_count_and_get_link_for_redirected_user(): void
    {
        $marketer = User::factory()->create(['type' => 'marketer']);
        $product = Product::factory()->create();
        MarketerProduct::factory()->create([
            'marketer_id' => $marketer->id,
            'product_id' => $product->id,
        ]);

        $this->json('get', '/api/redirector?marketerId='.$marketer->id.'&productId='.$product->id)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll('data.redirectToUrl', 'success')
                     ->missing('errors')
            );

        $marketerProductViewCount = MarketerProduct::query()
            ->where([
                'marketer_id' => $marketer->id,
                'product_id' => $product->id,
                'creation_date' => Carbon::now()->format('Y-m-d')
            ])
            ->first();
        $this->assertEquals(1, $marketerProductViewCount->view_count);

        $productViewCount = Product::query()->find($product->id);
        $this->assertEquals(1, $productViewCount->view_count);
    }

    public function test_not_increment_view_count_and_not_get_link_for_redirected_user(): void
    {
        $marketer = User::factory()->create(['type' => 'marketer']);
        $product = Product::factory()->create();
        MarketerProduct::factory()->create([
            'marketer_id' => $marketer->id,
            'product_id' => $product->id,
        ]);

        $wrongProductId = 1000;

        $this->json('get', '/api/redirector?marketerId='.$marketer->id.'&productId='.$wrongProductId)
            ->assertStatus(400)
            ->assertJsonPath('errors', 'invalid productId or marketerId')
            ->assertJsonPath('success', false);

        $marketerProductViewCount = MarketerProduct::query()
            ->where([
                'marketer_id' => $marketer->id,
                'product_id' => $product->id,
                'creation_date' => Carbon::now()->format('Y-m-d')
            ])
            ->first();
        $this->assertEquals(0, $marketerProductViewCount->view_count);

        $productViewCount = Product::query()->find($product->id);
        $this->assertEquals(0, $productViewCount->view_count);
    }

    public function test_get_validation_error_when_productId_or_marketerId_is_not_set(): void
    {
        $productId = 1;
        $marketerId = 2;

        $this->json('get', '/api/redirector?marketerId=&productId=')
            ->assertStatus(422)
            ->assertJsonPath('errors.marketerId', 'The marketer id field is required.')
            ->assertJsonPath('errors.productId', 'The product id field is required.')
            ->assertJsonPath('success', false);

        $this->json('get', '/api/redirector?marketerId=&productId='.$productId)
            ->assertStatus(422)
            ->assertJsonPath('errors.marketerId', 'The marketer id field is required.')
            ->assertJsonPath('success', false);

        $this->json('get', '/api/redirector?marketerId='.$marketerId.'&productId=')
            ->assertStatus(422)
            ->assertJsonPath('errors.productId', 'The product id field is required.')
            ->assertJsonPath('success', false);
    }
}
