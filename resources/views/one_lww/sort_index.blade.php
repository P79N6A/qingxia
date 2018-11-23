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
            <!-- 右侧具体内容栏目 -->
            <div id="rightContent">
                <div class="box">
                    <div class="box-header">

                        <div class="col-md-4">
                        <a class="btn @if(!in_array($data['type'],['lww','important'])) btn-primary @else btn-default @endif" href="{{ route('one_lww_sort_index',['all']) }}" >全部系列</a>
                        <a class="btn @if($data['type']==='lww') btn-primary @else btn-default @endif" href="{{ route('one_lww_sort_index',['lww']) }}" >仅限05wang系列</a>
                        <a class="btn @if($data['type']==='important') btn-primary @else btn-default @endif" href="{{ route('one_lww_sort_index',['important']) }}" >重要系列</a>
                        </div>
                        <div class="col-md-8">
                        <select class="select2 form-control sort_name">
                            <option value="-1">选择系列</option>

                        </select>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th style="width: 10px">id</th>
                                <th>系列名</th>
                                <th>关注数</th>
                                <th>管理</th>
                            </tr>
                            @foreach($data['list'] as $v)
            <tr data-id="{{ $v->id }}" data-name="{{ $v->name }}">
                <td>{{ $v->id }}</td>
                <td>{{ $v->name }}</td>
                <td>{{ $v->concern_num }}</td>
                <td>
                    <a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_booklist',[-1,-1,$v->id,-1,-1,-1])  }}">onlyid查看</a>
                    <a type="button" target="_blank" class="btn btn-success" href="{{ route('one_lww_hotbooklist',[-1,-1,$v->id,-1,-1,-1])  }}">热门练习册查看</a>
                    {{--<button type="button" class="btn btn-success change_sort" data-toggle="modal" data-target="#myModal">修改</button>--}}
                    {{--<button type="button" class="btn btn-danger del_sort">删除</button>--}}
                </td>
            </tr>
           @endforeach
            </tbody>
            </table>
            </div>
            <!-- /.box-body -->
            </div>
            {{ $data['list']->links() }}



            </div>
        </div>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    $(function(){
        $('#add_sort').click(function(){
            let sort_name=$('#sort_name').val();
            axios.post('{{ route('one_lww_ajax','add_sort') }}',{sort_name}).then(response=>{
                window.location.reload();
            })
        });

        $('.del_sort').click(function(){
            if(!confirm('确定要删除此系列？')){
                return false;
            }
            let id=$(this).parents('tr').attr('data-id');
            axios.post('{{ route('one_lww_ajax','del_sort') }}',{id}).then(response=>{
                window.location.reload();
            });
        });

        $('.change_sort').click(function(){
            let tr=$(this).parents('tr');
            let id=tr.attr('data-id');
            let sort_name=tr.attr('data-name');
            $('#myModal').attr('data-id',id);
            $('#myModal').find('.new_name').val(sort_name);
        });

        $('#myModal .update_name').click(function(){
            let id=$('#myModal').attr('data-id');
            let sort_name=$('#myModal').find('.new_name').val();
            axios.post('{{ route('one_lww_ajax','update_sort') }}',{id,sort_name}).then(response=>{
                window.location.reload();
            });
        })

        $('.add_city').click(function () {
            let city = $(this).prev().val();
            if(city=='') {
                window.location.href = '{{ route('one_lww_index') }}'
                    }else{
                window.location.href = '{{ route('one_lww_index') }}/' + city + '/{{ $data['order'] }}/{{ $data['asc'] }}'
            }
        })

        //系列
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
        $('.sort_name').change(function () {
            let now_sort = $(this).val();
            if(now_sort!=-1){
                window.location.href = '{{ route('one_lww_booklist') }}/-1/-1/'+now_sort;
            }
        })
    })
</script>
@endpush
