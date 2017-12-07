@extends('layouts.app')
@section('content')
<style>
    #print{
        visibility: hidden;
    }
</style>
<div class="container">
    <div class="col-md-4">
        <div class="form-group">
            <label>Schoolyear</label>
            <select class="form-control" id="schoolyear" name="schoolyear" onchange="changeSy(this.value)">
                @for ($i = 2016; $i <= $currSY; $i++)
                    <option value="{{$i}}"
                            @if($i==$selectedSY)
                            selected
                            @endif
                            >{{$i}}</option>
                @endfor            
            </select>
        </div>
        <div class="form-group">
            <label>Level</label>
            <select class="form-control" id="level" name="level" onchange="getsection(this.value)">
                <option selected="selected" hidden="hidden">--Select--</option>
                @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="section"></div>
        <div>
            <button id="print" class="col-md-12 btn btn-danger" onclick="printsheetA()">PRINT</button>
        </div>
    </div>
    <div class="col-md-8" id="report">
    </div>
</div>
<script>
    var lvl = "";
    var sec = "";
    
    function changeSy(schoolyear){
        window.location.href = "/electivesheeta/"+schoolyear;
    }
    
    function getsection(level){
        document.getElementById("print").style.visibility = "hidden";
        lvl = level;
        $('#report').html("");
        var array = {};
        array['sy'] = {{$selectedSY}};
        array['level'] = level;
        $.ajax({
            type:"GET",
            data:array,
            url: "{{url('sheetAelectivesection','getlist')}}", 
            success:function(data){
                $('#section').html(data);
            }
        });
    }
    
    function getlist(section){
        sec = section
        var array = {};
        array['sy'] = {{$selectedSY}};
        array['level'] = lvl;
        array['section'] = section;
        $.ajax({
            type:"GET",
            data:array,
            url: "/sheetAelectivelist",
            success:function(data){
                $('#report').html(data);
                document.getElementById("print").style.visibility = "visible";
            }
        });
    }
    
    function printsheetA(){
        window.open("{{url('/printelectivesheeta')}}"+"/"+sec, '_blank');
    }
</script>
@stop