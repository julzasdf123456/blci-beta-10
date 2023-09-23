<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMServiceConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_ServiceConnections', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('MemberConsumerId')->nullable();
            $table->date('DateOfApplication')->nullable();
            $table->string('ServiceAccountName')->nullable();
            $table->integer('AccountCount')->nullable();
            $table->string('Sitio', 1000)->nullable();
            $table->string('Barangay', 10)->nullable();
            $table->string('Town', 10)->nullable();
            $table->string('ContactNumber', 500)->nullable();
            $table->string('EmailAddress', 800)->nullable();
            $table->string('AccountType', 100)->nullable();
            $table->string('AccountOrganization', 100)->nullable();
            $table->string('OrganizationAccountNumber', 100)->nullable();
            $table->string('IsNIHE')->nullable();
            $table->string('AccountApplicationType', 100)->nullable();
            $table->string('ConnectionApplicationType', 100)->nullable();
            $table->string('Status', 100)->nullable();
            $table->string('Notes', 2000)->nullable();
            $table->string('Trash', 50)->nullable(); // YES OR NO/NULL
            $table->string('ORNumber', 50)->nullable();
            $table->date('ORDate')->nullable();
            $table->datetime('DateTimeLinemenArrived')->nullable();
            $table->datetime('DateTimeOfEnergization')->nullable();
            $table->string('EnergizationOrderIssued', 50)->nullable();
            $table->datetime('DateTimeOfEnergizationIssue')->nullable();
            $table->string('StationCrewAssigned', 50)->nullable();
            $table->string('LoadCategory', 50)->nullable();
            $table->string('TemporaryDurationInMonths', 50)->nullable();
            $table->string('LongSpan', 50)->nullable(); // YES - if SDW is more than 70 meters, NO - if otherwise 
            $table->string('Office')->nullable();
            $table->string('TypeOfOccupancy')->nullable();
            $table->string('ResidenceNumber')->nullable();
            $table->string('AccountNumber')->nullable();
            $table->string('UserId')->nullable();
            $table->date('ConnectionSchedule')->nullable();
            $table->time('TimeOfApplication')->nullable();
            $table->date('CertificateOfConnectionIssuedOn')->nullable();
            $table->decimal('LoadInKva', 18, 2)->nullable();
            $table->string('ZoneAndBlock')->nullable();
            $table->string('TransformerID')->nullable();
            $table->string('PoleNumber')->nullable();
            $table->string('Feeder')->nullable();
            $table->string('ChargeTo', 500)->nullable();
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
        Schema::dropIfExists('CRM_ServiceConnections');
    }
}
