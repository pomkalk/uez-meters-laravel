<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned();
            $table->integer('meter_id')->unsigned();
            $table->integer('ls')->unsigned();
            $table->integer('meter_code')->unsigned();
            $table->timestamp('date');
            $table->double('value', 15, 5);
            $table->timestamps();

            $table->index('file_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meter_values');
    }
}
