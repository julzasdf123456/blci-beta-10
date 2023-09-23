<?php

namespace Database\Factories;

use App\Models\MemberConsumerImages;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberConsumerImagesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberConsumerImages::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ConsumerId' => $this->faker->word,
        'PicturePath' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'HexImage' => $this->faker->text
        ];
    }
}
