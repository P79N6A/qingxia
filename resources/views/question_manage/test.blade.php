<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>后台管理</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
    <style>
        .ui-helper-hidden-accessible { display:none; }
    </style>
    @stack('need_css')
    <link rel="stylesheet" href="/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/adminlte/dist/css/skins/_all-skins.min.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-blue sidebar-mini @if(isset($_COOKIE['tool_bar']) && $_COOKIE['tool_bar']==1) sidebar-collapse @endif ">
<div class="wrapper">
    <header class="main-header">
        <a href="{{ route('backend') }}" class="logo">
            <span class="logo-mini"><b>后台</b></span>
            <span class="logo-lg"><b>后台管理</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <form action="{{ route('logout') }}" method="post" id="logout">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <a href="javascript:onclick=$('#logout').submit()" style="padding: 15px 15px;color: #fff;" class="pull-right">退出登录</a>
            </form>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">1010jiajiao相关</li>
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i> <span>练习册购买</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu menu-open">
                        <li class="@yield('book_buy_index')"><a href="{{ route('book_buy_index') }}">练习册搜索及筛选</a></li>
                        <li class="@yield('book_buy_wait')"><a href="{{ route('book_buy_wait') }}">等待购买列表</a></li>
                        <li class="@yield('book_buy_done')"><a href="{{ route('book_buy_done') }}">购买完成</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>练习册整理</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu menu-open">
                        @can('workbook_only')<li class="@yield('workbook_now')"><a href="{{ route('workbook_only') }}"><i class="fa fa-file-text-o"></i><span>练习册唯一化整理</span></a></li>@endcan
                        @can('sort_name')<li class="@yield('sort_name')"><a href="{{ route('sort_name') }}"><i class="fa fa-files-o"></i><span>sort_name整理</span></a></li>@endcan
                        <li class="@yield('lxc_now') hide"><a href="{{ route('lxc') }}"><i class="fa fa-files-o"></i><span>整理vzy练习册</span></a></li>
                        <li class="@yield('lxc_now_v2') hide"><a href="{{ route('lxc_v2') }}"><i class="fa fa-files-o"></i><span>整理vzy练习册(重新整理)</span></a></li>
                        @can('book_now')<li class="@yield('book_now')"><a href="{{ route('sub_sort') }}"><i class="fa fa-th"></i> <span>vzy子系列整理情况</span></a></li>@endcan
                        @can('book_now_v2')<li class="@yield('book_now_v2')"><a href="{{ route('book_arrange') }}"><i class="fa fa-book"></i> <span>课本整理</span></a></li>@endcan
                        @can('workbook_cover')<li class="@yield('workbook_cover')"><a href="{{ route('workbook_cover') }}"><i class="fa fa-file-o"></i> <span>练习册封面整理</span></a></li>@endcan
                        @can('video_manage')<li class="@yield('video_manage')"><a href="{{ route('video_manage') }}"><i class="fa fa-file-video-o"></i> <span>视频管理</span></a></li>@endcan
                        @can('isbn_manage')<li class="@yield('isbn_manage')"><a href="{{ route('isbn_manage') }}"><i class="fa fa-th-large"></i> <span>isbn整理</span></a></li>@endcan
                        @can('book_recycle')<li class="@yield('book_recycle')"><a href="{{ route('book_recycle') }}"><i class="fa fa-trash-o"></i> <span>回收站</span></a></li>@endcan
                        @can('book_check')<li class="@yield('book_check')"><a href="{{ route('book_check') }}"><i class="fa fa-check-square-o"></i> <span>答案审核</span></a></li>@endcan
                    </ul>
                </li>
                <li class="@yield('question_manage_index')">
                    <a href="{{ route('que_manage_index') }}"><i class="fa fa-th"></i> <span>解题管理</span></a>
                </li>
                @can('lww')
                    <li class="header">05网相关</li>
                    <li class="@yield('lww_index')"><a href="{{ route('lww_index') }}"><i class="fa fa-file-text-o"></i> <span>05网练习册管理</span></a></li>
                @endcan
                @can('manage')
                    <li class="header">系统相关</li>
                    <li class="@yield('system_manage')"><a href="{{ route('system_manage') }}"><i class="fa fa-user"></i> <span>权限管理</span></a></li>
                @endcan
            </ul>
        </section>
    </aside>
    <div id="app-vue" class="content-wrapper">
        <router-view></router-view>
    </div>
    <footer class="main-footer"></footer>
</div>
<script src="{{ asset('js/app.js') }}"></script>
{{--<script src="{{ asset('js/jquery-ui.min.js') }}"></script>--}}
{{--<script>--}}
{{--$.widget.bridge('uibutton', $.ui.button);--}}
{{--</script>--}}
<script src="/js/js_cookie.js"></script>
@stack('need_js')
<script src="/adminlte/plugins/fastclick/fastclick.js"></script>
<script src="/adminlte/dist/js/app.min.js"></script>
<script>
    $(function () {
        $('.sidebar-toggle').click(function () {
            console.log(Cookies.get('tool_bar'));
            if(Cookies.get('tool_bar')==1){
                Cookies.set('tool_bar',null);
            }else{
                Cookies.set('tool_bar','1');
            }
        });
    });
</script>
</body>
</html>
