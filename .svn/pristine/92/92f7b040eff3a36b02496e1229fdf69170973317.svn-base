@extends('layouts.backend')

@section('audit_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
    <style>
        .panel-body img{
            height: 400px;
        }
    </style>
@endpush



@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">用户奖励发放审核</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                用户奖励发放列表
                <a type="button" class="btn btn-primary @if($data['status']==0) active @endif" href="{{ route("answer_user_award",0)}}">未申请</a>
                <a type="button" class="btn btn-primary @if($data['status']==1) active @endif" href="{{ route("answer_user_award",1)}}">已申请</a>
                <a type="button" class="btn btn-primary @if($data['status']==2) active @endif" href="{{ route("answer_user_award",2)}}">已结算</a>
                @if($data['status']==2)
                <div class="form-group">
                    <label>结算时间筛选</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i>
                    </span>
                            <i class="fa fa-caret-down"></i>{{$data['start']}}~{{$data['end']}}
                        </button>
                    </div>
                </div>
                @endif
            </div>
            <div class="box-body no-padding">
                <table class="table table-condensed">
                    <tbody>
                    <tr>
                        <th style="width: 10px">uid</th>
                        <th>奖励Q币数</th>
                        <th>通过时间</th>
                        @if($data['status']==1)
                        <th>申请时间</th>
                        <th>QQ</th>
                        <th>查看</th>
                        <th>操作</th>
                        @elseif($data['status']==2)
                        <th>申请时间</th>
                        <th>结算时间</th>
                        <th>QQ</th>
                        @endif
                    </tr>
                    @foreach($data['list'] as $v)
                    <tr data-uid="{{ $v['uid'] }}">
                        <td>{{ $v['uid'] }}</td>
                        <td>{{ $v['award'] }}</td>
                        <td>{{ $v['add_date'] }}</td>
                        @if($data['status']==1)
                            <td>{{ $v['shenqing_date'] }}</td>
                            <td>{{ $v['qq'] }}</td>
                            <td>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-warning dropdown-toggle lookup" data-toggle="dropdown" aria-expanded="false" >查看
                                        <span class="fa fa-caret-down"></span></button>
                                </div>
                            </td>
                            <td><button type="button" class="btn btn-primary award">结算</button></td>
                        @elseif($data['status']==2)
                            <td>{{ $v['shenqing_date'] }}</td>
                            <td>{{ $v['award_date'] }}</td>
                            <td>{{ $v['qq'] }}</td>
                        @endif
                    </tr>
                    @endforeach
                    @if($data['status']==2)
                        <tr>
                            <td>总计</td>
                            <td>{{ $data['zongji'] }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{ $data['list']->links() }}
    </section>
@endsection

@push('need_js')
<script>
    $(function(){
        $('.award').click(function(){
            var tr=$(this).parents('tr');
            var uid=tr.attr('data-uid');
            axios.post('{{ route('award_user') }}',{uid}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                }
             })
        });

        $('.lookup').click(function(){
            var td=$(this).parents('td');
            var uid=$(this).parents('tr').attr('data-uid');

            if(td.find('.dropdown-menu').length<=0){
                axios.post('{{ route('award_show_answer') }}',{uid}).then(response=>{
                    if(response.data.status===1){
                    if(response.data.data.length>0) {
                        td.find('.lookup').after('<ul class="dropdown-menu"></ul>');
                        for (var i in response.data.data) {

                                td.find('.dropdown-menu').append(`<li><a  target="show_answer" href="{{ route('user_audit')}}/${response.data.data[i].bookid}/1">${response.data.data[i].bookid}</a></li>`);
                        }
                    }
                }
            });
        }
        });
    })
</script>
@endpush

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
                window.location.href = `{{ route('answer_user_award',2) }}/${start.format("YYYY-MM-DD")}/${end.format("YYYY-MM-DD")}`;
            }
    )
</script>
@endpush