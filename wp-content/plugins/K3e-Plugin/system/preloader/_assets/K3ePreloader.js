jQuery(document).ready(function($) {
    var Body = $('body');
    Body.addClass('preloader-site');
});
jQuery(window).load(function() {
    jQuery('.preloader-wrapper').fadeOut();
    jQuery('body').removeClass('preloader-site');
});