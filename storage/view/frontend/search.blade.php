@extends('layouts.frontendhead')
@section('content')
<div id="content">
    <div class="container">
        <div class="content">
            <div class="search">
                <form name="articlesearch" method="post" action="{{$booksearch_url}}">
                    <select name="searchtype" id="searchtype" class="select">
                        <option value="book_name" selected>{{trans('default.frontend.book_name') ?? '小說名'}}</option>
                        <option value="author">{{trans('default.frontend.author') ?? '作者名'}}</option>
                    </select>
                    <input name="searchkey" type="text" class="text" id="searchkey" size="10" maxlength="50" placeholder="搜索从这里开始...">
                    <input type="hidden" name="action" value="login">
                    <button type="submit" name="submit">{{trans('default.frontend.search') ?? '搜 索'}}</button>
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content book">
            <div class="keywords">
                <h2>{{$search_title}}</h2>
                @foreach($books as $key => $book)
                <div class="bookbox">
                    <div class="p10">
                        <span class="num">{{$key + 1 }}</span>
                        <div class="bookinfo">
                            <h4 class="bookname">
                                <a href="{{$bookinfo_url}}{{$book -> book_id}}&page={{$page}}">{{$book -> book_name}}</a>
                            </h4>
                            <div class="author">{{trans('default.frontend.author') ?? '作者'}}：{{$book -> author}}</div>
                            <div class="author">{{trans('default.frontend.word_num') ?? '字数'}}：{{$book -> word_num}}</div>
                            <div class="author">{{trans('default.frontend.read') ?? '阅读量'}}：{{$book -> book_counts}}</div>
                            <div class="cat">
                                <span>{{trans('default.frontend.update_to') ?? '更新到'}}：</span>
                                <a href="{{$bookcontent_url}}{{$book -> book_content_id}}">{{$book -> latest_chapter}}</a>
                            </div>
                            <div class="update">
                                <span>{{trans('default.frontend.introduction') ?? '简介'}}：</span> 
                                {{$book -> introduction}}
                            </div>
                        </div>
                        <div class="delbutton">
                            <a class="del_but" href="{{$bookinfo_url}}{{$book -> book_id}}&page={{$page}}">阅读</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection