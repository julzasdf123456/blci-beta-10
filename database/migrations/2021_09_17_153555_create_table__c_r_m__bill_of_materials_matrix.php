<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMBillOfMaterialsMatrix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_BillOfMaterialsMatrix', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('StructureAssigningId')->nullable();
            $table->string('StructureId')->nullable();
            $table->string('StructureType')->nullable(); // FOR BRACKETS
            $table->string('MaterialsId')->nullable();
            $table->string('Quantity')->nullable();
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
        Schema::dropIfExists('CRM_BillOfMaterialsMatrix');
    }
}
