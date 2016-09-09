<div class="image-field">

    <div class="image-display" style="background-image: url('{{ $src }}');"></div>
    <a class="image-upload"><i class="fa fa-upload"></i> Upload</a>
    <a class="image-delete" data-name="{{ $name }}"><i class="fa fa-trash"></i> Delete</a>
    <div class="clearfix"></div>

    <input type="file" name="{{ $name }}" class="hidden">

</div>