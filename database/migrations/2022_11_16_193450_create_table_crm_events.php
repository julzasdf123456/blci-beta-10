<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_Events', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('EventTitle', 300);
            $table->string('EventDescription', 2000)->nullable();
            $table->datetime('EventStart')->nullable();
            $table->datetime('EventEnd')->nullable();
            $table->datetime('RegistrationStart')->nullable();
            $table->datetime('RegistrationEnd')->nullable();
            $table->string('UserId')->nullable();
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
        Schema::dropIfExists('CRM_Events');
    }
}
