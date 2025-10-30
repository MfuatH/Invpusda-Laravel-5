<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@pusda.com',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'bidang' => null,
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Sekretariat',
                'email' => 'sekretariat@pusda.com',
                'password' => bcrypt('password'),
                'role' => 'admin_barang',
                'bidang' => 'sekretariat',
                'no_hp' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin PSDA',
                'email' => 'psda@pusda.com',
                'password' => bcrypt('password'),
                'role' => 'admin_barang',
                'bidang' => 'psda',
                'no_hp' => '081234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            // If bidang is provided as name, try to map to bidang_id
            if (isset($user['bidang']) && $user['bidang']) {
                $bidangId = DB::table('bidang')->where('nama', $user['bidang'])->value('id');
                $user['bidang_id'] = $bidangId ?: null;
            } else {
                $user['bidang_id'] = null;
            }

            // Remove old bidang key if present
            unset($user['bidang']);

            DB::table('users')->insert($user);
        }
    }
}
