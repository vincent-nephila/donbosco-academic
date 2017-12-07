<?php
use App\Http\Controllers\GradeComputation;
use App\Http\Controllers\AttendanceController as Attendance;
use App\Http\Controllers\SectionRanking;
use App\Http\Controllers\Helper as RegistrarHelper;

$acad = 0;
$tech = 0;
?>
<html>
    <head>
        <link href="{{ asset('/css/fonts.css') }}" rel="stylesheet">
    </head>
    <body>
        <table width="100%" class="print-header">
    <tr>
        <td rowspan="3" style="text-align: right;padding-left: 0px;vertical-align: top" class="logo" width="55px">
            <img src="{{asset('images/logo.png')}}"  style="display: inline-block;height:50px">
        </td>
        <td style="padding-left: 0px;">
            <span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute</span>
        </td>
        <td style="text-align: center;font-size:12pt; font-weight: bold">
            GENERATED SHEET B
        </td>
        <td style="text-align: right;font-size:12pt;">
            DAYS OF SCHOOL: <span id="dos">{{RegistrarHelper::quarterattendance($sy,$level,$quarter)}} Days</span>
        </td>

    </tr>
    <tr>
        <td style="font-size:10pt;padding-left: 0px;">Chino Roces Ave., Makati City </td>
        <td style="text-align:center;font-weight: bold;">
            <b id="sy">SCHOOL YEAR {{$sy}} - {{intval($sy)+1}}</b>
        </td>    
        <td style="text-align: right;font-size:12pt;">ADVISER:<span id="adviser">{{RegistrarHelper::getAdviser($sy,$level,$section,2)}}</span></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
                <td style="text-align: center;font-size:12pt; font-weight: bold"><span id="qtr">
            @if($quarter == 1)
            FIRST
            @elseif($quarter == 2)
            SECOND
            @elseif($quarter == 3)
            THIRD
            @elseif($quarter == 4)
            FOURTH
            @elseif($quarter == 5)
            FINAL
            @endif
            </span> GRADING PERIOD</td>
        <td style="text-align: right;font-size:12pt;">Gr and Sec:<span id="year">{{$level}} / {{$section}}</span></td>
    </tr>
    @if(in_array($level,array('Grade 9','Grade 10')))
    <tr>
        <td style="font-size:10pt;padding-left: 0px;"></td>
        <td style="text-align:center;font-weight: bold;"></td>    
        <td style="text-align: right;font-size:12pt;">Strand: <span id="adviser">{{$strand}}</span></td>
    </tr>
    @endif
</table>

        <table width="100%" border="1" cellspacing="0" style="font-size: 9pt;">
    <thead>
    <tr style="text-align: center">
        <td>CN</td>
        <td width='300px;'>Student Name</td>
        @if(count($subjects) > 0)
        
            @foreach($subjects as $subject)
                @if(in_array($subject->subjecttype,array(0,5,6)))
                <?php $acad++;?>
                <td>{{$subject->subjectcode}}</td>
                @endif
            @endforeach
            @if($acad > 0)
            <td>ACAD<br> GEN AVE</td>
            <td>ACAD<br> RANK</td>
            @endif
            
            @foreach($subjects as $subject)
                @if(in_array($subject->subjecttype,array(1)))
                <?php $tech++;?>
                <td>{{strtoupper($subject->subjectcode)}}</td>
                @endif
            @endforeach
            
            @if($tech > 0)
            <td>TECH<br> GEN AVE</td>
            <td>TECH<br> RANK</td>
            @endif
            
            <td>GMRC</td>
            <td>DAYP</td>
            <td>DAYT</td>
            <td>DAYA</td>
            
        @endif
    </tr>
    </thead>
    @foreach($students as $student)
    <?php 
            $grades = \App\Grade::where('idno',$student->idno)->where('schoolyear',$sy)->get();
            $attendance = Attendance::studentQuarterAttendance($student->idno,$sy,$attendanceQtr,$level);
            $name = App\User::where('idno',$student->idno)->first();
            ?>
    <tr style="text-align: center">
        <td width='50px'>{{$student->class_no}}</td>
        <td style="text-align: left;font-size: 8pt;">{{$name->lastname}}, {{$name->firstname}} @if($name->middlename != ""){{substr($name->middlename,0,1)}}. @endif 
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
            <td style="font-weight: bold">{{GradeComputation::computeQuarterAverage($sy,$level,array(0,5,6),$semester,$quarter,$grades)}}</td>
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
            <td style="font-weight: bold">{{GradeComputation::computeQuarterAverage($sy,$level,array(1),$semester,$quarter,$grades)}}</td>
            <td>{{SectionRanking::getStudentRank($student->idno,$sy,$tech_field)}}</td>
            @endif
            
            <td>{{GradeComputation::computeQuarterAverage($sy,$level,array(3),$semester,$quarter,$grades)}}</td>
            <td style="text-align: center">{{$attendance[0]}}</td>
            <td style="text-align: center">{{$attendance[2]}}</td>
            <td style="text-align: center">{{$attendance[1]}}</td>
    </tr>
    @endforeach
</table>  
    </body>
</html>
