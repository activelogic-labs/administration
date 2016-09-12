$("#main #content .success .dismiss, #main #content .error .dismiss").click(function(){
    $(this).parent().hide();
    return false;
}); 