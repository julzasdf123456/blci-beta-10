<?php

namespace Database\Factories;

use App\Models\WarehouseItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseItemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseItems::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'reqno' => $this->faker->word,
        'ent_no' => $this->faker->randomDigitNotNull,
        'tdate' => $this->faker->word,
        'itemcd' => $this->faker->word,
        'ascode' => $this->faker->word,
        'qty' => $this->faker->word,
        'uom' => $this->faker->word,
        'cst' => $this->faker->word,
        'amt' => $this->faker->word,
        'itemno' => $this->faker->randomDigitNotNull,
        'rdate' => $this->faker->word,
        'rtime' => $this->faker->word
        ];
    }
}
