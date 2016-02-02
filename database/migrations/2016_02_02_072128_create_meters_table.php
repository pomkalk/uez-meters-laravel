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
            $table->integer('apartment_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->date('last_date')->nullabel();
            $table->float('last_value')->nullabel();
            $table->date('new_date')->nullabel();
            $table->float('new_value')->nullabel();
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
