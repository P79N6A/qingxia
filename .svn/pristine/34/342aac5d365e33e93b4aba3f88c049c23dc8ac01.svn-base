@extends('layouts.backend')

@section('baidu_manage','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">百度统计数据查看</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                详细信息统计
            </div>
            <div class="box-body">
                <div class="box box-success">
                     <span>
                        <label>时间区间:</label>
                        <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="reservation">
                        <span id="get_search" class="input-group-addon btn btn-primary">查询</span>
                    </div>
                    </span>
                    <a class="btn btn-primary hide" id="get_now">确认</a>
                    <hr>
                    <a href="http://www.1010jiajiao.com/{{ $data['shiti_type'] }}/shiti_id_{{ $data['shiti_md5id'] }}"
                       target="_blank">http://www.1010jiajiao.com/{{ $data['shiti_type'] }}
                        /shiti_id_{{ $data['shiti_md5id'] }}</a>

                    <!-- /.box-body -->
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="chart">
                            <canvas id="lineChart" style="height:500px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection

@push('need_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('adminlte') }}/plugins\datepicker\locales\bootstrap-datepicker.zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>
        $(function () {
            $('#reservation').daterangepicker({
                language: 'zh-CN',
                startDate: '{{ $data['start'] }}',
                endDate: '{{ $data['end'] }}',
            });
            $('#daterange-btn').daterangepicker(
                {
                    language: 'zh-CN',
                    autoclose: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
            );
            $('#get_search').click(function () {
                let time = $('#reservation').val();
                time = time.replace(/\//g, '_');
                time = time.replace('-', '__');
                window.location.href = '{{ route('baidu_shiti_detail',[$data['shiti_type'],$data['shiti_md5id']]) }}' + '/' + time;
            });


            var ctx1 = document.getElementById("lineChart").getContext('2d');
            var myChart1 = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: {!! $data['shiti_info']->pluck('date_now') !!} ,
                    datasets: [
                        {
                            label: '总访问次数',
                            backgroundColor: 'rgba(255,0,0,0.5)',
                            borderColor: 'rgba(255,0,0,0.5)',
                            fill: false,
                            hidden: true,
                            data: {!! $data['shiti_info']->pluck('visit_count') !!}
                        },
                        {
                            label: '访客数',
                            backgroundColor: 'rgba(255,127,0,0.5)',
                            borderColor: 'rgba(255,127,0,0.5)',
                            fill: false,
                            hidden: true,
                            data: {!! $data['shiti_info']->pluck('visitor_count') !!}
                        },
                        {
                            label: '新访客数',
                            backgroundColor: 'rgba(0,0,0,0.5)',
                            borderColor: 'rgba(0,0,0,0.5)',
                            fill: false,
                            hidden: true,
                            data: {!! $data['shiti_info']->pluck('new_visitor_count') !!}
                        },
                        {
                            label: 'ip数',
                            backgroundColor: 'rgba(0,255,0,0.5)',
                            borderColor: 'rgba(0,255,0,0.5)',
                            fill: false,
                            hidden: true,
                            data: {!! $data['shiti_info']->pluck('ip_count') !!}
                        },
                        {
                            label: '跳出率',
                            backgroundColor: 'rgba(0,255,255,0.5)',
                            borderColor: 'rgba(0,255,255,0.5)',
                            fill: false,
                            data: {!! $data['shiti_info']->pluck('bounce_ratio') !!},
                        },
                        {
                            label: '平均访问时长',
                            backgroundColor: 'rgba(0,0,255,0.5)',
                            borderColor: 'rgba(0,0,255,0.5)',
                            fill: false,
                            hidden: true,
                            data: {!! $data['shiti_info']->pluck('avg_visit_time') !!}
                        },
                    ]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: '趋势统计'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },

                }
            });

        });

    </script>
@endpush