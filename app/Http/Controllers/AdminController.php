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
        $uploads = \App\MeterFile::latest()->get();
        return view('admin.database', ['files'=>$uploads]);
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

        $file->delete();

        return redirect('admin/database');
    }

    public function getActivate($id){
        $file = \App\MeterFile::findOrFail($id);

        DB::table('meter_files')->update(['active'=>0]);
        DB::table('streets')->delete();
        DB::table('buildings')->delete();
        DB::table('apartments')->delete();
        DB::table('meters')->delete();

        $streets = [];
        $buildings = [];
        $apartments = [];
        $meters = [];

        try
        {
            $xml = simplexml_load_string(Storage::get($file->file));
            
            foreach ($xml->data->street as $xml_street){
                $street = [
                    'id'=>$xml_street['id'],
                    'name'=>$xml_street['name'],
                    'prefix'=>$xml_street['prefix'],
                ];
                array_push($streets, $street);

                foreach ($xml_street->building as $xml_building){
                    $building = [
                        'id'=>$xml_building['id'],
                        'street_id'=>$xml_street['id'],
                        'number'=>$xml_building['number'],
                        'housing'=>$xml_building['housing'],
                    ];
                    array_push($buildings, $building);

                    foreach($xml_building->apartment as $xml_apartment){
                        $apartment = [
                            'id'=>$xml_apartment['id'],
                            'building_id'=>$xml_building['id'],
                            'number'=>$xml_apartment['number'],
                            'part'=>$xml_apartment['lit'],
                            'people'=>$xml_apartment['people'],
                            'ls'=>$xml_apartment['ls'],
                        ];
                        array_push($apartments, $apartment);

                        foreach($xml_apartment->meter as $xml_meter){
                            $meter = [
                                'id'=>$xml_meter['id'],
                                'meter_id'=>$xml_meter['mid'],
                                'apartment_id'=>$xml_apartment['id'],
                                'service_id'=>$xml_meter['service'],
                                'status_id'=>$xml_meter['status'],
                                'last_date'=>Carbon::parse($xml_meter['last_date']),
                                'last_value'=>$xml_meter['last_value'],
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
            
        }catch (\Exception $e){
            DB::table('meter_files')->update(['active'=>0]);
            DB::table('streets')->delete();
            DB::table('buildings')->delete();
            DB::table('apartments')->delete();
            DB::table('meters')->delete();
            return redirect('admin/database')->withErrors($e->getMessage());
        }

        $file->active=true;
        $file->save();
        
        
        

        return redirect('admin/database');
    }

}
