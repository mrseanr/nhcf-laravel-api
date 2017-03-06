<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('individuals')) {
          Schema::create('individuals', function (Blueprint $table) {
              $table->increments('individual_id');
              $table->integer('address_id');
              $table->integer('role_id');
              $table->integer('church_status_id');
              $table->boolean('active');
              $table->string('first_name');
              $table->string('last_name');
              $table->string('email_address');
              $table->string('phone_number');
              $table->date('birthday');
              $table->char('gender', 1);
              $table->string('known_allergies');
              $table->string('notes');
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
        Schema::dropIfExists('individuals');
    }
}
