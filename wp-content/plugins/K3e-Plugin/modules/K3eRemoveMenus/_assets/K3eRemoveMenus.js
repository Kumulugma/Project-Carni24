jQuery(document).ready(function () {
    jQuery(".remove-menu").click(function(event) {
                event.preventDefault();
                jQuery(this).parent().remove();
            });
});