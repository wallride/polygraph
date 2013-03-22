

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
        for (var i=0; i<m.paper.sizes.length; i++){
            $('<option value="'+m.paper.sizes[i]+'">'+m.paper.sizes[i]+'</option>').appendTo($select);
        }
        render();
    }
    
    
    function render(){
        var sizeStr = $form.find('select[name="size"]').first().val();
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
    
    
    $form.submit(function(){
       var order='';
       var $typeSelect = $form.find('select[name="type"]').first();
       order+='Хочу заказать '+$typeSelect.children('option[value="'+$typeSelect.val()+'"]').text()+'\n';
       order+='Размер '+$form.find('select[name="size"]').val();
       order+=', тираж '+$form.find('input[name="count"]').val()+'.\n';
       $form.find('input[type="checkbox"]').each(function(){
           var $this = $(this);
           if (this.checked){
               order+=$this.parent().text()+'. ';
           }
       })
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
    res.x-=d/4/1.41;
    res.y-=d/4/1.41;
    return res;
}



function getMatrix(){
    var res = {};
    res.paper = {};
    res.paper.sizes = [
        '90x150x40', '90x160x40', 
        '150x200x80', '175x250x70', '86x383x78',
        '370x260x80', '270x245x100', '320x245x100', '125x400x116', '250x330x90', 
                '240x330x90', '250x250x70','240x185x70', '210x300x80','185x205x75',
                '245x370x80','220x320x120','150x390x140','230x350x110','120x350x120',
        '300x400x120','380x480x70','370x320x100','400x320x130','225x340x140',
                '260x300x220','340x540x140','500x350x120','435x470x100','330x330x100',
                '500x380x120','120x450x120','285x240x90','520x470x100','750x550x140'
    ];
    
    return res;
}