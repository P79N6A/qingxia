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
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
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
    <div id="app-vue" class="content-wrapper" style=" margin-left: 0px;">
        @yield('content')
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