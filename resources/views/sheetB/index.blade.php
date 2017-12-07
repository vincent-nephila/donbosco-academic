@extends('layouts.app')
@section('content')
<style>
    #print{
        visibility: hidden;
    }
    
    #semester{
        display: none;
    }
</style>
  <!-- Modal -->
  <div class="modal fade" id="download" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id='modalbody'>
          <p></p>
        </div>
      </div>
      
    </div>
  </div>


<div class="container-fluid">
    <div class="col-md-12"><h3>Sheet B</h3></div>
    <div class="col-md-3">

        <div class="form-group">
            <label>Level</label>
            <select class="form-control" id="level" name="level" onchange="updatelevel(this.value)">
                <option selected="selected" hidden="hidden">--Select--</option>
                @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="strand"></div>
        <div class="form-group" id="section"></div>
        <div class="form-group" id="semester">
            <label>Semester</label>
            <select class="form-control" id="sem" name="sem" onchange="updatesemester(this.value)">
                <option selected="selected" hidden="hidden" value="0">--Select--</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            </select>
        </div>
        <div class="form-group" id="subject"></div>
        <div>
            <button id="print" class="col-md-12 btn btn-danger" onclick="printsheetA()">PRINT</button>
        </div>
        <div class='panel panel-default' style='margin-top: 100px;'>
            <div class='panel-body'>
                    <dl class="dl-horizontal">
                        <dt>School Year:</dt>
                        <dd>{{$currSy}}</dd>
                        <dt>Level:</dt>
                        <dd id='levelLbl'></dd>
                        <dt>Semester:</dt>
                        <dd id='semLbl'></dd>
                        <dt>Quarter:</dt>
                        <dd id='quarterLbl'></dd>
                    </dl>
                <button id="downloadBtn" class="col-md-12 btn btn-alert">Download</button>
            </div>
        </div>
        
    </div>
    <div class="col-md-9" id="report" style="overflow-x: scroll">
    </div>
</div>
<script>
    var lvl = "";
    var sec = "null";
    var strand = "null";
    var sem = 0;
    var qtr = 0;
    
    function printsheetA(){

        
        window.open("/printsheetb/"+lvl+"/"+strand+"/"+sec+"/"+sem+"/"+qtr);
    }
   
    
    function updatelevel(level){
        qtr = 0;
        lvl = level;
        sec = "null";
        $('#strand').html("");
        $('#section').html("");
        $('#subject').html("");
        $('#levelLbl').html(level);

        document.getElementById("sem").value = 0;
        document.getElementById("semester").style.display = "none";
        if((jQuery.inArray( level,["Grade 9","Grade 10","Grade 11","Grade 12"]))>=0){
            getcourse();
        }else{
            updatestrand("null");
        }
    }
    
    function getcourse(){
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= "{{$currSy}}";
        $.ajax({
               type: "GET", 
               url: "/getlevelstrands/updatestrand",
               data : arrays,
               success:function(data){
                   $('#strand').html(data);
                   }
               });
    }
    
    function updatestrand(strnd){
        strand = strnd;
        
        getsection();
    }
    
    function getsection(){
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= '{{$currSy}}';
        arrays['course']= strand;
        $.ajax({
               type: "GET", 
               url: "/getlevelsections/0/updatesection",
               data : arrays,
               success:function(data){
                   $('#section').html(data)
                   }
               });
               
        if((jQuery.inArray( lvl,["Grade 11","Grade 12"]))>=0){
            getsemester();
        }else{
            updatesemester(0);
        }
    }
    
    function getsectionform(){
        arrays ={} ;
        arrays['level']= lvl;
        $.ajax({
               type: "GET", 
               url: "/getdownloadsections",
               data : arrays,
               success:function(data){
                   $('#modalbody').html(data)
                   }
               });
    }
    
    function updatesection(section){
        sec = section;
            getlist(qtr);
    }
    function getsemester(){
        document.getElementById("semester").style.display = "block";
    }
    
    function updatesemester(semester){
        sem = semester;
        $('#semLbl').html(semester);
        getQuarter();
    }
    
    function getQuarter(){
        
        arrays ={} ;
        arrays['level']= lvl;
        $.ajax({
               type: "GET", 
               url: "/getlevelquarter/getlist",
               data : arrays,
               success:function(data){
                   $('#subject').html(data);
                   }
               });
    }
    
    function getlist(quarter){
        $('#quarterLbl').html(quarter);
        $('#report').html("<div style='text-align:center;margin-left:auto;margin-right:auto;'><i class='fa fa-circle-o-notch fa-spin fa-3x fa-fw'></i><span >Loading report...</span></div>");
        qtr = quarter;
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= '{{$currSy}}';
        arrays['course']= strand;
        arrays['semester']= sem;
        arrays['section']= sec;
        arrays['subject']= 2;
        arrays['quarter']= quarter;
        $.ajax({
            type:"GET",
            data:arrays,
            url: "/gradeSheetBList",
            success:function(data){
                var name=lvl+"<br>";
                if(sem != 0){
                    name = name+" Semester:"+sem;
                }
                name = name+" Quarter:"+quarter;

                $('#modal-header').html(name);
                
                $('#report').html(data);
                getsectionform();
                document.getElementById("print").style.visibility = "visible";
            },
            error:function(){
                $('#report').css('overflow','hidden').html("<div class='alert alert-danger'>An unexpected error has occured. Please call dministrator.</div>");
                
            }
        });
    }
    
    $('#downloadBtn').click(function(){
        window.open("/downloadsheetb/"+lvl+"/"+strand+"/"+sem+"/"+qtr);
    });
        
</script>
@stop