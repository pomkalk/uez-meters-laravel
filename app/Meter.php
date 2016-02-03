<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    public $timestamps = false;

    public function apartment(){
    	return $this->belongsTo('App\Apartment','apartment_id');
    }

    public function service(){
    	return $this->belongsTo('App\Service','service_id');
    }

    public function status(){
    	return $this->belongsTo('App\Status','status_id');
    }
}
