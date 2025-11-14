<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCateringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catering', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pemesan', 100);
            $table->string('nip', 50)->nullable();
            $table->text('keperluan');
            $table->date('tanggal_kegiatan');
            $table->string('tempat');
            $table->integer('jumlah_peserta');
            $table->string('jenis_konsumsi', 255); // untuk multiple checkbox disimpan JSON
            $table->text('keterangan')->nullable();
            $table->string('nota_dinas_file', 500);
            $table->string('nota_dinas_original_name')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->integer('approved_by')->unsigned()->nullable();
            $table->datetime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('approved_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('tanggal_kegiatan');
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
        Schema::table('catering', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
        });
        
        Schema::drop('catering');
    }
}