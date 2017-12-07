<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
        
        @if (Auth::guest())
        <title>Don Bosco Technical Institute of Makati, Inc.</title>
        @else
	<title>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Don Bosco Technical Institute</title>
        @endif
        
	<link href="{{ asset('bootstrap/dist/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
            
        <script src="{{asset('/js/jquery.js')}}"></script>
        <script src="{{asset('/js/dist/bootstrap.js')}}"></script>
        <style>
            .no-round{
                border-radius: 0px;
            }
            .box{
                border: 1px solid;
                text-align: center;
            }
        </style>
    </head>
    <body> 
    <div class= "container-fluid no-print" >
    <div class="col-md-12">
    <div class="col-md-1"> 
    <img class ="img-responsive" style ="margin-top:10px;max-height: 80px" src = "{{ asset('/images/DBTI.png') }}" alt="Don Bosco Technical School" />
    </div>
    <div class="col-md-11" style="padding-top: 20px"><span style="font-size: 14pt; font-weight: bold;">Don Bosco Technical Institute of Makati</span><br>Chino Roces Ave., Makati City<br>Tel No : 892-01-01
    </div>      
    </div>
    </div>

        @yield('menu')
        <?php
            $sy = \App\CtrSchoolYear::first()->schoolyear;
            $homerooms = \App\CtrSection::where('schoolyear',$sy)->where('adviserid',\Auth::user()->idno)->get();
        ?>
        <div class="container_fluid">
            <div class="col-md-3">
                @foreach($homerooms as $homeroom)
                <div>
                    <h4><div class="col-md-12 label label-default no-round">{{$homeroom->level}} @if($homeroom->strand != "")({{$homeroom->strand}})@endif <br> {{$homeroom->section}}</div></h4>
                    <h5><a href="{{url('classconduct',array($homeroom->level,$homeroom->section))}}" class="col-md-12 btn btn-default no-round">Conduct</a></h5>
                    <h5><a href="{{url('classattendance')}}" class="col-md-12 btn btn-default no-round">Attendance</a></h5>
                </div>
                @endforeach
            </div>
            
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
        
    
        <div class="container_fluid no-print">
            <div class="col-md-12"> 
                <p class="text-muted"> Copyright 2016, Don Bosco Technical Institute of Makati, Inc.  All Rights Reserved.<br />
                <a href="http://www.nephilaweb.com.ph">Powered by: Nephila Web Technology, Inc.</a></p>
            </div>
        </div>
    </body>
</html>
