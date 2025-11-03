<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('jumlah');
            $table->enum('tipe', ['masuk', 'keluar', 'rejected']);
            $table->date('tanggal');
            $table->timestamps();
            
            $table->foreign('request_id')->references('id')->on('request_barang')->onDelete('set null');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
