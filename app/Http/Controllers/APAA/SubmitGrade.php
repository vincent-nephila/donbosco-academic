<?php

namespace App\Http\Controllers\APAA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Helper as mainHelper;
use Excel;

class SubmitGrade extends Controller
{

    public function __construct(){
	$this->middleware('auth');
	}
    function index(){
        $levels = mainHelper::getLevels();

        return view('head.submit.grade',compact('levels'));
    }
    
    function importgrade(Request $request){
        if(Input::hasFile('import_file')){
            $row=6;
            $path = Input::file('import_file')->getRealPath();
             Excel::selectSheets('Registrar')->load($path, function($reader) use ($row){
                $uploaded = array();
                do{
                    $idno = $reader->getActiveSheet()->getCell('B'.$row)->getOldCalculatedValue();
                    
                    if(strlen($idno)<6){
                        $idno = "0".$idno;
                    }
                    $grade = $reader->getActiveSheet()->getCell('F'.$row)->getOldCalculatedValue();
                    $uploaded[] = array('idno'=>$idno,'grade'=>$grade);
                    $row++;
                }while(strlen($reader->getActiveSheet()->getCell('B'.$row)->getOldCalculatedValue())>4);
                
                session()->flash('grades', $uploaded);
                
            });
            $grades = session('grades');
            $levels = mainHelper::getLevels();
                return view('head.submit.grade',compact('grades','request','levels'));
        }
    }
    
    function saveentry(Request $request){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $grades = $request->input('student');
        foreach($grades as $key=>$value){
            if(preg_match('/^[0-9]*$/', $value)){
                $grade = \App\Grade::where('idno',$key)->where('subjectcode',$request->subj)->where('schoolyear',$sy)->first();
                if(count($grade)>0){
                    if($grade->second_grading == 0){
                        $grade->second_grading = $value;
                        $grade->save();                                            
                    }                    
                }


                $this->savetorepo($key,$request->subj,$value,$sy);
            }
        }
        return redirect('importgrade');
        //return "me";
    }
    
    function savetorepo($idno,$subjcode,$grade,$sy){
        $repo = new \App\SubjectRepo();
        $repo->idno = $idno;
        $repo->subjectcode = $subjcode;
        $repo->grade = $grade;
        $repo->qtrperiod = 2;
        $repo->schoolyear = $sy;
        $repo->save();
                
        
    }


}
