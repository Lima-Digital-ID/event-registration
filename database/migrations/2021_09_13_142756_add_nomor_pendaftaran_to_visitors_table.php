<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomorPendaftaranToVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('nomor_pendaftaran', 100)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('nomor_pendaftaran');
        });
    }
}
