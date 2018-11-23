


@extends('layouts.simple')
@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
@endpush

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <!--<div class="col-sm-4">
                            <div class="input-group">
                                    <span class="input-group-addon">
                                        系列
                                    </span>
                                <input type="text" class="form-control" id="sortname" placeholder="系列" value="">
                            </div>
                        </div>-->
                        <form action="">
                        <div class="col-sm-6">
                            <div class="input-group">
                                    <span class="input-group-addon">
                                        包含
                                    </span>
                                <input type="text" class="form-control" placeholder="包含" name="contain" value="{{$contain}}" id="contain" />
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                    <span class="input-group-addon">
                                        排除
                                    </span>
                                <input type="text" class="form-control" placeholder="排除" name="remove" value="{{$remove}}" id="remove"/>
                                <!--<div class="input-group-addon">
                                    <i class="fa fa-search" style="cursor:pointer;" id="search"></i>
                                </div>-->
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info btn-flat" id="search">搜</button>
                                </span>
                            </div>
                        </div>
                        </form>

                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row docs-premium-template">
                                    @forelse($data as $item)
                                        <div class="col-sm-6 col-md-3">
                                            <div class="box box-widget">
                                                <div class="box-header with-border">
                                                    <div class="user-block">
                                                        <span style="float: left;">
                                                            <a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item->shopLink}}">{{$item->nick}}</a>
                                                            <input type="checkbox" @if($item->shopTop) checked @endif value="{{$item->shopLink}}" />
                                                        </span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <div class="box-tools">
                                                        <button type="button" class="btn btn-box-tool removeid" removeid="{{$item->detail_url}}"><i class="fa fa-times"></i></button>
                                                    </div>
                                                    <!-- /.box-tools -->
                                                </div>
                                                <div class="box-body">
                                                    <div class="media">
                                                        <div class="media-body">
                                                            <a target="_blank" href="https://item.taobao.com/item.htm?id={{$item->detail_url}}" class="ad-click-event" >
                                                                <img src="{{$item->pic_url}}_230x230.jpg_.webp" alt="Now UI Kit" class="media-object" style="height: 230px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                            </a>
                                                            <p style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;">
                                                                {{$item->raw_title}}
                                                            </p>
                                                             <span style="color: #F40;font-weight: 700;" class="pull-right text-muted">￥{{$item->view_price}}
                                                                @if($item->view_fee == 0)
                                                                    <div style="background: url(//img.alicdn.com/tps/i3/TB1bh5IMpXXXXacaXXXrG06ZpXX-316-272.png);background-position: -42px -139px;width: 27px;height: 14px; float: right; margin-top: 2px;"></div>
                                                                @else
                                                                    <strong>邮费:￥{{$item->view_fee}}</strong>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <ul class="nav navbar-nav">
                                                        <li>
                                                            <?php $record = getRecord($item->detail_url);  ?>
                                                        @if(isset($record) && count($record) > 0)
                                                            <li class="dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"  href="#"> 加入待购买</a>
                                                                <ul class="dropdown-menu" role="menu">

                                                                    @forelse($record as $citem)

                                                                        <li>
                                                                        <a class="addCart" href="{{route('taobao_addChart',['goodsId'=>$citem->detail_url,'jId' => $citem->hasOnly->id])}}">{{$citem->hasOnly->newname}}</a>
                                                                        </li>
                                                                        @endforeach
                                                                </ul>
                                                            </li>
                                                        @else
                                                            <li class="dropdown">
                                                                <a class="dropdown-toggle get_need_buy_books" data-goods-id="{{ $item->detail_url }}" data-sort="{{ $sort }}" data-subject="{{ $subject }}" data-grade="{{ $grade }}" data-toggle="dropdown" aria-expanded="false"> 加入待购买</a>
                                                                <ul class="dropdown-menu need_buy_books" role="menu">

                                                                </ul>
                                                            </li>
                                                        @endif
                                                        </li>
                                                        <li>
                                                            <a class="shopTop" href="{{route('taobao_shopTop')}}" data-id="{{$item->id}}" data-top="{{$item->bookTop}}">@if($item->bookTop)取消置顶@else 置顶本书 @endif</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                     @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <!--
                                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">-->
                                        <nav aria-label="Page navigation" class="page-nav-outer" id="PageNavId"></nav>
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
@push('need_js')
    <script src="/adminlte/plugins/layer/layer.js"></script>
    <script src="/adminlte/plugins/autocompleter/jquery.autocompleter.js"></script>
    <script type="text/javascript">
        $(".removeid").click(function () {
            var that = this;
            var removeid = $(this).attr("removeid");
            layer.confirm('确定删除', {
                title : "删除",
                btn: ['确定','取消'] //按钮
            }, function(){
                layer.closeAll();
                var index = layer.load(1, {
                    shade: [0.1,'#999'] //0.1透明度的白色背景
                });
                $.getJSON("{{route('hideItem')}}/"+removeid,function (data) {
                    layer.close(index);
                    if(data.status == 1){
                        $(that).parent().parent().parent().parent().remove();
                    }
                } );
            }, function(){

            });
        });

        $(function () {
            $('#sortname').autocompleter({
                highlightMatches: true,
                source: "{{route('getSortByKey')}}",
                hint: true,
                empty: false,
                limit: 10,
                callback: function (value, index, selected) {
                    if (selected) {
                        $("#contain").val(value);
                    }
                },
                combine: function (params) {
                    //var key = $('#keyword').val();
                    //alert(params.query);
                    return {
                        key: params.query,
                        count: params.limit,
                        //key: key
                    };
                }
            });
            $(":checkbox").change(function () {
                var check = $(this).prop("checked");
                var shopid = $(this).val();
                var index = layer.load(1, {
                    shade: [0.1, '#999'] //0.1透明度的白色背景
                });
                $.getJSON("{{route('taobao_shopTop')}}", {
                    shopid: shopid,
                    top: 'shopTop',
                    val: check ? 1 : 0
                }, function (data) {
                    layer.close(index);
                    if (check) {
                        $("[value='" + shopid + "']").prop("checked", 'true');
                    } else {
                        $("[value='" + shopid + "']").prop("checked", false);
                    }
                    parent.updatecheck(shopid, check);
                });
            });
            $('body').delegate(".addCart", 'click', function () {
                index = layer.load(1);
                $.getJSON($(this).attr("href"), function (data) {
                    layer.close(index);
                    if (data.status == 0) {
                        layer.msg(data.msg, {icon: 5});
                    } else {
                        layer.msg(data.msg, {icon: 6});
                        window.localtion.reload();
                    }
                });
                return false;
            });
            //http://192.168.0.200/taobaoBook/shopTop?shopid=1572612&top=bookTop&val=1
            $(".shopTop").click(function () {
                var id = $(this).data("id");
                var url = $(this).attr("href");
                var bookTop = parseInt($(this).attr('data-top'));
                index = layer.load(1);
                var that = this;
                $.getJSON(url, {top: "bookTop", shopid: id, val: bookTop ? 0 : 1}, function (data) {
                    layer.close(index);
                    if (data.status == 0) {
                        layer.msg("操作失败", {icon: 5});
                    } else {
                        layer.msg('操作成功', {icon: 6});
                        if (bookTop == 1) {
                            $(that).text("置顶本书")
                            $(that).attr("data-top", "0")
                        } else {
                            $(that).text("取消置顶");
                            $(that).attr("data-top", "1");
                        }
                    }
                });
                return false;
            });

            $(document).on('click','.get_need_buy_books',function () {
                let sort = $(this).attr('data-sort');
                let grade = $(this).attr('data-grade');
                let subject = $(this).attr('data-subject');
                let goods_id = $(this).attr('data-goods-id');

                axios.post('{{ route('ajax_new_buy','get_related_books') }}',{sort,grade,subject}).then(response=>{
                    if(response.data.status===1){
                        let all_data = response.data.data;

                        let now_html = '';
                        for(let item of all_data){
                            console.log(item)
                            now_html += `<li><a class="addCart" data-goods_id="${goods_id}" data-only_id="${item['only_id']}" href="{{route('taobao_addChart')}}/${goods_id}/${item['only_id']}">${item['has_only_detail']['newname']}</a></li>`;
                            console.log(item);
                        }
                        $(this).next().html(now_html);
                    }
                }).catch();
            });
            {{--$(document).on('click','.addCart',function () {--}}
                {{--let only_id = $(this).attr('data-only_id');--}}
                {{--let goods_id = $(this).attr('data-goods_id');--}}
                {{--axios.get(`{{route('taobao_addChart')}}/${goods_id}/${only_id}`).then(response=>{--}}
                    {{--if(response.data.status===1){--}}
                        {{--window.location.reload();--}}
                    {{--}--}}
                {{--})--}}
            {{--});--}}

        });


    </script>
@endpush