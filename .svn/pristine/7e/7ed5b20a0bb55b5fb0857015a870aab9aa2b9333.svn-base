@extends('layouts.backend')

@section('book_new_sort')
    active
@endsection

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">sort_name整理</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> sort_name整理</h3>
                <a href="{{ strstr(url()->full(),'page')===false?url()->full().'?flush=1':url()->full().'&flush=1' }}" class="btn btn-danger pull-right">强制刷新</a>
            </div>
            <div class="box-body">
                <div class="input-group" style="width: 50%">
                    <label class="input-group-addon">系列查询</label>
                    <select class="form-control sort_name click_to">

                    </select>
                    <a class="input-group-addon btn btn-primary click_to_btn">查看</a>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    @foreach($data['all_sort'] as $value)
                        <li>
                    <span href="#">
                        <a target="_blank" href="{{ route('book_new_subsort_arrange',[$value->id]) }}" class="btn btn-info">
                            {{ $value->name }}({{ $value->sub_sorts_count }})
                        </a>
                        <br>
                            @forelse($value->sub_sorts as $value1)
                                <a class="btn btn-xs btn-primary" target="_blank" href="{{ route('book_new_subsort_arrange',[$value->id,$value1->id]) }}">{{ $value1->name }}({{ $value1->num }})</a>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
                <div>
                    {{ $data['all_sort']->links() }}
                </div>

            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        //获取系列
        $(".sort_name").select2({
            language: "zh-CN",
            ajax: {
                type: 'GET',
                url: "{{ route('book_new_workbook_api','sort') }}",
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
        //查看指定系列
        $('.click_to_btn').click(function () {
            window.open('{{ route('book_new_subsort_arrange') }}'+'/'+$('.click_to').val());
        });
    </script>
@endpush
