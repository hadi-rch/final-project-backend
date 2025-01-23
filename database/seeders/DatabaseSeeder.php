<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Categories::factory(10)->create();
        // $categories = [
        //     'Electronics',
        //     'Fashion',
        //     'Home & Garden',
        //     'Sports',
        //     'Health & Beauty',
        //     'Toys & Hobbies',
        //     'Automotive',
        //     'Books',
        //     'Music',
        //     'Movies',
        //     'Groceries',
        //     'Pet Supplies',
        //     'Office Supplies',
        //     'Tools & Hardware',
        //     'Jewelry',
        //     'Watches',
        //     'Baby Products',
        //     'Video Games',
        //     'Software',
        //     'Art & Collectibles',
        //     'Crafts',
        //     'Industrial',
        //     'Musical Instruments',
        //     'Cameras & Photo',
        //     'Cell Phones & Accessories'
        // ];

        // $categoryData = [];

        // foreach ($categories as $category) {
        //     $categoryData[] = [
        //         'id' => Str::uuid(),
        //         'name' => $category,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ];
        // }

        // Categories::insert($categoryData);

        // \App\Models\Products::factory(100)->create();




        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
