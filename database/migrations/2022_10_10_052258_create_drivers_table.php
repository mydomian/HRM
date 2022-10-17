<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('thana_id');
            $table->string('name');
            $table->bigInteger('phone');
            $table->string('father_name');
            $table->text('post_office');
            $table->text('village');
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('thana_id')->references('id')->on('thanas')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('drivers');
    }
}
