<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemForEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_for_employee', function (Blueprint $table) {
            $table->increments('item_id');
            $table->enum('item_type', ['Mobile Phone', 'Vehicle', 'Laptop', 'Tablet', 'Others']);
            $table->enum('status', ['Active'], ['Inactive']);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('description');
            $table->string('other_unique_references')->nullable();
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
        Schema::dropIfExists('item_for_employee');
    }
}
