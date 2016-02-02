<?php

use Illuminate\Database\Seeder;

class DefaultStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
        	[
        		'id'=>'1',
        		'name'=>'Установлен'
        	],
        	[
        		'id'=>'-1',
        		'name'=>'Заблокирован'
        	],
        	[
        		'id'=>'-2',
        		'name'=>'Приостановлен'
        	]
        	]);
    }
}
