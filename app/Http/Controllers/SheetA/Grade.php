<?php

namespace App\Http\Controllers\SheetA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper as mainHelper;

class Grade extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = mainHelper::getLevels();;
        
        return view('sheetA.grade',compact('selectedSY','currSY','levels'));
    }
}
