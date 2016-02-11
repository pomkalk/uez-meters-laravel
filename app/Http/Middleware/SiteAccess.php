<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use AppConfig;
class SiteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $current = Carbon::now("+7");
        $sd = new Carbon(AppConfig::get('work.s_date').Carbon::now()->format('.m.Y ').AppConfig::get('work.s_time'),'+7');
        $ed = new Carbon(AppConfig::get('work.e_date').Carbon::now()->format('.m.Y ').AppConfig::get('work.e_time'),'+7');
        
        if (!$current->between($sd,$ed))
        {
            return view('no-access');
        }
        return $next($request);
    }
}
