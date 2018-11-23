@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-hotspotter.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-ui-1.9.2.custom.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/pageeditor/editor.css') }}">
<link rel="stylesheet" href="{{ asset('css/daan.css') }}">
<style>
    .fenye {
        float: left;
        margin-bottom: 15px;
        clear: both;
        height: auto;
        position: relative;
        left: 5%;
    }

    .fenye p {
        color: #6D6B6B;
        clear: both;
    }

    .fenye p.pg a {
        background-color: #49B3C8;
        border: 1px solid #2D9FB3;
        color: #FFFFFF;
        display: inline;
        float: left;
        height: 26px;
        margin-left: 4px;
        overflow: hidden;
        padding: 0 8px;
        text-decoration: none;
        line-height: 26px;
    }

    .fenye p.pg a.on {
        background-color: #FFFFFF;
        color: #0099CC;
    }

    .timu_page {
        float: right;
        width: 22px;
        margin-right: 18px;
        height: 10px;
    }

    .timu_sort {
        float: left;
        width: 18px;
        height: 10px;
    }
</style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">05网练习册管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 05网练习册管理</h3>
                <a class="btn btn-danger pull-right" href="{{ route('lww_chapter',$data['book_id']) }}">返回</a></div>
            <div class="box-body">
                <div class="ed-top">
                    <span class="spot-options">
                        <button id="clone-btn" title="复制"><span class="btn-icon"><img
                                        src="{{ asset('images/pageeditor/clone.png') }}"/></span></button>
                        <button id="del-btn" title="删除"><span class="btn-icon"><img
                                        src="{{ asset('images/pageeditor/del.png') }}"/></span></button>
                        {{--<button id="show_detail"><span class="btn-icon">预览</span></button>--}}
                    </span>
                </div>

                <div class="ed-center">
                    <div class="edit-area" tabindex="-1">
                        <img id="ed-img"/></div>
                </div>
                <div id="thank-famfamfam">
                    <button id="editorsave">保存编辑</button>
                </div>
                <div class="fenye">
                    <p class="pg">
                        @foreach($data['all_pages'] as $v)
                            <a data-id={{ $v['id'] }} data-page={{ $v['page'] }} data-img={{ $v['img'] }} data-width={{ $v['width'] }} data-height={{ $v['height'] }}>{{ $v['page'] }}</a>
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="box-body">
                <hr>
                <div class="timu_box">
                    <span class="pull-right"><a class="btn btn-danger">一键搜索图片答案</a></span>
                    <table class="table table-bordered">
                        <tbody id="timu_table">
                        <tr>
                            <th>timuid</th>a
                            <th>图片</th>
                            <th>题目</th>
                            <th>答案</th>
                            <th>视频</th>
                            <th>操作</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--题目编辑-->

            <div class='left_box'>
                <div id="timu_box">

                    <div class="xiti" id="m_111" uid="111" qid="111"><p class="xth">序号：<input type="text" class="inorder" value="111" />
                            @php
                            $selectText = '<select name = "question_type" style = "font-size:0.8em;color:#118F99;margin-left:0.8em;">'
                                    . '<option value = "0">题型</option>'
                                    . '<option value = "1">单项选择</option>'
                                    . '<option value = "2">多项选择</option>'
                                    . '<option value = "3">判断题</option>'
                                    . '<option value = "4">填空题</option>'
                                    . '<option value = "5">解答题</option>'
                                    . '</select>';
                            $selectText = preg_replace('/value = "' . 111 . '"/','value = "' . 1 . '" selected = "selected"',$selectText);
                            echo $selectText;
                            @endphp
                            <span>
		                        解题老师：<a href=\"http://bbs.1010jiajiao.com/home.php?mod=space&uid=111&do=index>111</a>

		                    </span>
                        </p>
                        <div class="xtq">
                            <div>
                                <div class="que">111</div>
                                <p class = "uni-answer"><textarea name = "uni-answer" rows = "2" cols = "75" readonly = "readonly">1111</textarea></p>
                                <div class="ans">111</div>
                                <div class = "analysis">111</div>
                                <div class = "remark">111</div>
                            </div>
                        </div>

                    </div>

                </div>

            <div class="floatbox" style="display:none;" id="floatbox_add">
                <div class="tit">
                    <a href="javascript:void(0)" onclick="changeWH(2);">-变窄</a>
                    <a href="javascript:void(0)" onclick="changeWH(1);">+变宽</a>
                    <a href="javascript:void(0)" onclick="changeWH(4);">↓变矮</a>
                    <a href="javascript:void(0)" onclick="changeWH(3);">↑变高</a>
                    <a href="javascript:void(0)" onclick="autoCompose();">自动排版</a>
                    <a href="javascript:void(0)" onclick="$('.auto_search').toggle()">显示/隐藏搜索</a>
                    &nbsp;&nbsp;(Esc键关闭窗口)
                    <i class="close">关闭</i>
                </div>
                <div class="cont">

                    <script type="text/plain" id="E_add1" name='question' style="width:500px;height:150px;">
    <p>这里输入题目</p>
	</script>

                    <script type="text/plain" id="E_add2" name='answer' style="width:500px;height:150px;">
    <p>这里输入答案及解析</p>
	</script>

                </div>

                <div class="auto_search">
                    <form onsubmit="gettimulist('1','2');return false;">
                        <input type="text" name="txtword" value="" placeholder="搜索题目" id='txtword' style='width:260px;height:30px; margin-left:8px;'>
                        <input type="submit" class='form-button' value='搜索 '>

                    </form>
                    <div id="txtbox"></div>
                </div>

            </div>

            <div class="floatbox" id="floatbox_edit" timuid="">
                <div class="tit">&nbsp;&nbsp;小提示：可按Esc键关闭窗口<i class="close">关闭</i></div>

                <div class="cont">
                    <script type="text/plain" id="E_edit1" name='que' style="width:850px;height:150px;"></script>
                    <script type="text/plain" id="E_edit2" name='ans' style="width:850px;height:150px;"></script>
                </div>
                <div id="editbar2"><a href="javascript:void(0)" onclick="t_save()">保存</a> <a href="javascript:void(0)" onclick="t_close('floatbox_edit')">取消</a></div>
            </div>

            <div class="floatbox" id="floatbox_timupic" style="width:1000px; height:300px; overflow:scroll">

                <div class="tit" style="position: fixed;width: inherit;"><i class="close" style="padding-right:20px;">关闭</i><div id="sel_page">
                        选择书本页码：
                        <select name="sel_page"></select>
                    </div></div>


                <img id="timupic" src="images/loading.gif" style="width:100%;margin-top: 30px;" />

            </div>

            <div id="editbar3">
                <br /><a href="javascript:void(0)" onclick="popWin('floatbox_add')">+新增题目</a> <a href="javascript:void(0)" onclick="update_orderid()">更新序号</a>
                {{--<a href="javascript:void(0)" style = "margin-left:24em;" onclick="testOnlineExercise()">在线答题测试</a><br />--}}
                <input type="text" id="ans_added" /><a href="javascript:void(0)" onclick="set_ans_added()">前多少题设置为已添加答案</a><br />

                <p style="text-align:center;"><a href="javascript:void(0)" onclick="t_complete()" style="color:#FF0000">确定本章节题目编辑已经完成</a></p>
            </div>



                <div class="sbox" style="overflow:hidden;" >
                    <div id="bookname" style="border-bottom: none;margin: 0;padding: 10px 5px;">
                        <div class="bkl" style="width: 250px;">
                            <img style="margin: 0 auto" src="1" />
                        </div>
                        <div class="bkr" style="width: 270px;">
                            <p>练习册名称：<span class="b1">1</span></p>
                            <p>年级：2</p>
                            <p>学科：3</p>
                            <p>卷册：4</p>
                            <p>版本：5</p>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="infobox" style="padding:5px; line-height:25px; background-color:#33CC99; color:#FFFFFF;">

                        <a style="float:right; color:#FFEC06;" href="javascript:void(0)" onclick="$(this).parent().hide()">X关闭</a>

                        录题方法及注意事项：<br />
                        1. 点击新增题目，将识别后本章节的题目全部拷贝到上面的区域，先点击“自动排版”可以对题目进行自动排列整理。<br />
                        2. 仔细检查并修正识别错误的文字，识别的原始题目若是文字，录入也要求都是文字，原始题目若有图片，可以用上面自带的截图工具截图。<br />
                        3. 按住Ctrl后点击每个题目结束的地方添加题目；本章节所有题目添加完毕后，将答案拷贝到下面的区域，以同样的方法录入答案，答案会按照顺序一一对应加入题目，录入答案时需要注意答案和题目是否一一对应。如果本章节录入答案过程中网页有刷新，需要填入序号点击“前多少题设置为已添加答案”后继续录入对应答案。录入后的题目可以进行编辑和删除。<br />
                        4. 每个章节录题完成后点击最下面“确定本章节题目编辑已经完成”来标识本章节题目已完善。<br />
                        5. 序号为题目整体排序，章节题目按照序号顺序排列，和题目的题号无关。如果要调整题目顺序，请设置好各个题目序号后点击“更新序号”。<br />
                        6. 先选题型，再录题，相同题型不用重复选择。如果已录入的题目未选择题型，单独选择题型后才能录入答案。<br />
                        <font color="#FCFF2A">7. 注意不能编辑不属于自己录入的题目，如果需要编辑联系管理人员更改。</font><br />
                        8. 录入过程有什么疑问，录错书本或章节，请及时报告。<br />
                        快捷键：Ctrl+开始键=下划线
                    </div>

                </div>
        </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/pageeditor/jquery-hotspotter.min.js') }}"></script>
