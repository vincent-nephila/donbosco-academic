<label>Quarter</label>
<select name="quarter" id="quarter" class="form-control"
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
        > 
    <option value="" hidden="hidden">-- Select Quarter--</option>
    <option value="1">1st Quarter</option>
    <option value="2">2nd Quarter</option>
    @if(!in_array($level,array("Grade 11","Grade 12")))
    <option value="3">3rd Quarter</option>
    <option value="4">4th Quarter</option>
    @endif
    <option value="5">Final</option>
</select>