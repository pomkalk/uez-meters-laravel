<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Validator;
use AppConfig;
use Carbon\Carbon;
use App\User;
use Hash;
use Storage;
use File;
use DB;

class AdminController extends Controller
{
    public function getLogin(){
    	return view('admin.login');
    }

	public function postLogin(Request $request){
		$this->validate($request,[
			'email'=>'required|email',
			'password'=>'required'
		],[
			'email.required'=>'Заполните поле "Электронная почта"',
			'password.required'=>'Заполните поле "Пароль"',
			'email.email'=>'Укажите правильный email'
		]);
		
		if (Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')], false))
		{
			return redirect()->intended('admin');
		}else{
			return redirect()->back()->withInput()->withErrors('Неверное имя пользователя или пароль');
		}
	}
	
	public function getLogout(){
		if (Auth::check())
			Auth::logout();
		return redirect('admin');
	}
	
	
    public function getAdmin(){
    	return view('admin.main');
    }

    public function getSettings(){
    	$settings = [];
    	$settings['site_available'] = AppConfig::get('site.available');
    	$settings['site_unmessage'] = AppConfig::get('site.unmessage');
    	$settings['work_startdate'] = (new Carbon(AppConfig::get('work.s_date').Carbon::now()->format('.m.Y ').AppConfig::get('work.s_time')))->format('Y.m.d h:i');
    	$settings['work_enddate'] = (new Carbon(AppConfig::get('work.e_date').Carbon::now()->format('.m.Y ').AppConfig::get('work.e_time')))->format('Y.m.d h:i');
    	$settings['work_unmessage'] = AppConfig::get('work.unmessage');
        $settings['work_infometter'] = AppConfig::get('work.infometter');
        


    	return view('admin.settings', $settings);
    }

    public function postSettings(Request $request){
    	$response = ['success'=>true];
    	$key = $request->input('pk');
    	$value = $request->input('value');

    	switch ($key) {
    		case 'site.available':
    			$validator = Validator::make(['value'=>$value],['value'=>'required|numeric|min:0|max:1']);
    			if ($validator->fails()){ return response('Ошибка параметра "Доступ"',400); }
    			AppConfig::set($key,$value);
    			return response('',200);
    			break;
    		case 'site.unmessage':
    			AppConfig::set($key,$value);
    			return response('',200);
    			break;
    		case 'work.startdate':
    		    $validator = Validator::make(['value'=>$value],['value'=>'required|date_format:Y-m-d H:i']);
    			if ($validator->fails()){ return response('Все поля обязательны',400); }
    			$ds = new Carbon($value);
    			$ds->month = Carbon::now()->month;
    			$ds->year = Carbon::now()->year;
    			$de = new Carbon(AppConfig::get('work.e_date').Carbon::now()->format('.m.Y ').AppConfig::get('work.e_time'));
    			if ($ds>$de) return response('Начальная дата не может быть больше конечной',400);
    			AppConfig::set('work.s_date',$ds->day);
    			AppConfig::set('work.s_time',$ds->toTimeString());
    			break;
    		case 'work.enddate':
    		    $validator = Validator::make(['value'=>$value],['value'=>'required|date_format:Y-m-d H:i']);
    			if ($validator->fails()){ return response('Все поля обязательны',400); }
    			$de = new Carbon($value);
    			$de->month = Carbon::now()->month;
    			$de->year = Carbon::now()->year;
    			$ds = new Carbon(AppConfig::get('work.s_date').Carbon::now()->format('.m.Y ').AppConfig::get('work.s_time'));
    			if ($de<$ds) return response('Конечная дата не может быть меньше начальной',400);
    			AppConfig::set('work.e_date',$de->day);
    			AppConfig::set('work.e_time',$de->toTimeString());
    			break;    
    		case 'work.unmessage':
    			AppConfig::set($key,$value);
    			return response('',200);
    			break;
            case 'work.infometter':
                AppConfig::set($key,$value);
                return response('',200);
                break;                
    		default:
    			return response('Неизвестный запрос',400);
    			break;
    	}
    }

    public function getChangepassword(){
    	return view('admin.changepassword');
    }

    public function postChangepassword(Request $request){
		$this->validate($request,[
			'old_pass'=>'required',
			'new_pass'=>'required|min:6',
			'new_repeat'=>'required|same:new_pass'
		],[
			'old_pass.required'=>'Поле "Старый пароль" обязательно для заполнения',
			'new_pass.required'=>'Поле "Новый пароль" обязательно для заполнения',
			'new_pass.min'=>'Минимальная длинна пароля 6 символов',			
			'new_repeat.required'=>'Поле "Новый пароль еще раз" обязательно для заполнения',
			'same'=>'Пароль не совпадает',
		]);

		$user = User::where('email',Auth::user()->email)->first();
		if (Hash::check($request->input('old_pass'), $user->password)){
			$user->password = bcrypt($request->input('new_pass'));
			$user->save();
	    	return redirect('admin/changepassword')->with(['success'=>'Пароль успешно изменен!']);
		}
		return redirect('admin/changepassword')->withErrors('Неверно указан действующий пароль');
    }


    public function getDatabase(){
        $uploads = \App\MeterFile::latest()->paginate(15);
        $trashed = \App\MeterFile::onlyTrashed()->count();
        $file = \App\MeterFile::where('active',1)->first();
        return view('admin.database', ['files'=>$uploads, 'trashed'=>$trashed, 'isTrash'=>false, 'active_file'=>$file]);
    }
    public function getDatabaseTrashed(){
        $uploads = \App\MeterFile::onlyTrashed()->paginate(15);
        return view('admin.database', ['files'=>$uploads, 'trashed'=>0, 'isTrash'=>true]);
    }    

    public function getDatabaseAdd(){
        return view('admin.addfile');
    }

    public function postDatabaseAdd(Request $request){
        set_time_limit(0);
        $this->validate($request, [
                'name'=>'required',
                'xml'=>'mimes:xml',
            ]);

        $file_name = 'meter_files/'.str_random(50);

        $file = new \App\MeterFile();
        $file->name = $request->input('name');
        $file->file = $file_name;

        Storage::put($file_name, File::get($request->file('xml')));
        $file->save();

        return redirect('admin/database');
    }

    public function getDelete($id){
        $file = \App\MeterFile::findOrFail($id);
        $file->active = 0;
        $file->save();

        $file->delete();

        return redirect('admin/database');
    }

    public function getRestore($id){
        $file = \App\MeterFile::onlyTrashed()->where('id',$id)->first();
        $file->active = 0;
        $file->restore();

        return redirect('admin/database');
    }    

    public function getActivate($id){
        $file = \App\MeterFile::findOrFail($id);

        DB::table('meter_files')->update(['active'=>0]);
        DB::table('streets')->delete();
        DB::table('buildings')->delete();
        DB::table('apartments')->delete();
        DB::table('meters')->delete();
        DB::table('services')->delete();

        $streets = [];
        $buildings = [];
        $apartments = [];
        $meters = [];

        try
        {
            $xml = simplexml_load_string(Storage::get($file->file));
            
            foreach ($xml->data->street as $xml_street){
                $street = [
                    'id'=>(string)$xml_street['id'],
                    'name'=>(string)$xml_street['name'],
                    'prefix'=>(string)$xml_street['prefix'],
                ];
                array_push($streets, $street);

                foreach ($xml_street->building as $xml_building){
                    $building = [
                        'id'=>(string)$xml_building['id'],
                        'street_id'=>(string)$xml_street['id'],
                        'number'=>(string)$xml_building['number'],
                        'housing'=>(string)$xml_building['housing'],
                    ];
                    array_push($buildings, $building);

                    foreach($xml_building->apartment as $xml_apartment){
                        $apartment = [
                            'id'=>(string)$xml_apartment['id'],
                            'building_id'=>(string)$xml_building['id'],
                            'number'=>(string)$xml_apartment['number'],
                            'part'=>(string)$xml_apartment['lit'],
                            'people'=>(string)$xml_apartment['people'],
                            'ls'=>(string)$xml_apartment['ls'],
                            'space'=>str_replace(',', '.', (string)$xml_apartment['space']),
                        ];
                        array_push($apartments, $apartment);

                        foreach($xml_apartment->meter as $xml_meter){
                            $meter = [
                                'id'=>(string)$xml_meter['id'],
                                'meter_id'=>(string)$xml_meter['mid'],
                                'apartment_id'=>(string)$xml_apartment['id'],
                                'service_id'=>(string)$xml_meter['service'],
                                'status_id'=>(string)$xml_meter['status'],
                                'last_date'=>Carbon::parse((string)$xml_meter['last_date']),
                                'last_value'=>(string)$xml_meter['last_value'],
                            ];
                            array_push($meters, $meter);
                        }
                    }
                }
            }


            DB::table('streets')->insert($streets);
            DB::table('buildings')->insert($buildings);
            foreach (array_chunk($apartments, 100) as $part)
                DB::table('apartments')->insert($part);
            foreach (array_chunk($meters, 100) as $part)
                DB::table('meters')->insert($part);            
            
            $services = [];

            foreach ($xml->services->service as $xml_service){
                $service = [
                    'id'=>$xml_service['id'],
                    'name'=>$xml_service['name'],
                    'norm'=>$xml_service['norm'],
                    'additional'=>$xml_service['additional'],
                ];
                array_push($services, $service);
            }
            DB::table('services')->insert($services);

        }catch (\Exception $e){
            DB::table('meter_files')->update(['active'=>0]);
            DB::table('streets')->delete();
            DB::table('buildings')->delete();
            DB::table('apartments')->delete();
            DB::table('meters')->delete();
            DB::table('services')->delete();
            return redirect('admin/database')->withErrors($e->getMessage());
        }

        $file->active=true;
        $file->save();
        
        
        

        return redirect('admin/database');
    }

    public function getDownloadCsv()
    {
        $file = \App\MeterFile::where('active',1)->first();
        if (!$file)
            return back()->withErrors('Необходимо активировать базу.');

        $values = \App\MeterValue::where('file_id',$file->id)->get();

        if (count($values)==0)
            return back()->withErrors('Список показаний пуст.');
        $mv = [];        
        foreach($values as $val)
            $mv[$val->meter_id] = ['value'=>$val->value,'date'=>$val->date];

        $meters = \App\Meter::where('status_id',1)->whereIn('id', array_keys($mv))->with('apartment.building.street', 'service')->get();

        $response = "prefix;street;number;housing;unit;part;service_name;ls;meter_id;date;value\r\n";

        foreach($meters as $item){
            $line = $item->apartment->building->street->prefix.';';
            $line.= $item->apartment->building->street->name.';';
            $line.= $item->apartment->building->number.';';
            $line.= $item->apartment->building->housing.';';
            $line.= $item->apartment->number.';';
            $line.= $item->apartment->part.';';
            $line.= $item->service->name.';';
            $line.= $item->apartment->ls.';';
            $line.= $item->meter_id.';';
            $line.= with(new \Carbon\Carbon($mv[$item->id]['date']))->format('d.m.Y').';';
            $line.= $mv[$item->id]['value']."\r\n";

            $response.=$line;
        }

        $response = iconv('utf-8','cp1251', $response);

        return response($response)->withHeaders([
                'Content-type'=>'text/html; charset=utf-8',
                'Content-Disposition'=>'attachment; filename='.$file->name.'.csv',
                'Expires'=>'0'
            ]);
    }

}
