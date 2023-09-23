<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMBillOfMaterialsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_BillOfMaterialsDetails', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('BillOfMaterialsId')->nullable();
            $table->string('NeaCode')->nullable();
            $table->string('Description', 1000)->nullable();
            $table->string('Rate', 50)->nullable();
            $table->string('Quantity', 15)->nullable();
            $table->string('Amount')->nullable();
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
        Schema::dropIfExists('CRM_BillOfMaterialsDetails');
    }
}
