<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    public $timestamps = false;

    public function street(){
    	return $this->belongsTo('App\Street', 'id');
    }

    public function apartments(){
    	return $this->hasMany('App\Apartment','building_id');
    }
}
