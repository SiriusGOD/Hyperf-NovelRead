@extends('layouts.frontendhead')
@section('content')
    <div class="container container01">
        <div class="content">
            <div class="content-left" id="fengtui">
                <h2>{{trans('default.frontend.popular') ?? '热门小说推荐'}}</h2>
                @foreach($popular as $book)
                <div class="item">
                    <div class="image">
                        <a href="{{$bookinfo_url}}{{$book->book_id}}">
                            <img src="{{$book->cover_img}}" alt="{{$book->book_name}}" width="120" height="150">
                        </a>
                    </div>
                    <dl>
                        <dt>
                            <span>{{$book->author}}</span>
                            <a href="{{$bookinfo_url}}{{$book->book_id}}">{{$book->book_name}}</a>
                        </dt>
                        <dd> 
                            {{$book->introduction}}
                        </dd>
                    </dl>
                    <div class="clear"></div>
                </div>
                @endforeach
            </div>
            <div class="content-right" id="fengyou">
                <div class="search hidden-xs">
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
                <h2 class="visible-xs">{{trans('default.frontend.study') ?? '阅读排行榜'}}</h2>
                <ul>
                @foreach($study as $book)
                    <li>[{{$book->type_name}}]<a href="{{$bookinfo_url}}{{$book->book_id}}">{{$book->book_name}}</a><span>{{$book->author}}</span></li>
                @endforeach
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <div class="content-right" id="zuixin">
                <h2>{{trans('default.frontend.book_create') ?? '最新小说'}}</h2>
                <ul>
                @foreach($book_create as $book)
                    <li>[{{$book->type_name}}]<a href="{{$bookinfo_url}}{{$book->book_id}}">{{$book->book_name}}</a><span>{{$book->author}}</span></li>
                @endforeach
                </ul>
            </div>
            <div class="content-left" id="gengxin">
                <h2>{{trans('default.frontend.book_update') ?? '最近更新'}}</h2></h2>
                <ul>
                @foreach($book_update as $book)
                    <li>
                        <span class="s1">[{{$book->type_name}}]</span>
                        <span class="s2">
                            <a href="{{$bookinfo_url}}{{$book->book_id}}">{{$book->book_name}}</a>
                        </span>
                        <span class="s3">
                            <a href="{{$bookcontent_url}}{{$book->book_content_id}}">{{$book->latest_chapter}}</a>
                        </span>
                        <span class="s5"></span>
                        <span class="s4">{{$book->author}}</span>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content tuijian hidden-xs">
            友情链接：<a href="{{$home_url}}" title="{{trans('default.frontend.title')}}">{{trans('default.frontend.title')}}</a>
            <div class="clear"></div>
        </div>
    </div>
@endsection
