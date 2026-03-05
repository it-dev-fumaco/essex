<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiometricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biometrics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('biometric_id');
            $table->integer('employee_id');
            $table->date('bio_date');
            $table->time('bio_time');
            $table->integer('trans_type');
            $table->string('serial_no');
            $table->date('received_date');
            $table->time('received_time');
            $table->string('unit_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('biometrics');
    }
}
