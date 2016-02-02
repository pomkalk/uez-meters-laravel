<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	public $incrementing = false;
    public $timestamps = false;
    protected $table='config';
    protected $primaryKey = 'key';
    
}
