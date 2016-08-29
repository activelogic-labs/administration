<select name="{{ $name }}" class="form-control">
    <option value=""></option>

    @foreach($options as $value => $label)
        <option value="{{ $value }}" {{ $value == $selected ? " selected" : "" }}>{{ $label }}</option>
    @endforeach

</select>