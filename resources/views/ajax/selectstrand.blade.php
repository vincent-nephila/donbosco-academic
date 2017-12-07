<label>Strand</label>
<select name="strand" id="strand" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Strand --</option>
    @foreach($strands as $strand)
    <option value="{{$strand->strand}}">{{$strand->strand}}</option>
    @endforeach
</select>