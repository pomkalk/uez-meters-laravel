<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function feedback()
    {
    	return $this->belongsTo('\App\Feedback');
    }
}
