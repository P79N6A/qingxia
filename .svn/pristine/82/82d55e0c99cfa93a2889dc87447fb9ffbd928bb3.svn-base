

@extends('layouts.backend')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
@endpush
@push('need_js')
    <script src="/adminlte/plugins/autocompleter/jquery.autocompleter.js"></script>
    <script src="/adminlte/plugins/layer/layer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script type="text/javascript">
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
                    }
                });
                return false;
            });
        });


        $("#search").click(function () {
            var sortname = $("#sortname").val();
            var contain = $("#contain").val();
            var remove = $("#remove").val();
            if(sortname.replace(/^\s+|\s+$/gm,'') == ''){
                alert("请选择系列")
                return;
            }
            $(".tddata").html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
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

            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'3'},function(data){
                $("#s_1_g_3").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'3'},function(data){
                $("#s_2_g_3").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'3'},function(data){
                $("#s_3_g_3").html(data);
            });
            //四年级
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'4'},function(data){
                $("#s_1_g_4").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'4'},function(data){
                $("#s_2_g_4").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'4'},function(data){
                $("#s_3_g_4").html(data);
            });
            //五年级
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'5'},function(data){
                $("#s_1_g_5").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'5'},function(data){
                $("#s_2_g_5").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'5'},function(data){
                $("#s_3_g_5").html(data);
            });
            //六年级
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'6'},function(data){
                $("#s_1_g_6").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'6'},function(data){
                $("#s_2_g_6").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'6'},function(data){
                $("#s_3_g_6").html(data);
            });
            //七年级
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'7'},function(data){
                $("#s_1_g_7").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'7'},function(data){
                $("#s_2_g_7").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'7'},function(data){
                $("#s_3_g_7").html(data);
            });

            //八年级
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'8'},function(data){
                $("#s_1_g_8").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'8'},function(data){
                $("#s_2_g_8").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'8'},function(data){
                $("#s_3_g_8").html(data);
            });
            //九年级
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'1',grade:'9'},function(data){
                $("#s_1_g_9").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'2',grade:'9'},function(data){
                $("#s_2_g_9").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'3',grade:'9'},function(data){
                $("#s_3_g_9").html(data);
            });

            //物理
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'4',grade:'8'},function(data){
                $("#s_4_g_8").html(data);
            });
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'4',grade:'9'},function(data){
                $("#s_4_g_9").html(data);
            });
            //化学
            $.get("{{route('taobao_getBookInfo')}}",{key:sortname,contain:contain,remove:remove,subject:'5',grade:'9'},function(data){
                $("#s_5_g_9").html(data);
            });
        }
    </script>
@endpush
@section('content')
    <section class="content-header">
        <h1>找书<a href="{{ route('tao_cartList') }}">购买详情</a></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">---</li>
        </ol>

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
                            <input type="text" class="form-control" id="sortname" placeholder="系列" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                                    <span class="input-group-addon">
                                        包含
                                    </span>
                            <input type="text" class="form-control" placeholder="包含" value="" id="contain" />
                        </div>

                    </div>

                    <div class="col-sm-5">
                        <div class="input-group">
                                    <span class="input-group-addon">
                                        排除
                                    </span>
                            <input type="text" class="form-control" placeholder="排除" value="" id="remove"/>
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
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 4%;"></th>
                            <th style="width: 32%;">语文</th>
                            <th style="width: 32%;">数学</th>
                            <th style="width: 32%;">英语</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>三年级</td>
                            <td id="s_1_g_3" subject="1" grade="3" class="tddata">
                            </td>
                            <td id="s_2_g_3" subject="2" grade="3" class="tddata">
                            </td>
                            <td id="s_3_g_3" subject="3" grade="3" class="tddata">
                            </td>
                        </tr>
                        <tr>
                            <td>四年级</td>
                            <td id="s_1_g_4" subject="1" grade="4" class="tddata">
                            </td>
                            <td id="s_2_g_4" subject="2" grade="4" class="tddata">
                            </td>
                            <td id="s_3_g_4" subject="3" grade="4" class="tddata">
                            </td>
                        </tr>
                        <tr>
                            <td>五年级</td>
                            <td id="s_1_g_5" subject="1" grade="5" class="tddata">
                            </td>
                            <td id="s_2_g_5" subject="2" grade="5" class="tddata">
                            </td>
                            <td id="s_3_g_5" subject="3" grade="5" class="tddata">
                            </td>
                        </tr>
                        <tr>
                            <td>六年级</td>
                            <td id="s_1_g_6" subject="1" grade="6" class="tddata">
                            </td>
                            <td id="s_2_g_6" subject="2" grade="6" class="tddata">
                            </td>
                            <td id="s_3_g_6" subject="3" grade="6" class="tddata">
                            </td>
                        </tr>

                        <tr>
                            <td>七年级</td>
                            <td id="s_1_g_7" subject="1" grade="7" class="tddata">
                            </td>
                            <td id="s_2_g_7" subject="2" grade="7" class="tddata">
                            </td>
                            <td id="s_3_g_7" subject="3" grade="7" class="tddata">
                            </td>
                        </tr>

                        <tr>
                            <td>八年级</td>
                            <td id="s_1_g_8" subject="1" grade="8" class="tddata">
                            </td>
                            <td id="s_2_g_8" subject="2" grade="8" class="tddata">
                            </td>
                            <td id="s_3_g_8" subject="3" grade="8" class="tddata">
                            </td>
                        </tr>
                        <tr>
                            <td>九年级</td>
                            <td id="s_1_g_9" subject="1" grade="9" class="tddata">
                            </td>
                            <td id="s_2_g_9" subject="2" grade="9" class="tddata">
                            </td>
                            <td id="s_3_g_9" subject="3" grade="9" class="tddata">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 4%"></th>
                            <th style="width: 32%">物理</th>
                            <th style="width: 32%">化学</th>
                            <th style="width: 32%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>八年级</td>
                            <td id="s_4_g_8" subject="4" grade="8" class="tddata"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>九年级</td>
                            <td id="s_4_g_9" subject="4" grade="9" class="tddata"></td>
                            <td id="s_5_g_9" subject="5" grade="9" class="tddata"></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @endsection