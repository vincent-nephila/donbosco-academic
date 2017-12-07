<table class="table table-bordered">
    <tr style="text-align: center">
        <td>CLASS NO</td>
        <td>LAST NAME</td>
        <td>FIRST NAME</td>
        <td>QTR 1</td>
        <td>QTR 2</td>
        @if(in_array($semester,array(0)))
        <td>QTR 3</td>
        <td>QTR 4</td>
        @endif
        <td>RUNNING AVE</td>
    </tr>
    <?php $cn = 1; ?>
    @foreach($students as $student)
    <?php 
    $name = \App\User::where('idno',$student->idno)->first();
    $first_grading = 0;
    $second_grading = 0;
    $third_grading = 0;
    $fourth_grading = 0;
    $running_ave = 0;
    $count = 0;
    
    if($subject == 3){
        $grades = App\Grade::where('subjecttype',$subject)->where('schoolyear',$sy)->where('idno',$student->idno)->get();
    }else{
        $grades = App\Grade::where('subjectcode',$subject)->where('schoolyear',$sy)->where('idno',$student->idno)->get();
    }
    
    foreach($grades as $grade){
        $grade_setting = App\GradesSetting::where('level',$grade->level)->first();
        $first_grading = $first_grading+$grade->first_grading;
        $second_grading = $second_grading+$grade->second_grading;
        $third_grading = $third_grading+$grade->third_grading;
        $fourth_grading = $fourth_grading+$grade->fourth_grading;
    }
    ?>
    <tr>
        <td style="text-align: center">{{$cn}}</td>
        <td>{{$name->lastname}}</td>
        <td>{{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
        @if(in_array($semester,array(0,1)))
        <td style="text-align: center">
            @if($first_grading != 0)
            <?php 
                $running_ave = $running_ave+$first_grading;
                $count++;
            ?>
            {{$first_grading}}
            @endif
        </td>
        <td style="text-align: center">
            @if($second_grading != 0)
            <?php 
                $running_ave = $running_ave+$second_grading;
                $count++;
            ?>
            {{$second_grading}}
            @endif
        </td>
        @endif
        @if(in_array($semester,array(0,2)))
        <td style="text-align: center">
            @if($third_grading != 0)
            <?php 
                $running_ave = $running_ave+$third_grading;
                $count++;
            ?>
            {{$third_grading}}
            @endif
        </td>
        <td style="text-align: center">
            @if($fourth_grading != 0)
            <?php 
                $running_ave = $running_ave+$fourth_grading;
                $count++;
            ?>
            {{$fourth_grading}}
            @endif
        </td>
        @endif
        
        <td style="text-align: center">
            @if($count != 0)
            {{number_format(round($running_ave/$count,$grade_setting->decimal),$grade_setting->decimal)}}
            @endif
        </td>
    </tr>
    <?php $cn++; ?>
    @endforeach
</table>