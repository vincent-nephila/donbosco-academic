<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SectionRanking extends Controller
{
    static function getStudentRank($idno,$schoolyear,$field){
        $position = "";
        $record = \App\Ranking::where('idno',$idno)->where('schoolyear',$schoolyear)->first();
        
        if($record){
            $position = $record->$field;
            
            if($position ==0){
                $position = "";
            }
        }
        
        return $position;
    }
}
