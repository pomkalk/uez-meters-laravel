<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterFile extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];



    public function getSavedValuesAttribute()
    {
    	$val = \App\MeterValue::where('file_id', $this->id)->count();
    	return ($val>0)?$val:null;
    }
}
