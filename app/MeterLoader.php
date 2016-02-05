<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeterLoader extends Model
{
    public $timestamps = false;

    public function user(){
    	return $this->hasOne('App\User','id','user_id');
    }
}
