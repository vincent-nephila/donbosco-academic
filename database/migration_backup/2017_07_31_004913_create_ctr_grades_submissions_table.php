<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrGradesSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_grades_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->integer('quarter');
            $table->date('duedate');
            $table->integer('override');
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
        Schema::drop('ctr_grades_submissions');
    }
}
