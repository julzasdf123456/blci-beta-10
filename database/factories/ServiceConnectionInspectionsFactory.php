<?php

namespace Database\Factories;

use App\Models\ServiceConnectionInspections;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionInspectionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionInspections::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'SEMainCircuitBreakerAsPlan' => $this->faker->word,
        'SEMainCircuitBreakerAsInstalled' => $this->faker->word,
        'SENoOfBranchesAsPlan' => $this->faker->word,
        'SENoOfBranchesAsInstalled' => $this->faker->word,
        'PoleGIEstimatedDiameter' => $this->faker->word,
        'PoleGIHeight' => $this->faker->word,
        'PoleGINoOfLiftPoles' => $this->faker->word,
        'PoleConcreteEstimatedDiameter' => $this->faker->word,
        'PoleConcreteHeight' => $this->faker->word,
        'PoleConcreteNoOfLiftPoles' => $this->faker->word,
        'PoleHardwoodEstimatedDiameter' => $this->faker->word,
        'PoleHardwoodHeight' => $this->faker->word,
        'PoleHardwoodNoOfLiftPoles' => $this->faker->word,
        'PoleRemarks' => $this->faker->word,
        'SDWSizeAsPlan' => $this->faker->word,
        'SDWSizeAsInstalled' => $this->faker->word,
        'SDWLengthAsPlan' => $this->faker->word,
        'SDWLengthAsInstalled' => $this->faker->word,
        'GeoBuilding' => $this->faker->word,
        'GeoTappingPole' => $this->faker->word,
        'GeoMeteringPole' => $this->faker->word,
        'GeoSEPole' => $this->faker->word,
        'FirstNeighborName' => $this->faker->word,
        'FirstNeighborMeterSerial' => $this->faker->word,
        'SecondNeighborName' => $this->faker->word,
        'SecondNeighborMeterSerial' => $this->faker->word,
        'EngineerInchargeName' => $this->faker->word,
        'EngineerInchargeTitle' => $this->faker->word,
        'EngineerInchargeLicenseNo' => $this->faker->word,
        'EngineerInchargeLicenseValidity' => $this->faker->word,
        'EngineerInchargeContactNo' => $this->faker->word,
        'Status' => $this->faker->word,
        'Inspector' => $this->faker->word,
        'DateOfVerification' => $this->faker->date('Y-m-d H:i:s'),
        'EstimatedDateForReinspection' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
