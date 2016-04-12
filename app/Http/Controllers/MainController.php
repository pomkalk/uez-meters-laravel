<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
	public function __construct(){
		$this->middleware('site.available');
		$this->middleware('site.access');
	}

    public function index(){
    	$streets = \App\Street::orderBy('name')->orderBy('prefix')->get();
    	return view('main', ['streets'=>$streets]);
    }

    public function getBuilding($id){
    	$building = \App\Building::where('street_id', $id)->orderBy('number')->orderBy('housing')->get();
        $data = [];
        foreach($building as $item){
            $n = strval($item->number);
            if ($item->housing)
                $n.='/'.$item->housing;
            array_push($data,['id'=>$item->id, 'title'=>$n]);
        }
    	return json_encode($data);
    }

    public function getApartment($id){
        $building = \App\Apartment::where('building_id', $id)->orderBy('number')->orderBy('part')->get();
        $data = [];
        foreach($building as $item){
            $n = strval($item->number);
            if ($item->housing)
                $n.='('.$item->housing.')';
            array_push($data,['id'=>$item->id, 'title'=>$n]);
        }
        return json_encode($data);
    }    

    public function postIndex(Request $request){
        $this->validate($request, [
                'street'=>'required|exists:streets,id',
                'building'=>'required|exists:buildings,id',
                'apartment'=>'required|exists:apartments,id',
                'ls'=>'required|integer',
                'space'=>'required|numeric',
                'g-recaptcha-response'=>'required',
            ],[
                'street.required'=>'Укажите улицу.',
                'street.exists'=>'Вы указали неверную улицу.',
                'building.required'=>'Укажите номер дома.',
                'building.exists'=>'Вы указали неверный номер дома.',
                'apartment.required'=>'Укажите номер квартиры.',
                'apartment.exists'=>'Вы указали неверный номер квартиры.',         
                'ls.required'=>'Укажите лицевой счет.',
                'ls.integer'=>'Лицевой счет это целое число.',
                'space.required'=>'Укажите площадь помещения.',
                'space.numeric'=>'Площадь помещение должно быть числом.',
                'g-recaptcha-response.required'=>'Нажмите на флажек подтверждения, что Вы не робот.',
            ]);

        $curl = new \Curl\Curl();
        $curl->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'=>env('RECAPTHCA_SECRET'),
                'response'=>$request->input('g-recaptcha-response'),
            ]);
        $response = $curl->response;
        if (!isset($response->success))
            return redirect('/')->withErrors('Ошибка recaptcha, обратитесь в организацию.');
        if (!$response->success)
            return redirect('/')->withErrors('Ошибка recaptcha, обратитесь в организацию.');  

        $apartment = \App\Apartment::find($request->input('apartment'));
        if ($apartment->ls != $request->input('ls'))
            return redirect('/')->withErrors('Неверно указан лицевой счет.');              

    }
}
