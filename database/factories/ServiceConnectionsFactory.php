<?php

namespace Database\Factories;

use App\Models\ServiceConnections;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnections::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MemberConsumerId' => $this->faker->word,
        'DateOfApplication' => $this->faker->word,
        'ServiceAccountName' => $this->faker->word,
        'AccountCount' => $this->faker->randomDigitNotNull,
        'Sitio' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Town' => $this->faker->word,
        'ContactNumber' => $this->faker->word,
        'EmailAddress' => $this->faker->word,
        'AccountType' => $this->faker->word,
        'AccountOrganization' => $this->faker->word,
        'OrganizationAccountNumber' => $this->faker->word,
        'IsNIHE' => $this->faker->word,
        'AccountApplicationType' => $this->faker->word,
        'ConnectionApplicationType' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
