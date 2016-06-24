<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function feedback()
    {
    	return $this->belongsTo('\App\Feedback');
    }
}
