@extends('layouts.backend')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/datatables/dataTables.bootstrap.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>购买列表</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">购买列表</li>
        </ol>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">【{{$username}}】购买列表</h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="isbn_data" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>书名</th>
                                            <th>价格</th>
                                            <th>时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($datas as $data)
                                            <tr>
                                                <td><a class="jumpgoods" href="https://item.taobao.com/item.htm?id={{$data->goodsId}}">{{$data->bookname}}</a></td>
                                                <td>￥<span class="price">{{$data->price}}</span></td>
                                                <td>{{date("Y-m-d h:i",$data->addtime)}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                        共{{$datas->total()}}条数据 &nbsp;&nbsp;&nbsp; 本页合计 <span id="total"></span>
                                        &nbsp;&nbsp;全部合计：￥{{$total}}
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                        {{$datas->links()}}

                                    </div>
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
    <script type="text/javascript">
        $(".isbn_chart_index").parent().css("display",'block').parent().addClass("active");
        var total = 0.00;
        $('.price').each(function (i,e) {
            var price = $(e).text();
            price = parseFloat(price)
            total = total + price;
        });
        $("#total").text("￥" + total.toFixed(2));
        $(".jumpgoods").click(function () {
            layer.open({
                type: 2,
                title: $(this).text(),
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['100%', '600px'],
                content: $(this).attr("href")
            });
            return false;
        });
    </script>
@endpush