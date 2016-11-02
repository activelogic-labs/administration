/**
 * Created by daltongibbs on 10/24/16.
 */


let self,
    settings,
    buttons,
    Filters = {
        buttons: {
            newFilter: $(".new-filter-button"),
            applyFilters: $(".apply-filters"),
            removeFilter: $(".remove-filter"),
            addFilter: $(".add-filter-button"),

            newSort: $(".new-sort-button"),
            addSort: $(".add-sort-button"),
            removeSort: $(".remove-sort"),

        },

        addFilterForm: $(".add-filter-form"),
        filterSelect: $("[name=filterColumn]"),

        filters: $(".applied-filters"),

        addSortForm: $(".add-sort-form"),

        sorts: $(".applied-sorts"),

        init: function(filterSettings) {
            self = this;
            settings = filterSettings;
            buttons = this.buttons;

            this.bindUIActions();
            this.removeAppliedFiltersFromSelection();

            if (self.sorts.children(":not(:first-child)").length != 0) {
                buttons.newSort.hide();
            };
        },

        bindUIActions: function() {
            //--- Filtering
            //NOTE: The open and close events firing at the same time
            buttons.newFilter.on("click", function() {
                self.addFilterForm.toggle();
            });

            $(document).mouseup(function (event) {
                if (!self.addFilterForm.is(event.target) && self.addFilterForm.has(event.target).length === 0) {
                    self.addFilterForm.find("[name=filterColumn] :first-child").prop('selected', 'selected');
                    self.addFilterForm.find(".filter-input").hide();
                    self.addFilterForm.hide();
                }

                if (!self.addSortForm.is(event.target) && self.addSortForm.has(event.target).length === 0) {
                    self.addSortForm.find("[name=filterColumn] :first-child").prop('selected', 'selected');
                    self.addSortForm.find(".filter-input").hide();
                    self.addSortForm.hide();
                }
            });

            self.filterSelect.change(function() {
                var selected = $(this).val();
                var filterConfig = settings[selected];
                var currentInput = self.addFilterForm.find(".define-filter");

                if (currentInput.length !== 0) {
                    currentInput.remove();
                }

                self.addFilterForm.find(".filter-input").show();

                if (filterConfig.hasOwnProperty('type')) {
                    window["Filters"][filterConfig.type + "Input"](filterConfig);
                } else {
                    self.stringInput();
                }
            });

            self.addFilterForm.submit(function(event) {
                event.preventDefault();

                var selectBox = $(this).find('select');
                var filter = selectBox.find(':selected');
                var input = $(this).find("[name=filterValue]");

                var column = filter.html();
                var value = input.val();

                self.addFilter(column, value);

                filter.remove();
                input.val('');
                selectBox.find(':first-child').prop('selected', 'selected');
                self.addFilterForm.toggle();

                self.bindApplyFiltersAction();
            });

            buttons.removeFilter.on("click", function() {
                var column = $(this).siblings(".column").text();
                var filter = $(this).parent();

                self.addFilterForm.find('select').append($("<option></option>").text(column));

                filter.remove();
            });

            if (self.filters.children(":not(:first-child)").length !== 0) {
                self.bindApplyFiltersAction();
            }

            //--- Sorting
            buttons.newSort.click(function() {
                self.addSortForm.toggle();
            });

            self.addSortForm.submit(function(event) {
                event.preventDefault();

                let column = $(this).find("[name=sortColumn] :selected").html();
                let direction = $(this).find("[name=sortDirection] :selected").val();

                self.addSort(column, direction);

                self.addSortForm.toggle();
                buttons.newSort.hide();

                self.bindApplyFiltersAction();
            });

            buttons.removeSort.click(function() {
                $(this).parent().remove();

                buttons.newSort.show();

                self.bindApplyFiltersAction();
            });
        },

        addFilter: function(column, value) {
            var newFilter = self.filters.find(".filter").first().clone(true);

            newFilter.find(".column").html(column);
            newFilter.find(".value").html(value);

            self.filters.append(newFilter);
        },

        addSort: function(column, direction) {
            let newSort = self.sorts.find('.sort').first().clone(true);

            newSort.find(".column").html(column);
            newSort.find('.direction').html(direction);

            self.sorts.append(newSort);
        },

        bindApplyFiltersAction: function() {
            buttons.applyFilters.css("color", "#BA1C1E");
            buttons.applyFilters.css("border-color", "#BA1C1E");

            buttons.applyFilters.on("click", function() {
                var url = $(location).attr('pathname') + "?";

                self.filters.children(":not(:first-child)").each(function(index, filter) {
                    var column = $(filter).find(".column").html();
                    var value = $(filter).find(".value").html();

                    if (index != 0) {
                        url += '&';
                    }

                    url += "filters[" + encodeURI(column) + "]=" + encodeURI(value);
                });

                self.sorts.children(":not(:first-child)").each(function(index, sort) {
                    let column = $(sort).find(".column").html();
                    let direction = $(sort).find(".direction").html();

                    if (index != 0) {
                        url += '&';
                    }

                    url += "sorts[" + encodeURI(column) + "]=" + encodeURI(direction);
                });

                window.location = url;
            });
        },

        removeAppliedFiltersFromSelection: function() {
            self.filters.children(":not(:first-child)").each(function(index, filter) {
                var column = $(filter).find(".column").html();

                var select = self.addFilterForm.find("[name=filterColumn] option:contains(" + column + ")");
                select.remove();
            });
        },

        stringInput: function() {
            var input = $("<input>").attr('class', 'define-filter').attr('name', 'filterValue').attr('placeholder', 'Filter Value');
            self.addFilterForm.find(".define-filter-label").after(input);

            input.focus();
        },

        selectInput: function(filterConfig) {
            var options = filterConfig['options'];
            var input = $("<select></select>").attr('class', 'define-filter').attr('name', 'filterValue');
            input.append($("<option></option>").prop('selected', 'selected').prop('disabled', true).html('Options'));

            for (var value in options) {

                var option = $("<option></option>").attr('value', options[value]).html(ucwords(options[value],true));

                input.append(option);

            }

            self.addFilterForm.find(".define-filter-label").after(input);

            input.focus();
        },

        booleanInput: function() {

        }
    };

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
function ucwords(str,force){
    str=force ? str.toLowerCase() : str;
    return str.replace(/(\b)([a-zA-Z])/g,
        function(firstLetter){
            return   firstLetter.toUpperCase();
        });
}
$("#main #content .success .dismiss, #main #content .error .dismiss").click(function(){
    $(this).parent().hide();
    return false;
});
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
//# sourceMappingURL=all.js.map
