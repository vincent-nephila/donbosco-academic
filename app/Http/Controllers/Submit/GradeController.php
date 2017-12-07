<?php

namespace App\Http\Controllers\Submit;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    function index(){
        if(\Auth::user()->accesslevel == env('USER_ELEM_ACAD') || \Auth::user()->accesslevel == env('USER_ELEM_APSA')){
            $levels = \App\CtrLevel::whereIn('department',array('Elementary','Kindergarten'));
        }elseif(\Auth::user()->accesslevel == env('USER_HS_APSA') || \Auth::user()->accesslevel == env('USER_HS_APSA')){
            $levels = \App\CtrLevel::where('department','Junior High School');
        }elseif(\Auth::user()->accesslevel == env('USER_SHS_APSA') || \Auth::user()->accesslevel == env('USER_SHS_APSA')){
            $levels = \App\CtrLevel::where('department','Senior High School');
        }elseif(\Auth::user()->accesslevel == env('USER_TECH')){
            $levels = \App\CtrLevel::whereIn('department',array('Junior High School','Senior High School'));
        }
        return view('head.submit',compact('levels'));
    }
}
