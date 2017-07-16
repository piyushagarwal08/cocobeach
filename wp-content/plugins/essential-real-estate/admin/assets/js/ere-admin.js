(function ($) {
    'use strict';
    $(document).ready(function ($) {
        $('.tips, .help_tip').tipTip({
            'attribute': 'data-tip',
            'fadeIn': 50,
            'fadeOut': 50,
            'delay': 200
        });
    });
})(jQuery);