<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'body' => $this->faker->sentence(),
            'images' => [
                'thumb' => $this->faker->imageUrl(),
                'images' => [
                    '300' => $this->faker->imageUrl(300, 300),
                    'original' => $this->faker->image()
                ]
            ],
            'tags' => 'tag1,tag2'
        ];
    }
}
