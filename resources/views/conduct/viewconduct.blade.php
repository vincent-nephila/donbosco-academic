@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\Teacher\Helper;?>
<div class="col-md-6">
<h3>{{$level}} - {{$section}}</h3>
<h4>Quarter:{{$quarter}}</h4>    
</div>
    <table class="table table-bordered" style="font-size: 10px" cellpadding="">
        <tr style="text-align: center">
            <td width="5%">CLASS NO</td>
            <td  width="10%">NAME</td>
            <?php $width = 86/(count($conducts)*2)?>
            @foreach($conducts as $conduct)
            <td width="{{$width}}%">{{strtoupper($conduct->subjectname)}}
                <br>
                <p style="text-align: center">({{$conduct->points}})</p>
            </td>
            <td width="{{$width}}%">Remarks</td>
            @endforeach
        </tr>
        @foreach($students as $student)
        <?php $name = \App\User::where('idno',$student->idno)->first(); ?>
        <tr>
            <td style="text-align: center">{{$student->class_no}}</td>
            <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
            @foreach($conducts as $conduct)
                <?php $currgrade = Helper::quarterSubjectGrade($quarter,$conduct->subjectcode,$student->idno); ?>
                <td style="text-align: center">
                    @if($currgrade[0] > 0)
                    {{$currgrade[0]}}
                    @endif
                </td>
                <td style="text-align: left">
                    {{$currgrade[1]}}
                </td>
            @endforeach
        </tr>
        @endforeach
    </table>
@stop