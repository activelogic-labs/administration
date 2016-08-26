<select name="{{ $name }}" class="form-control">
    <option value=""></option>

    @foreach($options as $option)
        <option value="{{ $option->{$value} }}" {{ $option->{$value} == $selected ? " selected" : "" }}>{{ $option->{$display} }}</option>
    @endforeach

</select>