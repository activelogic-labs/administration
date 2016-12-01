/**
 * Combobox
 */

var combobox = function(element)
{
    this.class_active = 'active';
    this.element = $(element);
    this.searchField = this.element.find("ul li input");
    this.name = this.element.attr('name');
    this.value = this.element.attr('value');
    this.options = this.element.find("ul li a");
    this.optionMenuInstantiated = false;
    this.hiddenInputString = "<input type='hidden' name='' value='' />";

    /**
     * Configure hidden input
     *
     * @param value
     */
    this.configureHiddenInputString = function(value)
    {
        if(this.element.find("input[name='"+this.name+"']").length){
            this.element.find("input[name='"+this.name+"']").val(value);
            return;
        }

        var hiddenInputString = $(this.hiddenInputString);
        hiddenInputString.val(value);
        hiddenInputString.attr('name', this.name);

        this.element.prepend(hiddenInputString);
    };

    /**
     * Show
     */
    this.show = function()
    {
        var self = this;

        this.element.addClass(this.class_active);

        var inputWidth = this.element.outerWidth() - 2;
        var ul = this.element.find('ul');

        ul.width(inputWidth).show(0, function(){

            if(self.optionMenuInstantiated == false){
                self.optionMenuInstantiated = true;
                self.instantiateOptions();
            }

        });

        var searchInput = this.element.find('input');

        searchInput.focus();

        searchInput.blur(function(){
            setTimeout(function(){

                self.hide();

            }, 100);
        });
    };

    /**
     * Hide
     */
    this.hide = function()
    {
        this.element.removeClass(this.class_active);
        this.element.find('ul').hide();
    };

    /**
     * Instantiate Options (Click Events)
     */
    this.instantiateOptions = function()
    {
        var self = this;

        console.log("options: ", this.options);

        this.options.click(function(e){
            e.preventDefault();
            self.optionSelect($(this));
        });
    };

    /**
     * Option Select
     */
    this.optionSelect = function(element) {
        this.configureHiddenInputString(element.attr('value'));
        this.setLabel(element.html());
        this.searchField.val("");
        this.element.find("ul li").removeClass('hidden');
    };

    /**
     * Clear Option Select Value
     */
    this.clearOptionSelect = function(){
        this.configureHiddenInputString("");
    };

    /**
     * Set Label
     *
     * @param name
     */
    this.setLabel = function(name) {
        this.element.find("label").html(name);
    };

    /**
     * Find Label for value
     *
     * @param value
     * @returns {string}
     */
    this.findLabelForValue = function(value)
    {
        var labelString = "";

        this.options.each(function(index, element){

            var elem = $(element);

            if(elem.attr('value') == value){
                labelString = elem.html();
            }

        });

        return labelString;
    };

    // Initialize
    this.configureHiddenInputString(this.value);
    this.setLabel(this.findLabelForValue(this.value));

    // Listen for search input
    var self = this;

    this.searchField.keyup(function(input){

        var value = $(this).val();

        self.options.each(function(index, element){

            var optionText = $(element).text();
            var optionTextLowerCase = optionText.toLowerCase();
            var optionElement = $(element);

            if(optionTextLowerCase.search(value.toLowerCase()) == -1){

                optionElement.parent().addClass('hidden');

            }else{

                optionElement.parent().removeClass('hidden');

            }

        });

    });
};

/**
 * Find & Instantiate All objects
 */

$('.combobox').each(function(i, element){

    var combo = new combobox(element);

    $(element).find('label').on('click', function(){

        combo.show();

    });

});