<?php

namespace Database\Factories;

use App\Models\MemberConsumers;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberConsumersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberConsumers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MembershipType' => $this->faker->word,
        'FirstName' => $this->faker->word,
        'MiddleName' => $this->faker->word,
        'LastName' => $this->faker->word,
        'Suffix' => $this->faker->word,
        'OrganizationName' => $this->faker->word,
        'Birthdate' => $this->faker->word,
        'Sitio' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Town' => $this->faker->word,
        'ContactNumbers' => $this->faker->word,
        'EmailAddress' => $this->faker->word,
        'DateApplied' => $this->faker->word,
        'DateOfPMS' => $this->faker->word,
        'DateApproved' => $this->faker->word,
        'CivilStatus' => $this->faker->word,
        'Religion' => $this->faker->word,
        'Citizenship' => $this->faker->word,
        'ApplicationStatus' => $this->faker->word,
        'Notes' => $this->faker->word,
        'Trashed' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
