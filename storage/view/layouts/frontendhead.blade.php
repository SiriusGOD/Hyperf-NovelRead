<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{{trans('default.frontend.title')}}</title>
        <meta name="keywords" content="{{trans('default.frontend.keywords')}}" />
        <meta name="description" content="{{trans('default.frontend.description')}}"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="renderer" content="webkit">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,
            user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta http-equiv="Cache-Control" content="no-transform">
        <meta http-equiv="Cache-Control" content="no-siteapp">
        <link href="/dist/css/default.css" rel="stylesheet">
        <script src="/dist/js/default.js"></script>
        <script src="/dist/js/index.js"></script>
        <script src="/dist/js/frontend.js"></script>
        <script src="/dist/js/jquery-3.6.3.min.js"></script>
        <!-- <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script> -->
    </head>
    <body>
        <div class="header" id="header">
            <div class="container01">
                <div class="header-left">
                    <a href="{{$home_url}}" title="{{trans('default.frontend.title')}}" class="logo">{{trans('default.frontend.title')}}</a>
                </div>
                <div class="header-nav">
                    <a href="{{$home_url}}" title="{{trans('default.frontend.home') ?? '首 页'}}">{{trans('default.frontend.home') ?? '首 页'}}</a>
                    <a href="{{$library_url}}" title="{{trans('default.frontend.library') ?? '书 库'}}">{{trans('default.frontend.library') ?? '书 库'}}</a>
                    <a href="{{$rank_url}}" title="{{trans('default.frontend.rank') ?? '排 行'}}">{{trans('default.frontend.rank') ?? '排 行'}}</a>
                    <a href="{{$complete_url}}" title="{{trans('default.frontend.complete') ?? '全 本'}}">{{trans('default.frontend.complete') ?? '全 本'}}</a>
                    <a href="{{$search_url}}" title="{{trans('default.frontend.search') ?? '全 本'}}">{{trans('default.frontend.search') ?? '搜 索'}}</a>
                </div>

                <div class="header-right">
                    <a href="{{$history_url}}">阅读历史</a>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <!-- /.content-header -->
        @yield('content')

        <!-- /.footer -->
        @include("partial.frontendfooter")
    </body>
</html>
