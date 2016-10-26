/**
 * Created by daltongibbs on 10/24/16.
 */


var self,
    settings,
    buttons,
    Filters = {
        buttons: {
            newFilter: $(".new-filter-button"),
            applyFilters: $(".apply-filters"),
            removeFilter: $(".remove-filter"),
            addFilter: $(".add-filter-button")
        },

        addFilterForm: $(".add-filter-form"),
        filterSelect: $("[name=filterColumn]"),

        filters: $(".applied-filters"),

        init: function(filterSettings) {
            self = this;
            settings = filterSettings;
            buttons = this.buttons;

            this.bindUIActions();
            this.removeAppliedFiltersFromSelection();
        },

        bindUIActions: function() {
            buttons.newFilter.on("click", function() {
                self.addFilterForm.toggle();
            });

            $(document).mouseup(function (event) {
                if (!self.addFilterForm.is(event.target) && self.addFilterForm.has(event.target).length === 0) {
                    self.addFilterForm.find("[name=filterColumn] :first-child").prop('selected', 'selected');
                    self.addFilterForm.find(".filter-input").hide();
                    self.addFilterForm.hide();
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
        },

        addFilter: function(column, value) {
            var newFilter = self.filters.find(".filter").first().clone(true);

            newFilter.find(".column").html(column);
            newFilter.find(".value").html(value);

            self.filters.append(newFilter);
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