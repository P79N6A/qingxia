@extends('layouts.backend')

@section('manage_new_local_test_answer','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">本地答案整理</li>
        </ol>
    </section>
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
            <div class="input-group" style="width: 50%">
                <select id="sort_id" class="form-control sort_name click_to">
                    <option value="-999">全部系列</option>
                </select>
                <a class="input-group-addon btn btn-primary" id="select_sort">查看</a>
                <a class="input-group-addon btn btn-primary" id="add_sort">新增</a>
            </div>
        </div>

        <div class="box-body">
            <div class="col-md-12">
                @forelse($data['all_sort'] as $sort)
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><i class="badge bg-green">全部</i>{{ $sort->all_num }}<i class="badge bg-red">待处理</i>{{ $sort->not_confirm_num }}</h3>

                                <p>{{ $sort->has_sort?$sort->has_sort->name:"未知系列" }}</p>
                            </div>
                            <a target="_blank" href="{{ route('manage_new_local_test_list',[$sort->sort,'pending']) }}" class="small-box-footer">立即处理<i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>
                {{ $data['all_sort']->links() }}
            </div>

        </div>
    </div>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
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
            $('#select_sort').click(function () {
                let sort_id = $('.sort_name').val();
                if (sort_id === '-999') {
                    return false;
                }
                window.open('{{ route('manage_new_local_test_list') }}' + '/' + sort_id+'/pending');
            });
            //新增系列
            $('#add_sort').click(function () {
                let sort_id = $('.sort_name').val();

                if (sort_id === '-999') {
                    return false;
                }
                let sort_name = $('.sort_name').select2('data')[0].name
                axios.post('{{ route('manage_new_local_test_api','add_new_sort') }}',{sort_id}).then(response=>{
                    if(response.data.status===1){
                        $('.col-md-12').prepend(`<div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><i class="badge bg-green">全部</i>0<i class="badge bg-red">待处理</i>0</h3>
                                <p>${sort_name}</p>
                            </div>
                            <a target="_blank" href="http://www.test2.com/manage_new/workbook_local_test/detail/${sort_id}/pending" class="small-box-footer">立即处理<i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>`)
                    }
                }).catch(function () {

                });
            })
        });
    </script>
@endpush