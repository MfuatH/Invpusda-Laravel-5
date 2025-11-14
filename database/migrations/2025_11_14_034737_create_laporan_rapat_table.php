<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporanRapatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_rapat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catering_id')->unsigned()->nullable();
            $table->string('pengunggah', 100);
            $table->string('nip', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_laporan', 500);
            $table->string('file_original_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('submitted');
            $table->integer('verified_by')->unsigned()->nullable();
            $table->datetime('verified_at')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('catering_id')
                  ->references('id')->on('catering')
                  ->onDelete('set null');
                  
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('verified_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('catering_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_rapat', function (Blueprint $table) {
            $table->dropForeign(['catering_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['verified_by']);
        });
        
        Schema::drop('laporan_rapat');
    }
}