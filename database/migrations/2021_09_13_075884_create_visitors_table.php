<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->bigInteger('province_id', false, true);
            $table->bigInteger('city_id', false, true);
            $table->text('address');
            $table->string('phone', 15)->unique();
            $table->string('email', 60)->unique();
            $table->enum('gender', ['Pria', 'Wanita']);
            $table->date('date_of_birth')->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('province');
            $table->foreign('city_id')->references('id')->on('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
