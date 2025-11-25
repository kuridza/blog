<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use App\Services\Locations;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    protected $model = Ad::class;
    private Locations $locations;



    public function definition(): array
    {
        return [
            'user_id' => null,
            'category_id' => null,
            'title' => $this->faker->sentence(mt_rand(3, 5)),
            'content' => $this->faker->paragraphs(mt_rand(1,3), true),
            'price' => mt_rand(1, 1000),
            'is_new' => $this->faker->boolean(),
            'contact_phone' => $this->faker->phoneNumber(),
            'location' => $this->faker->randomElement((new Locations())->getLocations()),
        ];
    }
}
