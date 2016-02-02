<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    public $timestamps = false;

    public function apartment(){
    	return $this->belongsTo('App\Apartment','id');
    }

    public function service(){
    	return $this->belongsTo('App\Service','id');
    }

    public function status(){
    	return $this->belongsTo('App\Status','id');
    }
}
