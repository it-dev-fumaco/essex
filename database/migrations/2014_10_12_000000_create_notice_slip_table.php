<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticeSlipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_slip', function (Blueprint $table) {
            $table->increments('notice_id');
            $table->integer('user_id');
            $table->integer('dept_id');
            $table->integer('leave_type_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->time('time_from');
            $table->time('time_to');
            $table->string('reason');
            $table->string('time_reported')->nullable();
            $table->string('info_by')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('approved_by')->nullable();
            $table->dateTIme('approved_date')->nullable();
            $table->dateTime('date_filled');
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
        Schema::dropIfExists('notice_slip');
    }
}
