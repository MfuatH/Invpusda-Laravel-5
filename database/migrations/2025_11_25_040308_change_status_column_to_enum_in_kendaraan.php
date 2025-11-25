<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusColumnToEnumInKendaraan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('kendaraan', function (Blueprint $table) {
            $table->enum('status', ['available', 'unavailable', 'maintenance'])
                  ->default('available')
                  ->after('plat_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('kendaraan', function (Blueprint $table) {
            $table->string('status', 30)
                  ->default('available')
                  ->after('plat_no');
        });
    }
}