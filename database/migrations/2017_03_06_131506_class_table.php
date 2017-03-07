<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('classes')) {
          Schema::create('classes', function (Blueprint $table) {
              $table->increments('class_id');
              $table->integer('church_id');
              $table->integer('teacher_id');
              $table->integer('class_type_id');
              $table->boolean('active');
              $table->string('class_name');
              $table->string('notes')->nullable();
              $table->timestamps();
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
        Schema::dropIfExists('classes');
    }
}
