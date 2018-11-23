@extends('layouts.backend')

@section('baidu_manage','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="http://thumb.1010pic.com/styles/inner.css">
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
                <strong>统计数据(<b class="label label-info" id="min_date">{{ $data['min'] }}</b>-<b class="label label-info" id="max_date">{{ $data['max'] }}</b>)<a class="btn btn-danger btn-xs" id="get_update" data-now="{{ date('Y-m-d',time()) }}">更新</a></strong>

            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="{{ route('baidu_manage',[$data['time_range']]) }}">答案</a></li>
                    <li><a href="{{ route('baidu_question_no_answer') }}">无答案题目</a></li>
                    <li><a href="{{ route('baidu_manage_question',[$data['time_range']]) }}">题目</a></li>
                    <li class="active"><a href="{{ route('baidu_manage_xiti',[$data['time_range']]) }}">习题</a></li>
                    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" >文章<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('baidu_manage_portal',[$data['time_range']]) }}">热门文章</a></li>
                            <li><a href="{{ route('baidu_new_portal',[$data['time_range']]) }}">新建文章</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="tab_1">

                    </div>
                    <div class="tab-pane active" id="tab_2">
                        <div class="box-body">
                            <div class="input-group " style="width:30%">
                                <select data-name="type" id="xiti_type"                                        class="grade_id form-control select2 pull-left" tabindex="-1"
                                        aria-hidden="true">
                                    @foreach($data['all_type'] as $key=>$type)
                                        <option value="{{ $type }}" @if($data['now_type']===$type) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
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
                            <table class="table table-bordered">
                                <tr>
                                    {{--<th>试题科目</th>--}}
                                    <th>试题详情</th>
                                    <th style="width: 30%">访问信息</th>
                                </tr>
                                @forelse($data['total'] as $key=>$value)
                                    <tr>
                                        {{--<td>{{ $data['all_type'][$value->xiti_type] }}</td>--}}
                                        <td class="shiti_all_info">
                                            <a target="_blank" href="{{ $value->url }}">{{ $value->url }}</a>
                                            <div class="question" style="border: 4px solid deepskyblue;border-radius: 4px">
                                                @if($value->timu_id>0 && $value->timu_id<=1223274)
                                                    @php
                                                    $xiti_info = $data['now_timu_1_jiexi']?$data['now_timu_1_jiexi']->where('cid',$value->timu_id)->first()?$data['now_timu_1_jiexi']->where('cid',$value->timu_id)->first():'':'';
                                                    $now_id = $value->timu_id;
                                                    $now_type = 'mo_cotimu';
                                                    $old_xiti_info = $data['now_timu_1'];
                                                    @endphp
                                                @elseif($value->timu_id>1223274)
                                                    @php
                                                        $xiti_info = $data['now_timu_2_jiexi']?$data['now_timu_2_jiexi']->where('cid',$value->timu_id)->first()?$data['now_timu_2_jiexi']->where('cid',$value->timu_id)->first():'':'';
                                                        $now_id = $value->timu_id;
                                                        $now_type = 'mo_cotimu2';
                                                        $old_xiti_info = $data['now_timu_2'];
                                                    @endphp
                                                @elseif($value->timu3_id>0)
                                                    @php
                                                        $xiti_info = $data['now_timu_3_jiexi']?$data['now_timu_3_jiexi']->where('cid',$value->timu3_id)->first()?$data['now_timu_3_jiexi']->where('cid',$value->timu3_id)->first():'':'';
                                                        $now_id = $value->timu3_id;
                                                        $now_type = 'mo_cotimu3';
                                                    $old_xiti_info = $data['now_timu_3'];
                                                    @endphp
                                                @elseif($value->xiti_id>0)
                                                    @php
                                                        $xiti_info = $data['now_xiti_jiexi']?$data['now_xiti_jiexi']->where('cid',$value->xiti_id)->first()?$data['now_xiti_jiexi']->where('cid',$value->xiti_id)->first():'':'';
                                                        $now_id = $value->xiti_id;
                                                        $now_type = 'testpaper';
                                                    $old_xiti_info = $data['now_xiti'];
                                                    @endphp
                                                @endif

                                                <p>问题：</p>
                                                <div class="now_question">@if($xiti_info && $xiti_info->question) {!! $xiti_info->question !!} @else {!!  $old_xiti_info->where('id',$now_id)->first()?$old_xiti_info->where('id',$now_id)->first()->question:''  !!} @endif </div>
                                            </div>
                                            <div class="answer" style="border: 4px solid orangered;border-radius: 4px;">
                                                <p>回答：</p>
                                                <div class="now_answer">@if($xiti_info && $xiti_info->answer) {!! $xiti_info->answer !!} @else {!! $old_xiti_info->where('id',$now_id)->first()?$old_xiti_info->where('id',$now_id)->first()->answer:'' !!} @endif
                                                </div>
                                            </div>

                                            @if($xiti_info && $xiti_info->analysis)
                                            <div class="analysis" style="border: 4px solid lightseagreen;border-radius: 4px;">
                                                <p>解析：</p>
                                                <div class="now_analysis">{!! $xiti_info->analysis !!}</div>
                                            </div>
                                            @endif
                                            <a class="btn btn-primary edit_xiti" data-id="{{ $now_id }}"  data-table="{{ $now_type }}">编辑</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('baidu_xiti_detail',[$now_type,$now_id,$data['time_range']]) }}" target="_blank" class="btn btn-danger">查看习题统计信息</a>
                                            <p><strong>访问次数<a class="label label-info">{{ ceil($value->all_pv) }}</a></strong></p>
                                            <p><strong>访客数<a class="label label-info">{{ ceil($value->all_uv) }}</a></strong></p>
                                            <p><strong>新访客数<a class="label label-info">{{ ceil($value->new_uv) }}</a></strong></p>
                                            <p><strong>新访客比例<a class="label label-info">{{ round($value->new_visitor_ratio,2) }}%</a></strong></p>
                                            <p><strong>ip数 <a class="label label-info">{{ $value->ip_count }}</a></strong></p>
                                            <p><strong>贡献浏览量<a class="label label-info">{{ $value->out_pv_count }}</a></strong></p>
                                            <p><strong>跳出率<a class="label label-info">{{ round($value->bounce_ratio,2) }}%</a></strong></p>
                                            <p><strong>平均访问时长<a class="label label-info">{{ round($value->avg_visit_time,2) }}</a></strong></p>
                                            <p><strong>平均访问页数<a class="label label-info">{{ round($value->avg_visit_pages,2) }}</a></strong></p>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            {{ $data['total']->links() }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="panel panel-primary" id="xiti_box_now" style="overflow: auto;z-index:9;width:900px;display: none;position: fixed;bottom: 20px;right: 30px;">
                <div class="panel-body" style="overflow: auto">


                        <div style="height: 200px;overflow: auto">
                            <script style="width:850px;" name="question" id="E_edit1" type="text/plain"></script>
                        </div>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_answer" data-toggle="tab">答案</a></li>
                                <li><a href="#tab_analysis" data-toggle="tab">解析</a></li>
                            </ul>
                            <div class="tab-content" style="max-height:500px;overflow:auto">
                                <div class="tab-pane active" id="tab_answer" style="height:200px;overflow:auto">
                                    <script style="width:850px;150px;" type="text/plain" name="answer" id="E_edit2" type="text/plain"></script>
                                </div>
                                <div class="tab-pane" id="tab_analysis" style="height:200px;overflow:auto">
                                    <script style="width:850px;150px;" type="text/plain" name="analysis" id="E_edit3" type="text/plain"></script>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-primary" id="confirm_xiti">保存</a>
                    <a class="btn btn-default" id="cancel_shiti">取消</a>
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

            $('#xiti_type').select2();


            $('#get_search').click(function () {
                let time = $('#reservation').val();
                time = time.replace(/\//g,'_');
                time = time.replace('-','__');
                let xiti_type = $('#xiti_type').val();
                window.location.href ='{{ route('baidu_manage_xiti') }}'+'/'+time+'/'+xiti_type;
            });



            $('#get_now').click(function () {
                let time = $('#reservation').val();
                time = time.replace(/\//g,'_');
                time = time.replace('-','__');
                let grade_id = $('#grade_id').val();
                let subject_id = $('#subject_id').val();
                let volumes_id = $('#volumes_id').val();
                let version_id = $('#version_id').val();
                let sort_id = $('#sort_id').val();

                window.location.href ='{{ route('baidu_manage') }}'+'/'+time+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id;
            })

            //更新时间段
            $('#get_update').click(function () {
                if($('#get_update').attr('data-on')===1){
                    alert('正在更新中');
                    return false;
                }
                $('#get_update').attr('data-on',1);
                let max_date = $('#max_date').html();
                let now_date = $(this).attr('data-now');
                axios.post('{{ route('baidu_manage_api') }}',{type:'get_information',max_date,now_date}).then(response=>{
                    if(response.data.status===0){
                        alert(response.data.msg);
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });


        });
        window.UEDITOR_HOME_URL = '{{ asset('ueditor') }}/';
    </script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('ueditor/ueditor1.all.js') }}"></script>
    <script src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <script src="{{ asset('ueditor/kityformula-plugin/addKityFormulaDialog.js') }}"></script>
    <script src="{{ asset('ueditor/kityformula-plugin/getKfContent.js') }}"></script>
    <script src="{{ asset('ueditor/kityformula-plugin/defaultFilterFix.js') }}"></script>
    <script>
        let toolbar = {
            toolbars: [[
                'source', '|', 'undo', 'redo',
                'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist','link'
            ]],
        };
        //    var ue1=UE.getEditor('E_add1', toolbar);
        //    var ue2=UE.getEditor('E_add2', toolbar);

        let ue_question = UE.getEditor('E_edit1', toolbar);
        let ue_answer = UE.getEditor('E_edit2', toolbar);
        let ue_analysis = UE.getEditor('E_edit3', toolbar);
        ue_analysis.ready(function () {
            $('#xiti_box_now').draggable({});
            //新增解析
//            $(document).on('click','.add_jiexi',function () {
//                let xiti_id = $(this).attr('data-id');
//                let xiti_type = $(this).attr('data-type');
//                $('#xiti_box_now').attr('data-id',xiti_id).attr('data-type',xiti_type).show();
//            });
            $(document).on('click','.edit_xiti',function () {
                let xiti_id = $(this).attr('data-id');
                let xiti_type = $(this).attr('data-table');
                let now_td = $(this).parent();
                let question_content = now_td.find('.now_question').html();
                let answer_content = '';
                if(now_td.find('.now_answer').length>0){
                    answer_content = now_td.find('.now_answer').html();
                }
                let analysis_content = '';
                if(now_td.find('.now_analysis').length>0){
                    analysis_content = now_td.find('.now_analysis').html();
                }

                $('#xiti_box_now').attr('data-id',xiti_id).attr('data-type',xiti_type).show();
                ue_question.setContent(question_content);
                ue_answer.setContent(answer_content);
                ue_analysis.setContent(analysis_content);
            });
            //保存
            $(document).on('click','#confirm_xiti',function () {
                let xiti_id = $('#xiti_box_now').attr('data-id');
                let xiti_type = $('#xiti_box_now').attr('data-type');
                let question = ue_question.getContent();
                let answer = ue_answer.getContent();
                let analysis = ue_analysis.getContent();
                console.log(xiti_id,xiti_type,question.length,answer.length);
                if(xiti_id && xiti_type && question.length>0 && answer.length>0){
                    axios.post('{{ route('baidu_add_xiti') }}',{xiti_id,xiti_type,question,answer,analysis}).then(response=>{
                        if(response.data.status===1){
                            $('#xiti_box_now').removeAttr('data-id').removeAttr('data-type').hide();
                            let now_btn = $(`.edit_xiti[data-id=${xiti_id}][data-type=${xiti_type}]`)
                            let now_td = now_btn.parent();

                            now_td.find('.now_question').html(question);
                            now_td.find('.now_answer').html(answer);
                            if(now_td.find('.analysis')){
                                now_td.find('.now_analysis').html(analysis);
                            }else{
                                now_btn.before(`
                            <div class="analysis callout callout-success">
                                                <p>解析：</p>
                                               <div class="now_analysis">${analysis}</div>
                            </div>`);
                            }
                        }
                        alert(response.data.msg);
                    }).catch(function (e) {
                        console.log(e);
                    })
                }


            });
            //取消
            $(document).on('click','#cancel_shiti',function () {
                $('#xiti_box_now').removeAttr('data-id').removeAttr('data-type').hide();
                ue_analysis.setContent('');
            });



        });




    </script>



@endpush