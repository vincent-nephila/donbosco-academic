<?php

namespace App\Http\Controllers\APAA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Helper as MainHelper;

class Helper extends Controller
{
    function getsubmittersubjects($access){
        $level = Input::get('level');
        $strand= Input::get('strand');
        $quarter = \App\CtrQuarter::first();
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $department = \App\CtrLevel::where('level',$level)->first()->department;
        $data = "";
        $semester = 0;
        if(in_array($level,array('Grade 11','Grade 12'))){
            if(in_array($quarter->qtrperiod,array(1,2))){
                $semester = 1;
            }else{
                $semester = 2;
            }
        }
        $subjects = MainHelper::getSubjects($department, $level, $sy, $strand, $access);
        
        foreach($subjects as $subject){
            if($semester == $subject->semester){
                $data = $data."<option value='".$subject->subjectcode."'>".$subject->subjectname."</option>";
            }
            
        }
        return $data;
    }
}
