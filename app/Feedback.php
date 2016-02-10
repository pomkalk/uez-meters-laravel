<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	
	public function unread(){
		return $this->hasOne('App\FeedbackUnread', 'id','feedback_id');
	}
}
