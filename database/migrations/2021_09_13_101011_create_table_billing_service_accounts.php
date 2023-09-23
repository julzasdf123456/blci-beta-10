<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingServiceAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_ServiceAccounts', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceAccountName', 600);
            $table->string('Town', 50)->nullable();
            $table->string('Barangay', 50)->nullable();
            $table->string('Purok', 200)->nullable();
            $table->string('AccountType', 100)->nullable(); // Residential, Commercial, Public Building, School, Etc
            $table->string('AccountStatus', 50)->nullable(); // PENDING, ACTIVE, DISCONNECTED
            $table->string('ContactNumber', 60)->nullable();
            $table->string('EmailAddress', 60)->nullable();
            $table->string('ServiceConnectionId', 30)->nullable();
            $table->string('MemberConsumerId', 50)->nullable();
            $table->string('AccountCount', 10)->nullable();
            // NEW
            $table->string('MeterReader', 60)->nullable();
            $table->string('GroupCode', 60)->nullable();
            $table->string('ForDistribution', 60)->nullable(); // Yes, No
            $table->string('Multiplier', 10)->nullable();
            $table->string('Coreloss', 20)->nullable();
            $table->string('Main', 10)->nullable(); // Yes, No
            $table->string('Evat5Percent', 10)->nullable(); // Yes, No
            $table->string('Ewt2Percent', 10)->nullable(); // Yes, No
            $table->string('AccountPaymentType', 50)->nullable(); // Prepaid, Postpaid

            // METER READER
            $table->string('MeterDetailsId', 50)->nullable(); // CURRENT ACTIVE
            $table->string('TransformerDetailsId', 50)->nullable();
            $table->string('PoleNumber', 255)->nullable();
            $table->string('AreaCode', 50)->nullable();
            $table->string('BlockCode', 50)->nullable();
            $table->string('SequenceCode', 50)->nullable();
            
            $table->string('Feeder', 50)->nullable();

            $table->string('ComputeType', 20)->nullable(); // Metered, FlatConsumption, NetMetering
            $table->string('Organization', 30)->nullable(); // Individual, BAPA, ECA, Cluster
            $table->string('OrganizationParentAccount', 30)->nullable();

            // GPS COORDINATES
            $table->string('GPSMeter', 50)->nullable();
            $table->string('Latitude')->nullable();
            $table->string('Longitude')->nullable();

            $table->string('BillingType', 50)->nullable(); // Prepaid, Postpaid
            $table->string('SeniorCitizen', 50)->nullable(); // Yes, No
            $table->string('Locked', 50)->nullable(); // Yes, No
            $table->timestamps();

            // OLD Data
            $table->string('OldAccountNo', 50)->nullable();

            // DISCONNECTION and CONNECTION DATA            
            $table->date('ConnectionDate')->nullable();
            $table->datetime('LatestReadingDate')->nullable();
            $table->date('DateDisconnected')->nullable();
            $table->date('DateTransfered')->nullable();

            $table->string('AccountRetention')->nullable(); // TEMPORARY, PERMANENT
            $table->date('AccountExpiration')->nullable();
            $table->string('DurationInMonths')->nullable();

            $table->string('Contestable')->nullable();
            $table->string('NetMetered')->nullable();
            $table->string('Notes', 500)->nullable();
            $table->string('Migrated')->nullable();

            $table->string('DistributionAccountCode')->nullable();
            $table->string('Item1')->nullable();

            $table->string('UserId', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Billing_ServiceAccounts');
    }
}
