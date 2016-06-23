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
        $start_day = AppConfig::get('work.s_date');
        $end_day = AppConfig::get('work.e_date');

        $current = Carbon::now();
        $sd = new Carbon($start_day.Carbon::now()->format('.m.Y ').AppConfig::get('work.s_time'));
        $ed = new Carbon($end_day.Carbon::now()->format('.m.Y ').AppConfig::get('work.e_time'));

        if (!$current->between($sd,$ed))
        {
            return view('no-access');
        }
        return $next($request);
    }
}
