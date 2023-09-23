<?php

namespace Database\Factories;

use App\Models\PaymentOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'MaterialDeposit' => $this->faker->word,
        'TransformerRentalFees' => $this->faker->word,
        'Apprehension' => $this->faker->word,
        'OverheadExpenses' => $this->faker->word,
        'CIAC' => $this->faker->word,
        'ServiceFee' => $this->faker->word,
        'CustomerDeposit' => $this->faker->word,
        'MeterQuantity' => $this->faker->word,
        'MeterUnitPrice' => $this->faker->word,
        'MeterAmount' => $this->faker->word,
        'TwistedWire6Quantity' => $this->faker->word,
        'TwistedWire6UnitPrice' => $this->faker->word,
        'TwistedWire6Amount' => $this->faker->word,
        'StrandedWire8Quantity' => $this->faker->word,
        'StrandedWire8UnitPrice' => $this->faker->word,
        'StrandedWire8Amount' => $this->faker->word,
        'SaleOfItemsQuantity' => $this->faker->word,
        'SaleOfItemsUnitPrice' => $this->faker->word,
        'SaleOfItemsAmount' => $this->faker->word,
        'CompressionTapQuantity' => $this->faker->word,
        'CompressionTapUnitPrice' => $this->faker->word,
        'CompressionTapAmount' => $this->faker->word,
        'PlyboardQuantity' => $this->faker->word,
        'PlyboardUnitPrice' => $this->faker->word,
        'PlyboardAmount' => $this->faker->word,
        'StainlessBuckleQuantity' => $this->faker->word,
        'StainlessBuckleUnitPrice' => $this->faker->word,
        'StainlessBuckleAmount' => $this->faker->word,
        'ElectricalTapeQuantity' => $this->faker->word,
        'ElectricalTapeUnitPrice' => $this->faker->word,
        'ElectricalTapeAmount' => $this->faker->word,
        'StainlessStrapQuantity' => $this->faker->word,
        'StainlessStrapUnitPrice' => $this->faker->word,
        'StainlessStrapAmount' => $this->faker->word,
        'MetalWoodScrewQuantity' => $this->faker->word,
        'MetalWoodScrewUnitPrice' => $this->faker->word,
        'MetalWoodScrewAmount' => $this->faker->word,
        'TotalSales' => $this->faker->word,
        'Others' => $this->faker->word,
        'LocalFTax' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'VAT' => $this->faker->word,
        'OthersTotal' => $this->faker->word,
        'OverAllTotal' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
