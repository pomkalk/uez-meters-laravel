<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class DashboardController extends Controller
{
    public function getValues()
    {
    	$file = \App\MeterFile::where('active',1)->first();
        if (!$file)
            return 'Необходимо активировать базу.';

        $a = DB::table('meter_values')->join('meters', 'meters.id','=','meter_values.meter_id')->join('apartments','apartments.id','=','meters.apartment_id')->select(DB::raw('DATE_FORMAT(meter_values.date, "%d.%m.%Y") as date'), DB::raw('count(apartments.ls) as cn'))->where('meter_values.file_id',$file->id)->groupBy(DB::raw('DATE_FORMAT(meter_values.date, "%d.%m.%Y")'), DB::raw('apartments.ls'))->get();



        dd($a);
    }
}
