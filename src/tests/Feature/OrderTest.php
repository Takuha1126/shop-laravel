<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;


public function it_shows_order_details()
{

    $user = User::factory()->create();


    $this->actingAs($user);


    $category = Category::factory()->create();

    $product = Product::factory()->create();


    $orderData = [
        'product_id' => $product->id,
        'order_id' => 'test_order_id',
        'payment_method' => 'credit_card',
    ];
    session(['order_data' => $orderData]);


    $response = $this->get(route('order.details'));


    $response->assertStatus(200);

    $response->assertViewIs('purchase');

    $response->assertViewHasAll([
        'orderData' => $orderData,
        'product' => $product,
        'categories' => Category::all(),
    ]);
}

    public function test_it_stores_temporary_order()
{
    $product = Product::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    $requestData = [
        'product_id' => $product->id,
    ];

    $response = $this->post(route('order.storeTemporary'), $requestData);

    $response->assertStatus(302);
    $response->assertRedirect(route('order.details'));

    $this->assertNotNull(session('order_data'));
}



}
