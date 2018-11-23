

@extends('layouts.simple')
@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
@endpush
@push('need_js')
<script src="/adminlte/plugins/autocompleter/jquery.autocompleter.js"></script>
<script src="/adminlte/plugins/layer/layer.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
{{--<script type="text/javascript">
    $(".taobao_index").parent().css("display",'block').parent().addClass("active");
    $(function() {
        $('#sortname').autocompleter({
            highlightMatches: true,
            source: "{{route('getSortByKey')}}",
            //template: '## label ## <span>(## hex ##)</span>',
            hint: true,
            empty: false,
            limit: 10,
            callback: function (value, index, selected) {
                if (selected) {
                    // $("#contain").val(value);
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

        $('body').delegate('.removeid', 'click', function() {
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
                        //subject="1" grade="3"
                        var tddom = $(that).parent().parent().parent().parent();
                        //alert(tddom.html());
                        var subject = tddom.attr("subject");
                        //alert(subject);
                        var grade = tddom.attr("grade");
                        var sortname = $("#sortname").val();
                        var contain = $("#contain").val();
                        var remove = $("#remove").val();
                        var id = "#s_"+subject+"_g_"+grade;
                        $(id).html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                        $.get("{{route('taobao_getBookInfo')}}",
                                {key:sortname,contain:contain,remove:remove,subject:subject,grade:grade},
                                function(data){
                                    $(id).html(data);
                                });
                    }
                });
            }, function(){

            });
        });
        $("body").delegate(".gotolist",'click',function(){
            var that = this;
            var grade = $(this).attr("grade");
            layer.open({
                type: 2,
                title: grade,
                shadeClose: true,
                shade: false,
                maxmin: true,
                area: ['893px', '590px'],
                content:$(that).attr("href"),
                scrollbar: false
            });
            return false;
        });
        $('body').delegate(":checkbox",'change',function () {
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

            });
        });

        $('body').delegate('.shopTop','click',function () {
            var check = $(this).hasClass("btn-info");
            var that = this;
            var shopid = $(this).attr('value');
            var strwidget = $(this).attr('widget');

            //var widget = parseInt(strwidget);
            if(check){
                //widget = widget -1;
            }
            var index = layer.load(1, {
                shade: [0.1,'#999'] //0.1透明度的白色背景
            });

            $.getJSON("{{route('taobao_shopTop')}}",{shopid:shopid,top:strwidget,val:check?0:1},function (data) {
                layer.close(index);
                if(check){

                    $(".shopTop[value='"+shopid+"'][widget='"+strwidget+"']").removeClass('btn-info').addClass('btn-default');
                    //$(that).removeClass('btn-info').addClass('btn-default');
                }else{
                    $(".shopTop[value='"+shopid+"'][widget='"+strwidget+"']").removeClass('btn-default').addClass('btn-info');
                }
                window.location.reload();
            }).fail(function(){
                layer.closeAll();
            });

        });
        $("#remove").focus(function () {
            var val = $(this).val();
            var that = this;
            if($(this).val() ==  ''){
                layer.load(1);
                $.get("{{route('getRemove')}}",{sortname:$('#sortname').val()},function (data) {
                    layer.closeAll();
                    $(that).val(data);
                }).fail(function () {
                    layer.closeAll();
                });
            }
        });

        var navH = $("#box-header").offset().top;
        $(window).scroll(function(){
            var scroH = $(this).scrollTop();
            if(scroH>=navH){
                $("#box-header").css({"position":"fixed"});
            }else if(scroH<navH){
                $("#box-header").css({"position":"relative",'top':"0px",'z-index':"9999"});

            }
        });
        $('body').delegate(".addCart",'click',function(){
            index  = layer.load(1);
            $.getJSON($(this).attr("href"),function (data) {
                layer.close(index);
                if(data.status == 0){
                    layer.msg(data.msg,{icon:5});
                }else{
                    layer.msg(data.msg,{icon:6});
                    window.location.reload();
                }
            });
            return false;
        });
        if($("#sortname").val().length > 0){
            $("#search").trigger("click");
        }

    });


    $("#search").click(function () {
        var sortname = $("#sortname").val();
        var contain = $("#contain").val();
        var remove = $("#remove").val();
        if(sortname.replace(/^\s+|\s+$/gm,'') == ''){
            alert("请选择系列")
            return;
        }
        $(".tddata").html('<div class="overlay"><i class="fa fa-refresh fa-spin hide"></i></div>');
        startsearch(sortname,contain,remove);
    });
    function updatecheck(shopid,check) {
        if(check){
            $("[value='"+shopid+"']").prop("checked",'true');
        }else{
            $("[value='"+shopid+"']").prop("checked",false);
        }
    }
    function startsearch(sortname,contain,remove) {
        /*var subject = [1,2,3,4,5,6];
         var grade = [3,4,5,6,7,8,9];
         for(var i=0;i<subject.length;i++){
         for (var j=0;i<grade.length;i++){

         }
         }*/
        //三年级
        if(remove){
            $.post('{{route('saveRemove')}}',{sortname:sortname,remove:remove,_token:"{{csrf_token()}}"},function (data) {

            });
        }

        @forelse($data['grade_subject_info'] as $grade=>$grade_info)
        @forelse($grade_info as $subject=>$subject_info)
        $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'{{ $subject }}',grade:'{{ $grade }}'},function(data){
            $("#s_{{ $subject }}_g_{{ $grade }}").html(data);
        });
        @endforeach
    @endforeach
}

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


    --}}{{--$(document).on('click','.addCart',function () {--}}{{--
    --}}{{--let only_id = $(this).attr('data-only_id');--}}{{--
    --}}{{--let goods_id = $(this).attr('data-goods_id');--}}{{--
    --}}{{--axios.get(`{{route('taobao_addChart')}}/${goods_id}/${only_id}`).then(response=>{--}}{{--
    --}}{{--if(response.data.status===1){--}}{{--
    --}}{{--window.location.reload();--}}{{--
    --}}{{--}--}}{{--
    --}}{{--})--}}{{--
    --}}{{--});--}}{{--

    //清空发现
    $("#find_clear").click(function(){

    });

</script>--}}
@endpush
@section('content')
    <section class="content-header">


    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-header" class="box-header">
                    <div class="col-sm-4">
                        <div class="input-group">
                                    <span class="input-group-addon">
                                        系列
                                    </span>
                            <input type="text" class="form-control" id="sortname" value="{{$data['sortname']}}" placeholder="系列" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                                    <span class="input-group-addon">
                                        包含
                                    </span>
                            <input type="text" class="form-control" placeholder="包含" value="{{$data['contain']}}" id="contain" />
                        </div>

                    </div>

                    <div class="col-sm-5">
                        <div class="input-group">
                                    <span class="input-group-addon">
                                        排除
                                    </span>
                            <input type="text" class="form-control" placeholder="排除" value="{{$data['remove']}}" id="remove"/>
                            <!--<div class="input-group-addon">
                                <i class="fa fa-search" style="cursor:pointer;" id="search"></i>
                            </div>-->
                            <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="search">搜</button>
                                    </span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" style="float: right; margin-top: 10px;" id="find_clear">清除选中</button>
                </div>

                <div class="box-body table-responsive">
                    {{--<div style="margin-top: 5px;">
                        @forelse($data['all_need_buy_info'] as $need_buy)
                            <a data-id="{{ $need_buy->hasOnlyDetail->id }}" href="#s_{{ $need_buy->hasOnlyDetail->subject_id }}_g_{{ $need_buy->hasOnlyDetail->grade_id }}" class="btn btn-default">{{ $need_buy->hasOnlyDetail->newname }}</a>
                            @endforeach
                    </div>
                    --}}
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 4%;"></th>
                            @foreach(config('workbook.subject_1010') as $key=> $subject)
                                @if($key>0)
                                    <th>{{ $subject }}</th>
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($data['grade_subject_info'] as $grade=>$grade_info)
                            <tr>
                                <td>{{ config('workbook.grade')[$grade] }}</td>
                                @foreach(config('workbook.subject_1010') as $subject=>$subject_info)
                                    @if($subject>0)
                                        <td id="s_{{ $subject }}_g_{{ $grade }}" subject="{{ $subject }}" grade="{{ $grade }}" class="tddata">
                                            @if(!empty($grade_info[$subject]))
                                                <div class="box box-widget">
                                                    <div class="box-header with-border">
                                                        <div class="user-block">
                                                        <span style="float: left;">
                                                            <!--<a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id=2731750157">志鸿优化图书旗舰店</a>-->
                                                                <div class="input-group-btn">
                                                                    <input type="checkbox" class="clear" value="{{ $grade_info[$subject][0]['sortname'].'-'.$grade_info[$subject][0]['subject_id'].'-'.$grade_info[$subject][0]['grade_id'] }}">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">{{$grade_info[$subject][0]['nick']}}
                                                                        <span class="fa fa-caret-down"></span></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a  class="gotolist" href="{{ route("new_bookList",$grade_info[$subject][0]['shopLink']) }}">店铺全部书</a></li>
                                                                        <li><a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$grade_info[$subject][0]['shopLink']}}">淘宝店铺</a></li>
                                                                    </ul>
                                                                    <button value="{{$grade_info[$subject][0]['shopLink']}}" widget="shopTop" type="button" class="shopTop btn  btn-info  ">店铺顶</button>
                                                                    <button value="{{$grade_info[$subject][0]['jiajiao_id']}}" widget="bookTop" type="button" class="shopTop btn  btn-default ">本书顶</button>
                                                                    <!--<button value="2731750157" widget="3" type="button" class="shopTop btn  btn-default ">3</button>-->
                                                                </div>
                                                            <!--
                                                            <input type="checkbox"  checked  value="2731750157" />-->
                                                        </span>
                                                        </div>
                                                        <!-- /.user-block -->
                                                        <div class="box-tools" style="z-index:9999;">
                                                            <button type="button" class="btn btn-box-tool removeid" removeid="{{$grade_info[$subject][0]['detail_url']}}"><i class="fa fa-times"></i></button>
                                                        </div>
                                                        <!-- /.box-tools -->
                                                    </div>
                                                    <div class="box-body">

                                                        <div class="media">
                                                            <div class="media-body">
                                                                <a target="_blank" href="https://item.taobao.com/item.htm?id={{$grade_info[$subject][0]['detail_url']}}" class="ad-click-event">
                                                                    <img src="{{$grade_info[$subject][0]['pic_url']}}_230x230.jpg_.webp" alt="Now UI Kit" class="media-object" style="height: 230px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                                </a>
                                                                <p style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;">
                                                                    {{$grade_info[$subject][0]['title']}}
                                                                </p>

                                                            <span style="color: #F40;font-weight: 700;" class="pull-right text-muted">￥{{$grade_info[$subject][0]['view_price']}}
                                                                    <strong>邮费:￥{{$grade_info[$subject][0]['view_fee']}}</strong>
                                                            </span>
                                                            </div>
                                                        </div>
                                                        <ul class="nav navbar-nav">
                                                            <li>
                                                                <a class="gotolist" grade="{{$grade}}年级" href="{{ route("new_shopList",[$grade_info[$subject][0]['sortname'],$grade_info[$subject][0]['subject_id'],$grade_info[$subject][0]['grade_id'],$data['contain'],$data['remove']])}}">查看全部</a>
                                                            </li>
                                                            <li class="dropdown">
                                                                <a class="dropdown-toggle get_need_buy_books" data-goods-id="{{$grade_info[$subject][0]['detail_url']}}" data-sort="{{$grade_info[$subject][0]['sort']}}" data-subject="{{$grade_info[$subject][0]['subject_id']}}" data-grade="{{$grade_info[$subject][0]['grade_id']}}" data-toggle="dropdown" aria-expanded="false"> 加入待购买</a>
                                                                <ul class="dropdown-menu need_buy_books" role="menu">

                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                             @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('need_js')
