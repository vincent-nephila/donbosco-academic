<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('section');
            $table->integer('quarter');
            $table->string('schoolyear');
            $table->date('in_apsa');
            $table->date('in_registrar');
            $table->string('gradetype');
            $table->integer('status');
            
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
        Schema::drop('grades_statuses');
    }
}
