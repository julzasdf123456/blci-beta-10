<?php

namespace Database\Factories;

use App\Models\Notifiers;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotifiersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notifiers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Notification' => $this->faker->word,
        'From' => $this->faker->word,
        'To' => $this->faker->word,
        'Status' => $this->faker->word,
        'Intent' => $this->faker->word,
        'IntentLink' => $this->faker->word,
        'ObjectId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
