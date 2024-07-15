<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Facades\App;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a product can be stored.
     *
     * @return void
     */
    public function test_store_product()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    // テスト用のカテゴリを作成
    $categoryName = 'Sample Category';
    $category = Category::factory()->create(['name' => $categoryName]);

    // テスト用の画像を生成
    Storage::fake('public');
    $dummyImage = UploadedFile::fake()->image('sample.jpg');

    // 商品登録用のリクエストデータ
    $requestData = [
        'productName' => 'Sample Product',
        'brand' => 'Sample Brand',
        'description' => 'This is a sample product description.',
        'price' => 1000,
        'status' => 'available',
        'image' => $dummyImage,
        'categories' => [$categoryName],
    ];

    // 商品登録のHTTPリクエストをシミュレート
    $response = $this->post('/product/store', $requestData);

    // リダイレクトの確認
    $response->assertStatus(302);
    $response->assertRedirect(route('home.index'));

    // データベースに商品が正しく保存されたかどうかを確認
    $this->assertDatabaseHas('products', [
        'user_id' => $user->id,
        'status' => 'available',
        'productName' => 'Sample Product',
        'brand' => 'Sample Brand',
        'description' => 'This is a sample product description.',
        'price' => 1000,
    ]);

    // 画像が正しく保存されたかどうかをストレージで確認
    Storage::disk('public')->assertExists('images/' . $dummyImage->hashName());
}

}