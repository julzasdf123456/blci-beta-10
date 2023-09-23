<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_Tickets', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->string('ConsumerName', 500)->nullable();
            $table->string('Town')->nullable();
            $table->string('Barangay')->nullable();
            $table->string('Sitio', 800)->nullable();
            $table->string('Ticket')->nullable();
            $table->string('Reason', 2000)->nullable();
            $table->string('ContactNumber', 100)->nullable();
            $table->string('ReportedBy', 200)->nullable();
            $table->string('ORNumber')->nullable();
            $table->date('ORDate')->nullable();
            $table->string('GeoLocation', 60)->nullable(); // LAT LONG
            $table->string('Neighbor1', 500)->nullable();
            $table->string('Neighbor2', 500)->nullable();
            $table->string('Notes', 2000)->nullable();
            $table->string('Status')->nullable();
            $table->datetime('DateTimeDownloaded')->nullable();
            $table->datetime('DateTimeLinemanArrived')->nullable();
            $table->datetime('DateTimeLinemanExecuted')->nullable();
            $table->string('UserId')->nullable();
            $table->string('CrewAssigned')->nullable();
            $table->string('Trash')->nullable();
            $table->string('Office')->nullable();
            $table->string('CurrentMeterNo')->nullable();
            $table->string('CurrentMeterBrand')->nullable();
            $table->string('CurrentMeterReading')->nullable();
            $table->string('KwhRating')->nullable();
            $table->string('PercentError')->nullable();
            $table->string('NewMeterNo')->nullable();
            $table->string('NewMeterBrand')->nullable();
            $table->string('NewMeterReading')->nullable();
            $table->date('ServicePeriod')->nullable();
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
        Schema::dropIfExists('CRM_Tickets');
    }
}
