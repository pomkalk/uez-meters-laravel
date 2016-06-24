<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	public function answer()
	{
		return $this->hasOne('\App\Answer');
	}
}
