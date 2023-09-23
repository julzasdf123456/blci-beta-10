<?php

namespace Database\Factories;

use App\Models\WarehouseHead;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseHeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseHead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'orderno' => $this->faker->word,
        'ent_no' => $this->faker->randomDigitNotNull,
        'misno' => $this->faker->word,
        'tdate' => $this->faker->word,
        'emp_id' => $this->faker->word,
        'ccode' => $this->faker->word,
        'dept' => $this->faker->word,
        'pcode' => $this->faker->word,
        'reqby' => $this->faker->word,
        'invoice' => $this->faker->word,
        'orno' => $this->faker->word,
        'purpose' => $this->faker->word,
        'serv_code' => $this->faker->word,
        'account_no' => $this->faker->word,
        'cust_name' => $this->faker->word,
        'tot_amt' => $this->faker->word,
        'chkby' => $this->faker->word,
        'appby' => $this->faker->word,
        'stat' => $this->faker->word,
        'rdate' => $this->faker->word,
        'rtime' => $this->faker->word,
        'walk_in' => $this->faker->word,
        'appl_no' => $this->faker->word
        ];
    }
}
