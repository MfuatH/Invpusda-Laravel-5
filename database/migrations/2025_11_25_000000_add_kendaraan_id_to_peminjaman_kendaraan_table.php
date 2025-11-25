<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKendaraanIdToPeminjamanKendaraanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            $table->unsignedBigInteger('kendaraan_id')->nullable()->after('urgensi');
            $table->foreign('kendaraan_id')->references('id')->on('kendaraan')->onDelete('set null');
            
            // Drop plat_no if it exists
            if (Schema::hasColumn('peminjaman_kendaraan', 'plat_no')) {
                $table->dropColumn('plat_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
            $table->dropColumn('kendaraan_id');
            $table->string('plat_no', 50)->nullable();
        });
    }
}
