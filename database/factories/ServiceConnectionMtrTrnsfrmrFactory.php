<?php

namespace Database\Factories;

use App\Models\ServiceConnectionMtrTrnsfrmr;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionMtrTrnsfrmrFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionMtrTrnsfrmr::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'MeterSerialNumber' => $this->faker->word,
        'MeterBrand' => $this->faker->word,
        'MeterSealNumber' => $this->faker->word,
        'MeterKwhStart' => $this->faker->word,
        'MeterEnclosureType' => $this->faker->word,
        'MeterHeight' => $this->faker->word,
        'MeterNotes' => $this->faker->word,
        'DirectRatedCapacity' => $this->faker->word,
        'InstrumentRatedCapacity' => $this->faker->word,
        'InstrumentRatedLineType' => $this->faker->word,
        'CTPhaseA' => $this->faker->word,
        'CTPhaseB' => $this->faker->word,
        'CTPhaseC' => $this->faker->word,
        'PTPhaseA' => $this->faker->word,
        'PTPhaseB' => $this->faker->word,
        'PTPhaseC' => $this->faker->word,
        'BrandPhaseA' => $this->faker->word,
        'BrandPhaseB' => $this->faker->word,
        'BrandPhaseC' => $this->faker->word,
        'SNPhaseA' => $this->faker->word,
        'SNPhaseB' => $this->faker->word,
        'SNPhaseC' => $this->faker->word,
        'SecuritySealPhaseA' => $this->faker->word,
        'SecuritySealPhaseB' => $this->faker->word,
        'SecuritySealPhaseC' => $this->faker->word,
        'Phase' => $this->faker->word,
        'TransformerQuantity' => $this->faker->word,
        'TransformerRating' => $this->faker->word,
        'TransformerOwnershipType' => $this->faker->word,
        'TransformerOwnership' => $this->faker->word,
        'TransformerBrand' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
