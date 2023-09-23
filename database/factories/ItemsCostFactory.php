<?php

namespace Database\Factories;

use App\Models\ItemsCost;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemsCostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemsCost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cst_no' => $this->faker->randomDigitNotNull,
        'rrno' => $this->faker->word,
        'it_code' => $this->faker->word,
        'ave_qty' => $this->faker->word,
        'qty' => $this->faker->word,
        'uom' => $this->faker->word,
        'cst' => $this->faker->word,
        'amt' => $this->faker->word,
        'rdate' => $this->faker->word,
        'rtime' => $this->faker->word,
        'categ' => $this->faker->word,
        'specs' => $this->faker->word
        ];
    }
}
