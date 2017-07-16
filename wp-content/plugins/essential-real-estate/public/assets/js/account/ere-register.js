(function ($) {
    'use strict';
    $(document).ready(function ($) {
        if (typeof ere_register_vars !== "undefined") {
            var ajax_url = ere_register_vars.ajax_url;
            var loading = ere_register_vars.loading;

            $('.ere-register-button').on('click',function (e) {
                e.preventDefault();
                var $form = $(this).parents('form');
                var $messages = $(this).parents('.ere-register-wrap').find('.ere_messages');
                $.ajax({
                    type: 'post',
                    url: ajax_url,
                    dataType: 'json',
                    data: $form.serialize(),
                    beforeSend: function () {
                        $messages.empty().append('<span class="success text-success"> ' + loading + '</span>');
                    },
                    success: function (response) {
                        if (response.success) {
                            $messages.empty().append('<span class="success text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');
                        } else {
                            $messages.empty().append('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                        }
                    },
                    error: function (xhr) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                });
            });
        }
    });
})(jQuery);