<?php

use Doctrine\DBAL\Types\StringType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

class ChangeVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Type::hasType('enum') ?: Type::addType('enum', StringType::class);

        Schema::table('visitors', function (Blueprint $table) {
            $table->string('meja', 3)->nullable()->after('nomor_pendaftaran');
            $table->string('no_urut', 20)->nullable()->after('meja');
            $table->string('kode', 20)->nullable()->after('no_urut');
            $table->dropUnique('visitors_email_unique');
            $table->dropUnique('visitors_phone_unique');
            $table->bigInteger('province_id', false, true)->nullable()->change();
            $table->bigInteger('city_id', false, true)->nullable()->change();
            $table->text('address')->nullable()->after('city_id')->change();
            $table->string('phone', 15)->nullable()->after('address')->change();
            $table->string('email', 60)->nullable()->after('phone')->change();
            $table->enum('gender', ['Pria', 'Wanita'])->nullable()->after('email')->change();
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
            $table->unique('email', 'visitors_email_unique');
            $table->unique('phone', 'visitors_phone_unique');
        });
    }
}
