// document ready function
$(document).ready(function() {
    //------------- Some fancy stuff in error pages -------------//
    $('.errorContainer').hide();
    $('.errorContainer').fadeIn(1000).animate({
        'top': "50%", 'margin-top': +($('.errorContainer').height()/-2-30)
        }, {duration: 750, queue: false}, function() {
        // Animation complete.
    });
});