<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_types', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('package_buy_id')->unsigned();
            $table->string('name');
            $table->enum('status',['active', 'deactive'])->default('active');
            $table->bigInteger('view_id')->nullable();
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_types');
    }
}
