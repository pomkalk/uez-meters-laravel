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
        	'email'=>env('ADMIN_DEFAULT_EMAIL','admin@admin.org'),
        	'password'=>bcrypt(env('ADMIN_DEFAULT_PASSWORD','nimda'))
        ]);
    }
}
