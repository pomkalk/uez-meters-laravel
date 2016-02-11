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
    	return view('main');
    }
}
