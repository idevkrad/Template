<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtrs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->date('date');
            $table->json('am_in_at');
            $table->json('am_out_at');
            $table->json('pm_in_at');
            $table->json('pm_out_at');
            $table->json('remarks');
            $table->integer('tardiness');
            $table->integer('undertime');
            $table->boolean('is_updated')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->smallInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('dtrs');
    }
};
