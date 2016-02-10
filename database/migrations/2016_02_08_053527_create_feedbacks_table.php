<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_id',60);
            $table->integer('apartment_id')->unsigned();
            $table->string('email')->nullable();
            $table->text('feedback');
            $table->date('shown_at')->nullable();
            $table->integer('shown_times')->unsigned()->default(0);
            $table->boolean('unread')->default(true);
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
        Schema::drop('feedbacks');
    }
}
