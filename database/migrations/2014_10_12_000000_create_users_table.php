<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('department_id');
            $table->string('password');
            $table->string('employee_name');
            $table->string('designation');
            $table->string('email')->nullable();
            $table->enum('user_type', ['Employee', 'Applicant'])->nullable();
            $table->enum('employment_status', ['Regular', 'Contractual', 'Probationary'])->nullable();
            $table->string('position_applied_for')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('user_group', ['Manager', 'Employee', 'HR Personnel'])->nullable();
            $table->string('birth_date')->nullable();
            $table->integer('phone_local_no')->nullable();
            $table->enum('status', ['Active', ['Inactive']])->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
