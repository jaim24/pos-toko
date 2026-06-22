<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        return [
            'category_id' => Category::factory(),
            'name'        => $name,
            'slug'        => \Illuminate\Support\Str::slug($name),
            'description' => null,
            'price'       => fake()->numberBetween(1000, 500000),
            'stock'       => fake()->numberBetween(0, 100),
            'is_active'   => true,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => fake()->numberBetween(0, 5),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
