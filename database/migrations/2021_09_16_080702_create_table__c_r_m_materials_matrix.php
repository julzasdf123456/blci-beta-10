<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMMaterialsMatrix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_MaterialsMatrix', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('StructureId')->nullable();
            $table->string('MaterialsId')->nullable();
            $table->string('Quantity')->nullable();
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
        Schema::dropIfExists('CRM_MaterialsMatrix');
    }
}
