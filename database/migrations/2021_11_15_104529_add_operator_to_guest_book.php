<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOperatorToGuestBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_book', function (Blueprint $table) {
            $table->bigInteger('operator', false, true)->nullable()->after('visitor_id');

            $table->foreign('operator')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_book', function (Blueprint $table) {
            $table->dropForeign('guest_book_operator_foreign');
            $table->dropColumn('operator');
        });
    }
}
