@extends('layouts.app')
@section('content')
<div class="container">
    <div class="col-md-3">
        <h4>Upload Grade</h4>
        
        <form action="{{ url('upload/grade') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!} 
            <div class="form form-group">
                <label>Level</label>
                <select class='form-control' name='level' id='level'>
                    <option value='' hidden='hidden'> -- Select Level --</option>
                    @foreach($levels as $level)
                    <option value='{{$level->level}}'>{{$level->level}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form form-group" id='strandgroup' style='display: none;'>
                <label>Strand</label>
                <select class='form-control' name='strand' id='strand'>
                    <option value=" " hidden='hidden'>-- Select Strand --</option>
                    <option value='ABM'>ABM</option>
                    <option value='STEM'>STEM</option>
                </select>
            </div>
            
            <div class="form form-group" id='subjectgroup' >
                <label>Subject</label>
                <select class='form-control' name='subjects' id='subjects' >
                </select>
            </div>  
            
            <div class="form form-group" s id='file'>
                <input type="file" name="import_file" class="form"/>
            </div>
            
            <div class="form form-group" id='button'>
                <button type="submit" class="btn btn-primary">Import Grade</button>
            </div>    
        </form>
    </div>
    <div class="col-md-9">
        @if(isset($grades))
        <form id="form" method="POST" action="{{url('saveentry')}}"  enctype="multipart/form-data">
            <?php $quarter = \App\CtrQuarter::first();?>
            <h2>{{$request->level}} @if(isset($request->strand))- {{$request->strand}}@endif</h2>
            <h4>Subjects: {{$request->subjects}}</h4>
            <b>Quarter:{{$quarter->qtrperiod}}</b>
        {!! csrf_field() !!} 
        
        <input type="hidden" value="{{$request->subjects}}" name="subj">
        <table class='table table-bordered'>
            <tr>
                <td>CN</td>
                <td>SN</td>
                <td>Name</td>
                <td>Grade</td>
                <td>Locked</td>
            </tr>
        @foreach($grades as $key=>$info)
        <?php 
        
        $infos = App\User::where('idno',$info['idno'])->first();
        $cn = App\Status::where('idno',$info['idno'])->first();
        
        $existcn = App\User::where('idno',$info['idno'])->exists();
        ?>
        @if($existcn)
            <tr>
                <td style='text-align: center;'>{{$existcn ? $cn->class_no:''}}</td>
                <td style='text-align: center;'>{{$info['idno']}}</td>
                <td>{{$infos->lastname}}, {{$infos->firstname}} {{$infos->middlename}}</td>
                <td><input class='form-control' type='text' value='{{$info['grade']}}' name='student[{{$info['idno']}}]'></td>
                <td><i class="fa fa-lock fa-3x" aria-hidden="true"></i></td>
            </tr>
        @else
        <tr>
            <td colspan="5" class="label-danger">Record unavailable for {{$info['idno']}}</td>
        </tr>
        @endif
        @endforeach
        </table>
        <input type='submit' value="Submit" class="btn btn-alert">
        </form>
        
        @endif
    </div>
</div>

<script>
    var lvl='';
    var strand='null';
    var subjects='';
    
    $("#level").change(function(){
        $("#subjects").html('');
        lvl = $(this).val();
        strand = 'null';
        subjects='';
        $("#strandgroup").css("display", "none");
        $("#strandgroup").css("display", "none");
        $("#strandgroup").css("display", "none");
        
        if (lvl == 'Grade 11' || lvl == 'Grade 12'){
            $("#strandgroup").css("display", "block");
        }else{
            getsubjects();
            $("#subjectgroup").css("display", "block");
        }
    });
    
    $("#strand").change(function(){
        strand = $(this).val();
        subjects='';
        getsubjects();
        $("#subjectgroup").css("display", "block");
        
    });
    
    
    
    function getsubjects(){

        var arrays ={};
        arrays['level'] = lvl;
        arrays['strand']= strand;
        $.ajax({
            
            type: "GET", 
            url: "/getsubmittersubjs/{{Auth::user()->accesslevel}}",
            data : arrays,
            success:function(data){
              $("#subjects").html(data);
                
                }
            });
    }
</script>
@stop