$(function(){

    $(":input").focus(function () {
        if($(this).hasClass('is-invalid')){
            $(this).removeClass('is-invalid');
            $parent = $(this).parent();
            $spanToRemove = $parent.find('.invalid-feedback');
            $spanToRemove.remove();
        }
    });

});