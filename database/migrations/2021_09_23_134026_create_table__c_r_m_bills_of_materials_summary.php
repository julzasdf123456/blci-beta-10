<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMBillsOfMaterialsSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_BillsOfMaterialsSummary', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('ExcludeTransformerLaborCost')->nullable();
            $table->string('TransformerChangedPrice')->nullable();
            $table->string('MonthDuration', 10)->nullable(); // FOR TEMPORARY
            $table->string('TransformerLaborCostPercentage', 10)->nullable();
            $table->string('MaterialLaborCostPercentage', 10)->nullable();
            $table->string('HandlingCostPercentage', 10)->nullable();
            $table->string('SubTotal', 20)->nullable();
            $table->string('TransformerLaborCost', 20)->nullable();
            $table->string('MaterialLaborCost', 20)->nullable();
            $table->string('LaborCost', 20)->nullable(); // TOTAL LABOR COST
            $table->string('HandlingCost', 20)->nullable();
            $table->string('Total', 25)->nullable();
            $table->string('TotalVAT', 20)->nullable();
            $table->string('TransformerTotal', 20)->nullable();
            $table->string('IsPaid')->nullable();
            $table->string('ORNumber')->nullable();
            $table->date('ORDate')->nullable();
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
        Schema::dropIfExists('CRM_BillsOfMaterialsSummary');
    }
}
