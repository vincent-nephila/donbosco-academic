<?php

namespace App\Http\Controllers\SheetA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helper as mainHelper;

class Helper extends Controller
{
    //Elective
    function electivesection($action = null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $sections = \App\CtrElectiveSection::where('level',$level)->where('schoolyear',$sy)->get();
        return view('ajax.electivesectionsheetA',compact('sections','action'));        
    }
    
    function sheetAelectivelist(){
        $section = Input::get('section');
        $sem = \App\CtrElectiveSection::find($section)->sem;
        $students = $this->electivestudentlist($section);
        return view('ajax.electiveSheetA',compact('section','students','sem'));
    }
    
    static function electivestudentlist($sectionid){
        $students = DB::Select("Select distinct s.idno from grades g "
                . "join statuses s on g.idno = s.idno "
                . "join ctr_sections cs on s.level = cs.level "
                . "and s.section = cs.section "
                . "and s.schoolyear = cs.schoolyear "
                . "where g.section = $sectionid "
                . "order by cs.sortto ASC,class_no ASC");
        
        return $students;
    }
    
    function getlevelstrands($action=null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $strands = DB::Select("select distinct strand from ctr_sections where level = '$level' ");
        return view('ajax.selectstrand',compact('strands','action'));
    }
    
    function getlevelsubjects($access,$action=null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $allavailable = 1;
        $department = \App\CtrLevel::where('level',$level)->first()->department;
        $subjects = mainHelper::getSubjects($department, $level, $sy, $course,$access);
        $quarter = \App\CtrQuarter::first();
        $semester = 0;
        if(in_array($level,array('Grade 11','Grade 12'))){
            if(in_array($quarter->qtrperiod,array(1,2))){
                $semester = 1;
            }else{
                $semester = 2;
            }
        }
        
        return view('ajax.selectsubjects',compact('subjects','action','allavailable','semester'));
        //return $subjects;
    }
    
    function getlevelsections($allavailable,$action=null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        
        $sections = mainHelper::levelSections($sy, $level, $course);
        return view('ajax.selectsection',compact('sections','action','allavailable'));
               
    }
    
    function gradeSheetAList(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $semester = Input::get('semester');
        $subject = Input::get('subject');
        $quarter = Input::get('quarter');
        $section = Input::get('section');

        $students = mainHelper::getSectionList($sy,$level,$course,$section);
        if($subject == 2){
            $quarter = self::setAttendanceQuarter($semester,$quarter);
            return view('ajax.sheetAAttendance',compact('students','semester','quarter','sy','level','quarter'));
        }else{
            return view('ajax.sheetAGrade',compact('students','semester','subject','sy'));
        }
    }
}
