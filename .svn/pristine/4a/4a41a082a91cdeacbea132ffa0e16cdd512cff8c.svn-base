@extends('layouts.backend')

@section('new_buy_analyze','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush


@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">解析记录</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header col-md-12">
                <div class="input-group col-md-6">
                    <select id="sort_id" class="form-control sort_name click_to">
                        @if($data['sort']!=-1)
                            <option selected value="{{ $data['sort'] }}">{{ cache('all_sort_now')->where('id',$data['sort'])->first()->name }}</option>
                        @endif
                        <option value="-999">全部系列</option>
                    </select>
                </div>
                <div class="input-group col-md-3">
                    <select class="select2 form-control subject_select">
                        <option value="-1">未选择</option>
                        @foreach(config('workbook.subject_1010') as $key=>$subject)
                            @if($key>0)
                                <option @if($key==$data['subject_id']) selected @endif value="{{ $key }}">{{ $subject }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>only_id</th>
                        <th>书名</th>
                        <th>收藏情况<a href="{{ route('new_buy_analyze',[$data['sort'],$data['only_id'],$data['subject_id'],'collect']) }}">收藏排序</a></th>
                        <th>练习册信息</th>
                        <th>操作信息</th>
                    </tr>
                    @forelse($data['all_record'] as $record)
                        <tr data-id="{{ $record->only_id }}" data-sort="{{ $record->sort }}">
                            <td>{{ $record->only_id }}</td>
                            <td class="book_info" data-analyze_status="{{ $record->analyze_status }}">
                                <p>{{ $record->hasOnlyDetail?$record->hasOnlyDetail->newname:'' }}</p>
                                <p>
                                    @if($record->analyze_status==0)
                                        <a class="btn btn-primary analyze_btn" data-type="start">开始解析</a>
                                    @endif
                                    @if($record->analyze_status==1)
                                        <a>开始解析时间：{{ $record->analyze_start_at }}</a><br>
                                        <a class="btn btn-success analyze_btn" data-type="end">解析完毕</a>
                                    @endif
                                    @if($record->analyze_status==2)
                                        <a>开始解析时间：{{ $record->analyze_start_at }}</a><br>
                                        <a>解析完成时间：{{ $record->analyze_end_at }}</a>
                                    @endif
                                </p>

                            </td>
                            <td class="collect_status">
                                <a>jj_collect_sum:<em class="badge bg-blue">{{ $record->collect2018+$record->collect2017+$record->collect2016+$record->collect2015+$record->collect2014 }}</em></a>
                                <br>
                                <a>hd_collect_sum:<em class="badge bg-blue">{{ $record->hd2014+$record->hd2015+$record->hd2016 }}</em></a>
                            </td>
                            <td>
                                <div class="input-group">
                                    <a class="input-group-addon">练习册页数</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->book_page }}">
                                    <a class="input-group-addon btn btn-primary save_record" data-type="book_page">保存</a>
                                </div>
                                <br>
                                <div class="input-group">
                                    <a class="input-group-addon">答案页数</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->answer_page }}">
                                    <a class="input-group-addon btn btn-primary save_record" data-type="answer_page">保存</a>
                                </div>
                                <br>
                            </td>

                            <td>
                                <p>操作者：{{ \App\User::find($record->uid)->name }}</p>
                                <p>新增时间：{{ $record->created_at }}</p>
                                @if($record->bought_at)
                                    <p>购买时间：{{ $record->bought_at }}</p>
                                @endif
                                <p></p>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div>
                    {{ $data['all_record']->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            //保存信息
            $(document).on('click','.save_record',function () {
                let now_box = $(this).parents('tr');
               let now_id = now_box.attr('data-id');
               let now_type = $(this).attr('data-type');
               let now_val = $(this).parent().find('.now_val').val();
               let sort = now_box.attr('data-sort');
               console.log(now_val);
               axios.post('{{ route('ajax_new_buy','save_record') }}',{now_id,now_type,now_val}).then(response=>{

               }).catch();
            });

            //选择系列
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
                    return '<option value="' + repo.id + '">' + repo.name + '_' + repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });

            //切换系列
            $(document).on('change','.sort_name',function () {
                let sort_id = $('.sort_name').val();
                if (sort_id === '-999') {
                    return false;
                }
                window.open('{{ route('new_buy_record') }}' + '/' + sort_id);
            });

            //切换科目
            $(document).on('change','.subject_select',function () {
                let sort_id = $('.sort_name').val();
                let subject_id = $('.subject_select').val();
                if (subject_id === '-1') {
                    return false;
                }
                window.location.href = `{{ route('new_buy_record') }}/${sort_id}/-1/${subject_id}`;
            });

            //开始解析，解析完毕
            $(document).on('click','.analyze_btn',function () {
                let only_id = $(this).parents('tr').attr('data-id');
                let analyze_type = $(this).attr('data-type');
                if(!confirm('确认操作?')){
                    return false;
                }
                axios.post('{{ route('ajax_new_buy','analyze_status') }}',{only_id,analyze_type}).then(response=>{

                })
            })

        })
    </script>
@endpush