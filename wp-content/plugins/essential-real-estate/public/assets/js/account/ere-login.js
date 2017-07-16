(function ($) {
    'use strict';
    $(document).ready(function ($) {
        if (typeof ere_login_vars !== "undefined") {
            var ajax_url = ere_login_vars.ajax_url;
            var loading = ere_login_vars.loading;
            $('.ere-login-button').on('click', function (e) {
                e.preventDefault();
                var $form = $(this).parents('form');
                var $messages = $(this).parents('.ere-login-wrap').find('.ere_messages');
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
                            window.location.reload();
                        } else {
                            $messages.empty().append('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                        }
                    },
                    error: function (xhr) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                })
            });

            $('#ere_forgetpass').on('click',function (e) {
                e.preventDefault();
                var user_login = $('#username_or_email').val(),
                    security = $('#ere_security_reset_password').val();
                $.ajax({
                    type: 'post',
                    url: ajax_url,
                    dataType: 'json',
                    data: {
                        'action': 'ere_reset_password_ajax',
                        'user_login': user_login,
                        'ere_security_reset_password': security
                    },
                    beforeSend: function () {
                        $('#ere_messages_reset_password').empty().append('<span class="success text-success"> ' + loading + '</span>');
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#ere_messages_reset_password').empty().append('<span class="success text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');
                        } else {
                            $('#ere_messages_reset_password').empty().append('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                        }
                    },
                    error: function (xhr) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.Message);
                    }
                });
            });
            $('.ere-reset-password-wrap').slideUp('slow');
            $('.ere-reset-password').off('click').on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    $login_wrap = $this.closest('.ere-login-wrap').slideUp('slow'),
                    $reset_password_wrap = $login_wrap.next('.ere-reset-password-wrap');
                setTimeout(function () {
                    $reset_password_wrap.slideDown('slow');
                    $reset_password_wrap.find('#username_or_email').focus();
                    if($this.closest('.modal-dialog').length == 0) {
                        var contentTop = $reset_password_wrap.offset().top - 30;
                        $('html,body').animate({scrollTop: +contentTop + 'px'});
                    }
                }, 500);
            });
            $('.ere-back-to-login').off('click').on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    $reset_password_wrap = $this.closest('.ere-reset-password-wrap').slideUp('slow'),
                    $login_wrap = $reset_password_wrap.prev('.ere-login-wrap');
                setTimeout(function () {
                    $login_wrap.slideDown('slow');
                    $login_wrap.find('#login_username').focus();
                    if($this.closest('#ere_signin_modal').length == 0) {
                        var contentTop = $login_wrap.offset().top - 30;
                        $('html,body').animate({scrollTop: +contentTop + 'px'});
                    }
                }, 500);
            });
            $('#ere_signin_modal').on('shown.bs.modal', function () {
                $('.ere-back-to-login', $('#ere_signin_modal')).click();
            });
            $('#ere_signin_modal').on('hide.bs.modal', function () {
                $('.ere-back-to-login', $('#ere_signin_modal')).click();
            })
        }
    });
})(jQuery);