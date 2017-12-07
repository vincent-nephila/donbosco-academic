<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper as MainHelper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if(\Auth::user()->accesslevel == env("USER_TEACHER")){
            return $this->teacher_view();
        }elseif(\Auth::user()->accesslevel == env("USER_ELEM_ACAD") || \Auth::user()->accesslevel == env("USER_HS_ACAD") || \Auth::user()->accesslevel == env("USER_SHS_ACAD")){
            return $this->acad_view();
        }elseif(\Auth::user()->accesslevel == env("USER_ELEM_APSA") || \Auth::user()->accesslevel == env("USER_JHS_APSA") || \Auth::user()->accesslevel == env("USER_SHS_APSA")){
            return $this->apsa_view();
        }elseif(\Auth::user()->accesslevel == env("USER_TECH")){
            return redirect('importgrade');
            
        }else{
            //return \Auth::user()->accesslevel;
            session()->put('message',true);
            
            return redirect('logout');
            //return session('message');
        }
    }
    
    function teacher_view(){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        //$subjects = \App\CtrSubjectTeacher::where('teacherid',\Auth::user()->id)->where('schoolyear',$sy)->get();
        $homeroom = \App\CtrSection::where('schoolyear',$sy)->where('adviserid',\Auth::user()->id)->first();
        
        return view('teacher.index');
    }
    
    function acad_view(){
        $sy = \App\CtrSchoolYear::first()->schoolyear; 
        return view('head.index');
    }
    function apsa_view(){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $levels = MainHelper::getLevels();
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        return view('head.apsa_index',compact('sy','levels','quarter'));
    }
}
