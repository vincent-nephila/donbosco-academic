<?php

namespace App\Http\Controllers\SheetB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Helper as mainHelper;

class Helper extends Controller
{
    function getSectionForm(){
        $sy = \App\RegistrarSchoolyear::first()->schoolyear;
        $level = Input::get('level');
        $sections = mainHelper::levelSections($sy, $level,"null");
        
        return view('ajax.sheetBDlForn',compact('sections'));
    }
}
