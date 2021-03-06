@extends('layouts.backend')

@section('lww_index','active')


@push('need_css')

<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush


@section('content')

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active"></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div id="rightContent">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">isbn列表</h3>
                        <div class="input-group col-md-6">
                            <label class="input-group-addon">系列</label>
                            @php
                            $now_sort_sql = cache('all_sort_now')->where('id',$data['sort_id'])->first()
                            @endphp
                            <select data-name="sort" class="form-control sort_name select2">
                                <option value="{{ $data['sort_id'] }}" selected>{{ $now_sort_sql?$now_sort_sql->name:'待定' }}</option>
                            </select>
                        </div>

                        <div class="input-group col-md-6">
                            <label class="input-group-addon">地区</label>
                            <input class="form-control searcharea"  value="{{ $data['area'] }}"/>
                            <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="search">搜</button>
                             </span>
                        </div>
                        <div class="input-group col-md-6">
                            <select  class="form-control type">
                                <option value="0" @if($data['type']==0) selected @endif>全部</option>
                                <option value="1" @if($data['type']==1) selected @endif>作业大师有，家教无</option>
                                <option value="2" @if($data['type']==2) selected @endif>作业大师比家教新</option>
                            </select>
                        </div>
                        <a class="btn btn-primary  @if($data['type']==0) active @endif" href="{{ route('isbn_list',[$data['sort_id'],$data['area'],$data['type'],0]) }}">未处理</a>
                        <a class="btn btn-primary  @if($data['type']==1) active @endif" href="{{ route('isbn_list',[$data['sort_id'],$data['area'],$data['type'],1]) }}">已处理</a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>isbn</th>
                                    <th>家教name</th>
                                    <th>作业大师name</th>
                                    <th>地区</th>
                                    <th>系列</th>
                                    <th>搜索量</th>
                                    <th>求助数</th>
                                    <th>操作</th>
                                </tr>
                            @foreach($data['booklist'] as $k=>$v)
                                <tr>
                                    <td>{{ $v['isbn'] }}</td>
                                    <td>{{ $v['jiajiao_name'] }}</td>
                                    <td>{{ $v['zyds_name'] }}</td>
                                    <td>{{ $v['searcharea'] }}</td>
                                    <td>{{ $v['sort_name'] }}</td>
                                    <td>{{ $v['searchnum'] }}</td>
                                    <td>{{ $v->user_book->count() }}</td>
                                    <td><a class="btn btn-primary" href="{{ route('isbn_book_list',$v['isbn']) }}">查看</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                        {{ $data['booklist']->links() }}

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

        $(".sort_name,.type").change(function(){
            var sort=$('.sort_name').val();
            var type=$('.type').val();
            window.location.href = `{{ route('isbn_list') }}/${sort}/{{ $data['area'] }}/${type}/{{ $data['status'] }}`;
        })

        $("#search").click(function(){
            var area=$('.searcharea').val();
            if(area=='') area='全部地区';
            window.location.href = `{{ route('isbn_list') }}/{{ $data['sort_id'] }}/${area}/{{ $data['type'] }}/{{ $data['status'] }}`;
        })
    })
</script>

@endpush