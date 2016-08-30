<div class="image-field">

    <form>
        <div class="image-display" style="background-image: url('{{ $src }}');"></div>
        <a class="image-upload"><i class="fa fa-upload"></i> Upload</a>
        <a class="image-delete"><i class="fa fa-trash"></i> Delete</a>
        <div class="clearfix"></div>
        {{--<button class=""><i class="fa fa-upload"></i> Upload</button>--}}
        {{--<button class=""><i class="fa fa-trash"></i> Delete</button>--}}
        <input type="file" name="{{ $name }}" class="hidden">
    </form>

</div>