<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMBillOfMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_BillOfMaterialsIndex', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->date('Date')->nullable();
            $table->string('SubTotal', 100)->nullable();
            $table->string('LaborCost', 100)->nullable(); // 35% (.35)
            $table->string('Others', 100)->nullable();    // 7% (.07)
            $table->string('Total', 100)->nullable();
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
        Schema::dropIfExists('CRM_BillOfMaterialsIndex');
    }
}
