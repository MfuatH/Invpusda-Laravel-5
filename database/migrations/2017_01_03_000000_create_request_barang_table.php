<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_barang', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('bidang_id');
            $table->string('nama_pemohon')->nullable();
            $table->string('nip')->nullable();
            $table->string('no_hp');
            $table->integer('jumlah_request');
            $table->enum('status', ['pending', 'approved', 'rejected', 'received'])->default('pending');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('bidang_id')->references('id')->on('bidang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_barang');
    }
}
