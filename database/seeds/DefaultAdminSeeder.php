<?php

use Illuminate\Database\Seeder;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name'=>'administrator',
        	'email'=>'admin@admin.org',
        	'password'=>bcrypt('nimda')
        ]);
    }
}
