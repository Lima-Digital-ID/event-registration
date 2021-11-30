<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitors', function (Blueprint $table) {
            // $table->dropForeign('visitors_province_id_foreign');
            // $table->dropForeign('visitors_city_id_foreign');
            // $table->dropColumn('province_id');
            // $table->dropColumn('city_id');
            // $table->dropColumn('phone');
            // $table->dropColumn('email');
            // $table->dropColumn('gender');
            // $table->dropColumn('date_of_birth');
            // $table->dropColumn('address');
            // $table->string('undangan', 3)->nullable()->after('name');
            // $table->string('meja', 3)->nullable()->after('undangan');
            // $table->string('urutan_no', 3)->nullable()->after('meja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $table->bigInteger('province_id', false, true)->after('name');
        // $table->bigInteger('city_id', false, true)->after('province_id');
        // $table->text('address')->after('city_id');
        // $table->string('phone', 15)->unique()->after('address');
        // $table->string('email', 60)->unique()->after('phone');
        // $table->enum('gender', ['Pria', 'Wanita'])->after('email');
        // $table->date('date_of_birth')->after('gender');
        // $table->dropColumn('undangan');
        // $table->dropColumn('meja');
        // $table->dropColumn('urutan_no');
    }
}
