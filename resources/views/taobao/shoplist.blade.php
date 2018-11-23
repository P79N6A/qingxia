


@extends('layouts.simple')
@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
@endpush

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row docs-premium-template">
                                    @forelse($data['list'] as $item)
                                        <div class="col-sm-6 col-md-3">
                                            <div class="box box-widget">
                                                <div class="box-header with-border">
                                                    <div class="user-block">
                                                        <span style="float: left;">
                                                            <a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item->shopLink}}">{{$item->nick}}</a>
                                                            <input type="checkbox"
                                                            @if($data['type']=='shop')
                                                                @if($item->shopTop)
                                                                   checked
                                                                   @endif
                                                                    value="{{$item->shopLink}}" data-type="shopTop"/>
                                                            @else
                                                                @if($item->bookTop)
                                                                    checked
                                                                @endif
                                                                 value="{{$item->id}}"  data-type="bookTop"/>
                                                            @endif

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
                                                            <a target="_blank" href="https://item.taobao.com/item.htm?id={{$item['detail_url']}}" class="ad-click-event" >
                                                                <img src="{{$item['pic_url']}}_230x230.jpg_.webp" alt="Now UI Kit" class="media-object" style="height: 230px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                            </a>
                                                            <p style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;">
                                                                {{$item['title']}}
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
                                                            <li><a>本书不在待购买列表</a></li>
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
         /*$('#sortname').autocompleter({
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
                combine:function (params) {
                    //var key = $('#keyword').val();
                    //alert(params.query);
                    return {
                        key: params.query,
                        count: params.limit,
                        //key: key
                    };
                }
            });*/


            $(":checkbox").change(function () {
                var check = $(this).prop("checked");
                var shopid = $(this).val();
                var top=$(this).attr('data-type');
                var index = layer.load(1, {
                    shade: [0.1,'#999'] //0.1透明度的白色背景
                });
                $.getJSON("{{route('new_shopTop')}}",{shopid:shopid,val:check?1:0,top},function (data) {
                    layer.close(index);
                    if(check){
                        $("[value='"+shopid+"']").prop("checked",'true');
                    }else{
                        $("[value='"+shopid+"']").prop("checked",false);
                    }
                    parent.updatecheck(shopid,check);
                });
            });
//            $(".addCart").click(function () {
//                index  = layer.load(1);
//                $.getJSON($(this).attr("href"),function (data) {
//                    layer.close(index);
//                    if(data.status == 0){
//                        layer.msg(data.msg,{icon:5});
//                    }else{
//                        layer.msg(data.msg,{icon:6});
//                        window.location.reload();
//                    }
//                });
//                return false;
//            });

       })
    </script>
@endpush