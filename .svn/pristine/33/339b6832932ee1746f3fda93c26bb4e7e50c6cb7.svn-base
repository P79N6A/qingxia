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
                <strong>统计数据(<b class="label label-info" id="min_date">{{ $data['min'] }}</b>-<b class="label label-info" id="max_date">{{ $data['max'] }}</b>)<a class="btn btn-danger btn-xs" id="get_update" data-now="{{ date('Y-m-d',time()) }}">更新</a></strong>

            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="{{ route('baidu_manage',[$data['time_range']]) }}">答案</a></li>
                    <li><a href="{{ route('baidu_question_no_answer') }}">无答案题目</a></li>
                    <li><a href="{{ route('baidu_manage_question',[$data['time_range']]) }}">题目</a></li>
                    <li><a href="{{ route('baidu_manage_xiti',[$data['time_range']]) }}">习题</a></li>
                    <li class="active"><a href="#" class="dropdown-toggle" data-toggle="dropdown" >文章<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('baidu_manage_portal',[$data['time_range']]) }}">热门文章</a></li>
                            <li class="active"><a href="{{ route('baidu_new_portal') }}">新建文章</a></li>
                        </ul>
                    </li>
                </ul>
                <a class="btn btn-danger edit_portal" data-id="0">新增文章</a>
                <div class="tab-content">
                    <div class="tab-pane" id="tab_1">

                    </div>
                    <div class="tab-pane active" id="tab_2">
                        <div class="box-body">
                            <div class="input-group " style="width:30%">

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
                                    <th>文章标题</th>
                                    <th>文章描述</th>
                                    <th style="width: 30%">新增时间</th>
                                </tr>
                                @forelse($data['all_des'] as $key=>$value)
                                    <tr>
                                        <td class="title">{!! $value->title !!}</td>
                                        <td class="shiti_all_info">
                                            <a target="_blank" href="http://www.1010jiajiao.com/qx_portal/doc/{{ $value->id }}.html">http://www.1010jiajiao.com/qx_portal/doc/{{ $value->id }}.html</a>
                                            <div class="des">{!! $value->des !!}</div>
                                            <div class="content hide">{!! $data['all_content']->where('id',$value->id)->first()->content !!}</div>
                                            <a class="btn btn-danger edit_portal" data-id="{{ $value->id }}">编辑</a>
                                        </td>
                                        <td class="text-center">
                                            <a>{{ date('Y-m-d H:i:s',$value->uptime) }}</a>
                                            <a href="{{ route('baidu_portal_detail',[$value->id,$data['time_range']]) }}" target="_blank" class="btn btn-danger">查看具体统计信息</a>

                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            {{ $data['all_des']->links() }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="panel panel-primary" id="shiti_box_now" style="overflow: auto;z-index:9;width:900px;display: none;position: fixed;bottom: 20px;right: 30px;">
                <div class="panel-body" style="overflow: auto">
                    <div style="height: 200px;overflow: auto">
                        <script style="width:850px;" name="question" id="E_edit1" type="text/plain"></script>
                        </div>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_answer" data-toggle="tab">描述</a></li>
                        <li><a href="#tab_analysis" data-toggle="tab">内容</a></li>
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
                            <a class="btn btn-primary" id="confirm_shiti">保存</a>
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

                                $('#shiti_type').select2();


                                $('#get_search').click(function () {
                                    let time = $('#reservation').val();
                                    time = time.replace(/\//g,'_');
                                    time = time.replace('-','__');
                                    let shiti_type = $('#shiti_type').val();
                                    window.location.href ='{{ route('baidu_new_portal') }}'+'/'+time+'/'+shiti_type;
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
                                $('#shiti_box_now').draggable({});
                                //新增解析
//            $(document).on('click','.add_jiexi',function () {
//                let now_id = $(this).attr('data-id');
//                let shiti_type = $(this).attr('data-type');
//                $('#shiti_box_now').attr('data-id',now_id).attr('data-type',shiti_type).show();
//            });
                                $(document).on('click','.edit_portal',function () {
                                    let now_id = $(this).attr('data-id');
                                    let now_tr = $(this).parent().parent();
                                    let title = now_tr.find('.title').html();
                                    let des = '';
                                    if(now_tr.find('.des').length>0){
                                        des = now_tr.find('.des').html();
                                    }
                                    let content = '';
                                    if(now_tr.find('.content').length>0){
                                        content = now_tr.find('.content').html();
                                    }
                                    $('#shiti_box_now').attr('data-id',now_id).show();
                                    ue_question.setContent(title);
                                    ue_answer.setContent(des);
                                    ue_analysis.setContent(content);
                                });
                                //保存
                                $(document).on('click','#confirm_shiti',function () {
                                    let now_id = $('#shiti_box_now').attr('data-id');
                                    let title = ue_question.getContent();
                                    let des = ue_answer.getContent();
                                    let contents = ue_analysis.getContent();
                                    if(now_id && title && des.length>0 && contents.length>0){
                                        axios.post('{{ route('baidu_add_portal') }}',{now_id,title,des,contents}).then(response=>{
                                            if(response.data.status===1){
                                                $('#shiti_box_now').removeAttr('data-id').removeAttr('data-type').hide();
                                                let now_btn = $(`.edit_portal[data-id=${now_id}]`)
                                                let now_td = now_btn.parent();

                                                now_td.find('.title').html(title);
                                                now_td.find('.des').html(des);
                                                now_td.find('.content').html(contents);
                                            }
                                            alert(response.data.msg);
                                        }).catch(function (e) {
                                            console.log(e);
                                        })
                                    }


                                });
                                //取消
                                $(document).on('click','#cancel_shiti',function () {
                                    $('#shiti_box_now').removeAttr('data-id').removeAttr('data-type').hide();
                                    ue_analysis.setContent('');
                                });



                            });




                        </script>
    @endpush