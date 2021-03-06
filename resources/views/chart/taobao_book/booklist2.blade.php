


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
                                                            <a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item['attrs']['shoplink']}}">{{$item['attrs']['nick']}}</a>
                                                            <input type="checkbox" @if($item['attrs']['shoptop']) checked @endif value="{{$item['attrs']['shoptop']}}" />
                                                        </span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <div class="box-tools">
                                                        <button type="button" class="btn btn-box-tool removeid" removeid="{{$item['attrs']['detail_url']}}"><i class="fa fa-times"></i></button>
                                                    </div>
                                                    <!-- /.box-tools -->
                                                </div>
                                                <div class="box-body">
                                                    <div class="media">
                                                        <div class="media-body">
                                                            <a target="_blank" href="https://item.taobao.com/item.htm?id={{$item['attrs']['detail_url']}}" class="ad-click-event" >
                                                                <img src="{{$item['attrs']['pic_url']}}_230x230.jpg_.webp" alt="Now UI Kit" class="media-object" style="height: 230px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                            </a>
                                                            <p style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;">
                                                                {{$item['attrs']['raw_title']}}
                                                            </p>
                                                            <span style="color: #F40;font-weight: 700;" class="pull-right text-muted">￥{{$item['attrs']['view_price']}}
                                                                @if($item['attrs']['view_fee'] == 0)
                                                                    <div style="background: url(//img.alicdn.com/tps/i3/TB1bh5IMpXXXXacaXXXrG06ZpXX-316-272.png);background-position: -42px -139px;width: 27px;height: 14px; float: right; margin-top: 2px;"></div>
                                                                @else
                                                                    <strong>邮费:￥{{$item['attrs']['view_fee']}}</strong>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
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
                combine:function (params) {
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
                    shade: [0.1,'#999'] //0.1透明度的白色背景
                });
                $.getJSON("{{route('taobao_shopTop')}}",{shopid:shopid,top:check?1:0},function (data) {
                    layer.close(index);
                    if(check){
                        $("[value='"+shopid+"']").prop("checked",'true');
                    }else{
                        $("[value='"+shopid+"']").prop("checked",false);
                    }
                    parent.updatecheck(shopid,check);
                });
            });

        })
    </script>
@endpush