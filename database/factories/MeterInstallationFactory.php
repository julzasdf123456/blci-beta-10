<?php

namespace Database\Factories;

use App\Models\MeterInstallation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterInstallationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeterInstallation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'Type' => $this->faker->word,
        'NewMeterNumber' => $this->faker->word,
        'NewMeterBrand' => $this->faker->word,
        'NewMeterSize' => $this->faker->word,
        'NewMeterType' => $this->faker->word,
        'NewMeterAmperes' => $this->faker->word,
        'NewMeterInitialReading' => $this->faker->word,
        'NewMeterLineToNeutral' => $this->faker->word,
        'NewMeterLineToGround' => $this->faker->word,
        'NewMeterNeutralToGround' => $this->faker->word,
        'DateInstalled' => $this->faker->word,
        'NewMeterMultiplier' => $this->faker->word,
        'TransfomerCapacity' => $this->faker->word,
        'TransformerID' => $this->faker->word,
        'PoleID' => $this->faker->word,
        'CTSerialNumber' => $this->faker->word,
        'NewMeterRemarks' => $this->faker->word,
        'OldMeterNumber' => $this->faker->word,
        'OldMeterBrand' => $this->faker->word,
        'OldMeterSize' => $this->faker->word,
        'OldMeterType' => $this->faker->word,
        'DateRemoved' => $this->faker->word,
        'ReasonForChanging' => $this->faker->word,
        'OldMeterMultiplier' => $this->faker->word,
        'OldMeterRemarks' => $this->faker->word,
        'InstalledBy' => $this->faker->word,
        'CheckedBy' => $this->faker->word,
        'Witness' => $this->faker->word,
        'BLCIRepresentative' => $this->faker->word,
        'ApprovedBy' => $this->faker->word,
        'RemovedBy' => $this->faker->word,
        'CustomerSignature' => $this->faker->word,
        'WitnessSignature' => $this->faker->word,
        'InstalledBySignature' => $this->faker->word,
        'ApprovedBySignature' => $this->faker->word,
        'CheckedBySignature' => $this->faker->word,
        'BLCIRepresentativeSignature' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
