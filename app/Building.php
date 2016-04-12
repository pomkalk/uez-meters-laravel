<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
	public $incrementing = false;
    public $timestamps = false;

    public function street(){
    	return $this->belongsTo('App\Street', 'street_id');
    }

    public function apartments(){
    	return $this->hasMany('App\Apartment','building_id');
    }
}
