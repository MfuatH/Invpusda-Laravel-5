<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidangAndRequestLinkzoomTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bidang')) {
            Schema::create('bidang', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama');
                $table->text('pesan_template')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('request_linkzoom')) {
            Schema::create('request_linkzoom', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_pemohon');
                $table->string('nip')->nullable();
                $table->string('no_hp');
                $table->unsignedInteger('bidang_id')->nullable();
                $table->string('link_zoom')->nullable();
                $table->dateTime('jadwal_mulai');
                $table->dateTime('jadwal_selesai')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('nama_rapat');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
                
                $table->foreign('bidang_id')->references('id')->on('bidang')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('request_linkzoom');
        Schema::dropIfExists('bidang');
    }
}
