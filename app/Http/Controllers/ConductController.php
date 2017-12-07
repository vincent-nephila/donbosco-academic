<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ConductController extends Controller
{
    public function __construct(){
		$this->middleware('auth');
    }
    
    public function index($level,$section){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $students = \App\Status::where('level',$level)->where('section',$section)->where('status',2)->orderBy('class_no')->get();
        $conducts = DB::Select("Select subjectname,subjectcode,points from grades where subjecttype = 3 AND level='$level' AND schoolyear = $sy GROUP BY subjectcode ORDER BY sortto");

        $allow = $this->allowAccess($level,$section);
        if($allow){
            return view('conduct.editconduct',compact('students','conducts','level','section','quarter'));
        }else{
            return view('conduct.viewconduct',compact('students','conducts','level','section','quarter'));
        }

    }
    
    public function import($level,$section){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        return view('conduct.import',compact('level','section','quarter'));
    }
    
    function viewimport($level,$section,Request $request){
        if(Input::hasFile('import_file')){
            $row=9;
            $sy = \App\CtrSchoolYear::first()->schoolyear;
            $students = \App\Status::where('level',$level)->where('section',$section)->where('status',2)->where('schoolyear',$sy)->orderBy('class_no')->get();
            
            $path = Input::file('import_file')->getRealPath();
             Excel::selectSheets('1stQTR')->load($path, function($reader) use ($row,$students){
                $uploaded = array();
                do{
                    $class_no = $reader->getActiveSheet()->getCell('A'.$row)->getOldCalculatedValue();
                    $grade = $reader->getActiveSheet()->getCell('F'.$row)->getOldCalculatedValue();
                    $uploaded[] = array('idno'=>$idno,'grade'=>$grade);
                    $row++;
                }while(strlen($reader->getActiveSheet()->getCell('B'.$row)->getOldCalculatedValue())>5);
                
                session()->flash('grades', $uploaded);
                
            });
            $grades = session('grades');
                return view('vincent.academic.uploadgrade',compact('grades','request'));
        }
   
    }
    
    function saveconduct($level,$section){
        $conducts = Input::get('conduct');
        $allow = $this->allowAccess($level,$section);
        if($allow){

            $this->updateconduct($conducts);
            return redirect('classconduct/'.$level.'/'.$section);
        }else{
            return "You are not allowed to access this section";
        }
    }
    
    function updateconduct($conducts){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        foreach($conducts as $student=>$value){
            foreach($value as $conduct=>$grade){
                $conduct = \App\Grade::where('idno',$student)->where('subjectcode',$conduct)->where('schoolyear',$sy)->first();
                switch($quarter){
                    case 1:
                        $conduct->first_grading = $grade['grade'];
                        $conduct->remarks1 = $grade['remarks'];
                        break;
                    case 2:
                        $conduct->second_grading = $grade['grade'];
                        $conduct->remarks2 = $grade['remarks'];
                        break;
                    case 3:
                        $conduct->third_grading = $grade['grade'];
                        $conduct->remarks3 = $grade['remarks'];
                        break;
                    case 4:
                        $conduct->fourth_grading = $grade['grade'];
                        $conduct->remarks4 = $grade['remarks'];
                        break;
                }
                $conduct->save();
            }
        }
    }
    function reopenconduct($level,$section){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->first();    
    
        if(count($hasconduct)>0){
                $hasconduct->status = 0;
                $hasconduct->in_apsa = "0000-00-00";
                $hasconduct->save();
        }
        
        return redirect(url('classconduct',array($level,$section)));
    }
    function submitconduct($level,$section){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->first();
        
        if(count($hasconduct)>0){
            $hasconduct->in_apsa = date("Y-m-d");
            if(in_array(\Auth::user()->accesslevel,array(env('USER_ELEM_APSA'),env('USER_JHS_APSA'),env('USER_SHS_APSA')))){
                $hasconduct->status = 2;
                $hasconduct->in_registrar = date("Y-m-d");
            }elseif(\Auth::user()->accesslevel == env('USER_TEACHER')){
                $hasconduct->status = 1;
                $hasconduct->in_apsa = date("Y-m-d");
            }else{
                $hasconduct->status = 0;
            }
            
            $hasconduct->save();
            
        }else{
            $newconduct = new \App\GradesStatus();
            $newconduct->level = $level;
            $newconduct->section = $section;
            $newconduct->quarter = $quarter;
            $newconduct->gradetype = 3;
            $newconduct->schoolyear = $sy;
            $newconduct->status = 1;
            $newconduct->in_apsa = date("Y-m-d");
            $newconduct->save();
        }
        
        $this->createLog($level,$section,$quarter);

        return redirect(url('classconduct',array($level,$section)));
    }
    
    function allowAccess($level,$section){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->first();
        $isteacher = \App\CtrSection::where('level',$level)->where('section',$section)->where('schoolyear',$sy)->where('adviserid',\Auth::user()->idno)->exists();
        
        
        if(count($hasconduct)>0){
            if($isteacher && $hasconduct->status == 0){
                return true;
            }elseif(in_array(\Auth::user()->accesslevel,array(env('USER_ELEM_APSA'),env('USER_JHS_APSA'),env('USER_SHS_APSA'))) && $hasconduct->status == 1){
                return true;
            }else{
                return false;
            }            
        }else{
            if($isteacher){
                return true;
            }else{
                return false;
            }
        }

    }
    
    function createLog($level,$section,$quarter){
        if(in_array(\Auth::user()->accesslevel,array(env('USER_ELEM_APSA'),env('USER_JHS_APSA'),env('USER_SHS_APSA')))){
            \App\Log::create(['user'=>\Auth::user()->idno,'message'=>$level.'-'.$section.' conduct for quarter '.$quarter.' was submitted to registrar','action'=>'log']);
        }elseif(\Auth::user()->accesslevel == env('USER_TEACHER')){
            \App\Log::create(['user'=>\Auth::user()->idno,'message'=>$level.'-'.$section.' conduct for quarter '.$quarter.' was submitted to APSA','action'=>'log']);
        }else{
            \App\Log::create(['user'=>\Auth::user()->idno,'message'=>'Conduct has been submitted for '.$level.'-'.$section.' for quarter '.$quarter.' by an invalid account','action'=>'alert']);
        }
    }
}
