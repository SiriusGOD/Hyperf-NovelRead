@extends('layouts.frontendhead')
@section('content')
<div id="content">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="{{$home_url}}" title="{{trans('default.frontend.home') ?? '首 页'}}">{{trans('default.frontend.home') ?? '首 页'}}</a></li>
            <li><a href="{{$library_url}}?typeId={{$type_id}}&page={{$page}}" title="{{$type_name}}">{{$type_name}}</a></li>
            <li class="active">{{$book_name}}</li>
        </ol>
        <div class="book pt10">
            <div class="bookcover hidden-xs">
                <img class="thumbnail" alt="{{$book_name}}" src="{{$book_img}}" title="{{$book_name}}" width="140" height="180">
            </div>
            <div class="bookinfo">
                <h1 class="booktitle">{{$book_name}}</h1>
                <p class="booktag"><a class="red" href="####" title="{{trans('default.frontend.author') ?? '作者'}}：{{$author}}">{{$author}}</a> <span class="blue">{{$word_num}}字</span> <span class="blue">{{$type_name}}</span>
                    <span class="red">{{$status}}</span>
                </p>
                <p class="bookintro">
                    <img class="thumbnail pull-left visible-xs" style="margin:0 5px 0 0" alt="{{$book_name}}" src="{{$book_img}}" title="{{$book_name}}" width="80" height="120"> 
                    {{$introduction}}
                </p>
                <p>{{trans('default.frontend.latest_chapter') ?? '最新章节'}}：<a class="bookchapter" href="{{$bookcontent_url}}{{$last_book_content_id}}" title="{{$latest_chapter}}">{{$latest_chapter}}</a></p>
                <p class="booktime">{{trans('default.frontend.update_time') ?? '更新时间'}}：{{$update_time}}</p>
            </div>
        </div>
        <dl class="book chapterlist">
            <div id="list-chapterAll">
                <h2>《{{$book_name}}》全部章节目录</h2>
                <div>
                    @foreach($book_info as $book)
                    <dd>
                        <a href="{{$bookcontent_url}}{{$book->book_content_id}}">{{$book->chapter}}</a>
                    </dd>
                    @endforeach
                <div class="clear"></div>
                </div>
            </div>
        </dl>
    </div>
</div>
@endsection