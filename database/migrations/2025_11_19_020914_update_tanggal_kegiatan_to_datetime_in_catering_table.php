<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UpdateTanggalKegiatanToDateTimeInCateringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catering', function (Blueprint $table) {
            $table->dropColumn('tanggal_kegiatan');
        });

        Schema::table('catering', function (Blueprint $table) {
            $table->dateTime('tanggal_kegiatan')->after('keperluan');
            $table->index('tanggal_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catering', function (Blueprint $table) {
            $table->dropIndex(['tanggal_kegiatan']);

            $table->dropColumn('tanggal_kegiatan');
        });

        Schema::table('catering', function (Blueprint $table) {
            $table->date('tanggal_kegiatan')->after('keperluan');

            $table->index('tanggal_kegiatan');
        });
    }
}