<?php

namespace Database\Factories;

use App\Models\Items;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Items::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'itm_code' => $this->faker->word,
        'itm_no' => $this->faker->randomDigitNotNull,
        'itm_desc' => $this->faker->word,
        'itm_specs' => $this->faker->word,
        'itm_uom' => $this->faker->word,
        'itm_aveqty' => $this->faker->randomDigitNotNull,
        'itm_cat' => $this->faker->word,
        'itm_yr' => $this->faker->randomDigitNotNull,
        'itm_date' => $this->faker->word,
        'itm_time' => $this->faker->word,
        'itm_pcode' => $this->faker->word
        ];
    }
}
