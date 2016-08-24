/**
 * Created by daltongibbs on 8/22/16.
 */


/**
 * Created by daltongibbs on 8/22/16.
 */

$("[wysiwyg='true']").each(function(index, value)
{
    var attrId = $(this).attr('id');
    editor = CKEDITOR.inline(attrId);
});

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

    // Details field BLUR
    $('.data-group-field input[type=text]').blur(function() {

        $('.data-group-field').removeClass('active');

        var parentForm = $(this).closest("form");
        var formParams = {};
        formParams[$(this).attr('name')] = $(this).val();

        $.post(parentForm.attr("action"), formParams, function(response){
            console.log(response);

            console.log(parentForm.attr("action"));
        });

    });

    // Static Submit Button
    $(".data-group-submit").click(function(){

        console.log("??");

        return false;
    });

});
//# sourceMappingURL=all.js.map
