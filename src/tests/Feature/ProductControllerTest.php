<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

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
        // テスト用ユーザーの作成
        $user = User::factory()->create();

        // テスト用のカテゴリーを作成
        $categoryName = 'Sample Category';
        $category = Category::factory()->create(['name' => $categoryName]);

        // ダミーの画像ファイルを準備する
        Storage::fake('s3'); // S3ストレージを使用することを宣言
        $dummyImage = UploadedFile::fake()->image('sample.jpg');

        // テスト用リクエストデータの準備
        $requestData = [
            'productName' => 'Sample Product',
            'brand' => 'Sample Brand',
            'description' => 'This is a sample product description.',
            'price' => 1000,
            'status' => 'available',
            'image' => $dummyImage,
            'categories' => [$categoryName],
        ];

        // 認証されたユーザーとしてテストする
        $this->actingAs($user);

        // 商品の作成リクエストを送信する
        $response = $this->post('/product/store', $requestData);

        // レスポンスの確認とステータスコードのアサーション
        $response->assertStatus(302); // リダイレクトの確認
        $response->assertRedirect(route('home.index')); // リダイレクト先のURLを適宜変更する

        // データベースに商品が保存されたことを確認する
        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'status' => 'available',
            'productName' => 'Sample Product',
            'brand' => 'Sample Brand',
            'description' => 'This is a sample product description.',
            'price' => 1000,
            'image' => 'images/' . $dummyImage->hashName(),
        ]);

        // S3に画像が保存されていることを確認する
        Storage::disk('s3')->assertExists('images/' . $dummyImage->hashName());
    }
}
