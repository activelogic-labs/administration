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
                    self[filterConfig.type + "Input"](filterConfig);
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
            // buttons.applyFilters.css("color", "#BA1C1E");
            // buttons.applyFilters.css("border-color", "#BA1C1E");
            buttons.applyFilters.addClass('active-filters');

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