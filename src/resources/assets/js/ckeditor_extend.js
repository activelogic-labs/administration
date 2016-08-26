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