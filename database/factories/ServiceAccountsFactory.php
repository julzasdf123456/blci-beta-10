<?php

namespace Database\Factories;

use App\Models\ServiceAccounts;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceAccountsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceAccounts::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceAccountName' => $this->faker->word,
        'Town' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Purok' => $this->faker->word,
        'AccountType' => $this->faker->word,
        'AccountStatus' => $this->faker->word,
        'ContactNumber' => $this->faker->word,
        'EmailAddress' => $this->faker->word,
        'ServiceConnectionId' => $this->faker->word,
        'MeterDetailsId' => $this->faker->word,
        'TransformerDetailsId' => $this->faker->word,
        'PoleNumber' => $this->faker->word,
        'AreaCode' => $this->faker->word,
        'BlockCode' => $this->faker->word,
        'SequenceCode' => $this->faker->word,
        'Feeder' => $this->faker->word,
        'ComputeType' => $this->faker->word,
        'Organization' => $this->faker->word,
        'OrganizationParentAccount' => $this->faker->word,
        'GPSMeter' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
