<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase; // テスト後にデータベースをリフレッシュする

    /** @test */
    /** @test */
public function it_shows_order_details()
{
    // テスト用のユーザーを作成（必要に応じて）
    $user = User::factory()->create();

    // テスト中に作成したユーザーを認証
    $this->actingAs($user);

    // テスト用のカテゴリーを作成（必要に応じて）
    $category = Category::factory()->create();

    // テスト用の商品を作成（必要に応じて）
    $product = Product::factory()->create();

    // セッションに注文情報を設定
    $orderData = [
        'product_id' => $product->id,
        'order_id' => 'test_order_id', // 仮の注文ID
        'payment_method' => 'credit_card', // 仮の支払い方法
    ];
    session(['order_data' => $orderData]);

    // 注文詳細ページにGETリクエストを送信
    $response = $this->get(route('order.details'));

    // 正常にレスポンスを取得できたか確認
    $response->assertStatus(200);

    // 表示されているビューが正しいか確認（ビュー名が`purchase`と仮定）
    $response->assertViewIs('purchase');

    // ビューに必要なデータが含まれているか確認
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

    $response->assertStatus(302); // リダイレクトされることを確認
    $response->assertRedirect(route('order.details')); // 正しいリダイレクト先が設定されていることを確認

    $this->assertNotNull(session('order_data')); // セッションに注文データが保存されていることを確認
}



}
