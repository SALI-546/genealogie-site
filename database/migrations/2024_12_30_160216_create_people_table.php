<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('created_by');
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('birth_name', 255)->nullable();
            $table->string('middle_names', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('created_by');

            // Clés étrangères
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}
