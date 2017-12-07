@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\Teacher\Helper;?>
<div class="col-md-6">
<h3>{{$level}} - {{$section}}</h3>
<h4>Quarter:{{$quarter}}</h4>    
</div>
    <table class="table table-bordered" style="font-size: 11px">
        <tr style="text-align: center">
            <td>CLASS NO</td>
            <td>NAME</td>
            @foreach($conducts as $conduct)
            <td>{{strtoupper($conduct->subjectname)}}
                <br>
                <p style="text-align: center">({{$conduct->points}})</p>
            </td>
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
                    {{$currgrade[0]}}
                </td>
            @endforeach
        </tr>
        @endforeach
    </table>
@stop