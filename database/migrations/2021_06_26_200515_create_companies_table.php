<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('logo')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('whatsapp')->nullable();
            $table->integer('company_type');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('companies');
    }
}
