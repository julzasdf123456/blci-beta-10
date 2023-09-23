<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMServiceConnectionInspections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_ServiceConnectionInspections', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId');
            $table->string('SEMainCircuitBreakerAsPlan')->nullable();
            $table->string('SEMainCircuitBreakerAsInstalled')->nullable();
            $table->string('SENoOfBranchesAsPlan')->nullable();
            $table->string('SENoOfBranchesAsInstalled')->nullable();
            $table->string('PoleNumber')->nullable();
            $table->string('PoleGIEstimatedDiameter')->nullable();
            $table->string('PoleGIHeight')->nullable();
            $table->string('PoleGINoOfLiftPoles')->nullable();
            $table->string('PoleConcreteEstimatedDiameter')->nullable();
            $table->string('PoleConcreteHeight')->nullable();
            $table->string('PoleConcreteNoOfLiftPoles')->nullable();
            $table->string('PoleHardwoodEstimatedDiameter')->nullable();
            $table->string('PoleHardwoodHeight')->nullable();
            $table->string('PoleHardwoodNoOfLiftPoles')->nullable();
            $table->string('PoleRemarks', 2000)->nullable();
            $table->string('SDWSizeAsPlan')->nullable();
            $table->string('SDWSizeAsInstalled')->nullable();
            $table->string('SDWLengthAsPlan')->nullable();
            $table->string('SDWLengthAsInstalled')->nullable();
            $table->string('GeoBuilding', 500)->nullable();
            $table->string('GeoTappingPole', 500)->nullable();
            $table->string('GeoMeteringPole', 500)->nullable();
            $table->string('GeoSEPole', 500)->nullable();
            $table->string('FirstNeighborName', 1000)->nullable();
            $table->string('FirstNeighborMeterSerial', 1000)->nullable();
            $table->string('SecondNeighborName', 1000)->nullable();
            $table->string('SecondNeighborMeterSerial', 1000)->nullable();
            $table->string('EngineerInchargeName', 600)->nullable();
            $table->string('EngineerInchargeTitle')->nullable();
            $table->string('EngineerInchargeLicenseNo', 600)->nullable();
            $table->date('EngineerInchargeLicenseValidity')->nullable();
            $table->string('EngineerInchargeContactNo', 600)->nullable();
            $table->string('Status')->nullable();
            $table->string('Inspector')->nullable();
            $table->datetime('DateOfVerification')->nullable();
            $table->date('EstimatedDateForReinspection')->nullable();
            $table->string('Notes', 2000)->nullable();
            $table->date('InspectionSchedule')->nullable();
            $table->date('ReInspectionSchedule')->nullable();
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
        Schema::dropIfExists('CRM_ServiceConnectionInspections');
    }
}
