<?php

use Illuminate\Database\Seeder;

class DefaultConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->insert([
	        	['key'=>'site.available','value'=>'1'],
	        	['key'=>'site.unmessage','value'=>'На сайте проводятся технические работы'],
	        	['key'=>'work.s_date','value'=>'1'],
	        	['key'=>'work.s_time','value'=>'8:00'],
	        	['key'=>'work.e_date','value'=>'30'],
	        	['key'=>'work.e_time','value'=>'24:00'],
	        	['key'=>'work.unmessage','value'=>'Укажите сообщение для отображения, когда сайт не работает'],
        	]);
    }
}
