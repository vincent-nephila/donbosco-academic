<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function quarterSubjectGrade($quarter,$subjcode,$idno){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $grade = \App\Grade::where('idno',$idno)->where('subjectcode',$subjcode)->where('schoolyear',$schoolyear)->first();
        
        switch($quarter){
            case 1:
                $qtr_grade = $grade->first_grading;
                $remark = $grade->remarks1;
                break;
            case 2:
                $qtr_grade = $grade->second_grading;
                $remark = $grade->remarks2;
                break;
            case 3:
                $qtr_grade = $grade->third_grading;
                $remark = $grade->remarks3;
                break;
            case 4:
                $qtr_grade = $grade->fourth_grading;
                $remark = $grade->remarks4;
                break;
            default:
                $qtr_grade = $grade->final_grade;
                break;
        }
        
        return array(round($qtr_grade,0),$remark);
    }
    
    static function submissionLock($level){
        
    }
}
