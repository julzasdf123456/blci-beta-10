<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmCostCenters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_CostCenters', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('CostCode')->nullable();
            $table->string('CostName', 1500)->nullable();
            $table->string('CostDepartment')->nullable();
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
        Schema::dropIfExists('CRM_CostCenters');
    }
}
