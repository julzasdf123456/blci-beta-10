<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMPoleIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_PoleIndex', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('NEACode')->nullable();
            $table->string('Type')->nullable(); // Wood, Steel, Concrete
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
        Schema::dropIfExists('CRM_PoleIndex');
    }
}
