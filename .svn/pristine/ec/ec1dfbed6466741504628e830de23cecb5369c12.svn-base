@extends('layouts.backend')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
@endpush
@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">收藏统计</li>
        </ol>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i> 选择时间段
                    </span>
                        <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <a class="btn btn-default pull-right" href="{{route("favorite_chart_index")}}" >不限时间段</a>
                </div>

            </div>
        </div>

    </section>
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">收藏统计</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" id="chart-body">
                        <div class="chart">
                            <canvas id="barChart" style="height:800px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
@endsection
@push('need_js')
    <script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
    <script  src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
    <script  src="/adminlte/plugins/chartjs/Chart.js"></script>
<script type="text/javascript">
    $(".favorite_chart_index").parent().css("display",'block').parent().addClass("active");




        var areaChartData = {
            labels: [@forelse($data['list'] as $chart)"{{$chart->bookName}}"@if($loop->index < count($data['list'])-1),@endif @endforeach] ,

            datasets: [
                {
                    label               : 'Digital Goods',
                    fillColor           : '#00a65a',
                    strokeColor         : '#00a65a',
                    pointColor          : '#00a65a',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : [@forelse($data['list'] as $chart){{$chart->scount}}@if($loop->index < count($data['list'])-1),@endif @endforeach]
                }
            ]
        }
        var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = areaChartData
        //barChartData.datasets[0].fillColor   = '#00a65a'
        //barChartData.datasets[0].strokeColor = '#00a65a'
        //barChartData.datasets[0].pointColor  = '#00a65a'
        var barChartOptions                  = {
            scaleBeginAtZero        : true,
            scaleShowGridLines      : true,
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            scaleGridLineWidth      : 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines  : true,
            barShowStroke           : true,
            barStrokeWidth          : 2,
            barValueSpacing         : 5,
            barDatasetSpacing       : 1,
            responsive              : true,
            maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);


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
            startDate: moment().subtract(30, 'days'),
            endDate  : moment(),
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
            $.post("{{route('favorite_chart_ajax_index')}}",{"_token":"{{ csrf_token() }}","start":start.format('YYYY-MM-DD'),"end":end.format('YYYY-MM-DD')},function (data) {
                $("#chart-body").html(data);
            });
        }
    )
</script>
@endpush
