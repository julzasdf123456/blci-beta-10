<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMStructureAssignments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_StructureAssignments', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('StructureId')->nullable();
            $table->string('Quantity')->nullable();
            $table->string('Type')->nullable();
            $table->string('ConAssGrouping', 50)->nullable();
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
        Schema::dropIfExists('CRM_StructureAssignments');
    }
}
