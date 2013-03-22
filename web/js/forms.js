$(document).ready(function(){
    if (typeof(formErrors) !== 'undefined' && formErrors.length>0){
        for (var i=0; i<formErrors.length; i++){
            var name = formErrors[i];
            $('form [name="'+name+'"]').addClass('error');
        }
    }
})