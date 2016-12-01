<div class="combobox" name="{{ $name }}" value="{{ $selected }}">
    <label>
        <span></span>
        <i class="fa fa-caret-down" aria-hidden="true"></i>
    </label>

    <ul>
        <li><input type="text" name="search" value="" placeholder="Search..." /></li>

        @foreach($options as $value => $label)
            <li><a href="/" value="{{ $value }}">{{ $label }}</a></li>
        @endforeach

    </ul>
</div>
