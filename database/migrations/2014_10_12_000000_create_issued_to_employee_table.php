<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuedToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issued_to_employee', function (Blueprint $table) {
            $table->increments('issue_id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->enum('status', ['Active'], ['Revoked'], ['Returned']);
            $table->date('date_issued');
            $table->integer('issued_by');
            $table->date('valid_until');
            $table->string('revoke_reason')->nullable();
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
        Schema::dropIfExists('issued_to_employee');
    }
}
