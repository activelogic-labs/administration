
$(function(){
    $(".data-group .data-group-field").each(function(i, domElem){

        var currentElement = $(domElem);
        var currentHeight = currentElement.height();

        if( i % 2 ){
            var previousElement = $(currentElement).prev();
            var previousHeight = previousElement.height();

            if(currentHeight > previousHeight){

                //console.log("1: Set previous element height to current elements height");
                previousElement.height(currentHeight);

            }else{

                //console.log("2: Set current element height to previous elements height");
                currentElement.height(previousHeight);

            }
        }

    });
})

$("#main #content .success .dismiss, #main #content .error .dismiss").click(function(){
    $(this).parent().hide();
    return false;
});
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

});

//# sourceMappingURL=all.js.map
