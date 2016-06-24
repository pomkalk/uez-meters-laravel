<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	public function answer()
	{
		return $this->hasOne('\App\Answer');
	}

	public function getTextAttribute($value)
	{
			$text = $value;
		    $text = preg_replace('/\[p(.*?)\]/', '<p$1>', $text);
            $text = preg_replace('/\[\/p(.*?)\]/', '</p$1>', $text);
            $text = preg_replace('/\[strong(.*?)\]/', '<strong$1>', $text);
            $text = preg_replace('/\[\/strong(.*?)\]/', '</strong$1>', $text);
            $text = preg_replace('/\[em(.*?)\]/', '<em$1>', $text);
            $text = preg_replace('/\[\/em(.*?)\]/', '</em$1>', $text);
            $text = preg_replace('/\[u(.*?)\]/', '<u$1>', $text);
            $text = preg_replace('/\[\/u(.*?)\]/', '</u$1>', $text);
            return htmlspecialchars_decode($text);
	}

	public function setTextAttribute($value)
	{
		$text = $value;
		$text = preg_replace('/<p(.*?)>/', '[p$1]', $text);
        $text = preg_replace('/<\/p(.*?)>/', '[/p$1]', $text);
        $text = preg_replace('/<strong(.*?)>/', '[strong$1]', $text);
        $text = preg_replace('/<\/strong(.*?)>/', '[/strong$1]', $text);
        $text = preg_replace('/<em(.*?)>/', '[em$1]', $text);
        $text = preg_replace('/<\/em(.*?)>/', '[/em$1]', $text);
        $text = preg_replace('/<u(.*?)>/', '[u$1]', $text);
        $text = preg_replace('/<\/u(.*?)>/', '[/u$1]', $text);
        $text = htmlspecialchars($text);
        $this->attributes['text'] = $text;
	}
}
