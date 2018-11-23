@extends('layouts.backend')

@section('book_buy_done')
    active
@endsection

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <div class="modal fade" id="add_new">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    新增练习册
                    <span class="close" data-dismiss="modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="content-box">
                        <div class="input-group">
                            <label class="input-group-addon">练习册名称</label>
                            <input class="form-control" id="new_book_name"/>
                        </div>
                        <select id="new_book_subject" class="form-control">
                            @foreach(config('workbook.subject_1010') as $key=>$value)
                                @if($key>0)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select id="new_book_grade" class="form-control">
                            @foreach(config('workbook.grade') as $key=>$value)
                                @if($key>0)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select id="new_book_volumes" class="form-control">
                            @foreach(config('workbook.volumes') as $key=>$value)
                                @if($key>0)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="input-group">
                        <select id="new_book_version" data-name="version_id" class="version_sel version_sel form-control select2" tabindex="-1" aria-hidden="true">
                            @foreach($data['version'] as $value1)
                                <option value="{{ intval($value1->id) }}">{{ $value1->name }}</option>
                            @endforeach
                        </select>
                            <span class="input-group-addon">版本</span>
                        </div>
                        <div class="input-group">
                            <select id="new_book_press" style="width: 100%;" data-name="press_id" class="form-control press_select">

                            </select>
                            <span class="input-group-addon">出版社</span>
                        </div>
                        <div class="input-group">
                            <select id="new_book_sort" data-name="sort" class="form-control sort_select">

                            </select>
                            <span class="input-group-addon">sort</span>
                        </div>
                        <div class="input-group">
                            <select class="form-control" id="new_book_year">
                                @foreach(config('workbook.book_version') as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-addon">年份</span>
                        </div>
                        <div class="input-group">
                            <input id="new_book_isbn" class="form-control" value=""/>
                            <label class="input-group-addon">isbn</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="new_book_confirm" class="btn btn-primary">新增</a>
                    <a class="btn btn-default" data-dismiss="modal">取消</a>
                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册购买管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 练习册购买管理</h3></div>
            <a id="add_new_btn" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add_new">新增练习册</a>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#has_book" data-toggle="tab">已有书购买情况</a>
                    </li>
                    <li><a id="show_new_book" href="#new_book" data-toggle="tab">新增书购买情况</a></li>
                </ul>
                <div class="tab-content">
                    <div id="has_book" class="tab-pane active">
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>状态</th>
                                    <th>名称</th>
                                    <th>年级</th>
                                    <th>科目</th>
                                    <th>册次</th>
                                    <th>版本</th>
                                    <th>购买人</th>
                                    <th>操作</th>
                                </tr>
                                @if(!empty($data))
                                    @foreach($data['all'] as $value)
                                        <tr data-id="{{ $value->book_id }}">
                                            <td class="status_box">
                                                <label class="label @if($value->status==1) label-info @elseif($value->status==2) label-primary @else label-danger @endif">{{ $data['status'][$value->status] }}</label>
                                            </td>
                                            <td>{{ $value->book_name }}</td>
                                            <td>{{ config('workbook.grade')[intval($value->grade_id)] }}</td>
                                            <td>{{ config('workbook.subject')[intval($value->subject_id)] }}</td>
                                            <td>{{ config('workbook.volumes')[intval($value->volume_id)] }}</td>
                                            <td>{{ $value->version_name }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td class="tool_box">
                                                @if($value->status==1 || $value->status==3)
                                                    <div class="btn-group">
                                                        <button class="btn btn-primary dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown">操作
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                            <li><button class="now_confirm_btn">确认收货</button></li>
                                                            <li><button class="now_change_btn">需要退换</button></li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{ $data['all']->links() }}
                        </div>
                    </div>
                    <div id="new_book" class="tab-pane">
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>状态</th>
                                    <th>名称</th>
                                    <th>年级</th>
                                    <th>科目</th>
                                    <th>册次</th>
                                    <th>版本</th>
                                    <th>系列</th>
                                    <th>购买人</th>
                                    <th>操作</th>
                                </tr>
                                @if(!empty($data['new']))
                                    @foreach($data['new'] as $value)
                                        <tr data-id="{{ $value->id }}">
                                            <td class="status_box">
                                                <label class="label @if($value->status==1) label-info @elseif($value->status==2) label-primary @else label-danger @endif">{{ $data['status'][$value->status] }}</label>
                                            </td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ config('workbook.grade')[intval($value->grade_id)] }}</td>
                                            <td>{{ config('workbook.subject_1010')[intval($value->subject_id)] }}</td>
                                            <td>{{ config('workbook.volumes')[intval($value->volume_id)] }}</td>
                                            <td>{{ $value->version_name }}</td>
                                            <td>{{ $value->sort_name }}</td>
                                            <td>{{ $value->username }}</td>
                                            <td class="tool_box">
                                                @if($value->status==1 || $value->status==3)
                                                    <div class="btn-group">
                                                        <button class="btn btn-primary dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown">操作
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                            <li><button class="now_confirm_btn">确认收货</button></li>
                                                            <li><button class="now_change_btn">需要退换</button></li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{ $data['new']->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    const token = '{{ csrf_token() }}';
    const username = '{{ $data['username'] }}';
    function init_sel2() {
        //获取系列
        $(".sort_select").select2({
            language: "zh-CN",
            ajax: {
                type: 'GET',
                url: "{{ route('workbook_sort') }}",
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
                return '<option value="' + repo.id + '">' + repo.name + '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo){
                return repo.name || repo.text;
            },

        });

        //获取出版社
        $(".press_select").select2({
            language: "zh-CN",
            ajax: {
                type: 'GET',
                url: "{{ route('workbook_press') }}",
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
                return '<option value="' + repo.id + '">' + repo.name +'_'+repo.id+ '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo){
                return repo.name || repo.text;
            },

        });
    }
    $(function(){
        $('.select2').select2();
        $('.now_confirm_btn , .now_change_btn').click(function () {
            let now_type;
            if($(this).hasClass('now_confirm_btn')){
                now_type = 'done'
            }else{
                now_type = 'change'
            }
            let tr_box = $(this).parents('tr');
            let book_id = $(tr_box).attr('data-id');
            let o = {
                _token:token,
                book_id:book_id
            };
            if(now_type=='done'){
                $.post('{{ route('api_book_buy_finish','done') }}',o,function (s) {
                    if(s.status==1){
                        tr_box.find('.status_box').html('<label class="label label-primary">{{ $data['status'][2] }}</label>')
                        tr_box.find('.tool_box').html('');
                    }
                });
            }else{
                $.post('{{ route('api_book_buy_finish','change') }}',o,function (s) {
                    if(s.status==1){
                        tr_box.find('.status_box').html('<label class="label label-danger">{{ $data['status'][3] }}</label>')
                    }
                });
            }
        });


        $('.new_confirm_btn , .new_change_btn').click(function () {
            let now_type;
            if($(this).hasClass('now_confirm_btn')){
                now_type = 'done'
            }else{
                now_type = 'change'
            }
            let tr_box = $(this).parents('tr');
            let new_id = $(tr_box).attr('data-id');

            if(now_type==='done'){
                axios.post('{{ route('api_book_buy_finish','done') }}',{new_id}).then(response=>{
                    if(response.data.status===1){
                        tr_box.find('.status_box').html('<label class="label label-primary">{{ $data['status'][2] }}</label>')
                        tr_box.find('.tool_box').html('');
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            }else{
                $.post('{{ route('api_book_buy_finish','change') }}',o,function (s) {
                    if(s.status==1){
                        tr_box.find('.status_box').html('<label class="label label-danger">{{ $data['status'][3] }}</label>')
                    }
                });
            }
        });

        $('#add_new').on('shown.bs.modal',function () {
            $('#show_new_book').click()
            init_sel2();
        });

        //新增练习册
        $('#new_book_confirm').click(function () {
            let bookname = $('#new_book_name').val();
            let grade_id = $('#new_book_grade').val();
            let grade_text = $('#new_book_grade option:selected').text();
            let subject_id = $('#new_book_subject').val();
            let subject_text = $('#new_book_subject option:selected').text();
            let volume_id = $('#new_book_volumes').val();
            let volume_text = $('#new_book_volumes option:selected').text();
            let version_id = $('#new_book_version').val();
            let version_text = $('#new_book_version option:selected').text();
            let sort_id = $('#new_book_sort').val();
            let sort_text = $('#new_book_sort').select2('data')[0]['name'];
            let isbn = $('#new_book_isbn').val();
            let version_year = $('#new_book_year').val();
            let press_id = $('#new_book_press').val();

            let o = {bookname, grade_id, subject_id, volume_id, version_id, sort_id, isbn, press_id, version_year};
            axios.post('{{ route('api_book_add_new') }}',o).then(response=>{
                if(response.data.status===1){
                    $('#add_new').modal('hide');

                    $('#new_book tbody').append(`<tr data-id="1">
                                            <td class="status_box">
                                                <label class="label  label-info ">等待收货</label>
                                            </td>
                                            <td>${bookname}</td>
                                            <td>${grade_text}</td>
                                            <td>${subject_text}</td>
                                            <td>${volume_text}</td>
                                            <td>${version_text}</td>
                                            <td>${sort_text}</td>
                                            <td>${username}</td>

                                        </tr>`)
                }
            }).catch(function (error) {
                console.log(error);
            });
        });


    });
</script>

@endpush