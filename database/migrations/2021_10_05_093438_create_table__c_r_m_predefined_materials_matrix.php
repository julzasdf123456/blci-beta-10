<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMPredefinedMaterialsMatrix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_PreDefinedMaterialsMatrix', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('NEACode', 50)->nullable();
            $table->string('Description', 1000)->nullable();
            $table->string('Quantity', 20)->nullable();
            $table->string('Options')->nullable();
            $table->string('ApplicationType')->nullable();            
            $table->string('Cost')->nullable();
            $table->string('LaborCost')->nullable();
            $table->string('Amount')->nullable();
            $table->string('Notes', 1000)->nullable();
            $table->string('LaborPercentage', 50)->nullable();
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
        Schema::dropIfExists('CRM_PreDefinedMaterialsMatrix');
    }
}
