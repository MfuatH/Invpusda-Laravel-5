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
            DB::table('users')->insert($user);
        }
    }
}
