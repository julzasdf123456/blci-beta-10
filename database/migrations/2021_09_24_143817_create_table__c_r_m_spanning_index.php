<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMSpanningIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_SpanningIndex', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('NeaCode')->nullable();
            $table->string('Structure')->nullable();
            $table->string('Description')->nullable();
            $table->string('Size')->nullable();
            $table->string('Type')->nullable();
            $table->string('SpliceNeaCode')->nullable();
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
        Schema::dropIfExists('CRM_SpanningIndex');
    }
}
