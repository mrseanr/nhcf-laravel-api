<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndividualStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('individual_status')) {
          Schema::create('individual_status', function (Blueprint $table) {
              $table->increments('individual_status_id');
              $table->string('status_name');
              $table->string('description');
              $table->string('notes')->nullable();
              $table->boolean('active');
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
        Schema::dropIfExists('individual_status');
    }
}
