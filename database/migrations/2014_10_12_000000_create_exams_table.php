<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // <-- This is required

class Exams extends Eloquent
{
    use SoftDeletes; // <-- Use This Instead Of SoftDeletingTrait

    protected $table = 'exams';

    // ...
}

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('exam_id');
            $table->integer('exam_group_id');
            $table->integer('department_id');
            $table->string('exam_title');
            $table->integer('duration_in_minutes');
            $table->string('passing_mark');
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
        Schema::dropIfExists('exams');
    }
}
