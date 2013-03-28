

$(document).ready(function(){
    var prefixes = ['bc', 'blank','letter','nb'];
    for (var i=0; i<prefixes.length; i++){
        var prefix=prefixes[i];
        var $block = $('.block_'+prefix).first();
        $block.find('input, select').data('prefix', prefix).change(function(){
           renderCount($(this).data('prefix')); 
        });
        renderCount(prefix);
    }

    $('input.show').change( function(){
        var $this = $(this);
        var $result = $this.parent().parent().children('div.result, button');
        if ($this.is(':checked')){
            $result.slideDown('fast');
        }
        else{
            $result.slideUp('fast');
        }
    });

    $('form').each(function(){
        var $form = $(this);
    
        $form.submit(function(){
            var order='';
            order+='Хочу заказать '+$form.find('input[name="_name"]').first().val()+'\n';
            order+='Тираж '+$form.find('select[name="count"]').val()+' шт. ';
            var $opt = $form.find('input[name="option"]').first();
            if ($opt.is(':checked'))
                order+=$opt.parent().text()+'.\n';
            order+= 'По цене '+ $form.find('.result .total span b').first().text() + ' руб. за тираж и ';
            order+= $form.find('.result .one span b').first().text() + ' руб. за штуку.\n ';
            $form.find('textarea[name="order"]').first().val(order);
        });
    
    })
    
    
    function renderCount(prefix){
        var $block = $('.block_'+prefix).first();
        var cnt = $block.find('select[name="count"]').first().val()*1;
        var priceOne = 0;
        if (prefix=='bc'){
            var prices={'100':5,'200':4.5,'500':3.5, '1000':3};
            priceOne = prices[cnt];
            if ($block.find('input[name="option"]').is(':checked')){
                priceOne+=1;
            }
        }
        if (prefix=='blank'){
            var prices={'50':12,'100':10,'200':9,'500':8.5, '1000':8};
            priceOne = prices[cnt];
        }
        if (prefix=='letter'){
            var prices={'100':11,'200':10,'500':9, '1000':8};
            priceOne = prices[cnt];
            if ($block.find('input[name="option"]').is(':checked')){
                priceOne+=2;
            }
        }
        if (prefix=='nb'){
            var prices={'100':70,'200':60,'500':55, '1000':50};
            priceOne = prices[cnt];
            if ($block.find('input[name="option"]').is(':checked')){
                priceOne+=20 +8000/cnt;
            }
        }
        
        var $result = $block.find('.result').first();
        $result.find('.total span b').text(Math.ceil(cnt*priceOne));
        $result.find('.one span b').text(Math.round(priceOne*100)/100);
    }
    
    
   
});

