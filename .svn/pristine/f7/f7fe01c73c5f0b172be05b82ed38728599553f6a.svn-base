@extends('layouts.backend')

@section('user_feedback','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">反馈整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-body">
                <div class="form-group">
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i>
                    </span>
                            <i class="fa fa-caret-down"></i>{{substr($data['start'],0,10)}}~{{substr($data['end'],0,10)}}
                        </button>
                    </div>
                </div>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @forelse($data['all_books'] as $key=>$value)
                            <li @if($loop->first) class="active" @endif><a data-toggle="tab" href="#uid_{{ $key }}">{{ $value[0]->has_user->name }}<i class="badge bg-red">{{ count($value) }}</i></a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @forelse($data['all_books'] as $key=>$value)
                            <div class="tab-pane @if($loop->first) active @endif" id="uid_{{ $key }}">
                            @forelse($value as $book)
                                    <a target="_blank" class="btn btn-primary btn-xs" href="{{ route('audit_answer_detail',$book->bookid) }}">@if($book->bookid>10000000){{ $book->has_user_book->sort_name }} @else {{ $book->has_book->bookname }}  @endif @if($book->verified_at)<i class="badge bg-blue">已审核</i> @else <i class="badge bg-red">未审核</i>@endif @if($book->is_book==1) <i class="badge bg-black">课本</i> @endif</a>
                            @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection

@push('need_js')
    <script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
    <script  src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
    <script>
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
                startDate: moment("{{$data['start']}}").utc().subtract(-1,'days'),
                endDate  : moment("{{$data['end']}}").utc().subtract(-1,'days'),
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
                window.location.href = '{{ route('user_feedback_status') }}/'+start.format("YYYY-MM-DD")+"/"+end.format("YYYY-MM-DD");
            }
        )
    </script>
@endpush