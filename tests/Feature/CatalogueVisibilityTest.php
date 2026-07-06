<?php

namespace Tests\Feature;

use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use App\Models\City;
use App\Models\Province;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\Suburb;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_visible_scope_only_shows_active_published_catalogues(): void
    {
        $user = User::factory()->create();
        $shopCategory = ShopCategory::create(['name' => 'Supermarket']);
        $catalogueCategory = CatalogueCategory::create(['name' => 'Groceries']);
        $province = Province::create(['name' => 'Harare Metropolitan']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Harare']);
        $suburb = Suburb::create(['city_id' => $city->id, 'name' => 'Avondale']);

        $activeShop = Shop::create([
            'name' => 'OK Zimbabwe',
            'slug' => 'ok-zimbabwe',
            'shop_category_id' => $shopCategory->id,
            'status' => 'active',
            'email' => 'ok@example.com',
            'phone' => '0241234567',
            'created_by' => $user->id,
        ]);

        $expiredShop = Shop::create([
            'name' => 'Test Retailer',
            'slug' => 'test-retailer',
            'shop_category_id' => $shopCategory->id,
            'status' => 'active',
            'email' => 'test@example.com',
            'phone' => '0247654321',
            'created_by' => $user->id,
        ]);

        Catalogue::create([
            'shop_id' => $activeShop->id,
            'created_by' => $user->id,
            'catalogue_category_id' => $catalogueCategory->id,
            'title' => 'Current Promo',
            'slug' => 'current-promo',
            'valid_from' => now()->subDay(),
            'valid_to' => now()->addDay(),
            'status' => 'published',
            'cover_image' => 'covers/one.jpg',
            'file_path' => 'catalogues/one.pdf',
            'file_type' => 'pdf',
        ]);

        Catalogue::create([
            'shop_id' => $activeShop->id,
            'created_by' => $user->id,
            'catalogue_category_id' => $catalogueCategory->id,
            'title' => 'Draft Promo',
            'slug' => 'draft-promo',
            'valid_from' => now()->subDay(),
            'valid_to' => now()->addDay(),
            'status' => 'draft',
            'cover_image' => 'covers/two.jpg',
            'file_path' => 'catalogues/two.pdf',
            'file_type' => 'pdf',
        ]);

        Catalogue::create([
            'shop_id' => $expiredShop->id,
            'created_by' => $user->id,
            'catalogue_category_id' => $catalogueCategory->id,
            'title' => 'Expired Promo',
            'slug' => 'expired-promo',
            'valid_from' => now()->subDays(10),
            'valid_to' => now()->subDay(),
            'status' => 'published',
            'cover_image' => 'covers/three.jpg',
            'file_path' => 'catalogues/three.pdf',
            'file_type' => 'pdf',
        ]);

        $result = Catalogue::publicVisible()->get();

        $this->assertCount(1, $result);
        $this->assertSame('Current Promo', $result->first()->title);
    }
}
