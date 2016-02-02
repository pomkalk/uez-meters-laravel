<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    public function meters(){
    	return $this->hasMany('App\Meter','status_id');
    }
}
