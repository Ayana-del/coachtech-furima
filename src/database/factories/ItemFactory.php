<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id'      => User::factory(),
            'condition_id' => Condition::inRandomOrder()->first()->id ?? Condition::factory(),
            'name'         => $this->faker->words(3, true),
            'brand'        => $this->faker->company,
            'price'        => $this->faker->numberBetween(500, 100000),
            'description'  => $this->faker->realText(100),
            'image_url'    => 'https://dummyimage.com/600x400/ccc/000.png&text=Item',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Item $item) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $item->categories()->attach($categories);
        });
    }
}
