@extends('layouts.backend')

@section('new_buy_record','active')

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
            <li class="active">退货记录</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header">
                <div class="input-group" style="width: 50%">
                    <select id="sort_id" class="form-control sort_name click_to">
                        <option value="-999">全部系列</option>
                    </select>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>only_id</th>
                        <th>书名</th>
                        <th>购买信息</th>
                        <th>价格</th>
                        <th>操作信息</th>
                    </tr>
                    @forelse($data['all_record'] as $record)
                        <tr data-id="{{ $record->only_id }}" data-sort="{{ $record->sort }}">
                            <td>{{ $record->only_id }}</td>
                            <td>{{ $record->hasOnlyDetail?$record->hasOnlyDetail->newname:'' }}</td>
                            <td>
                                <div class="shop_detail input-group">
                                    <a class="input-group-addon">店铺：</a>
                                    <input type="text" class="form-control" disabled value="@if($record->hasGoodsDetail) {{  $record->hasGoodsDetail->nick}} @endif"/>
                                    @if($record->hasGoodsDetail)
                                        <a class="input-group-addon" target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{ $record->hasGoodsDetail->shopLink }}">查看</a>
                                    @endif
                                </div>
                                <br>
                                <div class="input-group">
                                    <a class="input-group-addon">商品id</a>
                                    <input type="text" class="form-control" value="@if($record->hasGoodsDetail) {{  $record->hasGoodsDetail->detail_url}} @endif">
                                    @if($record->hasGoodsDetail)
                                    <a class="input-group-addon" target="_blank" href="https://item.taobao.com/item.htm?id={{ $record->hasGoodsDetail->detail_url }}">查看</a>
                                    @else
                                        <a class="input-group-addon save_record" data-type="goods_id">保存</a>
                                    @endif
                                </div>
                                <br>

                                <div class="input-group">
                                    <a class="input-group-addon">购买依据</a>
                                    <select class="form-control select2">
                                        <option value="0" @if($record->goods_according_to==0) selected @endif>无</option>
                                        <option value="1" @if($record->goods_according_to==1) selected @endif>提供发票</option>
                                        <option value="2" @if($record->goods_according_to==2) selected @endif>提供收据</option>
                                    </select>
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_according_to">保存</a>
                                </div>
                            </td>
                            <td>
                                <div class="goods_price input-group">
                                    <a class="input-group-addon">价格</a>
                                    <input type="text" class="form-control" value="{{ $record->goods_price }}" />
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_price">保存</a>
                                </div>
                                <br>
                                <div class="goods_fee input-group">
                                    <a class="input-group-addon">运费</a>
                                    <input type="text" class="form-control" value="{{ $record->goods_fee }}" />
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_fee">保存</a>
                                </div>
                            </td>
                            <td>
                                <p>操作者：{{ \App\User::find($record->uid)->name }}</p>
                                <p>新增时间：{{ $record->created_at }}</p>
                                @if($record->bought_at)
                                    <p>购买时间：{{ $record->bought_at }}</p>
                                @endif
                                <p>退货时间：{{ $record->returned_at }}</p>
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
            //保存信息
            $(document).on('click','.save_record',function () {
                let now_box = $(this).parents('tr');
               let now_id = now_box.attr('data-id');
               let now_type = $(this).attr('data-type');
               let now_val = $(this).prev().val();
               let sort = now_box.attr('data-sort');
               axios.post('{{ route('ajax_new_buy','save_record') }}',{now_id,now_type,now_val}).then(response=>{
                    if(now_type==='goods_id' && response.data.status===1){
                        let goods_detail = response.data.data;
                        $(this).after(`<a class="input-group-addon" target="_blank" href="https://item.taobao.com/item.htm?id=${now_val}">查看</a>`);
                        now_box.find('.shop_detail').html(`<a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id=${goods_detail.shopLink}">${goods_detail.nick}</a>`);
                        now_box.find('.goods_price input').val(goods_detail.goods_price);
                        now_box.find('.goods_fee input').val(goods_detail.goods_fee);
                        now_box.find('.buy_status').html(`<div class="input-group">
                                    <a data-status="1" class="get_book_status input-group-addon bg-blue disabled">已匹配</a>
                                                                            <select class="select2 form-control change_buy_status" data-id="${now_id}" data-type="preg">
                                            <option value="-1">改变当前状态</option>
                                            <option value="3">重新匹配</option>
                                            <option value="6">已买</option>
                                        </select>
                                                                    </div>`);
                        now_box.find('.search_status').html(`<p class="text-center"><a target="_blank" href="{{ route('manage_new_local_test_list') }}/${sort}/local_dir/${goods_detail.new_id}"><img src="http://www.test2.com/images/check.png" alt=""></a>
                                                                            </p>`);
                        $(this).remove();
                    }
               }).catch();
            });


            //更改状态
            $(document).on('change','.change_buy_status',function () {
                let now_only_id =  $(this).attr('data-id');
                let now_status = $(this).val();
                let now_type = $(this).attr('data-type');
                if(now_status>0){
                    axios.post('{{ route('ajax_new_buy','change_status') }}',{now_only_id,now_status,now_type}).then(response=>{
                        if(response.data.status===1){
                            let now_html = $(`.change_buy_status[data-id=${now_only_id}]`).parent();
                            if(now_status==='3'){
                                if(now_type==='preg'){
                                    now_html.html(`<a class="input-group-addon bg-yellow-active">已删除</a>`)
                                }else{
                                    now_html.html(`<a class="input-group-addon bg-yellow-active">已退货</a>`)
                                }
                            }else if(now_status==='4'){
                                now_html.html(`<a class="input-group-addon get_book_status bg-purple disabled" data-status="4">已录</a>`)
                            }else if(now_status==='6'){
                                now_html.html(`<a data-status="1" class="get_book_status input-group-addon bg-blue disabled">已买</a>
                                                                            <select class="select2 form-control change_buy_status" data-id="${now_only_id}" data-type="bought">
                                            <option value="-1">改变当前状态</option>
                                            <option value="3">退货并重新购买</option>
                                        </select>`)
                            }else{}
                        }
                    });
                }
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
                window.open('{{ route('new_buy_return') }}' + '/' + sort_id);
            });
        })
    </script>
@endpush