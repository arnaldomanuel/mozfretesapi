<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreightJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight_journeys', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('from_location');
            $table->string('to_location');
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->boolean('price_set')->default(true);
            $table->double('price_negotiate')->nullable();
            $table->string('status')->nullable();
            $table->string('phone_journey')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->softDeletes();
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
        Schema::dropIfExists('freight_journeys');
    }
}
