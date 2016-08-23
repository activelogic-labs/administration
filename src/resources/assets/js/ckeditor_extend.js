/**
 * Created by daltongibbs on 8/22/16.
 */

$("[wysiwyg='true']").each(function(index, value)
{
    var attrId = $(this).attr('id');
    editor = CKEDITOR.inline(attrId);
});
