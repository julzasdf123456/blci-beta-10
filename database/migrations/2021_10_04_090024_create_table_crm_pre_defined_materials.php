<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmPreDefinedMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_PreDefinedMaterials', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('NEACode', 50)->nullable();
            $table->string('Quantity', 20)->nullable();
            $table->string('Options')->nullable();
            $table->string('ApplicationType')->nullable();
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
        Schema::dropIfExists('CRM_PreDefinedMaterials');
    }
}
