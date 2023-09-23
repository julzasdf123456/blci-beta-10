<?php

namespace Database\Factories;

use App\Models\MemberConsumerChecklists;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberConsumerChecklistsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberConsumerChecklists::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MemberConsumerId' => $this->faker->word,
        'ChecklistId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
