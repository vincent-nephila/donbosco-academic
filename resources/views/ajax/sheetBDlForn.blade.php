<style>
    #select{
        cursor: pointer;
    }
</style>
<form method='post' action='{{url("downloadsheetB")}}' target="_blank">
    <a onclick="selectItem()" id="select">Select All</a>
    @foreach($sections as $section)
    <div class="checkbox">
      <label><input type="checkbox" class="checkboxes" name="section" value="{{$section->section}}">{{$section->section}}</label>
    </div>
    @endforeach
    
    
</form>
<script>
    var all = 0;
    function selectItem(){
        if(all == 0){
            all = 1;
            $('.checkboxes').attr('checked', true);
            $('#select').html('Unselect All');
        }else{
            all = 0;
            $('.checkboxes').attr('checked', false);
            $('#select').html('Select All');
        }
    }
</script>