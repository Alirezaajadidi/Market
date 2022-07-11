<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, 2),
            'resnumber' => rand(1000, 50000),
            'price' => rand(1000, 50000),
            'payment' => rand(0, 1),
            'created_at' => $this->faker->dateTimeBetween('-5 months', 'now')
        ];
    }
}
