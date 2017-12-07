<?php
use App\Http\Controllers\GradeComputation;
use App\Http\Controllers\AttendanceController as Attendance;
use App\Http\Controllers\SectionRanking;



$acad = 0;
$tech = 0;
?>
<table class="table table-bordered" style="font-size: 9pt;">
    <tr style="text-align: center">
        <td>CN</td>
        <td>Student Name</td>
        @if(count($subjects) > 0)
        
            @foreach($subjects as $subject)
                @if(in_array($subject->subjecttype,array(0,5,6)))
                <?php $acad++;?>
                <td>{{$subject->subjectcode}}</td>
                @endif
            @endforeach
            @if($acad > 0)
            <td>ACAD GEN AVE</td>
            <td>ACAD RANK</td>
            @endif
            
            @foreach($subjects as $subject)
                @if(in_array($subject->subjecttype,array(1)))
                <?php $tech++;?>
                <td>{{strtoupper($subject->subjectcode)}}</td>
                @endif
            @endforeach
            
            @if($tech > 0)
            <td>TECH GEN AVE</td>
            <td>TECH RANK</td>
            @endif
            
            <td>GMRC</td>
            <td>DAYP</td>
            <td>DAYT</td>
            <td>DAYA</td>
            
        @endif
    </tr>
    @foreach($students as $student)
    <?php 
            $grades = \App\Grade::where('idno',$student->idno)->where('schoolyear',$sy)->get();
            $attendance = Attendance::studentQuarterAttendance($student->idno,$sy,$attendanceQtr,$level);
            $name = App\User::where('idno',$student->idno)->first();
            ?>
    <tr style="text-align: center">
        <td>{{$student->class_no}}</td>
        <td style="text-align: left">{{$name->lastname}}, {{$name->firstname}} @if($name->middlename != ""){{substr($name->middlename,0,1)}}. @endif 
            @if($student->status ==3)
                <span style='float: right;color: red;font-weight: bold'>DROPPED</span>
            @endif
        </td>
 
        @foreach($subjects as $subject)
            @if(in_array($subject->subjecttype,array(0,5,6)))
                @foreach($grades as $grade)
                    @if($grade->subjectcode == $subject->subjectcode)
                    <td>
                        @if($grade->$gradeField > 0)
                        {{round($grade->$gradeField,0)}}
                        @endif
                    </td>
                    @endif
                @endforeach
            @endif
        @endforeach
        
            @if($acad > 0)
            <td>{{GradeComputation::computeQuarterAverage($sy,$level,array(0,5,6),$semester,$quarter,$grades)}}</td>
            <td>{{SectionRanking::getStudentRank($student->idno,$sy,$acad_field)}}</td>
            @endif
            
        @foreach($subjects as $subject)
            @if(in_array($subject->subjecttype,array(1)))
                @foreach($grades as $grade)
                    @if($grade->subjectcode == $subject->subjectcode)
                    <td>
                        @if($grade->$gradeField > 0)
                        {{round($grade->$gradeField,0)}}
                        @endif
                    </td>
                    @endif
                @endforeach
            @endif
        @endforeach
        
            @if($tech > 0)
            <td>{{GradeComputation::computeQuarterAverage($sy,$level,array(1),$semester,$quarter,$grades)}}</td>
            <td>{{SectionRanking::getStudentRank($student->idno,$sy,$tech_field)}}</td>
            @endif
            
            <td>{{GradeComputation::computeQuarterAverage($sy,$level,array(3),$semester,$quarter,$grades)}}</td>
            <td style="text-align: center">{{$attendance[0]}}</td>
            <td style="text-align: center">{{$attendance[2]}}</td>
            <td style="text-align: center">{{$attendance[1]}}</td>
    </tr>
    @endforeach
</table>