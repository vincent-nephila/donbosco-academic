<?php $sy = App\ctrSchoolYear::first()->schoolyear; ?>
   <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                @if (!Auth::guest())
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="/"><i class="fa fa-btn fa-home">Home</i></a></li>
                    
                    @if(Auth::user()->accesslevel != env('USER_TEACHER'))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-btn fa-file"> Reports </i><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            
                            @if(Auth::user()->accesslevel == env('USER_TECH')||Auth::user()->accesslevel == env('USER_ELEM_ACAD')||Auth::user()->accesslevel == env('USER_HS_ACAD')||Auth::user()->accesslevel == env('USER_SHS_ACAD')||Auth::user()->accesslevel == env('USER_JHS_APSA'))
                            <li><a href="{{ url('gradesheetA',$sy) }}">Grade SheetA</a></li>
			    @endif
                            @if(Auth::user()->accesslevel == env('USER_ELEM_ACAD')||Auth::user()->accesslevel == env('USER_HS_ACAD')||Auth::user()->accesslevel == env('USER_SHS_ACAD'))
                            <li><a href="{{ url('gradesheetB') }}">Sheet B</a></li>
                            @endif
                            
                            @if(Auth::user()->accesslevel == env('USER_TECH'))
                            <li><a href="{{ url('electivesheetA',$sy) }}">Elective SheetA</a></li>
                            @endif

                        </ul>
                    </li>
                    @endif
                    @if(Auth::user()->accesslevel == env('USER_ELEM_ACAD')||Auth::user()->accesslevel == env('USER_HS_ACAD')||Auth::user()->accesslevel == env('USER_SHS_ACAD'))
                    <li><a href="/importgrade">Import Grades</a></li>
                    @endif
                    
                    @if(Auth::user()->accesslevel == env('USER_TEACHER'))
                    <?php
                        $homeroom = \App\CtrSection::where('schoolyear',$sy)->where('adviserid',\Auth::user()->idno)->first();
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-btn fa-file"> Homeroom </i><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{url('classconduct',array($homeroom->level,$homeroom->section))}}">Conduct</a></li>
                            <li><a href="{{url('classattendance')}}">Attendance</a></li>
                        </ul>
                    </li>
                    @endif
                    
                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}  <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            <li><a href="{{ url('/logout') }}"></i>Change Password</a></li>
                        </ul>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </nav>
