<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmPaymentOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_PaymentOrder', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId');
            $table->decimal('MaterialDeposit', 15, 2)->nullable();
            $table->decimal('TransformerRentalFees', 15, 2)->nullable();
            $table->decimal('Apprehension', 15, 2)->nullable();
            $table->decimal('OverheadExpenses', 15, 2)->nullable();
            $table->decimal('CIAC', 15, 2)->nullable();
            $table->decimal('ServiceFee', 15, 2)->nullable();
            $table->decimal('CustomerDeposit', 15, 2)->nullable();
            $table->decimal('MeterQuantity', 15, 2)->nullable();
            $table->decimal('MeterUnitPrice', 15, 2)->nullable();
            $table->decimal('MeterAmount', 15, 2)->nullable();
            $table->decimal('TwistedWire6Quantity', 15, 2)->nullable();
            $table->decimal('TwistedWire6UnitPrice', 15, 2)->nullable();
            $table->decimal('TwistedWire6Amount', 15, 2)->nullable();
            $table->decimal('StrandedWire8Quantity', 15, 2)->nullable();
            $table->decimal('StrandedWire8UnitPrice', 15, 2)->nullable();
            $table->decimal('StrandedWire8Amount', 15, 2)->nullable();
            $table->decimal('SaleOfItemsQuantity', 15, 2)->nullable();
            $table->decimal('SaleOfItemsUnitPrice', 15, 2)->nullable();
            $table->decimal('SaleOfItemsAmount', 15, 2)->nullable();
            $table->decimal('CompressionTapQuantity', 15, 2)->nullable();
            $table->decimal('CompressionTapUnitPrice', 15, 2)->nullable();
            $table->decimal('CompressionTapAmount', 15, 2)->nullable();
            $table->decimal('PlyboardQuantity', 15, 2)->nullable();
            $table->decimal('PlyboardUnitPrice', 15, 2)->nullable();
            $table->decimal('PlyboardAmount', 15, 2)->nullable();
            $table->decimal('StainlessBuckleQuantity', 15, 2)->nullable();
            $table->decimal('StainlessBuckleUnitPrice', 15, 2)->nullable();
            $table->decimal('StainlessBuckleAmount', 15, 2)->nullable();
            $table->decimal('ElectricalTapeQuantity', 15, 2)->nullable();
            $table->decimal('ElectricalTapeUnitPrice', 15, 2)->nullable();
            $table->decimal('ElectricalTapeAmount', 15, 2)->nullable();
            $table->decimal('StainlessStrapQuantity', 15, 2)->nullable();
            $table->decimal('StainlessStrapUnitPrice', 15, 2)->nullable();
            $table->decimal('StainlessStrapAmount', 15, 2)->nullable();
            $table->decimal('MetalWoodScrewQuantity', 15, 2)->nullable();
            $table->decimal('MetalWoodScrewUnitPrice', 15, 2)->nullable();
            $table->decimal('MetalWoodScrewAmount', 15, 2)->nullable();
            $table->decimal('TotalSales', 15, 2)->nullable();
            $table->decimal('Others', 15, 2)->nullable();
            $table->decimal('LocalFTax', 15, 2)->nullable();
            $table->decimal('SubTotal', 15, 2)->nullable();
            $table->decimal('VAT', 15, 2)->nullable();
            $table->decimal('OthersTotal', 15, 2)->nullable();
            $table->decimal('OverAllTotal', 15, 2)->nullable();
            $table->string('ORNumber')->nullable();
            $table->date('ORDate')->nullable();
            $table->string('Notes', 2000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CRM_PaymentOrder');
    }
}
