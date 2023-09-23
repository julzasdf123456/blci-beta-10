<?php

namespace Database\Factories;

use App\Models\MemberConsumerChecklistsRep;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberConsumerChecklistsRepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberConsumerChecklistsRep::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Checklist' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
