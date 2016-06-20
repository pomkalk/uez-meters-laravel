<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->integer('apartment_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->integer('status_id');
            $table->timestamp('last_date')->nullable();
            $table->float('last_value')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meters');
    }
}
