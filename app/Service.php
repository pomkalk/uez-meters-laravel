<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;

    public function meters(){
    	return $this->hasMany('App\Meter', 'service_id');
    }
}
