jQuery(document).ready(function () {
    jQuery(".remove-thumbnail").click(function(event) {
                event.preventDefault();
                jQuery(this).parent().parent().remove();
            });
});