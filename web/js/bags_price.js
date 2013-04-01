

$(document).ready(function(){
    var $form = $('#calculator').children('form').first();
    var $figure = $('#sizeFigure');
    var markers = {
        w: $figure.find('.sizeW').first(),
        h: $figure.find('.sizeH').first(),
        d: $figure.find('.sizeD').first()
    };
    var bag = {
        front: $figure.find('.bag_front').first(),
        back: $figure.find('.bag_back').first(),
        left: $figure.find('.bag_left').first(),
        right: $figure.find('.bag_right').first(),
        handsFront: $figure.find('.bag_hands_front').first(),
        handsBack: $figure.find('.bag_hands_back').first()
    };
    var type = $form.find('select[name="type"]').first().val();

    $form.find('select[name="size"]').first().change(function(){
        render();
    })
    $form.find('select[name="type"]').first().change(function(){
        type = $(this).val();
        initType();
    })

    initType();

    function initType(){
        var m = getMatrix();
        var $select = $form.find('select[name="size"]').first().empty();
        $.each(m.paper.sizes, function(k,v){
            var $group = $('<optgroup label="'+m.paper.price[k].name+'"/>').appendTo($select);
            for (var i=0; i<v.length; i++){
                $('<option value="'+k+'">'+v[i]+'</option>').appendTo($group);
            }
        })
        render();
        countPrice();
    }

    $form.find('input, select').change(function(){
        countPrice();
    })

    function render(){
        var $size = $form.find('select[name="size"]').first();
        var sizeStr = $size.find('option:selected').text();
        if (sizeStr == null || sizeStr.length<5){
            $figure.children().hide();
            return;
        }
        $figure.children().show();
        var size = stringToWHD(sizeStr);
        var pos;
        // Markers
        pos = transformCoordinates(0, 0, size.d);
        markers.d.css('right',-pos.x);
        markers.d.css('bottom',pos.y);
        markers.d.text(size.d+'мм');
        pos = transformCoordinates(0, size.h, 0);
        markers.h.css('right',-pos.x);
        markers.h.css('bottom',pos.y);
        markers.h.text(size.h+'мм');
        pos = transformCoordinates(size.w, 0, 0);
        markers.w.css('left',pos.x);
        markers.w.css('top',-pos.y);
        markers.w.text(size.w+'мм');
        // Bag
        pos= transformCoordinates(size.w, size.h, 0);
        bag.back.css({
            left:0,
            bottom:0,
            height:pos.y,
            width:pos.x
        });
        bag.front.css({
            height:pos.y,
            width:pos.x
        });
        bag.left.css({
            height:pos.y
        });
        bag.right.css({
            height:pos.y
        });
        bag.handsBack.css({
            left:pos.x/2-13,
            bottom:pos.y-5
        })
        pos= transformCoordinates(0,0, size.d);
        bag.front.css({
            left: pos.x,
            bottom: pos.y
        });
        bag.left.css({
            width: Math.abs(pos.x)-1,
            left: pos.x+1,
            bottom: pos.y,
            height: bag.front.css('height').replace('px','')*1 + Math.abs( pos.y)+1
        });
        bag.handsFront.css({
            left:bag.front.css('width').replace('px','')*1/2 +  pos.x -13,
            bottom:bag.front.css('height').replace('px','')*1 +  pos.y -5
        })
        bag.right.css({
            width: Math.abs(pos.x)-1,
            height: bag.front.css('height').replace('px','')*1 + Math.abs( pos.y)+1
        });
        pos= transformCoordinates(size.w, 0, size.d);
        bag.right.css({
            left: pos.x+1,
            bottom: pos.y
        });
    }
    
    function countPrice(){
        var m = getMatrix();
        var size = $form.find('select[name="size"]').val();
        var count = $form.find('select[name="count"]').val();
        var addons = {fix:0,one:0};
        console.log(size, count)
        $form.find('input[type="checkbox"]:checked').each(function(){
            var name = $(this).attr('name');
            var p = m.options[name];
            addons.fix+= p.fix;
            addons.one+= p.one;
            if ((size=='large_A2x2' || size=='huge_A1x2') && name=='doublePrint'){
                addons.fix+=m.paper.price[size].fix;
            }
            console.log(name, p, addons);
        })
        var totalPrice = m.paper.price[size].fix+addons.fix + count*(6+m.paper.price[size].one+addons.one);
        //СКИДКА 20%
        totalPrice*=0.8;
        
        $('#calculatorCheck p b.total').text(Math.round(totalPrice));
        $('#calculatorCheck p b.one').text(Math.round(totalPrice/count*100)/100);
        $('#others b').text(Math.round(totalPrice*1.2237514263144-totalPrice));
    }

    $form.submit(function(){
    var order='';
    var $size = $form.find('select[name="size"]').first();
    order+='Хочу заказать бумажный пакет.\n';
    order+='Размер '+$size.find('option:selected').text();
    order+=', тираж '+$form.find('select[name="count"]').val()+' шт.\n';
    $form.find('input[type="checkbox"]:checked').each(function(){
        order+=$(this).parent().text()+'. ';
    })
    order+='\nКалькулятор насчитал '+$('#calculatorCheck p b.total').text()+' руб. за тираж и ';
    order+=$('#calculatorCheck p b.one').text()+' руб. за пакет.\n ';
    $form.find('input[name="order"]').first().val(order);
    });

});


function stringToWHD(str){
    var res={w:0,h:0,d:0};
    res.w = str.replace(/^(\d+)x\d+x\d+$/, '$1');
    res.h = str.replace(/\d+x(\d+)x\d+/ig, '$1');
    res.d = str.replace(/\d+x\d+x(\d+)/ig, '$1');
    return res;
}


function transformCoordinates(w,h,d){
    var res = {x:w/4,y:h/4};
    res.x-=(d/4)/1.41;
    res.y-=(d/4)/1.41;
    return res;
}



function getMatrix(){
    var res = {};
    res.paper = {};
    res.paper.sizes = {
        'small_A4':
            ['90x150x40', '90x160x40'], 
        'medium_A3':
            ['150x200x80', '175x250x70', '86x383x78'],
        'big_A2':
            ['370x260x80', '270x245x100', '320x245x100', '125x400x116', '250x330x90', 
                '240x330x90', '250x250x70','240x185x70', '210x300x80','185x205x75',
                '245x370x80','220x320x120','150x390x140','230x350x110','120x350x120'],
        'large_A2x2':
            ['300x400x120','380x480x70','370x320x100','400x320x130','225x340x140',
                '260x300x220','340x540x140','500x350x120','435x470x100','330x330x100',
                '500x380x120','120x450x120','285x240x90','520x470x100'],
        'huge_A1x2':['750x550x140']
    };
    res.paper.price = {
        'small_A4':     {fix:9341, one: 14+5, name:'Маленький'}, 
        'medium_A3':    {fix:9683, one: 19.5+5, name:'Средний'},
        'big_A2':       {fix:13365, one: 28.6+5, name:'Стандартный'},
        'large_A2x2':   {fix:14730, one: 48.9+5, name:'Большой'},
        'huge_A1x2':   {fix:18480, one: 87.6+5, name:'Огромный'}
    };
    res.options ={
        'tisnenie':{fix:6000, one:3},
        'mate':{fix:0, one:6},
        'luvers':{fix:0, one:4},
        'ribbons':{fix:0, one:7},
        'doublePrint':{fix:0, one:0},
        'deadline':{fix:0, one:0}
    };

    return res;
}