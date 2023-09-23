<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierPaidBills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_PaidBills', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('BillNumber')->nullable();
            $table->string('AccountNumber')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('ORNumber')->nullable();
            $table->date('ORDate')->nullable();
            $table->string('DCRNumber')->nullable();
            $table->string('KwhUsed')->nullable();
            $table->string('Teller')->nullable();
            $table->string('OfficeTransacted')->nullable();
            $table->date('PostingDate')->nullable();
            $table->time('PostingTime')->nullable();
            $table->string('Surcharge')->nullable();
            $table->string('Form2307TwoPercent')->nullable();
            $table->string('Form2307FivePercent')->nullable();
            $table->string('AdditionalCharges')->nullable();
            $table->string('Deductions')->nullable();
            $table->string('NetAmount')->nullable();
            $table->string('Source')->nullable(); // MONTHLY BILL, ARREARS
            $table->string('ObjectSourceId')->nullable(); // INHERITS FROM Source (Bill Id, Arrers Id, etc.)
            $table->string('UserId')->nullable();
            $table->string('Status')->nullable(); // PENDING CANCEL, CANCELLED etc
            $table->string('FiledBy')->nullable(); // userid
            $table->string('ApprovedBy')->nullable(); // userid
            $table->string('AuditedBy')->nullable(); // userid
            $table->string('Notes', 1000)->nullable(); // userid
            $table->string('CheckNo')->nullable();
            $table->string('Bank')->nullable();
            $table->date('CheckExpiration')->nullable();
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
        Schema::dropIfExists('Cashier_PaidBills');
    }
}
