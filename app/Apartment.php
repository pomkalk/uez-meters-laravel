<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
	public $incrementing = false;
    public $timestamps = false;

    public function building(){
    	return $this->belongsTo('App\Building','building_id');
    }

    public function meters(){
    	return $this->hasMany('App\Meter','apartment_id');
    }
}
