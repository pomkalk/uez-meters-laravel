<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    public $incrementing = false;
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

    public function getLastDateAttribute($value){
        $date = \Carbon\Carbon::parse($value);
        $monthes = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь',];
        $text_date = $monthes[$date->month-1].' '.$date->year;
        return $text_date;
    }
}
