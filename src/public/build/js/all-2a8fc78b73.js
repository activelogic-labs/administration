/**
 * Created by daltongibbs on 8/22/16.
 */


/**
 * Created by daltongibbs on 8/22/16.
 */

$("[wysiwyg='true']").each(function(index, value)
{
    var attrId = $(this).attr('id');
    var parentForm = $(this).closest("form");
    var saveButton = $("#"+attrId+"_save");
    var editor = CKEDITOR.inline(attrId);

    saveButton.on('click', function(event) {
        var formParams = {};
        formParams[$("#"+attrId).attr('name').replace("_wysiwyg", "")] = editor.getData();

        $.post(parentForm.attr("action"), formParams, function(response){

            if (!response.error && parentForm.attr("action").search(response.id) == -1) {

                updateDetailWithId(response.id);

            }

        });
    });
});


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
/**
 * Created by daltongibbs on 8/22/16.
 */

$(function(){

    $(document).on('dragenter', function(){
        $("#main").prepend("<div id='upload_hotspot'></div>");

        var dropzone = $("#upload_hotspot").dropzone({
            url: "/upload/submit",
            method: "put",
            paramName: "file",
            uploadMultiple: true,
            autoProcessQueue: true
        });

        dropzone.on('drop', function(file){
            console.log(file);
        });
    });

    $(document).on('dragleave', "#upload_hotspot", function(){
        $("#upload_hotspot").remove();
    });

});
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

    $('.data-group-field input[type=file]').focus(function() {
        console.log("File input focus");

    });

    $('.data-group-field input[type=file]').change(function() {
        console.log("File input change");

        var parentForm = $(this).closest('form');
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).val();

        // $.post(parentForm.attr('action'), formParams, function (response){
        //     console.log(response);
        // });

        $.ajax({
            url: parentForm.attr('action'),
            type: 'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
            }
        })
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
//# sourceMappingURL=all.js.map
