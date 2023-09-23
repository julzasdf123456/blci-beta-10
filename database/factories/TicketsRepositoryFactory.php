<?php

namespace Database\Factories;

use App\Models\TicketsRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketsRepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TicketsRepository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Name' => $this->faker->word,
        'Description' => $this->faker->word,
        'ParentTicket' => $this->faker->word,
        'Type' => $this->faker->word,
        'KPSCategory' => $this->faker->word,
        'KPSIssue' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
