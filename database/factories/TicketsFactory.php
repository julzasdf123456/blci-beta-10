<?php

namespace Database\Factories;

use App\Models\Tickets;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tickets::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'ConsumerName' => $this->faker->word,
        'Town' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Sitio' => $this->faker->word,
        'Ticket' => $this->faker->word,
        'Reason' => $this->faker->word,
        'ContactNumber' => $this->faker->word,
        'ReportedBy' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'GeoLocation' => $this->faker->word,
        'Neighbor1' => $this->faker->word,
        'Neighbor2' => $this->faker->word,
        'Notes' => $this->faker->word,
        'Status' => $this->faker->word,
        'DateTimeDownloaded' => $this->faker->date('Y-m-d H:i:s'),
        'DateTimeLinemanArrived' => $this->faker->date('Y-m-d H:i:s'),
        'DateTimeLinemanExecuted' => $this->faker->date('Y-m-d H:i:s'),
        'UserId' => $this->faker->word,
        'CrewAssigned' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
