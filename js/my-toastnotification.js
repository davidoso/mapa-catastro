// http://www.bootstrapdash.com/demo/stellar-admin/jquery/pages/ui-features/notifications.html
// Required files: jquery.toast.min.css, jquery.toast.min.js & toastDemo.js
(function($) {
    showToastNotif = function(heading, text, position, icon) {
        'use strict';
        resetToastPosition();
        $.toast({
            heading: heading,
            text: text,
            position: String(position),
            showHideTransition: 'slide',
            icon: icon,             // 4 options (and therefore background color): info, error, warning, success
            stack: false,
            loaderBg: '#fe9a2e'     // Catastro orange color
        })
    }

    resetToastPosition = function() {
        // to remove previous position class
        $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center');
        // to remove previous position style
        $(".jq-toast-wrap").css({"top": "", "left": "", "bottom":"", "right": ""});
    }
})(jQuery);