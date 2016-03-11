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
use App\MeterUploads;
use File;

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
        return view('admin.database');
    }

    public function postDatabaseUpload(Request $request){
        $validator = Validator::make($request->all(),['meters_file'=>'required|mimes:zip']);
        if ($validator->fails())       
        {
            return response('Ошибка загрузки файла',400);
        }

        $f = $request->file('meters_file');
        $file = new MeterUploads();
        $file->name=$f->getFilename();
        $file->uploaded_at = Carbon::now();
        $file->file="meters_uploads/".uniqid();
        $file->user_id = Auth::user()->id;
        $file->save();

        Storage::put($file->file, File::get($f));
    }
}
