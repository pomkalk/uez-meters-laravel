<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserTableSeeder::class);
        $this->call(DefaultAdminSeeder::class);
        $this->call(DefaultStatusesSeeder::class);
        $this->call(DefaultConfigSeeder::class);
    }
}
