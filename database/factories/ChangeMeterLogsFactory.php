<?php

namespace Database\Factories;

use App\Models\ChangeMeterLogs;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChangeMeterLogsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChangeMeterLogs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'OldMeterSerial' => $this->faker->word,
        'NewMeterSerial' => $this->faker->word,
        'PullOutReading' => $this->faker->word,
        'AdditionalKwhForNextBilling' => $this->faker->word,
        'Status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
