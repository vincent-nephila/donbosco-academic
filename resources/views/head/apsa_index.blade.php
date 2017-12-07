@extends('layouts.app')
@section('content')
<style>
    .btn{
        border-radius: 0px;
    }
</style>

<div class='container'>
    <h4>Conducts</h4>
    @foreach($levels as $level)
    <?php $sections = \App\CtrSection::where('schoolyear',$sy)->where('level',$level->level)->get();?>
    <div class='col-md-3'>
        <div class='panel'>
            <div class='panel-danger'>
                <div class='panel-heading'>
                    {{$level->level}}
                </div>
            </div>
            <div class='panel-body '>
                @foreach($sections as $section)
                <?php
                $btn = 'btn-primary';
                $stat = 0;
                
                $status = \App\GradesStatus::where('schoolyear',$sy)->where('level',$level->level)->where('section',$section->section)->where('quarter',$quarter)->first();
                if($status){
                    if($status->status == 1){
                        $btn = 'btn-danger';
                    }elseif($status->status == 2){
                        $btn = 'btn-success';
                        $stat = 2;
                    }
                }
                ?>
                <a href="{{url('classconduct',array($level->level,$section->section))}}" class='col-md-12 btn {{$btn}}' style='text-align: left'>{{$section->section}}<span style='float:right'>@if($stat == 2)<i class="fa fa-check-circle" aria-hidden="true"></i></span>@endif</a>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>
@stop