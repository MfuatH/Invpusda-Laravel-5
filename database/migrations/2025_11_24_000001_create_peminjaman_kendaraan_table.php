<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeminjamanKendaraanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman_kendaraan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 150);
            $table->string('nip', 50)->nullable();
            $table->string('no_hp', 50)->nullable();
            $table->string('urgensi', 200)->nullable();
            $table->dateTime('tanggal_ambil')->nullable();
            $table->dateTime('tanggal_kembali')->nullable();
            $table->string('plat_no', 50)->nullable();
            $table->string('status', 30)->default('pending');
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
        Schema::dropIfExists('peminjaman_kendaraan');
    }
}