<script>
    $(function(){
        $("#search").click(function () {
            var sortname = $("#sortname").val();
            var contain = $("#contain").val();
            var remove = $("#remove").val();
            if(sortname.replace(/^\s+|\s+$/gm,'') == ''){
                alert("请选择系列")
                return;
            }
            window.location.href='{{ route('taobao_buybook') }}'+'/'+sortname+'/'+contain+'/'+remove;
        });

        $("body").delegate(".gotolist",'click',function(){
            var that = this;
            var grade = $(this).attr("grade");
            layer.open({
                type: 2,
                title: grade,
                shadeClose: true,
                shade: false,
                maxmin: true,
                area: ['893px', '590px'],
                content:$(that).attr("href"),
                scrollbar: false
            });
            return false;
        });

        //清除发现
        $("#find_clear").click(function(){
            var vals=[];
            $(".clear:checked").each(function(){
                vals.push($(this).val());
            });
            axios.post('{{ route('findClear') }}',{vals}).then(response=>{
                if(response.data.status===1){
                window.location.reload();
                }
            })
        });



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



    $('body').delegate('.removeid', 'click', function() {
        console.log(11);
         var that = this;
         var removeid = $(this).attr("removeid");
         layer.confirm('确定删除', {
         title : "删除",
         btn: ['确定','取消'] //按钮
         }, function() {
             layer.closeAll();
             var index = layer.load(1, {
                 shade: [0.1, '#999'] //0.1透明度的白色背景
             });
             $.getJSON("{{route('new_hideItem')}}/" + removeid, function (data) {
                 window.location.reload();
             });
         })
    });





</script>
@endpush