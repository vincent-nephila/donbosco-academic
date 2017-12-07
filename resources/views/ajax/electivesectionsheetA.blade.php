<label>Section</label>
<select class="form-control" id="level" name="level" onchange="{{$action}}(this.value)">
    <option selected="selected" hidden="hidden">--Select--</option>
    <optgroup label="First Semester">
        @foreach($sections as $section)
            @if($section->sem == 1)
                <option value="{{$section->id}}">{{$section->elective}} ({{$section->section}})</option>
            @endif
        @endforeach
    </optgroup>
    <optgroup label="Second Semester">
        @foreach($sections as $section)
            @if($section->sem == 2)
                <option value="{{$section->id}}">{{$section->elective}} ({{$section->section}})</option>
            @endif
        @endforeach
    </optgroup>
</select>