<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMServiceConnectionImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_ServiceConnectionImages', function (Blueprint $table) {
            $table->string('id', 100)->unsigned();
            $table->primary('id');
            $table->string('Photo', 1500)->nullable();
            $table->string('ServiceConnectionId', 60)->nullable();
            $table->string('Notes', 2000)->nullable();
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
        Schema::dropIfExists('CRM_ServiceConnectionImages');
    }
}
