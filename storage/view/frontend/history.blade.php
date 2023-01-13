@extends('layouts.frontendhead')
@section('content')
    <div id="content">
        <div class="container">
            <!-- 小說資訊 -->
            <div class="content book" id="fengtui">
                <h2 class="text-center">{{$type_title_name}}</h2>
                @if(empty($books))
                    <div id="showbook">
                        <div style="height:100px;line-height:100px; text-align:center">
                            还木有任何书籍( ˙﹏˙ )
                        </div>
                    </div>
                @else
                    @foreach($books as $key => $book)
                    <div class="bookbox">
                        <div class="p10">
                            <span class="num">{{$key + 1 }}</span>
                            <div class="bookinfo">
                                <h4 class="bookname">
                                    <a href="{{$bookinfo_url}}{{$book['book_id']}}">{{$book['book_name']}}</a>
                                </h4>
                                <div class="cat">分类：{{$book['type_name']}}</div>
                                <div class="author">{{trans('default.frontend.author') ?? '作者'}}：{{$book['author']}}</div>
                                <div class="update"><span>已读到：</span><a href="{{$bookcontent_url}}{{$book['book_content_id']}}">{{$book['chapter']}}</a></div>
                            </div>
                            <div class="delbutton">
                                <a class="del_but" href="{{$historydelete_url}}{{$book['book_id']}}">删除</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
@endsection
