/**
 * Created by daltongibbs on 8/22/16.
 */

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
    $('.data-group-field input[type=text]').focus(function() {
        $('.data-group-field').removeClass('active');
        $(this).closest('.data-group-field').addClass('active');
    });

    $('.data-group-field .image-field .image-upload').click(function() {
        $(this).siblings('form').children('input').click();
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

    $('.data-group-field input[type=file]').change(function() {

        var detailForm = $('#detailForm');
        var form = $(this).closest('form');

        $.ajax({
            url: detailForm.data('action'),
            type: 'POST',
            data: new FormData( form[0] ),
            processData: false,
            contentType: false,
            success: function (response) {
                if (!response.error && detailForm.data("action").search(response.id) == -1) {

                    updateDetailWithId(response.id);

                }

                if (response.value) {

                    $(form).siblings('.image-display').css({
                        'background-image' : "url(" + response.value + ")"
                    });

                }
            }
        });

    });

    // Details field BLUR
    $('.data-group-field input[type=text]').blur(function() {

        $('.data-group-field').removeClass('active');

        var parentForm = $('#detailForm');
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).val();

        $.post(parentForm.data("action"), formParams, function(response){

            if (!response.error && parentForm.data("action").search(response.id) == -1) {

                updateDetailWithId(response.id);

            }

        });

    });

    $('.data-group-field select').blur(function() {

        $('.data-group-field').removeClass('active');

        var parentForm = $('#detailForm');
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).val();

        $.post(parentForm.data("action"), formParams, function(response){

            if (!response.error && parentForm.data("action").search(response.id) == -1) {

                updateDetailWithId(response.id);

            }

        });

    });

    $('.data-group-field input[type=\'checkbox\']').change(function() {

        var parentForm = $('#detailForm');
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).is(':checked') ? "1" : "0";

        $.post(parentForm.data("action"), formParams, function(response){

            if (!response.error && parentForm.data("action").search(response.id) == -1) {

                updateDetailWithId(response.id);

            }

        });

    });

    // Static Submit Button
    // $(".data-group-submit").click(function(){
    //
    //     console.log("??");
    //
    //     return false;
    // });

    function updateDetailWithId(id)
    {
        var form = $("#detailForm");
        var formAction = form.attr('action');

        var subtitle = $("#subtitle");

        var deleteHref = formAction.replace('save', 'delete');
        var deleteButton = $("a[href='" + deleteHref + "']");

        form.data('action', formAction + "/" + id);
        deleteButton.attr('href', deleteHref + "/" + id);
        subtitle.html(id);
    }

});