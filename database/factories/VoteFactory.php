<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'option_id' => Option::factory(),
            'qty' => $this->faker->numberBetween()
        ];
    }
}
