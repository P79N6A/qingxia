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
                {{--<span>--}}
                    {{--<strong>统计数据</strong>--}}
                        {{--<label>Date range:</label>--}}
                        {{--<div class="input-group">--}}
                        {{--<div class="input-group-addon">--}}
                            {{--<i class="fa fa-calendar"></i>--}}
                        {{--</div>--}}
                        {{--<input type="text" class="form-control pull-right" id="reservation">--}}
                        {{--<span id="get_search" class="input-group-addon btn btn-primary">查询</span>--}}
                    {{--</div>--}}
                {{--</span>--}}
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="{{ route('baidu_manage',[$data['time_range']]) }}">答案</a></li>
                    <li><a href="{{ route('baidu_question_no_answer') }}">无答案题目</a></li>
                    <li class="hide"><a href="{{ route('baidu_manage_question',[$data['time_range']]) }}">题目</a></li>
                    <li><a href="{{ route('baidu_manage_xiti',[$data['time_range']]) }}">习题</a></li>
                    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" >文章<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('baidu_manage_portal',[$data['time_range']]) }}">热门文章</a></li>
                                <li><a href="{{ route('baidu_new_portal',[$data['time_range']]) }}">新建文章</a></li>
                            </ul>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body">
						<div>
							@forelse($data['total_sort'] as $sort)
								<a data-id="{{ $sort->sort_id }}" class="get_search_sort btn btn-primary btn-xs">{{ $sort->has_sort?$sort->has_sort->name:'' }}<em class="badge">{{ $sort->all_pv }}</em></a>
							@endforeach
						</div>
                            <div class="input-group pull-left" style="width:20%">
                                <select data-name="grade" id="grade_id"
                                        class="grade_id form-control select2 pull-left" tabindex="-1"
                                        aria-hidden="true">
                                    <option value="0">全部年级</option>
                                    @if($data['grade_id']>0)
                                        <option value="{{ $data['grade_id'] }}" selected>{{ config('workbook.grade')[$data['grade_id']] }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select data-name="subject" id="subject_id" class="subject_id form-control select2"
                                        tabindex="-1" aria-hidden="true">
                                    <option value="0">全部科目</option>
                                    @if($data['subject_id']>0)
                                        <option value="{{ $data['subject_id'] }}" selected>{{ config('workbook.subject_1010')[$data['subject_id']] }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select data-name="volumes" id="volumes_id" class="volumes_id form-control select2">
                                    <option value="0">全部卷册</option>
                                    @if($data['volume_id']>0)
                                        <option value="{{ $data['volume_id'] }}" selected>{{ $data['all_volumes']->where('id',$data['volume_id'])->first()->volumes }}</option>
                                    @endif
                                </select>

                            </div>
                            <div class="input-group pull-left" style="width: 20%">
                                <select data-name="version" id="version_id" class="version_id form-control select2"
                                        tabindex="-1" aria-hidden="true">
                                    <option value="-2">全部版本</option>
                                    @if($data['version_id']>0)
                                        <option value="{{ $data['version_id'] }}" selected>{{ $data['all_version']->where('id',$data['version_id'])->first()->name }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="input-group" style="width: 20%">
                                <select id="sort_id" class="form-control sort_name click_to">
                                    <option value="-999">全部系列</option>
                                    @if($data['sort_id']>-999)
                                        <option value="{{ $data['sort_id'] }}" selected>{{ \App\Sort::where('id',$data['sort_id'])->first()->name }}</option>
                                    @endif
                                </select>
                            </div>
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
                                    <th>练习册名称</th>
									<th>操作</th>
                                    <th>练习册现有情况</th>
                                    <th>练习册信息</th>
                                    <th>访问信息</th>
                                </tr>
                                @forelse($data['total'] as $value)
                                    <tr>
                                        <td>
										@if($value->book_id<10000000)
										<p class="label label-success">{{ $value->has_book?$value->has_book->isbn:'暂无isbn' }}</p>
                                            <p>
                                                <label class="label label-info">{{ $value->has_book?$value->has_book->version_year:'' }}</label><a href="#" aria-expanded="false">{{ $value->book_name }}<em class="badge bg-red">{{ '收藏数:'.$value->has_book->collect_count }}</em><em class="badge bg-red">{{ $value->has_book->has_hd_book?$value->has_book->has_hd_book->concern_num:0 }}</em></a>
                                            </p>
										@else
											<p class="label label-info">{{ $value->has_user_book?$value->has_user_book->isbn:'暂无isbn' }}</p>
										    <a href="#" aria-expanded="false">{{ $value->has_user_book->sort_name }}</a>
										@endif
										
										</td>
										<td>
											<p><a class="btn btn-primary" target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $value->book_id }}.html">查看该练习册</a></p>
												<p><a class="btn btn-success" target="_blank" href="{{ route('audit_answer_detail',$value->book_id) }}">查看该练习册答案</a></p>
												<p><a class="btn btn-danger" target="_blank" href="{{ route('baidu_book_detail',[$value->book_id,$data['time_range']]) }}">查看该练习册统计信息</a></p>
												</td>
                                        <td>
                                            @if($value->book_id<10000000)
                                                <p>关联练习册情况</p>
                                                @forelse($value['related_book'] as $relate_book)
                                                    <p><label class="label label-info">{{ $relate_book->version_year }}</label><a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $relate_book->id }}.html">{{ $relate_book->bookname }}<em class="badge bg-red">{{ $relate_book->collect_count }}</em><em class="badge bg-red">{{ $relate_book->has_hd_book?$relate_book->has_hd_book->concern_num:0 }}</em></a></p>
                                                @endforeach
                                            @else
                                                <p>求助练习册情况</p>
                                                @forelse($value['related_book'] as $relate_book)
                                                    <p><label class="label label-info">{{ $relate_book->version_year }}</label><a>{{ $relate_book->bookname }}<em class="badge bg-red">{{ $relate_book->collect_num }}</em></a></p>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
										<p><a class="btn btn-primary btn-xs">{{ $value->has_sort?$value->has_sort->name:$value->sort_id }}</a></p>
										<a class="btn btn-primary btn-xs">{{ $value->grade_id>0?config('workbook.grade')[$value->grade_id]:$value->grade_id }}</a>
                                            <a class="btn btn-primary btn-xs">{{ $value->subject_id>0?config('workbook.subject_1010')[$value->subject_id]:$value->subject_id }}</a>
                                            <a class="btn btn-primary btn-xs">{{ $data['all_volumes']->where('id',$value->volume_id)->first()?$data['all_volumes']->where('id',$value->volume_id)->first()->volumes:$value->volume_id }}</a>
                                            <a class="btn btn-primary btn-xs">{{ $data['all_version']->where('id',$value->version_id)->first()->name }}</a></td>
                                        <td>
                                            <a class="label label-info">访问次数<em class="badge bg-red">{{ ceil($value->all_pv) }}</em></a>
                                            <a class="label label-info">跳出率<em class="badge bg-red">{{ round($value->bounce_ratio,2) }}%</em></a>
                                            <a class="label label-info">平均访问时长<em class="badge bg-red">{{ round($value->avg_visit_time,2) }}</em></a>
                                            <a class="label label-info">访客数<em class="badge bg-red">{{ ceil($value->all_uv) }}</em></a>
                                            <a class="label label-info">新访客数<em class="badge bg-red">{{ ceil($value->new_uv) }}</em></a>
                                            <a class="label label-info">新访客比例<em class="badge bg-red">{{ round($value->new_visitor_ratio,2) }}%</em></a>
                                            <a class="label label-info">ip数<em class="badge bg-red">{{ $value->ip_count }}</em></a>
                                            <a class="label label-info">贡献浏览量<em class="badge bg-red">{{ $value->out_pv_count }}</em></a>
                                            <a class="label label-info">平均访问页数<em class="badge bg-red">{{ round($value->avg_visit_pages,2) }}</em></a>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            {{ $data['total']->links() }}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_2">

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
            $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['grade_select'] !!} '),});
            $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['subject_select'] !!} '),});
            $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['volume_select'] !!} '),});
            $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});

            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('workbook_sort','sort') }}",
                    dataType: 'json',
                    delay: 100,
                    data: function (params) {
                        return {
                            word: params.term, // search term 请求参数
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items,//itemList
                        };
                    },
                    cache: true
                },

                escapeMarkup: function (markup) {
                    return markup;
                }, // 自定义格式化防止xss注入
                minimumInputLength: 1,//最少输入多少个字符后开始查询
                templateResult: function formatRepo(repo) {
                    if (repo.loading) return repo.text;
                    return '<option value="' + repo.id + '">' + repo.name+'_'+repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });

            $('#get_search').click(function () {
                let type = 'get_information';
                let time = $('#reservation').val();
                time = time.replace(/\//g,'_');
                time = time.replace('-','__');
                let grade_id = $('#grade_id').val();
                let subject_id = $('#subject_id').val();
                let volumes_id = $('#volumes_id').val();
                let version_id = $('#version_id').val();
                let sort_id = $('#sort_id').val();
                window.location.href ='{{ route('baidu_manage') }}'+'/'+time+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id;
            });
			
			$('.get_search_sort').click(function () {
                let type = 'get_information';
                let time = $('#reservation').val();
                time = time.replace(/\//g,'_');
                time = time.replace('-','__');
                let grade_id = $('#grade_id').val();
                let subject_id = $('#subject_id').val();
                let volumes_id = $('#volumes_id').val();
                let version_id = $('#version_id').val();
                let sort_id = $(this).attr('data-id');
                window.open('{{ route('baidu_manage') }}'+'/'+time+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id);
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
    </script>
@endpush