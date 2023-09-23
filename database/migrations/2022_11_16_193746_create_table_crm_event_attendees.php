<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmEventAttendees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_EventAttendees', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('EventId');
            $table->string('HaveAttended')->nullable();
            $table->string('AccountNumber')->nullable();
            $table->string('Name', 400)->nullable();
            $table->string('Address', 550)->nullable();
            $table->string('RegisteredAt')->nullable();
            $table->string('RegistationMedium')->nullable();
            $table->string('UserId')->nullable();
            $table->string('Notes', 500)->nullable();
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
        Schema::dropIfExists('CRM_EventAttendees');
    }
}
