<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {
        $this->call(BidangSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ItemSeeder::class);
    }
}
