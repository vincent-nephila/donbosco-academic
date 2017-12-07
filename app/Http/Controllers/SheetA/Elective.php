<?php

namespace App\Http\Controllers\SheetA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Elective extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrElectiveSection::groupBy('level')->get();
        
        return view('sheetA.elective',compact('selectedSY','currSY','levels'));
    }
}
