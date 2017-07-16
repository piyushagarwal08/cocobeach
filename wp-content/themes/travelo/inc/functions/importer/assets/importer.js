(function($){
    "use strict";
    $(document).ready(function(){
        var importer = $('.travelo-importer');
        $('select[name="demo"]', importer).val('');
        $('.button', importer).attr('disabled','disabled');

        $('select[name="demo"]', importer).change(function(){
            var val = $(this).val();
            if( val ){
                $('.button', importer).removeAttr('disabled');
            } else {
                $('.button', importer).attr('disabled','disabled');
            }
        });
    });
})(jQuery);