<?php namespace App\Services;

use App\Config;


class AppConfig{

	public function get($key, $default_value = null){
		$param = Config::where('key',$key)->first();
		if ($param) {
			return $param->value;
		}else{
			return $default_value?$default_value:null;
		}
	}

	public function keys(){
		$params = Config::all();
		$keys = [];
		foreach ($params as $param){
			array_push($keys, $param->key);
		}
		return $keys;
	}
	public function set($key,$value=''){
		$param = Config::where('key',$key)->first();
		if ($param) {
			$param->value = $value;
			$param->save();
			return true;
		}else{
			return false;
		}		
	}
}