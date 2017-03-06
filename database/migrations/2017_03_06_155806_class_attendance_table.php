<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('class_attendance')) {
          Schema::create('class_attendance', function (Blueprint $table) {
              $table->increments('record_id');
              $table->integer('individual_id');
              $table->integer('class_id');
              $table->datetime('attendance_timestamp');
          });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_attendance');
    }
}
