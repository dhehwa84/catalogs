<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use App\Models\City;
use App\Models\Province;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\Suburb;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@cataloguehub.co.zw'],
            ['name' => 'Platform Admin', 'password' => Hash::make('password'), 'role' => 'super_admin', 'email_verified_at' => now()],
        );

        $owner = User::updateOrCreate(
            ['email' => 'owner@cataloguehub.co.zw'],
            ['name' => 'Demo Shop Owner', 'password' => Hash::make('password'), 'role' => 'shop_owner', 'email_verified_at' => now()],
        );

        $provinces = collect([
            'Harare Metropolitan' => ['Harare' => ['Avondale', 'Borrowdale', 'CBD', 'Greendale', 'Highfield', 'Msasa']],
            'Bulawayo Metropolitan' => ['Bulawayo' => ['Ascot', 'CBD', 'Hillside', 'Nkulumane']],
            'Manicaland' => ['Mutare' => ['Dangamvura', 'Sakubva', 'Yeovil']],
            'Masvingo' => ['Masvingo' => ['CBD', 'Mucheke', 'Rhodene']],
            'Midlands' => ['Gweru' => ['CBD', 'Mkoba', 'Senga']],
        ])->map(function ($cities, $provinceName) {
            $province = Province::updateOrCreate(['name' => $provinceName]);

            return collect($cities)->map(function ($suburbs, $cityName) use ($province) {
                $city = City::updateOrCreate(['province_id' => $province->id, 'name' => $cityName]);

                return collect($suburbs)->mapWithKeys(fn ($suburbName) => [
                    $suburbName => Suburb::updateOrCreate(['city_id' => $city->id, 'name' => $suburbName]),
                ]);
            });
        });

        $shopCategories = collect(['Supermarket', 'Pharmacy', 'Furniture', 'Hardware', 'Fashion', 'Electronics'])
            ->mapWithKeys(fn ($name) => [$name => ShopCategory::updateOrCreate(['name' => $name])]);

        $catalogueCategories = collect(['Groceries', 'Back to School', 'Home & Furniture', 'Hardware Deals', 'Health & Beauty', 'Electronics'])
            ->mapWithKeys(fn ($name) => [$name => CatalogueCategory::updateOrCreate(['name' => $name])]);

        $shops = [
            [
                'name' => 'OK Zimbabwe',
                'category' => 'Supermarket',
                'phone' => '+263 242 700 600',
                'whatsapp' => '+263 78 000 0001',
                'email' => 'promos@okzimbabwe.co.zw',
                'description' => 'National supermarket retailer with groceries, household goods, and seasonal promotions.',
                'branches' => [
                    ['name' => 'OK Avondale', 'city' => 'Harare', 'suburb' => 'Avondale', 'address' => 'Avondale Shopping Centre, Harare', 'lat' => -17.7959, 'lng' => 31.0372],
                    ['name' => 'OK First Street', 'city' => 'Harare', 'suburb' => 'CBD', 'address' => 'First Street, Harare', 'lat' => -17.8318, 'lng' => 31.0522],
                ],
                'catalogues' => [
                    ['title' => 'OK Grand Challenge Grocery Deals', 'category' => 'Groceries', 'days' => [0, 21], 'featured' => true],
                    ['title' => 'OK Winter Household Savings', 'category' => 'Groceries', 'days' => [-35, -10], 'featured' => false],
                ],
            ],
            [
                'name' => 'TM Pick n Pay Zimbabwe',
                'category' => 'Supermarket',
                'phone' => '+263 242 252 398',
                'whatsapp' => '+263 78 000 0002',
                'email' => 'specials@tmpnp.co.zw',
                'description' => 'Zimbabwe grocery chain covering fresh produce, pantry staples, toiletries, and weekly specials.',
                'branches' => [
                    ['name' => 'TM Borrowdale', 'city' => 'Harare', 'suburb' => 'Borrowdale', 'address' => 'Borrowdale Village, Harare', 'lat' => -17.7605, 'lng' => 31.0878],
                    ['name' => 'TM Bulawayo', 'city' => 'Bulawayo', 'suburb' => 'CBD', 'address' => 'Jason Moyo Street, Bulawayo', 'lat' => -20.1564, 'lng' => 28.5833],
                ],
                'catalogues' => [
                    ['title' => 'TM Pick n Pay Month End Specials', 'category' => 'Groceries', 'days' => [0, 14], 'featured' => true],
                ],
            ],
            [
                'name' => 'Bon Marche Zimbabwe',
                'category' => 'Supermarket',
                'phone' => '+263 242 301 505',
                'whatsapp' => '+263 78 000 0003',
                'email' => 'hello@bonmarche.co.zw',
                'description' => 'Premium supermarket offers for fresh foods, deli, bakery, and household essentials.',
                'branches' => [
                    ['name' => 'Bon Marche Borrowdale', 'city' => 'Harare', 'suburb' => 'Borrowdale', 'address' => 'Borrowdale Road, Harare', 'lat' => -17.7562, 'lng' => 31.0911],
                ],
                'catalogues' => [
                    ['title' => 'Bon Marche Fresh Market Offers', 'category' => 'Groceries', 'days' => [1, 18], 'featured' => false],
                ],
            ],
            [
                'name' => 'Electrosales Hardware',
                'category' => 'Hardware',
                'phone' => '+263 242 753 566',
                'whatsapp' => '+263 78 000 0004',
                'email' => 'sales@electrosales.co.zw',
                'description' => 'Hardware and electrical supplier for tools, building materials, lighting, and DIY projects.',
                'branches' => [
                    ['name' => 'Electrosales Msasa', 'city' => 'Harare', 'suburb' => 'Msasa', 'address' => 'Mutare Road, Msasa, Harare', 'lat' => -17.8468, 'lng' => 31.1188],
                ],
                'catalogues' => [
                    ['title' => 'Electrosales Builder Deals', 'category' => 'Hardware Deals', 'days' => [0, 30], 'featured' => true],
                ],
            ],
            [
                'name' => 'TV Sales & Home',
                'category' => 'Furniture',
                'phone' => '+263 242 704 201',
                'whatsapp' => '+263 78 000 0005',
                'email' => 'catalogues@tvsales.co.zw',
                'description' => 'Furniture, appliances, electronics, and home essentials with branch promotions.',
                'branches' => [
                    ['name' => 'TV Sales Harare CBD', 'city' => 'Harare', 'suburb' => 'CBD', 'address' => 'Robert Mugabe Road, Harare', 'lat' => -17.8303, 'lng' => 31.0496],
                    ['name' => 'TV Sales Mutare', 'city' => 'Mutare', 'suburb' => 'CBD', 'address' => 'Herbert Chitepo Street, Mutare', 'lat' => -18.9707, 'lng' => 32.6709],
                ],
                'catalogues' => [
                    ['title' => 'TV Sales Home Makeover Promo', 'category' => 'Home & Furniture', 'days' => [3, 28], 'featured' => false],
                ],
            ],
        ];

        foreach ($shops as $shopData) {
            $shop = Shop::updateOrCreate(
                ['slug' => Str::slug($shopData['name'])],
                [
                    'name' => $shopData['name'],
                    'shop_category_id' => $shopCategories[$shopData['category']]->id,
                    'description' => $shopData['description'],
                    'email' => $shopData['email'],
                    'phone' => $shopData['phone'],
                    'whatsapp' => $shopData['whatsapp'],
                    'status' => 'active',
                    'approved_at' => now()->subDays(8),
                    'approved_by' => $superAdmin->id,
                    'created_by' => $owner->id,
                    'is_demo' => true,
                ],
            );

            $shop->users()->syncWithoutDetaching([$owner->id => ['role' => 'shop_owner', 'is_owner' => true]]);

            $branches = collect($shopData['branches'])->map(function ($branchData) use ($shop, $provinces) {
                $suburb = $provinces->flatten(2)->first(fn ($candidate) => $candidate instanceof Suburb && $candidate->name === $branchData['suburb']);

                $branch = Branch::updateOrCreate(
                    ['shop_id' => $shop->id, 'name' => $branchData['name']],
                    [
                        'province_id' => $suburb?->city?->province_id,
                        'city_id' => $suburb?->city_id,
                        'suburb_id' => $suburb?->id,
                        'address' => $branchData['address'],
                        'latitude' => $branchData['lat'],
                        'longitude' => $branchData['lng'],
                        'phone' => $shop->phone,
                        'whatsapp' => $shop->whatsapp,
                        'email' => $shop->email,
                        'is_active' => true,
                    ],
                );

                foreach (range(1, 7) as $day) {
                    $branch->operatingHours()->updateOrCreate(
                        ['day_of_week' => $day],
                        ['opens_at' => $day === 7 ? '09:00' : '08:00', 'closes_at' => $day === 7 ? '13:00' : '18:00', 'is_closed' => false],
                    );
                }

                return $branch;
            });

            foreach ($shopData['catalogues'] as $catalogueData) {
                [$startOffset, $endOffset] = $catalogueData['days'];

                $catalogue = Catalogue::updateOrCreate(
                    ['shop_id' => $shop->id, 'slug' => Str::slug($catalogueData['title'])],
                    [
                        'created_by' => $owner->id,
                        'catalogue_category_id' => $catalogueCategories[$catalogueData['category']]->id,
                        'title' => $catalogueData['title'],
                        'description' => 'Selected Zimbabwe dollar and US dollar specials valid at participating branches while stocks last.',
                        'valid_from' => now()->addDays($startOffset)->toDateString(),
                        'valid_to' => now()->addDays($endOffset)->toDateString(),
                        'status' => 'published',
                        'is_featured' => $catalogueData['featured'],
                        'published_at' => now()->subDays(2),
                    ],
                );

                $catalogue->branches()->sync($branches->pluck('id'));
                $catalogue->areas()->sync($branches->pluck('suburb_id')->filter());
            }
        }
    }
}
