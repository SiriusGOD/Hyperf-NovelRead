<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">

</div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link"   href="#" role="button"> {{auth('session')->user()->name  }}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/user/logout" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

            </div>
            <div class="info">
                <a href="/admin/index/dashboard" class="d-block">{{trans('default.leftbox.tittle') ?? '入口網站後台控制'}}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
               @if(authPermission('manager-index'))
                    <li class="nav-item">
                        <a href="/admin/manager/index" class="nav-link {{$user_active ?? ''}}">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                            {{trans('default.leftbox.manager') ?? '使用者管理'}}
                            </p>
                        </a>
                    </li>
                @endif
                @if(authPermission('role-index'))
                    <li class="nav-item">
                        <a href="/admin/role/index" class="nav-link {{$role_active ?? ''}}">
                            <i class="nav-icon fas fa-user-tag"></i>
                            <p>
                            {{trans('default.leftbox.role') ?? '角色管理'}}
                            </p>
                        </a>
                    </li>
                @endif
                @if(authPermission('site-index'))
                <li class="nav-item">
                    <a href="/admin/site/index" class="nav-link {{$site_active ?? ''}}">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>
                        {{trans('default.leftbox.site') ?? '多站管理'}}
                        </p>
                    </a>
                </li>
                @endif
                @if(authPermission('usersite-index'))
                <li class="nav-item">
                    <a href="/admin/user_site/index" class="nav-link {{$user_site_active ?? ''}}">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>
                        {{trans('default.leftbox.user_site') ?? '用戶對應多站管理'}}
                        </p>
                    </a>
                </li>
                @endif
                @if(authPermission('book-index'))
                <li class="nav-item">
                    <a href="/admin/book/index" class="nav-link {{$book_active ?? ''}}">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                        {{trans('default.leftbox.book') ?? '小說管理'}}
                        </p>
                    </a>
                </li>
                @endif
                @if(authPermission('bookrecommend-index'))
                <li class="nav-item">
                    <a href="/admin/book_recommend/index" class="nav-link {{$book_recommend_active ?? ''}}">
                        <i class="nav-icon fa fa-bookmark"></i>
                        <p>
                        {{trans('default.leftbox.book_recommend') ?? '小說推薦'}}
                        </p>
                    </a>
                </li>
                @endif
                @if(authPermission('bookcount-index'))
                <li class="nav-item">
                    <a href="/admin/book_count/index" class="nav-link {{$book_count_active ?? ''}}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                        {{trans('default.leftbox.book_count') ?? '小說類型點擊圖表'}}
                        </p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

