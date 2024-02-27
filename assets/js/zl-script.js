jQuery(document).ready(function($) {
    var radioLabels = $(".image-select-label");
    var imageContainer = $("#selected_design_image");
    radioLabels.on("click", function() {
        radioLabels.removeClass("selected");
        $(this).addClass("selected");
        var selectedValue = $(this).find("input").val();
        imageContainer.html("<img src='" + scriptData.designOptions[selectedValue].image + "' alt='Selected Design' height='auto' width='700px'/>");
    });

    // Save the active tab as post meta using JavaScript
    var tabLinks = $('#tabs ul li a');
    tabLinks.on("click", function() {
        var activeTab = $(this).attr("href");

        // Save the active tab as post meta using AJAX
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "save_active_tab",
                post_id: scriptData.postId,
                active_tab: activeTab,
                security:scriptData.security,
            },
        });
    });

});

// Copyable
 document.addEventListener("DOMContentLoaded", function() {
    var shortcodeColumns = document.querySelectorAll(".column-shortcode");
    shortcodeColumns.forEach(function(column) {
        column.addEventListener("click", function() {
            const tempInput = document.createElement("input");
            tempInput.value = column.textContent.trim();
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
        });
    });
});

  jQuery(function($) {

    $('.my-color-field').wpColorPicker();

    // Update the text field value when the range input changes
    $('#range-field').on('input', function() {
        $('#range-value').val($(this).val()+ 'ms');
    });
 });

document.addEventListener("DOMContentLoaded", function(){
    var storedTab = scriptData.activeTab;
    var tabLinks = document.querySelectorAll('#tabs ul li a');
    tabLinks.forEach(function(tabLink){
        if(tabLink.getAttribute("href") === storedTab){
            tabLink.click();
        }
    });
});

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