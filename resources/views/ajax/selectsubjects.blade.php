<label>Subjects</label>
<select name="section" id="section" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Subject --</option>
    <optgroup value="0" label="Subjects">
    @foreach($subjects as $subject)
        @if($subject->semester ==$semester)
        <option value="{{$subject->subjectcode}}">{{$subject->subjectname}}</option>
        @endif
    @endforeach
    </optgroup>
</select>