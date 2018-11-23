@extends('layouts.backend')

@push('need_css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@section('content')

    <div id="sortable" class="row">
    {{--<div class="box box-solid bg-teal-gradient">--}}
        {{--<div class="box-header">--}}
            {{--<i class="fa fa-th"></i>--}}

            {{--<h3 class="box-title">Sales Graph</h3>--}}

            {{--<div class="box-tools pull-right">--}}
                {{--<button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                {{--</button>--}}
                {{--<button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>--}}
                {{--</button>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="box-body border-radius-none">--}}
            {{--<div class="chart" id="line-chart" style="height: 250px;"></div>--}}
        {{--</div>--}}
        {{--<!-- /.box-body -->--}}
        {{--<div class="box-footer no-border">--}}
            {{--<div class="row">--}}
                {{--<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">--}}
                    {{--<input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"--}}
                           {{--data-fgColor="#39CCCC">--}}

                    {{--<div class="knob-label">Mail-Orders</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
                {{--<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">--}}
                    {{--<input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"--}}
                           {{--data-fgColor="#39CCCC">--}}

                    {{--<div class="knob-label">Online</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
                {{--<div class="col-xs-4 text-center">--}}
                    {{--<input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"--}}
                           {{--data-fgColor="#39CCCC">--}}

                    {{--<div class="knob-label">In-Store</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
            {{--</div>--}}
            {{--<!-- /.row -->--}}
        {{--</div>--}}
        {{--<!-- /.box-footer -->--}}
    {{--</div>--}}

        <div class="col-md-6">

        <div class="resize_div ui-widget-content">
            <div class="box" id="chapter_detail">
                    <div class="box-header">
                        <div class="box-tools pull-right">

                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>

                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                    </div>
                </div>
        {{--<iframe src="http://www.test2.com/one_lww/chapter/0004407021000" frameborder="0"></iframe>--}}
        </div>
        </div>
        <div class="col-md-6">
        <div class="resize_div ui-widget-content">
            <div class="box" id="jiexi_detail">
                    <div class="box-header">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <iframe src="http://www.test2.com/one_lww/chapter/0031106021000?test=1" frameborder="0"></iframe>

                    </div>
                </div>
            {{--<iframe src="http://www.test2.com/05wang/book/0031106021000/chapter/2018"--}}
                    {{--frameborder="0"></iframe>--}}
        </div>
        </div>
    </div>
@endsection


@push('need_js')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(function(){
            $('#chapter_detail .box-body').load('http://www.test2.com/one_lww/chapter/0031106021000 #app-vue');
            $('#jiexi_detail .box-body').load('http://www.test2.com/05wang/book/0031106021000/chapter/2018 #app-vue');
            $('#sortable').sortable();
            $( ".resize_div" ).resizable();
            $( ".resize_div" ).draggable({ containment: "parent" });


        })
    </script>
@endpush