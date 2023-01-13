// 閱讀的背景燈開關
function bgLigh(){
    if($('body').attr('class') != 'nightbg'){
        $('body').attr('class', 'nightbg');
        var text = ($('.pattern').text()).replace("关灯", "开灯");
        $('.pattern').text(text);
    }else{
        $('body').attr('class', 'readbg');
        var text = ($('.pattern').text()).replace("开灯", "关灯");
        $('.pattern').text(text);
    }
}

// 字體大小變更
function changeFontSize(size){
    var size_now = $(".readcotent.bbb.font-normal").css("fontSize");
    size_now = size_now.replace('px','');
    
    // $('.readcotent.bbb.font-normal').attr('class', 'readcotent bbb font-large');
    if(size == 'add'){
        size_nex = "font-size:" + (parseInt(size_now) + 2).toString() + "px !important";
    }else{
        size_nex = "font-size:" + (parseInt(size_now) - 2).toString() + "px !important";
    }

    $(".readcotent.bbb.font-normal").css({
        'cssText': size_nex
    })
}