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

    // Details field BLUR
    $('.data-group-field input[type=text]').blur(function() {

        $('.data-group-field').removeClass('active');

        var parentForm = $(this).closest("form");
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).val();

        $.post(parentForm.attr("action"), formParams, function(response){

            if (!response.error && parentForm.attr("action").search(response.id) == -1) {

                updateDetailWithId(response.id);

            }

        });

    });

    $('.data-group-field select').blur(function() {

        $('.data-group-field').removeClass('active');

        var parentForm = $(this).closest("form");
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).val();

        $.post(parentForm.attr("action"), formParams, function(response){

            if (!response.error && parentForm.attr("action").search(response.id) == -1) {

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

        form.attr('action', formAction + "/" + id);
        deleteButton.attr('href', deleteHref + "/" + id);
        subtitle.html(id);
    }

});