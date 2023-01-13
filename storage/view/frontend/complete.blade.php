@extends('layouts.frontendhead')
@section('content')
    <div id="content">
        <div class="container">
            <!-- 小說資訊 -->
            <div class="content book" id="fengtui">
                <h2 class="text-center">{{$type_title_name}}</h2>
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
            <!-- 分頁 -->
            <div class="pages">
                <div class="pagelink" id="pagelink">
                    <em id="pagestats"> {{$page}}/{{$total_page_num}}</em>
                    <a href="{{$complete_url}}?page=1" class="first">1</a>
                    <a href="{{$complete_url}}?page={{$page - 1}}" class="pgroup">&lt;</a>
                    @if($total_page_num <= 5)
                        @for($i = 1; $i <= $total_page_num; $i++)
                            @if($i > 5)
                                @break
                            @elseif($page == $i)
                                <strong>{{$page}}</strong>
                            @else
                                <a href="{{$complete_url}}?page={{$i}}">{{$i}}</a>
                            @endif
                        @endfor
                    @else
                        @if(($total_page_num - $page) >= 3 && $page > 2)
                            @for($i = -2; $i <= 2; $i++)
                                @if($page == $page + $i)
                                    <strong>{{$page + $i}}</strong>
                                @else
                                    <a href="{{$complete_url}}?page={{$page + $i}}">{{$page + $i}}</a>
                                @endif
                            @endfor
                        @elseif(($total_page_num - $page) < 3)
                            @for($i = $total_page_num - 4; $i <= $total_page_num; $i++)
                                @if($page == $i)
                                    <strong>{{$page}}</strong>
                                @else
                                    <a href="{{$complete_url}}?page={{$i}}">{{$i}}</a>
                                @endif
                            @endfor
                        @else
                            @for($i = 1; $i <= 5; $i++)
                                @if($page == $i)
                                    <strong>{{$i}}</strong>
                                @else
                                    <a href="{{$complete_url}}?page={{$i}}">{{$i}}</a>
                                @endif
                            @endfor
                        @endif
                    @endif

                    @if($page + $i > $total_page_num)
                        <a href="{{$complete_url}}?page={{$page}}" class="next">&gt;</a>  
                    @else
                        <a href="{{$complete_url}}?page={{$page + 1}}" class="next">&gt;</a> 
                    @endif

                    <a href="{{$complete_url}}?page={{$total_page_num}}" class="last">{{$total_page_num}}</a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
@endsection
