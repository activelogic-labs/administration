
$("[wysiwyg='true']").each(function(index, value)
{
    var attrId = $(this).attr('id');
    var parentForm = $(this).closest("form");
    var saveButton = $("#"+attrId+"_save");

    CKEDITOR.replace( attrId, {
        customConfig: '',
        allowedContent: true,
        extraAllowedContent : true,
        toolbarGroups: [
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'others', groups: [ 'others' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'paragraph' ] },
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] }
        ]
    });

    // var editor = CKEDITOR.inline(attrId);
    //
    // saveButton.on('click', function(event) {
    //     console.log("wysiwyg save click");
    //     var formParams = {};
    //     formParams[$("#"+attrId).attr('name').replace("_wysiwyg", "")] = editor.getData();
    //
    //     $.post(parentForm.attr("action"), formParams, function(response){
    //
    //         if (!response.error && parentForm.attr("action").search(response.id) == -1) {
    //
    //             updateDetailWithId(response.id);
    //
    //         }
    //
    //     });
    // });

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