<?php

namespace Database\Factories;

use App\Models\MemberConsumerSpouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberConsumerSpouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberConsumerSpouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MemberConsumerId' => $this->faker->word,
        'FirstName' => $this->faker->word,
        'MiddleName' => $this->faker->word,
        'LastName' => $this->faker->word,
        'Suffix' => $this->faker->word,
        'Gender' => $this->faker->word,
        'Birthdate' => $this->faker->word,
        'Sitio' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Town' => $this->faker->word,
        'ContactNumbers' => $this->faker->word,
        'EmailAddress' => $this->faker->word,
        'Religion' => $this->faker->word,
        'Citizenship' => $this->faker->word,
        'Notes' => $this->faker->word,
        'Trashed' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
