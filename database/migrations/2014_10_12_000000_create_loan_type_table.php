<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_type', function (Blueprint $table) {
            $table->increments('loan_type_id');
            $table->integer('loan_type');
            $table->integer('loan_type_description');
            $table->decimal('maximum_loan_amount');
            $table->enum('loan_type_status', ['Active'], ['Disabled']);
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
        Schema::dropIfExists('loan_type');
    }
}
