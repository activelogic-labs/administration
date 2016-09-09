
$(function(){

    // Enable CardTable for responsive tables
    $('.table').cardtable();

    // Clickable table rows
    $('.table tbody tr').click(function(e){

        var attr = $(this).attr('href');

        if(attr) {
            window.location = attr;
        }

        return false;
    });

    // Details field FOCUS
    $('.data-group-field input[type=text],.data-group-field input[type=password]').focus(function() {
        $('.data-group-field').removeClass('active');
        $(this).closest('.data-group-field').addClass('active');
    });

    // Details field BLUR
    $('.data-group-field input[type=text],.data-group-field input[type=password]').blur(function() {
        $('.data-group-field').removeClass('active');
    });

    $('.data-group-field .image-field .image-upload').click(function() {
        $(this).siblings('input').click();
    });

    $('.data-group-field .image-field .image-delete').click(function() {
        var field = $(this);
        var fieldName = field.data('name');
        var deleteUrl = $("#deleteButton").attr('href') + "/" + fieldName;

        $.get(deleteUrl, function(response) {

            field.siblings('.image-display').css({
                'background-image' : "url()"
            })

        });
    });
});