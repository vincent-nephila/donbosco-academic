<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function __construct(){
	$this->middleware('auth');
    }

   static function studentQuarterAttendance($idno,$sy,$quarter,$level){
        if($sy == 2016){
            $qtrattendance = self::compute2016Attend($idno,$sy,$quarter,$level);
        }else{
            $qtrattendance = self::computeQuarterAttendance($idno,$sy,$quarter);
        }
        
        return $qtrattendance;
    }
    
    static function computeQuarterAttendance($idno,$sy,$quarter){
        $getdayp = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->whereIn('quarter',$quarter)->where('attendancetype','DAYP')->get();
        $getdayt = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->whereIn('quarter',$quarter)->where('attendancetype','DAYT')->get();
        $getdaya = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->whereIn('quarter',$quarter)->where('attendancetype','DAYA')->get();
        $dayp = 0;
        $dayt = 0;
        $daya = 0;
        
        if(count($getdayp)>0){
            foreach($getdayp as $getdayp){
                $dayp = $dayp+($getdayp->Jun+$getdayp->Jul+$getdayp->Aug+$getdayp->Sept+$getdayp->Oct+$getdayp->Nov+$getdayp->Dece+$getdayp->Jan+$getdayp->Feb+$getdayp->Mar);
            }
                $dayp = number_format($dayp,1);
        }else{
            $dayp = "";
        }
        
        if(count($getdayt)>0){
            foreach($getdayt as $getdayt){
                $dayt = $dayt+($getdayt->Jun+$getdayt->Jul+$getdayt->Aug+$getdayt->Sept+$getdayt->Oct+$getdayt->Nov+$getdayt->Dece+$getdayt->Jan+$getdayt->Feb+$getdayt->Mar);
            }
                $dayt = number_format($dayt,1);
        }else{
            $dayt = "";
        }
        
        if(count($getdaya)>0){
            foreach($getdaya as $getdaya){
                $daya = $daya+($getdaya->Jun+$getdaya->Jul+$getdaya->Aug+$getdaya->Sept+$getdaya->Oct+$getdaya->Nov+$getdaya->Dece+$getdaya->Jan+$getdaya->Feb+$getdaya->Mar);
            }
                $daya = number_format($daya,1);
        }else{
            $daya = "";
        }
        
        return array($dayp,$daya,$dayt);
    }
    static function compute2016Attend($idno,$sy,$quarter,$level){
        
        $months = self::attendance2016Reconstruct($quarter,$sy,$idno,$level);
        $month1 = $months[0];
        $month2 = $months[1];
        $month3 = $months[2];
        
        if(count($month1)>0 && count($month2)>0 && count($month3)>0){
            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
        }else{
            $dayt = 0;
            $dayp = 0;
            $daya = 0;
        }
        
        return array($dayp,$daya,$dayt);
    }
    
    static function attendance2016Reconstruct($quarter,$sy,$idno,$level){
        switch ($quarter){
            case 1;
                    $month1 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$sy)->where('month','JUN')->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$sy)->where('month','JUL')->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$sy)->where('month','AUG')->orderBy('id','DESC')->first();
            break;
            case 2;
                    $month1 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"Sept")->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"OCT")->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"AUG")->orderBy('id','DESC')->first();
            break;                
            case 3;
                    $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->whereIn('month',["NOV","Nov"])->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->whereIn('month',["DECE","Dece"])->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->whereIn('month',["JAN","Jan"])->orderBy('id','DESC')->first();
                    if(count($month1) == 0 || count($month2) == 0 || count($month3) == 0){
                        $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->whereIn('month',["OCT","Oct"])->orderBy('id','DESC')->first();
                        $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->whereIn('month',["NOV","Nov"])->orderBy('id','DESC')->first();
                        $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->whereIn('month',["DECE","Dece"])->orderBy('id','DESC')->first();
                    }
            break;
            case 4;
                $month1 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"JAN")->orderBy('id','DESC')->first();
                $month2 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"FEB")->orderBy('id','DESC')->first();
                $month3 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"MAR")->orderBy('id','DESC')->first();
            break;
            default;
                $dayp = 0;
                $dayt = 0;
                $daya = 0;
                
                $totaldayp = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->orderBy('sortto')->where('attendancetype','DAYP')->first();
                $totaldayt = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->orderBy('sortto')->where('attendancetype','DAYT')->first();
                $totaldaya = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->orderBy('sortto')->where('attendancetype','DAYA')->first();
                
                if($totaldayp){
                    $dayp = $totaldayp->Jul+$totaldayp->Aug+$totaldayp->Sept+$totaldayp->Oct+$totaldayp->Nov+$totaldayp->Dece+$totaldayp->Jan+$totaldayp->Feb+$totaldayp->Mar+$totaldayp->Jun;
                }
                
                if($totaldayt){
                    $dayt = $totaldayt->Jul+$totaldayt->Aug+$totaldayt->Sept+$totaldayt->Oct+$totaldayt->Nov+$totaldayt->Dece+$totaldayt->Jan+$totaldayt->Feb+$totaldayt->Mar+$totaldayt->Jun;
                }
                if($totaldaya){
                    $daya = $totaldaya->Jul+$totaldaya->Aug+$totaldaya->Sept+$totaldaya->Oct+$totaldaya->Nov+$totaldaya->Dece+$totaldaya->Jan+$totaldaya->Feb+$totaldaya->Mar+$totaldaya->Jun;
                }
                $month1 = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->orderBy('sortto')->where('attendancetype','DAYP')->first();
                $month1->DAYP = $dayp;
                $month1->DAYT = $dayt;
                $month1->DAYA = $daya;
                $month2 = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->orderBy('sortto')->where('attendancetype','DAYT')->first();
                $month2->DAYP = 0;
                $month2->DAYT = 0;
                $month2->DAYA = 0;
                $month3 = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->orderBy('sortto')->where('attendancetype','DAYA')->first();
                $month3->DAYP = 0;
                $month3->DAYT = 0;
                $month3->DAYA = 0;
                break;
        }
        
        return array($month1,$month2,$month3);
    }
    

}
