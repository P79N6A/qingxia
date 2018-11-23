


@extends('layouts.backend')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
    <style>
        .media-body .H,.media-body .h{
            color: #F40!important;
        }
        /*----------Bright Style Start----------*/

        nav.page-nav-outer {
            display:block;
            text-align: center;
        }
        nav.page-nav-outer .page-nav-inner {
            display: inline-block;
        }

        nav.page-nav-outer .pagination {
            float: left;
            padding-left: 0;
            margin: 15px 0;
            border-radius: 4px;
            display: inline-block;
        }
        nav.page-nav-outer .pagination > li{
            display: inline;
        }
        .pagination > li:first-child > a, .pagination > li:first-child > span {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .pagination > li:last-child > a, .pagination > li:last-child > span {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        nav.page-nav-outer .pagination > li > a {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.4em;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        nav.page-nav-outer .pagination > li a:hover {
            z-index: 2;
            color: #23527c;
            background-color: #eee;
            border-color: #ddd;
        }
        nav.page-nav-outer .pagination > .active > a {
            z-index: 3;
            color: #fff;
            cursor: default;
            background-color: #337ab7;
            border-color: #337ab7;
        }
        nav.page-nav-outer .pagination > .active > a:hover {
            z-index: 3;
            color: #fff;
            cursor: default;
            background-color: #337ab7;
            border-color: #337ab7;
        }
        nav.page-nav-outer .pagination > .disabled > a {
            cursor:no-drop;
        }
        nav.page-nav-outer .pagination > .disabled a:hover {

        }
        nav.page-nav-outer .page-input-box {
            display: inline-block;
            border-radius: 4px;
            margin: 0;
            margin-top: 16px;
            margin-left: 16px;
            float: left;
        }
        nav.page-nav-outer .page-input-box input {
            width: 45px;
            padding: 4px 4px;
            box-sizing: border-box;
            color: #333;
        }
        nav.page-nav-outer .page-input-box button {
            padding: 3px 5px;
            outline:none;
        }

        /*----------Bright Style End----------*/




        /*----------Dark Style Start----------*/

        nav.page-nav-outer.dark {
            text-align: center;
        }
        nav.page-nav-outer.dark .page-nav-inner {
            display: inline-block;
        }
        nav.page-nav-outer.dark .pagination {
            margin-top: 15px;
            margin-bottom: 15px;
            display: inline-block;
            float: left;
        }
        nav.page-nav-outer.dark .pagination > li > a {
            color: #FFF;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.15);
            border-color: rgba(131, 233, 255, 0.75);
        }
        nav.page-nav-outer.dark .pagination > li a:hover {
            background-color: rgba(90, 255, 200, 0.5);
        }
        nav.page-nav-outer.dark .pagination > .active > a {
            color: #FFEB3B;
            border-color: #FFEB3B;
        }
        nav.page-nav-outer.dark .pagination > .active > a:focus {
            background-color: rgba(90, 255, 200, 0.5);
        }
        nav.page-nav-outer.dark .pagination > .disabled > a {
            color: #fff;
            border-color: rgba(131, 233, 255, 0.75);
        }
        nav.page-nav-outer.dark .pagination > .disabled > a:focus {
            background-color: rgba(0, 0, 0, 0.15);
        }
        nav.page-nav-outer.dark .pagination > .disabled a:hover {
            background-color: rgba(0, 0, 0, 0.15);
        }
        nav.page-nav-outer.dark .page-input-box {
            display: inline-block;
            border-radius: 4px;
            margin: 0;
            margin-top: 15px;
            margin-left: 15px;
            float: left;
        }
        nav.page-nav-outer.dark .page-input-box input {
            width: 40px;
            padding: 4px 4px;
            box-sizing: border-box;
            color: #333;
        }
        nav.page-nav-outer.dark .page-input-box button {
            padding: 3px 5px;
        }
        .dark button.btn-green {
            padding: 3px 15px;
            text-decoration: none;
            outline: none;
            border: 1px solid rgba(0, 246, 4, 0.83);
            background: rgba(144, 255, 146, 0.6);
            cursor: pointer;
        }
        .dark button.btn-green:hover {
            color: #ffd600;
            text-decoration: none;
        }
        .dark button.btn-green:active {
            background: rgba(144, 255, 146, 0.4);
        }

        #suggest_ul{
            width:454px;
            margin-top:36px;
            border:1px solid #ccc;
            background-color:#FFFFFF;
            z-index:109;
            display:none;
            position:fixed;
            box-shadow: 1px 1px 3px #ededed;
            -webkit-box-shadow: 1px 1px 3px #ededed;
            -moz-box-shadow: 1px 1px 3px #ededed;
            -o-box-shadow: 1px 1px 3px #ededed;
        }
        .media, .media-body{
            overflow: visible;
        }
        /*----------Dark Style End----------*/
    </style>
@endpush

@section('content')
    <section class="content-header">
        <h1>淘宝搜索</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">淘宝搜索</li>
        </ol>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="background: #ecf0f5">
                    <div class="box-header">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        系列
                                    </span>
                            <input type="text" class="form-control" id="keyword" placeholder="系列" value="{{$key}}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        包含
                                    </span>
                                    <input type="text" class="form-control" placeholder="包含" value="{{$contain}}" id="contain" />
                                </div>

                            </div>

                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        排除
                                    </span>
                            <input type="text" class="form-control" placeholder="排除" value="{{$remove}}" id="remove"/>
                                    <!--<div class="input-group-addon">
                                        <i class="fa fa-search" style="cursor:pointer;" id="search"></i>
                                    </div>-->
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="search">搜</button>
                                    </span>
                                </div>
                            </div>


                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            @forelse($datas["list"] as $key=>$data)
                                            <li class="@if($loop->index == 0) active @endif "><a href="#tab_{{$key}}" data-toggle="tab">@if($key == 999) 其他 @else {{$key}}年级 @endif </a></li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" style="background: #ecf0f5;">

                                                @forelse($datas["list"] as $key=>$data)
                                                <div class="tab-pane @if($loop->index == 0) active @endif " id="tab_{{$key}}">
                                                    <div class="row docs-premium-template">
                                                    @forelse($data as $item)
                                                        <div class="col-sm-6 col-md-3">
                                                            <div class="box box-widget">
                                                                <div class="box-header with-border">
                                                                    <div class="user-block">
                                                                        <span style="float: left;"><a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item->shopLink}}">{{$item->nick}}</a></span>
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
                                                                                {!! $item['title'] !!}
                                                                            </p>
                                                                            <span style="color: #F40;font-weight: 700;" class="pull-right text-muted">￥{{$item->view_price}}
                                                                                @if($item->view_fee == 0)
                                                                                    <div style="background: url(//img.alicdn.com/tps/i3/TB1bh5IMpXXXXacaXXXrG06ZpXX-316-272.png);background-position: -42px -139px;width: 27px;height: 14px; float: right; margin-top: 2px;"></div>
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
                                                @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                        @if($datas["list"] == 0 || count($datas["list"]) == 0) 没有对应的数据  @endif
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
    <script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
    <script src="/adminlte/plugins/autocompleter/jquery.autocompleter.js"></script>
    <script src="/adminlte/plugins/layer/layer.js"></script>
    <script type="text/javascript">
        $(".taobao_index").parent().css("display",'block').parent().addClass("active");

        function PageNavCreate(id,props){
            if(id&&props){
                this.id=id;
                this.pageCount = props.pageCount,
                    this.currentPage = props.currentPage,
                    this.perPageNum = props.perPageNum || 5,
                    this.perPageNum = (this.perPageNum<3 ? 3 : this.perPageNum);//每页按钮数量最小值不能小于3
                this.target = document.getElementById(id);
                this.clickPage = null;
                this.halfPerPage = 3;

            }else{
                console.log("请传入正确参数");
                return false;
            }

            this.target.innerHTML = "";
            $('<div class="page-nav-inner clearfloat">'+
                '<ul class="pagination">'+
                '</ul>'+
                '<div class="page-input-box">'+
                '<input type="text" values=""/>'+
                '<button class="btn-green">Go</button>'+
                '</div>'+
                '</div>').appendTo($(this.target));
            this.pageNavUl =  $(this.target).find("ul.pagination");
            this.pageNavInput = $(this.target).find(".page-input-box");

            //总页数写入placeholder
            this.pageNavInput.children('input').val("").attr({"placeholder":this.pageCount,"max":this.pageCount});

            //若是总页数小于每页按钮数
            if(this.pageCount<=this.perPageNum){
                this.pageNavUl.html("");
                $('<li class="page-nav-first">'+
                    '<a href="javascript:void(null)" aria-label="First page" pagenum="1" >'+
                    '<span aria-hidden="true">&laquo;</span>'+
                    '</a>'+
                    '</li>'+
                    '<li class="page-nav-prev">'+
                    '<a href="javascript:void(null)" aria-label="Previous" pagenum="'+
                    (this.currentPage==1 ? 1 : (this.currentPage-1)) +
                    '" >'+
                    '<span aria-hidden="true">&lt;</span>'+
                    '</a>'+
                    '</li>').appendTo(this.pageNavUl);

                for(var i =1; i<=this.pageCount; i++){
                    $('<li class="pageNum" ><a href="javascript:void(null)"  pagenum="'+i+'" >'+i+'</a></li>').appendTo(this.pageNavUl);
                    if(i == this.currentPage){
                        this.pageNavUl.children("li.pageNum").last().addClass('active');
                    }
                }

                $('<li class="page-nav-next">'+
                    '<a href="javascript:void(null)" aria-label="Last page"  pagenum="'+
                    (this.currentPage==this.pageCount ? this.pageCount : (this.currentPage+1)) +
                    '" >'+
                    '<span aria-hidden="true">&gt;</span>'+
                    '</a>'+
                    '</li>'+
                    '<li class="page-nav-last">'+
                    '<a href="javascript:void(null)" aria-label="Last page"  pagenum="'+this.pageCount+'" >'+
                    '<span aria-hidden="true">&raquo;</span>'+
                    '</a>'+
                    '</li>').appendTo(this.pageNavUl);
            }else{//总页数大于每页按钮数
                //重写一遍翻页按钮 START
                this.pageNavUl.html("");
                $('<li class="page-nav-first">'+
                    '<a href="javascript:void(null)" aria-label="First page" pagenum="1" >'+
                    '<span aria-hidden="true">&laquo;</span>'+
                    '</a>'+
                    '</li>'+
                    '<li class="page-nav-prev">'+
                    '<a href="javascript:void(null)" aria-label="Previous" pagenum="'+
                    (this.currentPage==1 ? 1 : (this.currentPage-1)) +
                    '" >'+
                    '<span aria-hidden="true">&lt;</span>'+
                    '</a>'+
                    '</li>').appendTo(this.pageNavUl);

                for(var i=1; i<=this.perPageNum; i++){
                    $('<li class="pageNum" ><a href="javascript:void(null)"  pagenum="'+i+'" >'+i+'</a></li>').appendTo(this.pageNavUl);
                    if(i == this.currentPage){
                        this.pageNavUl.children("li.pageNum").last().addClass('active');
                    }
                }
                $('<li class="disabled">'+
                    '<a href="javascript:void(null)">...</a>'+
                    '</li>'+
                    '<li class="page-nav-next">'+
                    '<a href="javascript:void(null)" aria-label="Last page"  pagenum="'+
                    (this.currentPage==this.pageCount ? this.pageCount : (this.currentPage+1)) +
                    '" >'+
                    '<span aria-hidden="true">&gt;</span>'+
                    '</a>'+
                    '</li>'+
                    '<li class="page-nav-last">'+
                    '<a href="javascript:void(null)" aria-label="Last page"  pagenum="'+this.pageCount+'" >'+
                    '<span aria-hidden="true">&raquo;</span>'+
                    '</a>'+
                    '</li>').appendTo(this.pageNavUl);
                //重写一遍翻页按钮 END

                //若是目标页小于每页按钮数的一半/有余数+1,偶数+1
                this.halfPerPage = parseInt(this.perPageNum/2)+1;
                this.lastHalfPage = this.perPageNum%2==0 ? (this.perPageNum/2)-1 : parseInt(this.perPageNum/2);
                if(this.currentPage<=this.halfPerPage){
                    this.pageNavUl.children("li.disabled").show();
                    for(var i =0;i<this.perPageNum;i++){
                        this.pageNavUl.children("li.pageNum").eq(i).children('a').attr({"pagenum":i+1}).html(i+1);
                    }
                    this.pageNavUl.children("li.pageNum").removeClass('active').eq(this.currentPage-1).addClass('active');
                    this.pageNavUl.children("li:last-child").children("a").attr({"pagenum":this.pageCount});
                }else if(this.currentPage>=(this.pageCount - this.lastHalfPage)){//若是目标页是倒数每页按钮数一半以内,奇数一半，偶数-1
                    for(var i =0;i<this.perPageNum;i++){
                        this.pageNavUl.children("li.disabled").hide();
                        this.pageNavUl.children("li.pageNum").eq(i).children('a').attr({"pagenum":(this.pageCount-this.perPageNum+1+i)}).html(this.pageCount-this.perPageNum+1+i);
                        if((this.pageCount-this.perPageNum+1+i) == this.currentPage){
                            this.pageNavUl.children("li.pageNum").removeClass('active');
                            this.pageNavUl.children("li.pageNum").eq(i).addClass('active');
                        }
                    }
                    this.pageNavUl.children("li:last-child").children("a").attr({"pagenum":this.pageCount});
                }else{
                    this.pageNavUl.children("li.disabled").show();
                    for(var i =0;i<this.perPageNum;i++){
                        this.pageNavUl.children("li.pageNum").eq(i).children('a').attr({"pagenum":(this.currentPage-parseInt(this.perPageNum/2)+i)}).html(this.currentPage-parseInt(this.perPageNum/2)+i);
                    }
                    this.pageNavUl.children("li.pageNum").removeClass('active').eq(parseInt(this.perPageNum/2)).addClass('active');
                    //this.pageNavUl.children("li:last-child").attr({"pagenum":this.pageCount});
                }
            }

        }
        PageNavCreate.prototype.afterClick = function(func){
            this.pageNavUl.children('li.pageNum').off("click").on("click",function(event){
                if($(this).hasClass('active') != true){
                    var clickPage = parseInt($(this).children('a').attr("pagenum"));
                    //console.log("pageNum = "+clickPage);
                    //翻页按钮点击后触发的回调函数
                    func(clickPage);
                }else{
                    return false;
                }
            });
            this.pageNavUl.children('li.page-nav-first').off("click").on("click",function(event){
                var clickPage = parseInt($(this).children('a').attr("pagenum"));
                //console.log("prev = "+clickPage);
                //翻页按钮点击后触发的回调函数
                func(clickPage);
            });
            this.pageNavUl.children('li.page-nav-prev').off("click").on("click",function(event){
                var clickPage = parseInt($(this).children('a').attr("pagenum"));
                //console.log("prev = "+clickPage);
                //翻页按钮点击后触发的回调函数
                func(clickPage);
            });
            this.pageNavUl.children('li.page-nav-next').off("click").on("click",function(event){
                var clickPage = parseInt($(this).children('a').attr("pagenum"));
                //console.log("prev = "+clickPage);
                //翻页按钮点击后触发的回调函数
                func(clickPage);
            });
            this.pageNavUl.children('li.page-nav-last').off("click").on("click",function(event){
                var clickPage = parseInt($(this).children('a').attr("pagenum"));
                //console.log("next = "+clickPage);
                //翻页按钮点击后触发的回调函数
                func(clickPage);
            });

            this.pageNavInput.children('button').off("click").on("click",function(event){
                var inputVal = parseInt($(this).siblings('input').val());
                var inputMax = parseInt($(this).siblings('input').attr("max"));
                //console.log("button = "+inputVal);
                if(inputVal && inputVal<=inputMax){
                    //翻页按钮点击后触发的回调函数
                    func(inputVal);
                }else{
                    return false;
                }
            });
            this.pageNavInput.children('input').off("keydown").on('keydown', function(event) {
                if(event.which == 13){//若是回车
                    var inputVal = parseInt($(this).val());
                    var inputMax = parseInt($(this).attr("max"));
                    //console.log("input = "+inputVal);
                    if(inputVal && inputVal<=inputMax){
                        //翻页事件触发的回调函数
                        func(inputVal);
                    }else{
                        return false;
                    }
                }
            });

        }
        @if(isset($datas["pager"]))
        pageNavObj = new PageNavCreate("PageNavId",{ pageCount:"{{$datas["pager"]["totalPage"]}}", currentPage:"{{$datas["pager"]["currentPage"]}}", perPageNum:5});
        pageNavObj.afterClick(function(page){
            var s = {{$datas['pager']['pageSize']}} * (page-1);
            //alert(s);
            window.location = "{{route("taobao_book")}}/{{$key}}"+"/"+s;
            //alert(page)
        });
        @endif
        $("#search").click(function () {
            var key = $("#keyword").val();
            var contain = $("#contain").val()?$("#contain").val():"-";
            var remove = $("#remove").val()?$("#remove").val():"-";
            window.location ='{{route("taobao_book")}}'+"/"+key + "/" + contain + "/" + remove;
        });

        $(function() {
            $('#keyword').autocompleter({
                highlightMatches: true,
                source: "{{route('getSortByKey')}}",
                //template: '## label ## <span>(## hex ##)</span>',
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

            if (window.history && window.history.pushState) {
                $(window).on('popstate', function () {
                    window.history.pushState('forward', null, '#');
                    window.history.forward(1);
                    //alert("不可回退");
                });
            }

            window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
            window.history.forward(1);
        });

    </script>
@endpush