<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNotifiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifiers', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Notification', 3000)->nullable();
            $table->string('From')->nullable();
            $table->string('To')->nullable();
            $table->string('Status')->nullable(); // SENT, DELIVERED, READ
            $table->string('Intent', 600)->nullable(); // Module of the notification
            $table->string('IntentLink', 800)->nullable();
            $table->string('ObjectId')->nullable(); // in case the intent link requires an id
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
        Schema::dropIfExists('Notifiers');
    }
}
