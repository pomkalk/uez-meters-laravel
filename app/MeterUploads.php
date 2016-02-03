<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeterUploads extends Model
{
    public $timestamps = false;

    public function user(){
    	return $this->hasOne('App\User','id', 'user_id');
    }
}
