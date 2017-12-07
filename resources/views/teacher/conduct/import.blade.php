@extends('layouts.teacherapp')
@section('content')
<div class="col-md-12">
<h3>{{$level}} - {{$section}}</h3>
<h4>Quarter:{{$quarter}}</h4>    
</div>
<div class="col-md-12">
    <form action="{{ URL::to('importconduct/'.$level.'/'.$section) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!} 
            <div class="form form-group">
            <input type="file" name="import_file" class="form"/>
            </div>
            <div class="form form-group">
            <button class="btn btn-primary">Import Grade</button>
            </div>    
    </form>
</div>
@stop