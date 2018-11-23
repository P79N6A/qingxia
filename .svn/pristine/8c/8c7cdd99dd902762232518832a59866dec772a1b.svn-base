@extends('layouts.backend')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/adminlte/plugins/datepicker/datepicker3.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">搜索统计</li>
        </ol>


    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i> {{$start}}~{{$end}}
                    </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div><!--
            <div class="col-md-3">
                <div class="input-group">
                    <a class="btn btn-default pull-right" href="{{route("search_tongji")}}" >不限时间段</a>
                </div>

            </div>-->
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">ISBN无结果统计</h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="isbn_data" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ISBN</th>
                                            <th>搜索次数</th>
                                            <th>最新年份</th>
                                            <th>学科</th>
                                            <th>版本</th>
                                            <th>册次</th>
                                            <th>系列</th>
                                            <th>年级</th>
                                            <th>已购买</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($datas as $data)
                                            <tr>
                                                <td><a href="{{route('book_info_by_isbn',['isbn'=>$data->isbn])}}">{{$data->isbn}}</a></td>
                                                <td>{{$data->isbnSearchCount}} </td>

                                                @php
                                                    $bookInfo = getLastBookInfoByIsbn($data->isbn);
                                                @endphp
                                                @if(isset($bookInfo))
                                                @forelse($bookInfo as $book)
                                                    <td>{{$book}}</td>

                                                @endforeach
                                                        @endif

                                                        <!--<td>{{date("Y-m-d H:i",$data->addtime)}}</td>-->
                                                        <td>{{checkBuyByIsbn($data->isbn)}}</td>
                                                        <td>
                                                            <a data-isbn="{{$data->isbn}}" class="edit" href="http://www.1010jiajiao.com" >编辑</a>
                                                            <a target="_blank" href="https://s.taobao.com/search?q={{$data->isbn}}">淘宝</a>
                                                        </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                        共{{$datas->total()}}条数据
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                        {{$datas->links()}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
@endsection
@push('need_js')
    <script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
    <script  src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript">
        $(".isbn_nocontent_search").parent().css("display",'block').parent().addClass("active");
        $(".edit").click(function () {
            $("#modal-default").modal({
                remote: "{{route("buybookbyisbn")}}/"+$(this).attr("data-isbn")
            });
            return false;
        });
        // 每次隐藏时，清除数据，确保不会和主页dom元素冲突。确保点击时，重新加载。
        $("#modal-default").on("hidden.bs.modal", function() {
            // 这个#showModal是模态框的id
            $(this).removeData("bs.modal");
            $(this).find(".modal-content").children().remove();
        });
        $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                    '今天'       : [moment(), moment()],
                    '昨天'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '最近7天' : [moment().subtract(6, 'days'), moment()],
                    '最近30天': [moment().subtract(29, 'days'), moment()],
                    '本月'  : [moment().startOf('month'), moment().endOf('month')],
                    '上个月'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens : 'right', //日期选择框的弹出位置
                //startDate: moment().subtract(1, 'days'),
                //endDate  : moment().subtract(1, 'days'),
                startDate : moment("{{$start}}").utc().subtract(-1,'days'),
                endDate :moment("{{$end}}").utc().subtract(-1,'days'),
                maxDate:moment(),
                format : 'YYYY/MM/DD', //控件中from和to 显示的日期格式
                locale : {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '起始时间',
                    toLabel : '结束时间',
                    customRangeLabel : '自定义',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                        '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                    firstDay : 1
                }
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('YYYY/MM/DD') + ' ~ ' + end.format('YYYY/MM/DD'));
                $("#chart-body").html("<i class='fa fa-refresh fa-spin'></i>");
                //var url = changeUrlArg(window.location.href,"start",start.format('YYYY-MM-DD'));
                //url = changeUrlArg(url,"end",end.format('YYYY-MM-DD'));
                var url = '{{route("search_tongji")}}/'+start.format('YYYY-MM-DD')+"/"+end.format('YYYY-MM-DD');
                window.location = url;
            }
        )
        function changeUrlArg(url, arg, val){
            var pattern = arg+'=([^&]*)';
            var replaceText = arg+'='+val;
            return url.match(pattern) ? url.replace(eval('/('+ arg+'=)([^&]*)/gi'), replaceText) : (url.match('[\?]') ? url+'&'+replaceText : url+'?'+replaceText);
        }
    </script>
@endpush