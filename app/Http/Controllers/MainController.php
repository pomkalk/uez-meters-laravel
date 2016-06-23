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

    public function index(Request $request){
        $c = $request->cookie('kvowner');
        if ($c){
            $tmp = explode(':', $c);
            if (count($tmp)!=3)
                return redirect('/')->withCookie(\Cookie::forget('kvowner'));
            $ls = intval($tmp[0]);
            $space = floatval($tmp[1]);
            $file_id = intval($tmp[2]); 

            $apartment = \App\Apartment::where('ls', $ls)->where('space', $space)->first();
            if (!$apartment){
                \Log::info('1');
                return redirect('/')->withCookie(\Cookie::forget('kvowner'));
            }
            \Log::info('2');
            $file = \App\MeterFile::where('active',1)->first();
            if (!$file){
                \Log::info('3');
                return redirect('/')->withCookie(\Cookie::forget('kvowner'));
            }else{
                \Log::info('4');
                if ($file->id != $file_id){
                    \Log::info('5');
                    return redirect('/')->withCookie(\Cookie::forget('kvowner'));
                }
            }

            return view('main', ['saved'=>true, 'apartment'=>$apartment]);
        }

    	$streets = \App\Street::orderBy('name')->orderBy('prefix')->get();
    	return view('main', ['saved'=>false,'streets'=>$streets]);
    }

    public function changeAddress()
    {
        return redirect('/')->withCookie(\Cookie::forget('kvowner'));
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
            if ($item->part)
                $n.='('.$item->part.')';
            array_push($data,['id'=>$item->id, 'title'=>$n]);
        }
        return json_encode($data);
    }    

    public function postIndex(Request $request){
        $file = \App\MeterFile::where('active',1)->first();
        if (!$file)
            return redirect('/')->withErrors('Данные на загружены, обратитесь Вашу Управляющую организацию.');

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
                'g-recaptcha-response.required'=>'Нажмите на флажок подтверждения, что Вы не робот.',
            ]);

        $curl = new \Curl\Curl();
        $curl->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'=>env('RECAPTHCA_SECRET'),
                'response'=>$request->input('g-recaptcha-response'),
            ]);
        $response = $curl->response;
        if (!isset($response->success))
            return redirect('/')->withErrors('Ошибка recaptcha, попробуйте еще раз.');
        if (!$response->success)
            return redirect('/')->withErrors('Ошибка recaptcha, попробуйте еще раз.');

        $apartment = \App\Apartment::find($request->input('apartment'));
        if ($apartment->ls != $request->input('ls'))
            return redirect('/')->withErrors('Неверно указан лицевой счет.')->withInputs();
        $space = str_replace(',', '.', $request->input('space'));

        if ($apartment->space != $request->input('space'))
            return redirect('/')->withErrors('Неверно указана площадь.')->withInputs();

        if ($request->input('remember', false)){
            return redirect('open')->with('client',[
                    'file'=>$file->id,
                    'ls'=>$apartment->ls,
                    'apartment_id'=>$apartment->id,
                ])->withCookie('kvowner',$request->input('ls').':'.$request->input('space').':'.$file->id, 60*24*30*3 );
        }else{
            return redirect('open')->with('client',[
                    'file'=>$file->id,
                    'ls'=>$apartment->ls,
                    'apartment_id'=>$apartment->id,
                ]);            
        }
    }

    public function open(){
        $client = session('client');
        if (!$client)
            return redirect('/');

        $file = \App\MeterFile::where('active',1)->first();
        if (!$file)
            return redirect('/')->withErrors('Данные на загружены, обратитесь Вашу Управляющую организацию.');

        $apartment = \App\Apartment::find($client['apartment_id']);
        $building = $apartment->building;
        $street = $building->street;

        $full_address = $street->prefix.'. '.$street->name.', д. '.$building->number.(($building->housing)?'/'.$building->housing:'').' кв. '.$apartment->number.(($apartment->part)?'/'.$apartment->part:'');

        $meters = \App\Meter::where('apartment_id', $apartment->id)->orderBy('service_id')->get();
        $meter_ids = [];
        foreach($meters as $m)
            array_push($meter_ids, $m->id);
        $old_values = \App\MeterValue::where('file_id',$file->id)->whereIn('meter_id',$meter_ids)->get();
        $meter_values = [];
        foreach($old_values as $ov)
            $meter_values[$ov->meter_id] = $ov->value;

        session()->flash('can-save','1');

        $show_info = false;
        foreach($meters as $m){
            if ($m->status_id != 1)
                $show_info = true;
        }

        return view('open', [
                'address' => $full_address,
                'apartment'=>$apartment,
                'file_id'=>$file->id,
                'meters'=>$meters,
                'show_info'=>$show_info,
                'meter_values'=>$meter_values,
            ]);
    }

    public function save(Request $request)
    {
        if (!session()->has('can-save'))
            return json_encode(['success'=>false,'errors'=>['Ошибка прав доступа на сохранение показаний, обратитесь Вашу Управляющую организацию.']]);

        session()->flash('can-save','1');

        $file = \App\MeterFile::where('active',1)->first();
        if (!$file)
            return json_encode(['success'=>false,'errors'=>['Данные на загружены, обратитесь Вашу Управляющую организацию.']]);

        $sdata = $request->input('sdata', false);
        if (!$sdata)
            return json_encode(['success'=>false,'errors'=>['Ошибка в данных, обратитесь Вашу Управляющую организацию.']]);

        if ( strpos($sdata, ":") === false)
            return json_encode(['success'=>false,'errors'=>['Ошибка в данных, обратитесь Вашу Управляющую организацию.']]);

        $apartment_id = explode(':', $sdata)[0];
        $file_id = explode(':', $sdata)[1];

        if ($file->id != $file_id)
            return json_encode(['success'=>false,'errors'=>['Данные на загружены, обратитесь Вашу Управляющую организацию.']]);

        $apartment = \App\Apartment::find($apartment_id);
        if (!$apartment)
            return json_encode(['success'=>false,'errors'=>['Ошибка в данных, обратитесь Вашу Управляющую организацию.']]);

        $ls = $apartment->ls;

        $meters = $request->input('meter');

        $errors = [];
        $errorsFields = [];
        $saving = [];

        $people = ($apartment->people<=0)?1:$apartment->people;

        foreach ($meters as $key => $value) {
            $meter = \App\Meter::where('id',$key)->where('apartment_id', $apartment->id)->first();
            if (!$meter)
                array_push($errors, 'ER03:'.$key.' - системная ошибка, обратитесь Вашу Управляющую организацию и сообщите код ошибки.');
            if (empty($value)){
                $new_value = \App\MeterValue::where('file_id',$file_id)->where('meter_id', $key)->first();
                if ($new_value){
                    $new_value->date = \Carbon\Carbon::now();
                    $new_value->value = $value;
                    array_push($saving, $new_value);
                }
                continue;
            }
                

            $val = floatval($value);
            if ($val<0){
                array_push($errors, 'Показания счетчика <b>'.$meter->service->name.'</b> не могут быть отрицательными.');
                array_push($errorsFields, $meter->id);
                continue;
            }

            $norm = $meter->service->norm;
            
            if ($norm>0){
                if ($meter->last_value > $val){
                    array_push($errors, 'Показания счетчика <b>'.$meter->service->name.'</b> не могут быть меньше предыдущих.');
                    array_push($errorsFields, $meter->id);
                    continue;
                }

                $mx = $meter->service->additional;

                $max_value = $meter->last_value + ( $people * $norm + $norm * $mx );

                if ($val > $max_value){
                    array_push($errors, 'К сожалению, показания счетчика <b>'.$meter->service->name.'</b> не могут превышать установленный предел, максимальное значение составляет&nbsp;<b>'.round($max_value,3).'</b>. Пожалуйста установите значение меньше или передайте показания по телефону.');
                    array_push($errorsFields, $meter->id);
                    continue;
                }

                $new_value = \App\MeterValue::where('file_id',$file_id)->where('meter_id', $key)->first();
                if ($new_value){
                    $new_value->date = \Carbon\Carbon::now();
                    $new_value->value = $value;  
                }else{
                    $new_value = new \App\MeterValue();
                    $new_value->file_id = $file_id;
                    $new_value->meter_id = $key;
                    $new_value->date = \Carbon\Carbon::now();
                    $new_value->value = $value;    
                }

                array_push($saving, $new_value);

            }else{
                $new_value = \App\MeterValue::where('file_id',$file_id)->where('meter_id', $key)->first();
                if ($new_value){
                    $new_value->date = \Carbon\Carbon::now();
                    $new_value->value = $value;  
                }else{
                    $new_value = new \App\MeterValue();
                    $new_value->file_id = $file_id;
                    $new_value->meter_id = $key;
                    $new_value->date = \Carbon\Carbon::now();
                    $new_value->value = $value;    
                }

                array_push($saving, $new_value);
            }
        }

        if (count($errors)>0){
            return json_encode(['success'=>false,'errors'=>$errors, 'efields'=>$errorsFields]);    
        }else{

            if (count($saving)>0){
                foreach ($saving as $nv){
                    if ($nv->value == 0) $nv->delete();
                    else $nv->save();
                }
            }else{
                return json_encode(['success'=>true,'empty'=>true]);
            }
            return json_encode(['success'=>true, 'message'=>'Показания успешно сохранены.']);    
        }

    }
}
