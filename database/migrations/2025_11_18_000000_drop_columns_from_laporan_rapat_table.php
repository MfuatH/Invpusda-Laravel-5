<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromLaporanRapatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_rapat', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['catering_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['verified_by']);
            
            // Drop indexes if they exist
            $table->dropIndex(['catering_id']);
            
            // Drop the columns
            $table->dropColumn(['catering_id', 'verified_by', 'verified_at', 'created_by']);
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
            // Add columns back
            $table->integer('catering_id')->unsigned()->nullable();
            $table->integer('verified_by')->unsigned()->nullable();
            $table->datetime('verified_at')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            
            // Add foreign keys back
            $table->foreign('catering_id')
                  ->references('id')->on('catering')
                  ->onDelete('set null');
                  
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('verified_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
            
            // Add indexes back
            $table->index('catering_id');
        });
    }
}
