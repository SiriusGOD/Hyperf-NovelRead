@extends('layouts.frontendhead')
@section('content')
<div id="content">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="{{$home_url}}" title="{{trans('default.frontend.home') ?? '首 页'}}">{{trans('default.frontend.home') ?? '首 页'}}</a></li>
            <li><a href="{{$library_url}}" title="{{$type_name}}">{{$type_name}}</a></li>
            <li><a href="{{$bookinfo_url}}" title="{{$book_name}}">{{$book_name}}</a></li>
            <li class="active">{{$chapter}}</li>
        </ol>
        <!-- 文章 -->
        <div class="book read">
            <h1 class="pt10"> {{$chapter}}</h1>
            <div class="toolbar-box">
                <div class="toolbar">
                    <a href="javascript:bgLigh();" class="pattern" data-role="mode">{{trans('default.frontend.light_close') ?? '关灯'}}</a>
                    <a href="javascript:changeFontSize('reduce');" class="aminus">{{trans('default.frontend.front_reduce') ?? '字体-'}}</a>
                    <a href="javascript:changeFontSize('add');" class="aadd">{{trans('default.frontend.front_add') ?? '字体+'}}</a>
                </div>
                <div class="toolbar">
                    <a href="{{$bookcontent_url_pre}}">{{trans('default.frontend.chapter_pre') ?? '上一章'}}</a>
                    <a href="{{$bookinfo_url}}">{{trans('default.frontend.directory') ?? '目录'}}</a>
                    <a href="{{$bookcontent_url_next}}">{{trans('default.frontend.chapter_next') ?? '下一章'}}</a>
                </div>
            </div>
            <!-- content -->
            {!! $book_content !!}
            <div class="mulu-box">
                <p class="text-center">
                    <a id="linkPrev" class="btn btn-default" href="{{$bookcontent_url_pre}}">{{trans('default.frontend.chapter_pre') ?? '上一章'}}</a> 
                    <a id="linkIndex" class="btn btn-default" href="{{$bookinfo_url}}">{{trans('default.frontend.directory') ?? '目录'}}</a> 
                    <a id="linkNext" class="btn btn-default" href="{{$bookcontent_url_next}}">{{trans('default.frontend.chapter_next') ?? '下一章'}}</a>
                </p>
                <p class="pt10 text-center hidden-xs">{{trans('default.frontend.hint') ?? ''}}</p>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        // 記錄點擊次數
        var data = {
            "book_id" : "{{$book_id}}",
            "book_type_id" : "{{$book_type_id}}",
        }
        $.ajax({
            url:'/api/book_count/click',
            method: 'POST',
            dataType: 'JSON',
            data: data,
            success:function(res){console.log(res)},
            error:function(err){console.log(err)},
        });

        // 方向鍵控制
        $(document).keydown(function (event) {
            // console.log('按下了键' + event.keyCode);
            if(event.keyCode === 37){
                // console.log('按下了左方向键');
                //do somethings;
                $('#linkPrev')[0].click();;
            }else if (event.keyCode === 39){
                // console.log('按下了右方向键');
                //do somethings;
                $('#linkNext')[0].click();;
            }else if (event.keyCode === 13){
                // console.log('按下了Enter键');
                $('#linkIndex')[0].click();;
            }
        });
    }
</script>
@endsection