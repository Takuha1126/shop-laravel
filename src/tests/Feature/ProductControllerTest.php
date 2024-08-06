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


        $categoryName = 'Sample Category';
        $category = Category::factory()->create(['name' => $categoryName]);

        if (App::environment('local')) {
            Storage::fake('public');
        } else {
            Storage::fake('s3');
        }
        $dummyImage = UploadedFile::fake()->image('sample.jpg');

        $requestData = [
            'product_name' => 'Sample Product',
            'brand' => 'Sample Brand',
            'description' => 'This is a sample product description.',
            'price' => 1000,
            'status' => 'available',
            'image' => $dummyImage,
            'categories' => [$categoryName],
        ];

        $this->actingAs($user);

        $response = $this->post('/product/store', $requestData);

        $response->assertStatus(302);
        $response->assertRedirect(route('home.index'));


        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'status' => 'available',
            'product_name' => 'Sample Product',
            'brand' => 'Sample Brand',
            'description' => 'This is a sample product description.',
            'price' => 1000,
        ]);

        if (App::environment('local')) {
            Storage::disk('public')->assertExists('images/' . $dummyImage->hashName());
        } else {
            Storage::disk('s3')->assertExists('images/' . $dummyImage->hashName());
        }
    }
}