<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrSubjectTeacher extends Model
{
    function subject(){
        return CtrSubject::where('subjectcode',$this->subjcode)->where('level',$this->level)->first()->subjectname;
    }
}