<script src="{{ asset('js/pageeditor/editor.js') }}"></script>


<script src="{{ asset('ueditor/ueditor.config.js') }}"></script>
<script src="{{ asset('ueditor/ueditor1.all.js') }}"> </script>
<script src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>
<script src="{{ asset('ueditor/kityformula-plugin/addKityFormulaDialog.js') }}"></script>
<script src="{{ asset('ueditor/kityformula-plugin/getKfContent.js') }}"></script>
<script src="{{ asset('ueditor/kityformula-plugin/defaultFilterFix.js') }}"></script>
<script>
    const bookid = '{{ $data['book_id'] }}';
    const chapterid = '{{ $data['chapter_id'] }}';
    const request_url = '{{ route('lww_page_question_about') }}';
</script>
<script type="text/javascript" src="{{ asset('js/workbook1.js').'?v='.time().rand(100,999) }}"></script>

<script>
    var ajaxurl = '{{ route('lww_page_edit') }}';
    $(function () {

        var bookpage = {};
        $(".fenye p a").click(function () {
            if ($(this).attr('class') == 'on') return;
            Draw.indexsort = 1;

            bookpage.id = $(this).attr('data-id');
            bookpage.page = $(this).attr('data-page');
            Draw.page = bookpage.page;
            bookpage.img = $(this).attr('data-img');
            bookpage.width = $(this).attr('data-width');
            bookpage.height = $(this).attr('data-height');
            $(this).attr('class', 'on').siblings().removeClass('on');

            Editor.initNewImage(bookpage.img);//初始化加载图片

            $.getJSON(ajaxurl, {a: 'editorload', bookid: bookid, pageid: bookpage.page}, function (s) {
                $('#timu_table').html('<tr><th>timuid</th><th>图片</th><th>题目</th><th>答案</th><th>视频</th><th>操作</th></tr>');
                if (s.length > 0) {
                    var loadspot = {};
                    //console.log(s);
                    for (var i in s) {
                        var newSpotObj = new Spot("red-spot", s[i].pleft, s[i].ptop);

                        newSpotObj.dim[0] = s[i].pwidth;
                        newSpotObj.dim[1] = s[i].pheight;
                        newSpotObj.$edSpot.width(s[i].pwidth);
                        newSpotObj.$edSpot.height(s[i].pheight);
                        //newSpotObj.$edSpot.html('<input class="timu_sort" value='+s[i].sort+' type="text"><input class="timu_page" value='+s[i].timu_page+' type="text">');
                        Draw.indexsort = s[i].sort;
                        Draw.timu_page = s[i].timu_page;
                        //newSpotObj.$edSpot.css('left', s[i].pleft + 'px');
                        // newSpotObj.$edSpot.css('top', s[i].ptop + 'px');

                        //console.log(newSpotObj);

                        Editor.newSpot(newSpotObj);//加载已保存区域

                        var timu_now = '<tr data-id="'+s[i].timuid+'"><td><strong>' + s[i].timuid + '</strong></td><td class="col-md-3"><a class="thumbnail"><img src="' + s[i].cut_pic + '" alt=""></a></td><td class="for_timu"></td><td class="for_answer"></td><td class="for_video"></td><td><a class="btn btn-xs btn-default add_timu">新增题目</a></td></tr>';
                        $('#timu_table').append(timu_now);

                    }
                    Draw.timu_page = 0;
                    //获取题目及答案
                    axios.post('{{ route('lww_page_question') }}',{bookid: bookid, chapterid:chapterid,pageid: bookpage.page}).then(response=>{
                        let questions = response.data.questions;
                        let question_len = questions.length;
                        for(let i=0;i<question_len;i++){
                            $('tr[data-id="'+questions[i].timuid+'"] .for_timu').html(questions[i].question);
                            $('tr[data-id="'+questions[i].timuid+'"] .for_answer').html(questions[i].answer);
                            $('tr[data-id="'+questions[i].timuid+'"] .add_timu').removeClass('add_timu').addClass('edit_timu').html('编辑题目');
                        }
                        console.log(response);
                    }).catch(function (error) {
                        console.log(error);
                    })
                }
            });



        });


        //编辑题目
        $(document).on('click','.edit_timu',function () {
            var timuid = $(this).parents('tr').attr('data-id');
            t_edit(timuid);
        });

        //新增题目
        $(document).on('click','.add_timu',function () {
            popWin('floatbox_add');
            if(!document.getElementById("question-info")) {
                var ue2 = document.getElementById("E_add2");
                var questionInfoNode = document.createElement("div");
                questionInfoNode.id = "question-info";
                questionInfoNode.style.textAlign = "right";
                questionInfoNode.style.color = "#378DD5";
                var options = '<option value = "0">题型</option>'
                        + '<option value = "1">单项选择</option>'
                        + '<option value = "2">多项选择</option>'
                        + '<option value = "3">判断题</option>'
                        + '<option value = "4">填空题</option>'
                        + '<option value = "5">解答题</option>';
                questionInfoNode.innerHTML = '<select name = "question_type" id="question_type" style = "font-size:0.8em;color:#118F99;margin-right:0.8em;">' + options +'</select>'
                        + '知识点：<input name = "knowledge-point" size = "16" style = "font-size:0.8em;color:#118F99;margin-right:0.8em;" />';
                ue2.parentNode.insertBefore(questionInfoNode,ue2);
            }
        });


        $("#show_detail").click(function () {
            window.open(M_PRE + "book/detail/pageid-" + bookpage.id);
            //console.log(bookpage);
        });

        $("#editorsave").click(function () {
            if (Editor.spotPool.length == 0) {
                alert('请先划好题目区域');
                return;
            }
            var pageid = $(".fenye p a.on").attr('data-id');
            var bookid = '{{ $data['book_id'] }}';
            var cuts = [];
            var img_w = Math.round($("#ed-img").width()), img_h = Math.round($("#ed-img").height());
            for (var i in Editor.spotPool)//框选信息
            {
                var p = Editor.spotPool[i];//console.log(p.$edSpot.find('.timu_sort').val());
                cuts[i] = Math.round(p.coord[0]) + " " + Math.round(p.coord[1]) + " " + Math.round(p.dim[0]) + " " + Math.round(p.dim[1]) + " " + p.$edSpot.find('.timu_sort').val() + " " + p.$edSpot.find('.timu_page').val();//左 上 宽 高 题目排序 题目所属页面
                //console.log(p.coord[0]/img_w,p.coord[1]/img_h,p.dim[0]/img_w,p.dim[1]/img_h);//按照比例
            }
            //console.log(cuts);//return;
            $.getJSON(ajaxurl, {
                a: 'editorsave',
                width: img_w,
                height: img_h,
                bookid: bookid,
                pageid: pageid,
                data: cuts.join(',')
            }, function (s) {
                console.log(s);
            });
            if ($(".fenye p a.on").next().length == 0) alert('本书编辑完成');
            else $(".fenye p a.on").next().click();//保存后自动加载下一页
        });
        $(".fenye p a:eq(0)").click();//默认打开第一页


//        //搜索答案
//        $(document).on('click','.search_pic',function () {
//            let data = new FormData();
//
//            data.append('file', input.files[0]);
//            const userUploadAtt = (File,config) => axios.post("api3.iasku.com/api.php/question/search",File,config);
//            userUploadAtt(data,{headers: {'Content-Type':data.type}}).then((response)=>{
//                this.headPhoto = response.data[0].msg.url;
//            }).catch(()=>{
//
//            })
//        });





    });
    //题目相关
    var toolbar={
        toolbars: [[
            'source', '|','undo','redo',
            'bold', 'italic', 'underline','subscript','superscript', '|', 'forecolor','fontfamily','fontsize','insertimage', '|', 'inserttable','preview', 'spechars','snapscreen','insertorderedlist','insertunorderedlist'
        ]],
    };
    var ue1=UE.getEditor('E_add1', toolbar);
    var ue2=UE.getEditor('E_add2', toolbar);

    var um1 = UE.getEditor('E_edit1',toolbar);
    var um2 = UE.getEditor('E_edit2',toolbar);
    auto_timubar();
</script>
@endpush