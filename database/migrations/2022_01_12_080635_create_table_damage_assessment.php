<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDamageAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_DamageAssessment', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Type')->nullable(); // pole, meter, etc
            $table->string('ObjectName')->nullable(); // pole number, meter number, etc
            $table->string('Feeder')->nullable();
            $table->string('Town')->nullable();
            $table->string('Status')->nullable();
            $table->string('Notes', 3000)->nullable();
            $table->datetime('DateFixed')->nullable();
            $table->string('CrewAssigned', 500)->nullable();
            $table->string('Latitude')->nullable();
            $table->string('Longitude')->nullable();
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
        Schema::dropIfExists('CRM_DamageAssessment');
    }
}
