$(document).ready(function(){
    if (typeof(formErrors) !== 'undefined' ){
        for (var key in formErrors){
//            var name = formErrors[i];
            var $obj = $('form [name="'+key+'"]');
            $obj.addClass('error');
            $obj.change(function(){
                $(this).removeClass('error');
                $(this).on('change',null);
            })
        }
    }
})