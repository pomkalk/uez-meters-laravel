<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
	public $incrementing = false;
    public $timestamps = false;

    public function buildings(){
    	return $this->hasMany('App\Building', 'street_id');
    }
}
