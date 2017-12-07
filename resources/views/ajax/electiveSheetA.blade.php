<table class="table table-bordered">
    <tr style="text-align: center">
        <td>CLASS NO</td>
        <td>LAST NAME</td>
        <td>FIRST NAME</td>
        <td>QTR 1</td>
        <td>QTR 2</td>
        <td>RUNNING AVE</td>
    </tr>
    <?php $cn = 1; ?>
    @foreach($students as $student)
    <?php 
    $name = \App\User::where('idno',$student->idno)->first();
    $grade = App\Grade::where('section',$section)->where('idno',$student->idno)->first();
    ?>
    <tr>
        <td style="text-align: center">{{$cn}}</td>
        <td>{{$name->lastname}}</td>
        <td>{{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
        @if($sem==1)
        <td style="text-align: center">
            @if($grade->first_grading != 0)
            {{round($grade->first_grading,2)}}
            @endif
        </td>
        <td style="text-align: center">
            @if($grade->second_grading != 0)
            {{round($grade->second_grading,2)}}
	    @endif
        </td>
        @elseif($sem==2)
        <td style="text-align: center">
            @if($grade->third_grading != 0)
            {{$grade->third_grading}}
            @endif
        </td>
        <td style="text-align: center">
            @if($grade->fourth_grading != 0)
            {{$grade->fourth_grading}}
            @endif
        </td>
        @endif
        <td style="text-align: center">
            <?php 
            $running_ave = 0;
            if($sem ==1){
                $running_ave = round(($grade->first_grading+$grade->second_grading)/2,0);
            }elseif($sem == 2){
                $running_ave = round(($grade->third_grading+$grade->fourth_grading)/2,0);
            }
            ?>
            @if($running_ave != 0)
            {{$running_ave}}
            @endif
        </td>
    </tr>
    <?php $cn++; ?>
    @endforeach
</table>
