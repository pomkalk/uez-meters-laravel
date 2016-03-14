<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('uploaded_at');
            $table->string('file');
            $table->string('data');
            $table->string('addresses');
            $table->string('meters');
            $table->boolean('active')->default(0);
            $table->integer('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meter_uploads');
    }
}
