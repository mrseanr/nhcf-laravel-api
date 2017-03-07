<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('address')) {
          Schema::create('address', function (Blueprint $table) {
              $table->increments('address_id');
              $table->string('address_line_1');
              $table->string('address_line_2')->nullable();
              $table->string('city');
              $table->char('state', 2);
              $table->integer('zip');
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
        Schema::dropIfExists('address');
    }
}
