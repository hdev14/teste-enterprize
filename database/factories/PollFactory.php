<?php

namespace Database\Factories;

use App\Models\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Poll::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'poll_description' => $this->faker->text(),
            'views' => $this->faker->numberBetween()
        ];
    }
}
