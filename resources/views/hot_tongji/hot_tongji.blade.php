@extends('layouts.backend')

@section('baidu_manage','active')

@push('need_css')
<link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    tr:nth-child(odd)
    {
        background:lightblue;
    }

    tr:nth-child(even)
    {
        background:lightblue;
    }

    #title
    {
        background:#8c8c8c;
    }
</style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        {{--<ol class="breadcrumb">--}}
            {{--<li><a href=""><i class="fa fa-dashboard"></i> 主导航</a></li>--}}
            {{--<li class="active">百度统计数据查看</li>--}}
        {{--</ol>--}}
    </section>
    <section class="content">
        <div class="box box-primary">
            {{--<div class="box-header">--}}
                {{--<strong>统计数据(<b class="label label-info" id="min_date"></b>-<b class="label label-info" id="max_date"></b>)<a class="btn btn-danger btn-xs" id="get_update" data-now="">更新</a></strong>--}}
            {{--</div>--}}
            {{--<div class="nav-tabs-custom">--}}
                {{--<ul class="nav nav-tabs">--}}
                    {{--<li class="active"><a href="">答案</a></li>--}}
                    {{--<li><a href="">无答案题目</a></li>--}}
                    {{--<li class="hide"><a href="">题目</a></li>--}}
                    {{--<li><a href="">习题</a></li>--}}
                    {{--<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" >文章<span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="">热门文章</a></li>--}}
                            {{--<li><a href="">新建文章</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--<div class="tab-content">--}}
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body">
                            <div>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select data-name="grade" id="grade_id"
                                        class="grade_id form-control select2 pull-left" tabindex="-1"
                                        aria-hidden="true" name="grade">
                                    <option value="-5">全部年级</option>
                                </select>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select name="subject" data-name="subject" id="subject_id" class="subject_id form-control select2"
                                        tabindex="-1" aria-hidden="true">
                                    <option value="-5">全部科目</option>
                                </select>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select name="volumes" data-name="volumes" id="volumes_id" class="volumes_id form-control select2">
                                    <option value="-5">全部卷册</option>
                                </select>

                            </div>
                            <div class="input-group pull-left" style="width: 20%">
                                <select name="version" data-name="version" id="version_id" class="version_id form-control select2"
                                        tabindex="-1" aria-hidden="true">
                                    <option value="-5">全部版本</option>
                                </select>
                            </div>
                            <div class="input-group" style="width: 20%">
                                <select name="the_sort" data-name="sort" id="sort_id" class="form-control sort_name click_to">
                                    <option value="-5">全部系列</option>
                                </select>
                            </div>
                            <span>
                        <label>时间区间:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="time" type="text" class="form-control pull-right" id="reservation">
                            <span id="get_search" class="input-group-addon btn btn-primary">查询</span>
                        </div>
                </span>
                            <a class="btn btn-primary hide" id="get_now">确认</a>
                            <hr>
                            <table  class="table table-bordered">
                                <tr id="title">
                                    <th>书类名称</th>
                                    <th style="width:90px;">停留量<a href="">
                                            <a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_stay/desc"><em style="color:white">▲</em></a><a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_stay/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">收藏量<a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_collect_count/desc"><em style="color:white">▲</em></a><a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_collect_count/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">分享量<a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_sharenum/desc"><em style="color:white">▲</em></a><a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_sharenum/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">搜索量<a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_searchnum/desc"><em style="color:white">▲</em></a><a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_searchnum/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">评价<a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_good_evaluate/desc"><em style="color:white">▲</em></a><a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_good_evaluate/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">纠错<a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_correct/desc"><em style="color:white">▲</em></a><a href="{{route('hotlist')}}/{!! $data['de_grade'] !!}/{!! $data['de_subject'] !!}/{!! $data['de_volumes'] !!}/{!! $data['de_version'] !!}/{!! $data['de_the_sort'] !!}/{{$data['start']}}/{{$data['end']}}/sum_correct/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th>书本编码</th>
                                </tr>
                                @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')
                                @foreach($data['data'] as $k=>$v)
                                <tr>
                                        <td><strong ><p>{{$v->bookname}}</p>
                                                <p><a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{$v->id}}.html">http://www.1010jiajiao.com/daan/bookid_{{$v->id}}.html</a></p>
                                            @php
                                                try{
                                                echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$v->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                                }catch (Exception $e){
                                                echo '无法生成此isbn的条形码';
                                                }
                                            @endphp
                                        </td>
                                        <td><p><a target="_blank" href="{{route('stophere')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="btn btn-success btn-primary btn-xs" >{{$v->sum_stay}}<em class="badge bg-blue">图表</em> </a></p></td>
                                    <td><p><a href="{{route('stophere')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="btn btn-success btn-primary btn-xs" >{{$v->sum_collect_count}}<em class="badge bg-blue">图表</em> </a></p></td>
                                    <td><p><a href="{{route('stophere')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="btn btn-success btn-primary btn-xs" >{{$v->sum_sharenum}}<em class="badge bg-blue">图表</em> </a></p></td>
                                    <td><p><a href="{{route('stophere')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="btn btn-success btn-primary btn-xs" >{{$v->sum_searchnum}}<em class="badge bg-blue">图表</em> </a></p></td>
                                    <td>
                                        <p><a href="{{route('stophere')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="btn btn-success btn-primary btn-xs">好评<em class="badge bg-red">{{$v->sum_good_evaluate}}</em></a></p>
                                        <p><a href="{{route('stophere')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="btn btn-danger btn-primary btn-xs">差评<em class="badge bg-#ccc">{{$v->sum_bad_evaluate}}</em></a></p>
                                    </td>
                                    <td>
                                        <a href="{{route('hotcorrect')}}/{{$v->isbn}}/{{$data['start']}}/{{$data['end']}}" class="label label-info">反馈统计<em class="badge bg-red">{{$v->sum_correct}}</em></a>
                                    </td>
                                    <td>{{$v->onlyid}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div>
                        {{$data['data']->links()}}
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
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    $(function () {
        $('#reservation').daterangepicker({
            language:'zh-CN',
            startDate:'{{ $data['start'] }}',
            endDate:'{{ $data['end'] }}',
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
        //select2初始化
        $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['attr']['grade'] !!} '),});
        $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['attr']['subject'] !!} '),});
        $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['attr']['volumes'] !!} '),});
        $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['attr']['version'] !!} '),});
        $('select[data-name="sort"]').select2({data: $.parseJSON('{!! $data['attr']['sort'] !!} '),});

        //select默认值
        $("#grade_id").val(['{!!$data['de_grade']!!}']).trigger('change');
        $("#volumes_id").val(['{!!$data['de_volumes']!!}']).trigger('change');
        $("#version_id").val(['{!!$data['de_version']!!}']).trigger('change');
        $("#subject_id").val(['{!!$data['de_subject']!!}']).trigger('change');
        $("#sort_id").val(['{!!$data['de_the_sort']!!}']).trigger('change');


        $('#get_search').click(function(){
            var grade_id=$('#grade_id').val();
            var subject_id=$('#subject_id').val();
            var volumes_id=$('#volumes_id').val();
            var version_id=$('#version_id').val();
            var sort_id=$('#sort_id').val();
            var time=$('#reservation').val();
            var arr=time.split('-');
            var start=arr[0].replace(' ','');
            start=start.replace('/','_').replace('/','_');
            var end=arr[1].replace(' ','');
            end=end.replace('/','_').replace('/','_');
            console.log(start);
            console.log(end);

            window.location.href ='{{ route('hotlist') }}'+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id+'/'+start+'/'+end;
        });

        {{--$('.get_search_sort').click(function () {--}}
            {{--let type = 'get_information';--}}
            {{--let time = $('#reservation').val();--}}
            {{--time = time.replace(/\//g,'_');--}}
            {{--time = time.replace('-','__');--}}
            {{--let grade_id = $('#grade_id').val();--}}
            {{--let subject_id = $('#subject_id').val();--}}
            {{--let volumes_id = $('#volumes_id').val();--}}
            {{--let version_id = $('#version_id').val();--}}
            {{--let sort_id = $(this).attr('data-id');--}}
            {{--window.open('{{ route('baidu_manage') }}'+'/'+time+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id);--}}
        {{--});--}}


        {{--$('#get_now').click(function () {--}}
            {{--let time = $('#reservation').val();--}}
            {{--time = time.replace(/\//g,'_');--}}
            {{--time = time.replace('-','__');--}}
            {{--let grade_id = $('#grade_id').val();--}}
            {{--let subject_id = $('#subject_id').val();--}}
            {{--let volumes_id = $('#volumes_id').val();--}}
            {{--let version_id = $('#version_id').val();--}}
            {{--let sort_id = $('#sort_id').val();--}}

            {{--window.location.href ='{{ route('baidu_manage') }}'+'/'+time+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id;--}}
        {{--})--}}

        {{--//更新时间段--}}
        {{--$('#get_update').click(function () {--}}
            {{--if($('#get_update').attr('data-on')===1){--}}
                {{--alert('正在更新中');--}}
                {{--return false;--}}
            {{--}--}}
            {{--$('#get_update').attr('data-on',1);--}}
            {{--let max_date = $('#max_date').html();--}}
            {{--let now_date = $(this).attr('data-now');--}}
            {{--axios.post('{{ route('baidu_manage_api') }}',{type:'get_information',max_date,now_date}).then(response=>{--}}
                {{--if(response.data.status===0){--}}
                {{--alert(response.data.msg);--}}
            {{--}--}}
        {{--}).catch(function (error) {--}}
                {{--console.log(error);--}}
            {{--})--}}
        {{--});--}}


    });
</script>
@endpush