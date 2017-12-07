<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class HRConduct extends Controller
{
    public function __construct(){
		$this->middleware('auth');
    }
    
    public function index($level,$section){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $students = \App\Status::where('level',$level)->where('section',$section)->where('status',2)->orderBy('class_no')->get();
        $conducts = DB::Select("Select subjectname,subjectcode,points from grades where subjecttype = 3 AND level='$level' AND schoolyear = $sy GROUP BY subjectcode ORDER BY sortto");
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->first();
        if(count($hasconduct)>0){
            if($hasconduct->status == 0){
                return view('teacher.conduct.editconduct',compact('students','conducts','level','section','quarter'));
            }else{
                return view('teacher.conduct.viewconduct',compact('students','conducts','level','section','quarter'));
            }
        }else{
            return view('teacher.conduct.editconduct',compact('students','conducts','level','section','quarter'));
        }
    }
    
    public function import($level,$section){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        return view('teacher.conduct.import',compact('level','section','quarter'));
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
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        
        $isteacher = \App\CtrSection::where('level',$level)->where('section',$section)->where('schoolyear',$sy)->where('adviserid',\Auth::user()->idno)->exists();
        $conducts = Input::get('conduct');
        if($isteacher ){
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
                        default:

                            break;
                    }
                    $conduct->save();
                }
            }
            //return $conducts;
            return redirect('classconduct/'.$level.'/'.$section);
        }else{
            return "You are not allowed to access this section";
        }
    }
    
    function submitconduct($level,$section){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $hasconduct = \App\GradesStatus::where('level',$level)->where('section',$section)->where('gradetype',3)->where('schoolyear',$sy)->where('quarter',$quarter)->first();
        
        if(count($hasconduct)>0){
            $hasconduct->in_apsa = date("Y-m-d");
            $hasconduct->status = 1;
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
        
        \App\Log::create(['user'=>Auth::user()->idno,'action'=>$level.'-'.$section.' conduct for quarter '.$quarter.' was submitted to APSA']);
        
        return redirect(url('classconduct',array($level,$section)));
    }
}
