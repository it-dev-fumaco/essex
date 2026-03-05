<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatepassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gatepass', function (Blueprint $table) {
            $table->increments('gatepass_id');
            $table->integer('user_id');
            $table->enum('item_type', ['Returnable', 'Unreturnable']);
            $table->string('item_description');
            $table->string('purpose');
            $table->time('time');
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('status');
            $table->date('returned_on')->nullable();
            $table->integer('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('date_filed');
            $table->string('remarks');
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
        Schema::dropIfExists('gatepass');
    }
}
