<?php namespace App\Support;

use Illuminate\Support\Facades\Facade;

class AppConfigFacade extends Facade{
	protected static function getFacadeAccessor(){
		return 'App\Services\AppConfig';
	}
}