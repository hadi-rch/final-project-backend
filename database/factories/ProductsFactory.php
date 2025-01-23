<?php

namespace Database\Factories;
use App\Models\Products;
use App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductsFactory extends Factory
{
    protected $model = Products::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $title = fake()->name();

        // return [
        //     'name' => $title,
        //     'price' => fake()->numberBetween(),
        //     'description' => fake()->sentence(),
        //     'image' => "https://placehold.co/600x400?text=$title",
        //     'stock' => fake()->randomDigit(),
        //     'category_id' => Categories::inRandomOrder()->pluck('id')->first(),
        // ];
    }
}
