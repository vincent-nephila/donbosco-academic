<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Input;

class Helper extends Controller
{
    static function getSectionList($sy,$level,$course,$section){
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        if($currSY == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        if($course != "null"){
            $course = "and strand='".$course."'";
        }else{
            $course = "";
        }
        $students = DB::Select("Select * from  $table "
                . "where level = '$level' $course "
                . "AND section = '$section' "
                . "AND status IN(2,3) "
                . "ORDER BY class_no");
        
        return $students;
    }
    
    static function getLevels(){
        if(\Auth::user()->accesslevel == env('USER_ELEM_APSA') || \Auth::user()->accesslevel == env('USER_ELEM_ACAD')){
            $levels = \App\CtrLevel::whereIn('department',array('Elementary','Kindergarten'))->get();
        }elseif(\Auth::user()->accesslevel == env('USER_JHS_APSA') || \Auth::user()->accesslevel == env('USER_HS_ACAD')){
            $levels = \App\CtrLevel::where('department','Junior High School')->get();
        }elseif(\Auth::user()->accesslevel == env('USER_SHS_APSA') || \Auth::user()->accesslevel == env('USER_SHS_ACAD')){
            $levels = \App\CtrLevel::where('department','Senior High School')->get();
        }elseif(\Auth::user()->accesslevel == env('USER_TECH')){
            $levels = \App\CtrLevel::whereIn('department',array('Junior High School','Senior High School'))->get();
        }else{
            $levels = $levels = \App\CtrLevel::where('department','')->get();
        }
        return $levels;
    }
    
    static function getSubjects($department,$level,$sy,$course,$access){
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        if($currSY == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        if(($access == env('USER_ELEM_ACAD') || $access == env('USER_ELEM_ACAD')) && in_array($department,array('Kindergarten','Elementary'))){
            $subjects = DB::Select("Select distinct subjectcode,subjectname,semester from  grades g join $table s on g.idno = s.idno and g.schoolyear = s.schoolyear where s.level = '$level' and s.schoolyear = $sy and subjecttype IN(0) and isdisplaycard = 1 order by subjecttype,sortto");
        }elseif(($access == env('USER_HS_APSA') || $access == env('USER_HS_ACAD')) && in_array($department,array('Junior High School'))){
            $subjects = DB::Select("Select distinct subjectcode,subjectname,semester from  grades g join $table s on g.idno = s.idno and g.schoolyear = s.schoolyear where s.level = '$level' and s.schoolyear = $sy and subjecttype IN(0) and isdisplaycard = 1 order by subjecttype,sortto");
        }elseif($access == env('USER_SHS_APSA') || $access == env('USER_SHS_ACAD')){
            $subjects = DB::Select("Select distinct subjectcode,subjectname,semester from  grades g join $table s on g.idno = s.idno and g.schoolyear = s.schoolyear where s.level = '$level' and s.schoolyear = $sy and s.strand = '$course' and subjectcode NOT LIKE 'ELE%' and isdisplaycard = 1 order by subjecttype,sortto");
        }elseif($access == env('USER_TECH')){
            $subjects = DB::Select("Select distinct subjectcode,subjectname,semester from  grades g join $table s on g.idno = s.idno and g.schoolyear = s.schoolyear where s.level = '$level' and s.schoolyear = $sy and subjecttype IN(1) and isdisplaycard = 1 order by subjecttype,sortto");
	    if(count($subjects) ==0){
	         $subjects = DB::Select("Select distinct subjectcode,subjectname,semester from  grades g join $table s on g.idno = s.idno and g.schoolyear = s.schoolyear where s.level = '$level' and s.schoolyear = $sy and subjectcode LIKE 'ELE%' and s.strand = '$course' and isdisplaycard = 1 and semester= 1 and s.status = 2 order by subjecttype,sortto");
	    }

        }else{
            $subjects = \App\CtrLevel::where('department','')->get();
        }
        
        return $subjects;
    }
    
    static function levelSections($sy,$level,$course){
        if($course != "null"){
            $course = "and strand='".$course."'";
        }else{
            $course = "";
        }
        
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        if($currSY == $sy){
            $table = 'ctr_sections';
        }
        else{
            $table = 'ctr_sections_temp';
        }
        
        $sections = DB::Select("Select distinct section from  $table where level = '$level' and schoolyear = $sy $course");
        
        return $sections;
    }
    
    static function getQuarter($action = null){
        $level = Input::get('level');
        
        return view('ajax.selectquarter',compact('level','action'));
    }
    
    static function setQuarter($semester,$quarter){
        $qtr = $quarter;
        switch($semester){
            case 2;
                if($quarter == 1){
                    $qtr = 3;
                }
                if($quarter == 2){
                    $qtr = 4;
                }
            break;
        }
        
        return $qtr;
    }
    
    static function setAttendanceQuarter($semester,$quarter){
        $qtr = array($quarter);
        switch($semester){
            case 0;
                if($quarter == 5){
                    $qtr = array(1,2,3,4);
                }
            break;
            case 1;
                if($quarter == 5){
                    $qtr = array(1,2);
                }
            break;
            case 2;
                if($quarter == 1){
                    $qtr = array(3);
                }
                if($quarter == 2){
                    $qtr = array(4);
                }
                if($quarter == 5){
                    $qtr = array(3,4);
                }
            break;
        }
        
        return $qtr;
    }
    
    static function getGradeQuarter($quarter){
        switch ($quarter){
            case 1; 
                $qrt = "first_grading";
            break;
            case 2;
                $qrt = "second_grading";
            break;                
           case 3;
                $qrt = "third_grading";
            break;
            case 4;
                $qrt = "fourth_grading";
           break; 
            case 5;
                $qrt = "final_grade";
           break;
            default:
                $qrt = "period";
                break;
        }
        return $qrt;
    }
    
    static function rankingField($semester,$quarter,$subject){
        if($quarter == 5){
            if($semester == 0){
                $rankfield = $subject."final1";
            }else{
                $rankfield = $subject."final".$semester;
            }
        }else{
            $rankfield = $subject."".$quarter;
        }
        
        return $rankfield;
    }
    
    static function getLevelSubjects($level,$strand,$sy,$semester){
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        if($strand != "null"){
            $strand = "and s.strand='".$strand."'";
        }else{
            $strand = "";
        }
        
        if($currSy == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        $subjects = DB::Select("Select subjectcode,subjectname,subjecttype from grades g join $table s "
                . "on g.idno = s.idno AND g.schoolyear = s.schoolyear "
                . "where s.level = '$level' $strand "
                . "and subjecttype IN(0,1,5,6) "
                . "and isdisplaycard = 1 "
                . "AND g.semester = $semester "
                . "and g.schoolyear = $sy "
                . "group by subjectcode "
                . "order by subjecttype,sortto");
        
        return $subjects;
    }
    
    static function quarterattendance($sy,$level,$quarter){
        $noOfDays = "";
        $attendance = \App\CtrAttendance::where('schoolyear',$sy)->where('level',$level)->where('quarter',$quarter)->first();
        
        if($attendance){
            $noOfDays = number_format($attendance->Jun+$attendance->Jul+$attendance->Aug+$attendance->Sept+$attendance->Oct+$attendance->Nov+$attendance->Dece+$attendance->Jan+$attendance->Feb+$attendance->Mar,1);
        }
        
        return $noOfDays;
    }

    static function getAdviser($sy,$level,$section,$subjectcode){
        $teacher = "";
        $name = array();
        $adviser = \App\CtrSubjectTeacher::where('schoolyear',$sy)->where('level',$level)->where('section',$section)->where('subjcode',$subjectcode)->first();
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        if(count($adviser)> 0){
            $name = \App\User::where('idno',$adviser->instructorid)->first();
        }
        if(in_array($subjectcode,array(3,2))){
            if($currSy == $sy){
                $adviser = \App\CtrSection::where('schoolyear',$sy)->where('section',$section)->where('level',$level)->first();
            }else{
                $adviser = DB::Select("select * from `ctr_sections_temp` where `schoolyear` = '$sy' and `section` = '$section' and `level` = '$level' limit 1");
            }
            
            $name = \App\User::where('idno',$adviser->adviserid)->first();
        }
        
        if(count($name)> 0){
            $teacher = ucwords(strtolower($name->title.". ".$name->firstname." ".substr($name->middlename,0,1).". ".$name->lastname));
        }
        
        return $teacher;
    }
    
    static function shortLevel($level){
        $short = "";
        switch($level){
            case 'Kindergarten':
                $short = "K";
                break;
            default:
                $short = str_replace("Grade ","",$level);
                break;
        }
        
        return $short;
    }
}
