<?php

namespace App\Http\Controllers\APSA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConductController extends Controller
{   
    public function index($level,$section){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        
        $students = \App\Status::where('level',$level)->where('section',$section)->where('status',2)->orderBy('class_no')->get();
        $conducts = DB::Select("Select subjectname,subjectcode,points from grades where subjecttype = 3 AND level='$level' AND schoolyear = $sy GROUP BY subjectcode ORDER BY sortto");
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->first();
        if($hasconduct){
            if($hasconduct->status == 0){
                return view('teacher.conduct.editconduct',compact('students','conducts','level','section','quarter'));
            }else{
                return view('teacher.conduct.viewconduct',compact('students','conducts','level','section','quarter'));
            }
        }else{
            return view('teacher.conduct.editconduct',compact('students','conducts','level','section','quarter'));
        }
    }
    
    function revertconduct($level,$section){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->where('status',1)->first();

        if(count($hasconduct)>0){
            $hasconduct->in_registrar = date("Y-m-d");
            $hasconduct->status = 0;
            $hasconduct->save();


        \App\Log::create(['user'=>Auth::user()->idno,'action'=>$level.'-'.$section.' conduct for quarter '.$quarter.' was returned to adviser']);

        }
        
        return redirect(url('classconduct',array($level,$section)));
    }
    
    function submitconduct($level,$section){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->where('status',1)->first();

        if(count($hasconduct)>0){
            $hasconduct->in_registrar = date("Y-m-d");
            $hasconduct->status = 2;
            $hasconduct->save();


        \App\Log::create(['user'=>Auth::user()->idno,'action'=>$level.'-'.$section.' conduct for quarter '.$quarter.' was submitted to Registrar']);

        }
        
        return redirect(url('classconduct',array($level,$section)));
    }
    
    
}