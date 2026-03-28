<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_application', function (Blueprint $table) {
            $table->increments('loan_application_id');
            $table->integer('employee_id');
            $table->integer('loan_type_id');
            $table->integer('vehicle_id')->nullable();
            $table->date('loan_date');
            $table->string('loan_status')->nullable();
            $table->decimal('amount');
            $table->decimal('amount_approved')->nullable();
            $table->string('payment_type')->nulllable();
            $table->string('installment_rate')->nullable();
            $table->string('approved_by_signature')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('application_details')->nullable();
            $table->integer('driver_license_no')->nullable();
            $table->string('dl_type')->nullable();
            $table->integer('rc_no')->nullable();
            $table->integer('recommended_by')->nullable();
            $table->integer('processed_by')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('loan_application');
    }
}
