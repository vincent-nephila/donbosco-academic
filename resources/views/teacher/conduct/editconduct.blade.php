
@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\Teacher\Helper;?>
<div class="col-md-6">
<h3>{{$level}} - {{$section}}</h3>
<h4>Quarter:{{$quarter}}</h4>    
</div>
<div class="col-md-6">
    <h3 align="right">
        <a href="{{url('classconduct',array($level,$section,'import'))}}" class="btn btn-info" >Import</a>
    <button data-toggle="tooltip" title="Save changes to continue later" class="btn btn-success" onclick='saveconduct()'>Save</button>
    <button data-toggle="tooltip" title="Submit to APSA. Cannot edit once submitted" class="btn btn-danger" onclick="submitconduct()">Submit</button>
    </h3>
</div>
<form id="submitconduct" action="{{ URL::to('saveconduct/'.$level.'/'.$section) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
{!! csrf_field() !!} 
    <table class="table table-bordered" style="font-size: 11px">
        <tr style="text-align: center">
            <td>CLASS NO</td>
            <td>NAME</td>
            @foreach($conducts as $conduct)
            <td>{{strtoupper($conduct->subjectname)}}
                <br>
                <p style="text-align: center">({{$conduct->points}})</p>
            </td>
            <td>Remarks</td>
            @endforeach
        </tr>
        @foreach($students as $student)
        <?php $name = \App\User::where('idno',$student->idno)->first(); ?>
        <tr>
            <td style="text-align: center">{{$student->class_no}}</td>
            <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
            @foreach($conducts as $conduct)
                <?php $currgrade = Helper::quarterSubjectGrade($quarter,$conduct->subjectcode,$student->idno); ?>
                <td>
                    <input type="number" class="form-control serial" name="conduct[{{$student->idno}}][{{$conduct->subjectcode}}][grade]" min="0" max="{{$conduct->points}}"  value="{{$currgrade[0]}}" limit-to-max/>
                </td>
                <td>
                    <textarea  class="form-control serial" name="conduct[{{$student->idno}}][{{$conduct->subjectcode}}][remarks]"  value="{{$currgrade[1]}}" limit-to-max/>{{$currgrade[1]}}</textarea>
                </td>
            @endforeach
        </tr>
        @endforeach
    </table>
</form>
<script>
$('.serial').keydown(function(e) {
    console.log(this);
    if (e.which == 13) {
        var index = ($(this).parent('td').index())+1;
        $(this).closest('tr').next().children('td:nth-child('+index+')').find('.serial').first().focus();
        e.preventDefault();
    }
    if (e.which == 37){
        var index = $(this).parent('td').index();
        $(this).closest('tr').children('td:nth-child('+index+')').find('.serial').first().focus();
        e.preventDefault();
    }
    if (e.which == 39){
        var index = ($(this).parent('td').index())+2;
        $(this).closest('tr').children('td:nth-child('+index+')').find('.serial').first().focus();
        e.preventDefault();
    }
});

function saveconduct(){
    document.getElementById("submitconduct").submit();
}

function submitconduct(){
    window.location.href = "{{url('submitconduct',array($level,$section))}}"
}
</script>
@stop