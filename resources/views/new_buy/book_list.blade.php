@extends('layouts.backend')

@section('new_buy_analyze','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush


@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">全书总览</li>
        </ol>
    </section>
    <section class="content">
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
           <div class="col-md-12">
                <div class="input-group col-md-6">
                    <select id="sort_id" class="form-control sort_name click_to">
                        <option value="{{ cache('all_sort_now')->where("id",$data['sort'])->first()->id }}">
                            @php
                                $name=cache('all_sort_now')->where("id",$data['sort'])->first()->name
                            @endphp
                            @if($name!='nosort')
                                {{ $name }}
                            @else
                                全部系列
                            @endif
                            </option>
                        <option value="-999">全部系列</option>
                    </select>
                </div>
               <div class="input-group pull-left col-md-3" >
                   <select id="volumes_sel" class="form-control pull-left" style="width:50%">
                       <option value="0">卷册</option>
                       <option value="1">上册</option>
                       <option value="2">下册</option>
                       <option value="3">全一册</option>
                   </select>
            </div>
               <div class="input-group pull-left col-md-3">
                   <input class="form-control" id="search_word" placeholder="练习册名称" type="text" value="" />
                   <a class="input-group-addon btn btn-primary" id="search_book_btn">搜索</a>
               </div>

               <button type="button" class="btn btn-primary" style="margin-left: 20px;" id="AddMark">加入待购买</button>
               <button type="button" class="btn btn-danger" style="margin-left: 20px;" id="DelMark">作废</button>
        </div>

        <div class="box-body">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th><input type="checkbox" onclick="swapCheck()"/>选择</td></th>
                        <th>书名</th>
                        <th>购买状态</th>
                        <th>搜索次数</th>
                        <th>有无答案</th>
                        <th>收藏2018</th>
                        <th>收藏2017</th>
                        <th>收藏2016</th>
                        <th>收藏2015</th>
                    </tr>
                    @foreach($data['list'] as $k=>$v)
                        <tr data-oid="{{$v->id}}">
                            <td><input type="checkbox" class="check"></td>
                            <td>{{ $v->newname }}</td>
                            <td>
                                 @if($v['status']==0)
                                    <a class="get_book_status input-group-addon bg-red disabled">待购买</a>
                                @elseif($v['status']==1)
                                    <a class="get_book_status input-group-addon bg-yellow disabled">已匹配</a>
                                @elseif($v['status']==3)
                                    <a class="get_book_status input-group-addon bg-white disabled">退货</a>
                                @elseif($v['status']==4 || $v['status']==5)
                                    <a class="get_book_status input-group-addon bg-green disabled">已录入</a>
                                @elseif($v['status']==6)
                                    <a class="get_book_status input-group-addon bg-blue disabled">已购买</a>
                                @endif
                            </td>
                            <td>{{ $v->searchnum }}</td>
                            <td>
                                @if($v['answer_status']==1)
                                    有答案
                                @elseif($v['answer_status']==2)
                                    有部分答案
                                @elseif($v['answer_status']==3)
                                   无答案
                                @endif
                            </td>
                            <td>{{ $v->collect2018 }}</td>
                            <td>{{ $v->collect2017 }}</td>
                            <td>{{ $v->collect2016 }}</td>
                            <td>{{ $v->collect2015 }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div>
                {{ $data['list']->links() }}
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
        $(function () {
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

            $('#search_book_btn').click(function () {
                let sort_id=$('#sort_id').val();
                let volumes_id = $('#volumes_sel').val();
                let word = $.trim($('#search_word').val());
                window.location.href='{{ route('book_list') }}'+'/'+sort_id+'/'+volumes_id+'/'+word;
            });



            $('#volumes_sel').val('{{ $data['volumes_id'] }}');
            $('#search_word').val('{{ $data['word'] }}');
        })
    </script>

<script>
    //checkbox 全选/取消全选
    var isCheckAll = false;
    function swapCheck() {
        if (isCheckAll) {
            $("input[type='checkbox']").each(function() {
                this.checked = false;
            });
            isCheckAll = false;
        } else {
            $("input[type='checkbox']").each(function() {
                this.checked = true;
            });
            isCheckAll = true;
        }
    }

    $(function(){
       $("#AddMark").click(function(){
           if(confirm('确定要加入待购买吗?')){
               let checks = $(".check:checked");
               let checkData = new Array();
               checks.each(function(){
                   checkData.push($(this).parents("tr").attr('data-oid'));
               });
               axios.post('{{ route('ajax_book_list') }}',{checkData}).then(response=>{
                   if(response.data.status===1){
                        window.location.reload();
                    }
                });

           }
       });

        $("#DelMark").click(function(){
            if(confirm('确定要作废吗?')){
                let checks = $(".check:checked");
                let checkData = new Array();
                checks.each(function(){
                    checkData.push($(this).parents("tr").attr('data-oid'));
                });
                axios.post('{{ route('ajax_book_list') }}',{checkData}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
@endpush