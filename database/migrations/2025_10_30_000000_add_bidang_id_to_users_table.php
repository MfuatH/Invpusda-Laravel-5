<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddBidangIdToUsersTable extends Migration
{
    public function up()
    {
        // 1) add bidang_id nullable
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('bidang_id')->nullable()->after('role');
        });

        // 2) populate bidang_id from existing bidang name in users.bidang (if present)
        $mapping = DB::table('bidang')->pluck('id', 'nama');
        $users = DB::table('users')->select('id', 'bidang')->get();
        foreach ($users as $u) {
            if ($u->bidang) {
                $bidangName = $u->bidang;
                if (isset($mapping[$bidangName])) {
                    DB::table('users')->where('id', $u->id)->update(['bidang_id' => $mapping[$bidangName]]);
                }
            }
        }

        // 3) make bidang_id foreign key (nullable)
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('bidang_id')->references('id')->on('bidang')->onDelete('set null');
        });

        // 4) drop old bidang string column if exists
        if (Schema::hasColumn('users', 'bidang')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('bidang');
            });
        }
    }

    public function down()
    {
        // Recreate bidang string column (nullable)
        Schema::table('users', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('role');
        });

        // Try to repopulate bidang name from bidang_id
        $map = DB::table('bidang')->pluck('nama', 'id');
        $users = DB::table('users')->select('id', 'bidang_id')->get();
        foreach ($users as $u) {
            if ($u->bidang_id && isset($map[$u->bidang_id])) {
                DB::table('users')->where('id', $u->id)->update(['bidang' => $map[$u->bidang_id]]);
            }
        }

        // drop foreign key and bidang_id
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });
    }
}
