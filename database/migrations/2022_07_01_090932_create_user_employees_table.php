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
        Schema::create('user_employees', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->smallIncrements('id');
            $table->smallInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('position_id')->unsigned()->index();
            $table->foreign('position_id')->references('id')->on('list_positions')->onDelete('cascade');
            $table->tinyInteger('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('office_id')->unsigned()->index();
            $table->foreign('office_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('religion_id')->unsigned()->index();
            $table->foreign('religion_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('level_id')->unsigned()->index();
            $table->foreign('level_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('blood_id')->unsigned()->index();
            $table->foreign('blood_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->date('start');
            $table->date('end')->nullable();
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
        Schema::dropIfExists('user_employees');
    }
};
