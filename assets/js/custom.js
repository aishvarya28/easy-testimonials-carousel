jQuery(document).ready(function($) {
    $('.shortcode.column-shortcode').on('click', function() {
        toastr.options = {
            "positionClass": "toast-bottom-right"
        };
        toastr.success('Shortcode Copied!');
    });

    //  Hide all tab contents initially backend tab js
     $(function() {
        $("#tabs").tabs();
    });

});

