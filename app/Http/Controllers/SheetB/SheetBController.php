<?php

namespace App\Http\Controllers\SheetB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper as mainHelper;
use Illuminate\Support\Facades\Input;
use Excel;

class SheetBController extends Controller
{
    
    function __construct() {
        $this->middleware(['auth']);
    }
    function index(){
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        $levels = mainHelper::getLevels();
        
        return view('sheetB.index',compact('levels','currSy'));
    }
    
    function download($level,$strand,$semester,$quarter){
        $sy = \App\RegistrarSchoolyear::first()->schoolyear;
        
        $sections = mainHelper::levelSections($sy, $level, "null");
        
        $name = $level."_".$sy."_".$semester."_".$quarter;
        Excel::create($name, function($excel) use($name,$sections,$level,$sy,$semester,$quarter,$strand) {
            foreach($sections as $section){
                $thissection = $section->section;
                $excel->sheet($section->section, function($sheet) use($thissection,$level,$sy,$semester,$quarter,$strand){
                        $sheet->loadView('sheetB.download')->with('section',$thissection)
                                ->with('level',$level)
                                ->with('sy',$sy)
                                ->with('semester',$semester)
                                ->with('quarter',$quarter)
                                ->with('strand',$strand);   
                        
                        $sheet->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  12,
                            'bold'      =>  true
                        )
                    ));
                });
            }
        })->export('xls');
        
        return "Export Complete";
    }
    
    function printSheetBList($sy,$level,$strand,$section,$semester,$quarter){
        
        $gradeQuarter = RegistrarHelper::setQuarter($semester, $quarter);
        $acad_field = RankHelper::rankingField($semester,$quarter,'acad_');
        $tech_field = RankHelper::rankingField($semester,$quarter,'tech_');
        $attendanceQtr = RegistrarHelper::setAttendanceQuarter($semester, $quarter);
        $gradeField = RegistrarHelper::getGradeQuarter($gradeQuarter);
        
        $students = RegistrarHelper::getSectionList($sy,$level,$strand,$section);
        $subjects = RegistrarHelper::getLevelSubjects($level,$strand,$sy,$semester);
        
        return view('registrar.sheetB.printsheetb',compact('students','level','section','semester','subjects','sy','quarter','strand','attendanceQtr','gradeField','acad_field','tech_field'));
    }
    
    static function gradeSheetBList(){
        $sy = \App\RegistrarSchoolyear::first()->schoolyear;
        $level = Input::get('level');
        $semester = Input::get('semester');
        $quarter = Input::get('quarter');
        $section = Input::get('section');
        $strand = Input::get('course');
        
        $gradeQuarter = mainHelper::setQuarter($semester, $quarter);
        $acad_field = mainHelper::rankingField($semester,$quarter,'acad_');
        $tech_field = mainHelper::rankingField($semester,$quarter,'tech_');
        $attendanceQtr = mainHelper::setAttendanceQuarter($semester, $quarter);
        $gradeField = mainHelper::getGradeQuarter($gradeQuarter);
        
        $students = mainHelper::getSectionList($sy,$level,$strand,$section);
        $subjects = mainHelper::getLevelSubjects($level,$strand,$sy,$semester);
        return view('ajax.sheetBTable',compact('students','level','section','semester','subjects','sy','quarter','strand','attendanceQtr','gradeField','acad_field','tech_field'));
    }
}