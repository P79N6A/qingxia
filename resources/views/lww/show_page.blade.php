@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
    <link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-hotspotter.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-ui-1.9.2.custom.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/pageeditor/editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daan.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inner.css') }}">
    <style>
        #myvid{
            width: 100%;
            height: 300px;
        }
        #mask{
            width:100%;
            height:100%;
            position:absolute;
            top:0px;
            left:0px;
            display:none;
            z-index:100;
            filter:alpha(opacity=30);
        }
        #win{width:358px;height:200px;border-radius:10px;border:1px solid #a0bcd7;overflow:hidden;margin:auto;position:relative;display:none;z-index:999}
        #title{width:100%;height:18px;background:#63ABE7;line-height:40px;height:40px;}
        #title span{font-size:14px;color:white;font-family:"宋体";font-weight:bold;float:left;margin-left:10px;}
        #title a{margin-top:9px;margin-right:10px;}
        #btn{position:absolute;bottom:0;left:0;height:20px;overflow:hidden;background:#1287cc;height:30px;width:100%;}
        #btn a{float:left; text-align:center;font-size:12px;line-height:30px; text-decoration:none;color:white;width:33%; border-left:1px solid white;}
        #btn a:hover{font-size:14px;font-weight:bold;}
        .answer_now{
            width:20px;
            background-color: red;
        }
        .fenye {
            float: left;
            margin-bottom: 15px;
            clear: both;
            height: auto;
            position: relative;
            left: 5%;
            width: 100%;
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
            height: 20px;
        }

        .timu_sort {
            float: left;
            width: 25px;
            height: 20px;
        }

        #bdcs-search-form {
            display: none;
        }
    </style>
    <style type="text/css">
        .mytoggle {
            border-top-color: #F5F4EF;
        }

        .mytoggle a {
            background-color: #F2F2F2;
        }

        .xiti {
            background-color: #FFFFFF
        }

        .xtq {
            height: auto;
        }

        .que {
            background-color: #FFFFFF;
            padding: 5px;
        }

        .ans {
            padding: 5px;
            border-style: solid;
            border-color: grey;
            border-width: 2px;
            /*background-color: #F6F1FA;*/
            background-color: #ffffff;
        }
        .choice{
            padding: 5px;
            border-style: solid;
            border-color: #802b46;
            border-width: 2px;
            /*background-color: #F6F1FA;*/
            background-color: #ffffff;
        }

        .floatbox {
            position: fixed;
            width: auto;
            height: auto;
            border: 1px solid #ccc;
            background: #efefef;
            display: none;
        }

        .floatbox .tit {
            background: #ddd;
            display: block;
            height: 33px;
            cursor: move;
            line-height: 33px;
            color: #998C64;
        }

        .floatbox .tit a {
            color: #D60C30;
        }

        .floatbox .tit i {
            float: right;
            padding: 0 8px;
            cursor: default;
            color: #AB530A;
        }

        .floatbox .cont {
            height: 519px;
            overflow: scroll;
            float: left;
        }

        .floatbox .auto_search {
            float: right;
            width: 600px;
        }

        .inorder {
            width: 30px;
        }

        .xth span {
            float: right;
            color: #FF0000
        }

        .mytool a {
            color: #D22B2B;
            margin: 3px;
        }

        #txtbox {
            height: 430px;
            overflow-y: scroll;
            width: 600px;
        }

        #txtbox img {
            max-width: 100%;
        }

        .qdp li {
            float: left;
            cursor: pointer;
            margin-left: 5px;
        }

        .qpl li {
            float: left;
            cursor: pointer;
            margin-left: 5px;
        }

        .fenge {
            height: 100px;
            border: 1px solid #FB3030;
            margin: 5px;
        }

        span.fill-in {
            margin: 0 0.2em 0 0;
            padding: 0 1.2em 0.15em;
            border: 0;
            border-bottom: 1px solid #333;
        }

        span.paren {
            padding: 0 0.4em;
            border: 0;
        }

        div#box {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            background: #aaa;
            filter: alpha(opacity=80);
            -moz-opacity: 0.8;
            -khtml-opacity: 0.8;
            opacity: 0.8;
        }

        div#box input.cloze {
        }

        div#exercises {
            display: block;
            width: 90%;
            height: 80%;
        / / margin: 50 px auto;
            background-color: #FFF;
            position: fixed;
            bottom: 10%;
            left: 5%;
            z-index: 50;
            overflow-y: scroll;
        }

        div#exercises li {
            margin-left: 1em;
        }

        #ocr_page {
            margin-left: 20px;
        }

        .question-box {
            display: none;
            max-height: 800px;
            overflow-y: scroll;
        }

        .single-question {
            border: 1px solid #3c8dbc;
            margin: 10px 0;
        }

        .question_bottom {
            border-bottom: 1px solid grey;
            margin: 5px;
        }

        .tab-content {
            max-height: 1000px;
            overflow-y: scroll;
        }
        .ed-top{
                top: 100px;
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

        <div class="modal fade" id="for_upload">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        视频上传<span class="pull-right close" data-dismiss="modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="input-group" style="width:100%">
                            <input id="video_name" style="width: 50%;" class="form-control" placeholder="文件名(必填)" value=""/>
                        </div>
                        <input id="video_descript" class="form-control" placeholder="文件描述(必填)" value=""/>
                        <div id="mask" style="display:none;position:absolute"></div>
                        <div id="win">
                            <div id="title"><span>上传视频</span><a href=JavaScript:; onclick="hideWindows();cancelUpload();" style="float: right">[关闭]</a></div>
                            <div id="btn">
                                <a style="border-left:none;" href="javascript:void(0);" onclick="pauseUpload();">暂停</a>
                                <a href="javascript:void(0);" onclick="resumeUpload();">恢复</a>
                                <a href="javascript:void(0);" onclick="cancelUpload();hideWindows();" >取消</a>
                            </div>

                            <div class="all_percent" style="display:block; width:100%; " id="divstartup">

                                <div class="percent" style="width: 100%;height: 20px;background: rgba(18, 135, 204, 0.95);;margin:5px 0" id="percent"></div>
                                <div class="percent_text" style="color: #000;width: 100%;height: 20px;line-height: 23px;font-weight: normal;text-align: center;left: 0;top: 0;" ><span></span></div>


                                <span id="info"  style="top:280px;left:10px;"></span>

                            </div>
                        </div>
                        <input id="delfileid" name="myvid" type="hidden" value="" />
                        <div class="text-center">
                            <button type="button" class="btn btn-success btn-lg " onclick="initUpload_before()">上传视频</button>
                        </div>
                        <div id="testupload" class="text-center">
                            <button type="button" class="btn btn-success hide btn-lg " onclick="initUpload();">上传视频</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div><a class="btn btn-danger">确认</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="for_uploaded">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        已上传题目视频<span class="pull-right close" data-dismiss="modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        {{--<div class="player" data-id="{{ $value->fileId }}" data-book="{{ $data['ids_books'][$value->fileId] }}" data-status="{{ $data['ids_status'][$value->fileId] }}">--}}
                            {{--<a class="thumbnail show_video">--}}
                                {{--<img src="{{ $value->img }}" title="{{ $value->fileName }}" alt="{{ $value->description }}">--}}
                            {{--</a>--}}
                            {{--<span>--}}
                                {{--@if($data['ids_status'][$value->fileId]==1)--}}
                                    {{--<p class="text-center bg-blue">已上架</p>--}}
                                {{--@else--}}
                                    {{--<p class="text-center bg-red">暂未上架</p>--}}
                                {{--@endif--}}
                                {{--<button class="btn btn-xs btn-success video_play_btn" data-target="#video-modal" data-toggle="modal">播放</button>--}}
                                            {{--<button class="btn btn-xs btn-danger video_del_btn" data-target="#video-modal" data-toggle="modal">删除</button>--}}
                                            {{--<button class="btn btn-xs btn-primary video_modify_btn" data-target="#modify-modal" data-toggle="modal">修改状态</button>--}}
                                        {{--</span>--}}
                        {{--</div>--}}
                    </div>
                    <div class="modal-footer">
                        <div><a class="btn btn-danger">确认</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modify-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        修改状态<span class="pull-right close" data-dismiss="modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-addon">视频名称</span>
                            <input id="video_name_confirm" class="form-control" type="text" value="" />
                        </div>
                        <br />
                        <div class="input-group">
                            <span class="input-group-addon">视频介绍</span>
                            <input id="video_descript_confirm" class="form-control" type="text" value="" />
                        </div>
                        <br />
                        <hr>
                        <label for="show_confirm_modify"></label>
                        <select id="show_confirm_modify" class="select2 form-control" style="width:100%">
                            <option>请选择状态</option>
                            <option value="1">上架</option>
                            <option value="0">不上架</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" id="confirm_modify_btn">确认</a>
                        <a class="btn btn-primary" data-dismiss="modal">取消</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 05网练习册管理</h3>
                <a class="btn btn-danger" id="show_test_timu">查看试题情况</a>
                <a class="btn btn-success" id="show_test_pic">隐藏/展开图片</a>
                <a id="ocr_page" class="btn btn-primary">识别此页面所有题目</a>
                <a class="btn btn-danger pull-right" href="{{ route('lww_chapter',[substr($data['book_id'],0,-3),substr($data['book_id'],-3,2),substr($data['book_id'],-1,1)]) }}">返回</a></div>
            <div class="box-body">
                <span>
                    当前章节名称
                    <strong>{{ $data['chaptername'] }}</strong>
                </span>
                {{--<span id="image_size_box">--}}
                {{--<a id="image_big" class="btn btn-default">图片放大</a>--}}
                {{--<a id="image_small" class="btn btn-default">图片缩小</a>--}}
                {{--<a id="image_cut" class="btn btn-default">统一缩放比例</a>--}}
                {{--</span>--}}
                <div class="row">
                    <div id="max_pic_box" class="col-md-7">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#pic_tab" data-toggle="tab">图片信息</a></li>
                                <li><a href="#answer_tab" data-toggle="tab">答案信息</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="pic_tab">
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
                                <div class="tab-pane" id="answer_tab">
                                    @if($data['all_answers'])
                                        <div id="myCarousel" class="clear carousel slide" data-interval="false">
                                            <div class="carousel-inner">
                                                @foreach($data['all_answers'] as $key => $answer)
                                                    <div class="item @if ($loop->first && $key==1) active @endif">
                                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo"
                                                           data-toggle="modal" data-target="#cover_photo">
                                                            <img class="answer-img img-responsive"
                                                                src="{{ $answer }}"
                                                                 alt="First slide">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a class="carousel-control  left" href="#myCarousel"
                                               data-slide="prev"><i style="left:0;top:0"
                                                                    class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                            <a class="carousel-control right" href="#myCarousel"
                                               data-slide="next"><i style="right:0;top:0"
                                                                    class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                        </div>
                                    @else
                                        <p>暂无相应答案图片</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <div id="max_content_box" class="col-md-5">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#chapter_tab_pic" data-toggle="tab">对应剪切图片</a></li>
                                <li><a href="#chapter_tab_timu" data-toggle="tab">对应章节题目</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="chapter_tab_pic">
                                    <div class="timu_box row">
                                        <div class='left_box col-md-12'>
                                            <span>
                                            <strong>当前章节：<?php echo $data['chapter_id'];?></strong>

                                            </span>
                                            <div id="timu_box"></div>
                                            <div class="floatbox" style="display:none;" id="floatbox_add">
                                                <div class="close_box"><i class="close">关闭</i></div>
                                                <div class="auto_search">
                                                    <form onsubmit="gettimulist('{{ $data['bookinfo']->grade_id }}','{{ $data['bookinfo']->subject_id }}');return false;">
                                                        <input type="text" name="txtword" value="" placeholder="搜索题目"
                                                               id='txtword'
                                                               style='width:260px;height:30px; margin-left:8px;'>
                                                        <input type="submit" class='form-button' value='搜索 '>
                                                        <a class="btn btn-default hide" id="baidu_search">百度搜索</a>
                                                        <a class="btn btn-default" id="lww_search">05网搜索</a>
                                                        <a class="btn btn-default" id="question_search">question搜索</a>
                                                    </form>
                                                    <div id="txtbox"></div>
                                                </div>
                                            </div>

                                            <div class="floatbox" id="floatbox_edit" timuid="">
                                                <div class="tit">&nbsp;&nbsp;小提示：可按Esc键关闭窗口<i class="close">关闭</i></div>
                                                <div class="cont">
                                                    <script id="E_edit1" name="que" style="width:850px;height:150px;"></script>
                                                    <div>
                                                        <select id="question_type" class="select form-control" style="margin: 5px 1px;">
                                                        <option value = "0">题型</option>
                                                        <option value = "1">选择题(如ABCD|A,ABCD|C,判断题如TF|T)</option>
                                                        <option value = "4">填空题(答案直接新建在问题编辑框)</option>
                                                        <option value = "5">解答题(答案直接填入答案编辑框)</option>
                                                        </select>
                                                    </div>
                                                        <div class="nav-tabs-custom">
                                                            <ul class="nav nav-tabs">
                                                                <li class="active"><a href="#tab_1" data-toggle="tab">答案</a></li>
                                                                <li><a href="#tab_2" data-toggle="tab">解析</a></li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="tab_1"> <script type = "text/plain" id="E_edit2" name ='ans' style = "width:850px;height:150px;"></script></div>
                                                            <div class="tab-pane" id="tab_2"> <script type = "text/plain" id="E_edit3" name ='analysis' style = "width:850px;height:150px;"></script></div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div id="editbar2">
                                                    <a class="btn btn-primary" href="javascript:void(0)" onclick="t_save()">保存</a>
                                                    <a class="btn btn-default" href="javascript:void(0)" onclick="t_close('floatbox_edit')">取消</a>
                                                </div>
                                            </div>
                                            <div class="floatbox" id="floatbox_timupic"
                                                 style="width:1000px; height:300px; overflow:scroll">
                                                <div class="tit" style="position: fixed;width: inherit;"><i
                                                            class="close" style="padding-right:20px;">关闭</i>
                                                    <div id="sel_page">
                                                        选择书本页码：
                                                        <select name="sel_page"></select>
                                                    </div>
                                                </div>
                                                <img id="timupic" src="{{ asset('images/loading.gif') }}"
                                                     style="width:100%;margin-top: 30px;"/>
                                            </div>
                                            <div id="editbar3">
                                                {{--<br /><a href="javascript:void(0)" onclick="popWin('floatbox_add')">+新增题目</a> <a href="javascript:void(0)" onclick="update_orderid()">更新序号</a>--}}
                                                {{--<a href="javascript:void(0)" style = "margin-left:24em;" onclick="testOnlineExercise()">在线答题测试</a><br />--}}
                                                {{--<input type="text" id="ans_added" /><a href="javascript:void(0)" onclick="set_ans_added()">前多少题设置为已添加答案</a><br />--}}

                                                {{--<p style="text-align:center;"><a href="javascript:void(0)" onclick="t_complete()" style="color:#FF0000">确定本章节题目编辑已经完成</a></p>--}}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane" id="chapter_tab_timu">
                                    @if(isset($data['chapters']) && !empty($data['chapters']))
                                        <ul class="list-group">
                                            @foreach($data['chapters'] as $key=>$value)
                                                <li class="list-group-item chapter_now" data-id="{{ $value->id }}">
                                                    <a>{{ $value->chapter_name }}</a><em
                                                            class="badge">{{ $value->questions()->count() }}</em>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <hr>

            </div>
        </div>
    </section>
@endsection

@push('need_js')

    <script>window.UEDITOR_HOME_URL = '{{ asset('ueditor') }}/';</script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/pageeditor/jquery-hotspotter.min.js') }}"></script>
    <script src="{{ asset('js/pageeditor/editor.js') }}"></script>
    <script src="{{ asset('js/sdkbase_min.js') }}"></script>
    <script src="{{ asset('ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('ueditor/ueditor1.all.js').'?t='.time() }}"></script>
    <script src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <script src="{{ asset('ueditor/kityformula-plugin/addKityFormulaDialog.js') }}"></script>
    <script src="{{ asset('ueditor/kityformula-plugin/getKfContent.js') }}"></script>
    <script src="{{ asset('ueditor/kityformula-plugin/defaultFilterFix.js') }}"></script>
    <script>
        const bookid = '{{ $data['book_id'] }}';
        const chapterid = '{{ $data['chapter_id'] }}';
        const request_url = '{{ route('lww_page_question_about') }}';
        const token = '{{ csrf_token() }}';
        const uid = '{{ Auth::id() }}';
        {{--//const scale = '{{ $data['bookinfo']->scale }}';--}}

    </script>


    <script>
        var ajaxurl = '{{ route('lww_page_edit') }}';
        let pageid = 1;
        let add_timuid = '';
        //let cut_page_dir = '{{ asset('storage/all_book_pages/'.$data['book_id'].'/cut_pages/') }}';
        let cut_page_dir = '{{ 'http://192.168.0.117/analysis/'.get_bookid_path($data['book_id']).'/cut_pages' }}';
        $(function () {
            $('#ed-img').width('100%');
            var bookpage = {};
            $(".fenye p a").click(function () {
                $('#timu_box').html('');
                pageid = $(this).attr('data-page');
                if ($(this).attr('class') == 'on') return;
                Draw.indexsort = 1;
                if (pageid != 1) {
                    //$('#image_size_box').hide();
                }

                bookpage.id = $(this).attr('data-id');
                bookpage.page = $(this).attr('data-page');
                Draw.page = bookpage.page;
                bookpage.img = $(this).attr('data-img');
                bookpage.width = $(this).attr('data-width');
                bookpage.height = $(this).attr('data-height');
                $(this).attr('class', 'on').siblings().removeClass('on');

                Editor.initNewImage(bookpage.img);//初始化加载图片

                $.getJSON(ajaxurl, {
                    a: 'editorload',
                    bookid: bookid,
                    chapterid: chapterid,
                    pageid: bookpage.page
                }, function (response) {
                    var t = response.timu;

                    var s = response.pos;
                    if (s.length > 0) {
                        var now_img_width = Editor.$edImg.width();
                        var now_img_height = Editor.$edImg.height();
                        var loadspot = {};
                        let now_page_id = $('.pg .on').attr('data-id');
                        for (var i in s) {
                            if(s[i].pageid!=now_page_id){
                                continue;
                            }
                            var newSpotObj = new Spot("red-spot", s[i].pleft * now_img_width, s[i].ptop * now_img_height);
                            newSpotObj.dim[0] = s[i].pwidth * now_img_width;
                            newSpotObj.dim[1] = s[i].pheight * now_img_height;
                            newSpotObj.$edSpot.width(s[i].pwidth * now_img_width);
                            newSpotObj.$edSpot.height(s[i].pheight * now_img_height);

                            Draw.indexsort = s[i].sort;
                            Draw.timu_page = s[i].timu_page;
                            Editor.newSpot(newSpotObj);//加载已保存区域

                        }
                        Draw.timu_page = 0;
                        var timu_now = '';

                        for (let j in t) {
                            let ti_len = 1;
                            let many_ti;
                            if (t[j].length > 1) {
                                many_ti = t[j];
                                ti_len = t[j].length
                            }
                            t[j] = t[j][0];
                            timu_now += `<div class="xiti" data-type="${t[j].question_type}" id="m_${t[j].timuid}" uid="${t[j].uid}" qid="id_1" data-created="${t[j].created_at}" data-vid="${t[j].video_id}"><div class="xtq"><div class="all_detail_box">`;
                            if(t[j].question_type===1){
                                timu_now += `<a class="btn btn-xs btn-primary">选择题</a>`
                            }else if(t[j].question_type===4){
                                timu_now += `<a class="btn btn-xs btn-primary">填空题</a>`
                            }else{
                                timu_now += `<a class="btn btn-xs btn-primary">解答题</a>`
                            }
                            if (ti_len > 1) {
                                for (let k = 0; k < ti_len; k++) {
                                    timu_now += '<a class="thumbnail cut_images"><img src="' + cut_page_dir + '/' + many_ti[k].timu_page + '/' + many_ti[k].sort + '_' + many_ti[k].id + '.jpg" alt=""></a>';
                                }
                            } else {
                                timu_now += '<a class="thumbnail cut_images"><img src="' + cut_page_dir + '/' + t[j].timu_page + '/' + t[j].sort + '_' + t[j].id + '.jpg" alt=""></a>';
                            }
                            if (t[j].question) {
                                timu_now += '<div class="que">' + t[j].question + '</div> ';
                            } else {
                                timu_now += '<div class="que"></div>';
                            }
                            if (t[j].answer_normal) {
                                timu_now += '<p class="uni-answer"><textarea name = "uni-answer" rows = "2" cols = "75" readonly = "readonly">' + t[j].answer_normal + '</textarea></p> ';
                            }
                            if (t[j].answer) {
                                timu_now += '<div class="ans">' + t[j].answer + '</div> ';
                            } else {
                                timu_now += '<div class="ans"></div>';
                            }
                            if (t[j].analysis) {
                                timu_now += '<div class="analysis">' + t[j].analysis + '</div>'
                            }
                            if (t[j].remark) {
                                timu_now += '<div class="remark">' + t[j].remark + '</div>'
                            }
                            timu_now += `<div class="box box-success box-solid collapsed-box for_timu_search_nav"><div class="box-header with-border"><h3 class="box-title">匹配解析</h3><div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                                </div></div><div class="box-body for_jx_box"></div></div>`
                            if(t[j].question_type===1){
                                timu_now += `<div class="choice">${t[j].answer_new}</div>`;
                            }
                            timu_now += '</div> ' +
                                '</div> </div>';
                        }


                        $('#timu_box').append(timu_now);
                        jyload();
                    }

                    auto_timubar();

                    //答案
//                    $('.answer_now').each(function () {
//                        $(this).attr('data-answer',$(this).html());
//                        $(this).html('___');
//                    });

                });
                //缩放
                //$('#ed-img').width($('.pg a:first').attr('data-width')*scale);

            });


            //搜索题目
            $(document).on('click','.for_timu_search_nav [data-widget="collapse"]',function () {
                let now_xiti = $(this).parents('.xiti');
                let now_xiti_id = now_xiti.attr('id');
                let now_search_text = $(this).parents('.xiti').find('.que')[0].textContent;
                $.getJSON(ajaxurl, {
                    a: 'get_timu_jiexi',
                    now_search:now_search_text
                },function (s) {
                    if(s){
                        let now_jiexi =
                            `<div class="nav-tabs-custom"><ul class="nav nav-tabs">`
                        let jiexi_count = s.length
                        for(let i=0;i<jiexi_count;i++){
                            if(i===0){
                                now_jiexi += `<li class="active"><a href="#${now_xiti_id}_jiexi_${i}" data-toggle="tab">${i+1}</a></li>`
                            }else{
                                now_jiexi += `<li><a href="#${now_xiti_id}_jiexi_${i}" data-toggle="tab">${i+1}</a></li>`
                            }

                        }
                        now_jiexi += `</ul><div class="tab-content">`
                        s.forEach( ( jvalue, jindex ) => {
                            if(jindex===0){
                                now_jiexi += `<div class="tab-pane active" id="${now_xiti_id}_jiexi_${jindex}">`
                            }else{
                                now_jiexi += `<div class="tab-pane" id="${now_xiti_id}_jiexi_${jindex}">`
                            }
                            now_jiexi += `<div class="timu_jiexi jiexi_title">${jvalue.title}</div>`
                            now_jiexi += `<div class="bg-red text-center" style="font-size:20px">-----------------解析分割线-------------------</div>`
                            now_jiexi += `<div class="btn-block btn-primary text-center add_search_to_box">将此解析添加到编辑框</div>`
                            now_jiexi += `<div class="timu_jiexi jiexi_parse">${jvalue.parse}</div>`
                            now_jiexi += `</div>`
                        } );

                        now_jiexi += `</div>`

                        now_xiti.find('.for_jx_box').html(now_jiexi);
                    }
                });
            });

            //将搜索出来的解析添加到编辑框
            $(document).on('click','.add_search_to_box',function () {
                let now_parse = $(this).next().html()
                let now_id = $(this).parents('.xiti').attr('id').substring(2);
                t_edit(now_id)
                um3.setContent(now_parse)
            });







            //编辑题目
            $(document).on('click', '.edit_timu', function () {
                var timuid = $(this).parents('tr').attr('data-id');
                t_edit(timuid);
            });
            //新增题目
            $(document).on('click', '.add_timu', function () {
                add_timuid = $(this).parents('.xiti').attr('id').substr(2);

                $('#txtword').val($(this).parents('.xiti').find('.que')[0].textContent);
                popWin('floatbox_add');
            });
            $("#show_detail").click(function () {
                window.open(M_PRE + "book/detail/pageid-" + bookpage.id);
            });
            $("#editorsave").click(function () {
                if (Editor.spotPool.length == 0) {
                    alert('请先划好题目区域');
                    return;
                }
                var now_page_id = $(".fenye p a.on").attr('data-id');
                var pageid = [], timuid = [];

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
                    chapterid: chapterid,
                    now_page_id: now_page_id,
                    pageid: pageid,
                    timuid: timuid,
                    data: cuts.join(',')
                }, function (s) {
                    if (s.suc == 1) {
                        alert('保存成功');
                    } else {
                        alert('保存失败');
                    }
                });
//            if ($(".fenye p a.on").next().length == 0) alert('本书编辑完成');
//            else $(".fenye p a.on").next().click();//保存后自动加载下一页
            });
            $(".fenye p a:eq(0)").click();//默认打开第一页
            //搜索拖动
            $("#floatbox_add").draggable();
            //百度搜索
            $('#baidu_search').click(function () {
                let word = $('#txtword').val();
                if (word.length = 0) {
                    alert('请输入搜索词');
                    return false;
                }
                const txt_box_width = $('#txtbox').width();
                const txt_box_height = $('#txtbox').height();
                $('#txtbox').html(`<iframe width="${txt_box_width}" height="${txt_box_height}" src="https://www.baidu.com/s?ie=UTF-8&wd=${word}"></iframe>`)
            });
            //零五网搜索
            $('#lww_search').click(function () {
                let word = $('#txtword').val();
                if (word.length = 0) {
                    alert('请输入搜索词');
                    return false;
                }
                axios.post(request_url, {type: 'lww_search', word: word}).then(response => {
                    if (response.data.status == 1) {
                        let search_results = response.data.data;
                        let search_results_len = search_results.length;


                        let timu_detail = '';
                        for (let i = 0; i < search_results_len; i++) {
                            timu_detail += '<div class="xiti"><div class="timu_question">' + search_results[i].question + '</div>'
                                + '<div class="timu_answer">' + search_results[i].answer + '</div><p class="bottom"><a class="btn btn-success add_lww_result">加入</a></p></div>'
                        }
                        $('#txtbox').html(timu_detail);
//                    for(key,item in search_results){
//                        console.log(item);
//                    }
                    } else {
                        $('#txtbox').html('暂无搜索结果');
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });
            //question搜索
            $('#question_search').click(function(){
                let word = $('#txtword').val();

                if (word.length = 0) {
                    alert('请输入搜索词');
                    return false;
                }
                $.getJSON(ajaxurl, {
                    a: 'get_timu_jiexi',
                    now_search:word
                },function (search_results) {
                    if(search_results.length>0){
                        let search_results_len = search_results.length;

                        let timu_detail = '';
                        for (let i = 0; i < search_results_len; i++) {
                            timu_detail += '<div class="xiti"><div class="timu_question">' + search_results[i].title + '</div><div class="bg-red text-center" style="font-size:10px">-----------------解析分割线-------------------</div>'
                                + '<div class="timu_answer">' + search_results[i].parse + '</div><p class="bottom"><a class="btn btn-success add_lww_result">加入</a></p></div>'
                        }
                        $('#txtbox').html(timu_detail);
                    }else{
                        $('#txtbox').html('暂无搜索结果');
                    }
                });

            })



            //百度ocr
            $('#ocr_page').click(function () {
                axios.post('{{ route('lww_ocr_page') }}', {
                    bookid: bookid,
                    chapterid: chapterid,
                    pageid: pageid
                }).then(response => {
                    if (response.data.status === 1) {
                        let ocr_result = response.data.data;
                        let ocr_que_insert = [];
                        for (let timuid in ocr_result) {
                            let timu_all = ocr_result[timuid];
                            let pic_len = timu_all.length;

                            ocr_que_insert[timuid] = '';
                            for (let i = 0; i < pic_len; i++) {
                                for (let j in timu_all[i].words_result) {
                                    ocr_que_insert[timuid] += '<p>' + timu_all[i].words_result[j].words + '</p>';
                                }
                            }
                            $('#m_' + timuid + ' .que').html(ocr_que_insert[timuid]);
                            $("#m_" + timuid).children(".bottom").html('<a href="javascript:void(0)" class="add_timu">搜索</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\'' + timuid + '\')">编辑</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_del(\'' + timuid + '\')">删除</a>');//编辑完后修改删除权限
                        }

                        let search_result = response.data.search_result;
                        $('.all_detail_box').append('<div class="ans_choice"></div>');
                        for (let timuid in search_result) {
                            let search_result_now = JSON.parse(search_result[timuid]);
                            let search_result_now_len = search_result_now.length;
                            if (search_result_now_len >= 10) {
                                search_result_now_len = 10
                            }
                            for (let i = 0; i < search_result_now_len; i++) {
                                console.log(search_result_now[i]);
                            }

                            console.log();
                            //get_search_auto()
                        }

                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });

            //展开图片
            $('#show_test_pic').click(function () {
                if($('#max_pic_box').is(':visible')){
                    Cookies.set('hide_question','1');
                    $('#max_pic_box').hide();
                    $('#max_content_box').removeClass('col-md-5').addClass('col-md-12')
                }else{
                    Cookies.set('hide_question',null);
                    $('#max_pic_box').show();
                    $('#max_content_box').removeClass('col-md-12').addClass('col-md-5')
                }
            })

            if(Cookies.get('hide_question')==='1'){
                $('#show_test_pic').click();
            }

        });
        $(document).on('click', '.add_lww_result', function () {
            var question = $(this).parents('.xiti').find('.timu_question').html();
            var answer = $(this).parents('.xiti').find('.timu_answer').html();
            t_close('floatbox_add');
            t_search_edit(add_timuid);
            um1.setContent(question);
            um2.setContent(answer);
            $("#m_" + add_timuid).children(".bottom").html('<a href="javascript:void(0)" class="add_timu">搜索</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\'' + add_timuid + '\');">编辑</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_del(\'' + add_timuid + '\')">删除</a>');//编辑完后修改删除权限
        });
        //$( "#floatbox_timupic" ).resizable();

        //展示对应章节下题目
        $('.chapter_now').click(function () {
            let chapter_now_pos = $(this);
            let id = $(this).attr('data-id');
            if ($('.question-box[data-id=' + id + ']').length == 0) {
                let o = {
                    _token: token,
                    chapter_id: id
                };
                $(this).append('<div id="request_now" class="question-box" data-id="' + id + '"></div>');
                $.post('{{ route('get_chapter_timu') }}', o, function (s) {
                    let questions = s.questions;
                    let questions_len = questions.length;
                    let questions_html = '<div><div class="question-box" data-id="' + id + '" style="display:block">';
                    for (let i = 0; i < questions_len; i++) {
                        let single_question = questions[i];
                        questions_html += '<div class="panel-body single-question" data-id="' + single_question.id + '"> ' +
                            '<span class="now_question"> ' +
                            single_question.question +
                            '</span> ' +
                            '<div class="question_bottom"></div> ' +
                            '<sapn class="now_answer"> ' +
                            single_question.answer +
                            '</sapn> ' +
                            '<div class="clear"></div> ' +
                            '<span class="input-group pull-left" style="width:80%"> ' +
                            '<a class="input-group-addon">添加至本页第</a> ' +
                            '<input class="form-control timu_order" type="text" > ' +
                            '<a class="input-group-addon">题</a> ' +
                            '</span> ' +
                            '<a class="btn btn-primary pull-left add_to_left" data-question-type="' + single_question.question_type + '" style="width:20%">确认</a> ' +
                            '</div>'
                    }

                    questions_html += '</div></div>';

                    chapter_now_pos.after(questions_html);
                    $('#request_now').remove();
                })
            }
            if ($('.question-box[data-id=' + id + ']').is(':visible')) {
                $('.question-box[data-id=' + id + ']').fadeOut();
            } else {
                $('.question-box[data-id=' + id + ']').fadeIn();
            }
        });
        //选中题目新增
        $(document).on('click', '.add_to_left', function () {
            let question_type = $(this).attr('data-question-type');
            t_close('floatbox_add');
            let id = $(this).parents('.question-box').attr('data-id');
            let question_id = $(this).parents('.single-question').attr('data-id');
            let timu_sort = parseInt($('.single-question[data-id=' + question_id + '] input').val() - 1);
            let timuid = $('#timu_box .xiti:eq(' + timu_sort + ')').attr('id').substr(2);
            t_search_edit(timuid);
            var ti_que = $('.single-question[data-id=' + question_id + '] .now_question').html();
            var ti_ans = $('.single-question[data-id=' + question_id + '] .now_answer').html();
            um1.setContent(ti_que);
            um2.setContent(ti_ans);
            $("#m_" + timuid).children(".bottom").html('<a href="javascript:void(0)" class="add_timu">搜索</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\'' + timuid + '\');">编辑</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_del(\'' + timuid + '\')">删除</a>');//编辑完后修改删除权限
        })


//        $(document).on('click','.answer_now',function () {
//            if($(this).html()==='___'){
//                $(this).html($(this).attr('data-answer'));
//            }else{
//                $(this).html('___');
//            }
//
//        });

        //查看试题详情
        $('#show_test_timu').click(function () {
            let page = $('.pg .on').attr('data-page');
            window.open('{{ route('lww_show_timu',[$data['book_id'],$data['chapter_id']]) }}/'+page);
        })





        //切换题目类型
        $('#question_type').change(function () {
            let answer_type = parseInt($(this).val());
            $('#answer_new').remove();
            if(answer_type===1){//单选
                $('#question_type').after(`<input class="form-control" type="text" id="answer_new" placeholder="如ABCD|A,ABCD|C,判断题如TF|T" value=""/>`);
            }else{

            }
        })


    </script>

    <script type="text/javascript" src="{{ asset('js/workbook1.js').'?v='.time().rand(100,999) }}"></script>
    <script>
        //题目相关
        var editbar3 = $("#editbar3").html();
        var toolbar = {
            toolbars: [[
                'source', '|', 'undo', 'redo',
                'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist'
            ]],
        };
        //    var ue1=UE.getEditor('E_add1', toolbar);
        //    var ue2=UE.getEditor('E_add2', toolbar);

        var toolbar1 = {
            toolbars: [[
                'source', '|', 'undo', 'redo',
                'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist'
            ]],
            contextMenu:[
                {label:'', cmdName:'selectall'},
                {
                    label:'',
                    cmdName:'cleardoc',
                    exec:function () {
                        this.execCommand( 'cleardoc' );
                    }
                },
                {
                    label:'添加答案',cmdName:'copy',
                    icon:'aligntd',
                    exec:function () {
                        let now_choose = this.selection.getText();
                        if(now_choose.length===0){
                            now_choose = '添加答案';
                        }
                        //this.execCommand( 'cleardoc' );
                        this.focus();
                        this.execCommand('inserthtml','&nbsp;<span class="answer_now">'+now_choose+'</span>&nbsp;');
                        //this.setContent('添加答案区块',true);
                        console.log("添加一个菜单");
                    }
                },
                '-',
                {
                    cmdName:'unlink'
                },
                '-',
                {
                    group:'',
                    icon:'justifyjustify',
                    subMenu:[
                        {
                            label:'',
                            cmdName:'justify',
                            value:'left'
                        },
                        {
                            label:'',
                            cmdName:'justify',
                            value:'right'
                        },
                        {
                            label:'',
                            cmdName:'justify',
                            value:'center'
                        },
                        {
                            label:'',
                            cmdName:'justify',
                            value:'justify'
                        }
                    ]
                },
                '-',
                {
                    group:'',
                    icon:'table',
                    subMenu:[
                        {
                            label:'',
                            cmdName:'inserttable'
                        },
                        {
                            label:'',
                            cmdName:'deletetable'
                        },
                        '-',
                        {
                            label:'',
                            cmdName:'deleterow'
                        },
                        {
                            label:'',
                            cmdName:'deletecol'
                        },
                        {
                            label:'',
                            cmdName:'insertcol'
                        },
                        {
                            label:'',
                            cmdName:'insertcolnext'
                        },
                        {
                            label:'',
                            cmdName:'insertrow'
                        },
                        {
                            label:'',
                            cmdName:'insertrownext'
                        },
                        '-',
                        {
                            label:'',
                            cmdName:'insertcaption'
                        },
                        {
                            label:'',
                            cmdName:'deletecaption'
                        },
                        {
                            label:'',
                            cmdName:'inserttitle'
                        },
                        {
                            label:'',
                            cmdName:'deletetitle'
                        },
                        {
                            label:'',
                            cmdName:'inserttitlecol'
                        },
                        {
                            label:'',
                            cmdName:'deletetitlecol'
                        },
                        '-',
                        {
                            cmdName:'mergecells'
                        },
                        {
                            cmdName:'mergeright'
                        },
                        {
                            cmdName:'mergedown'
                        },
                        '-',
                        {
                            cmdName:'splittorows'
                        },
                        {
                            cmdName:'splittocols'
                        },
                        {
                            cmdName:'splittocells'
                        },
                        '-',
                        {
                            cmdName:'averagedistributerow'
                        },
                        {
                            cmdName:'averagedistributecol'
                        },
                        '-',
                        {
                            cmdName:'edittd',
                            exec:function () {
                                if ( UE.ui['edittd'] ) {
                                    new UE.ui['edittd']( this );
                                }
                                this.getDialog('edittd').open();
                            }
                        },
                        {
                            cmdName:'edittable',
                            exec:function () {
                                if ( UE.ui['edittable'] ) {
                                    new UE.ui['edittable']( this );
                                }
                                this.getDialog('edittable').open();
                            }
                        },
                        {
                            cmdName:'setbordervisible'
                        }
                    ]
                },
                {
                    group:'',
                    icon:'tablesort',
                    subMenu:[
                        {
                            cmdName:'enablesort'
                        },
                        {
                            cmdName:'disablesort'
                        },
                        '-',
                        {
                            cmdName:'sorttable',
                            value:'reversecurrent'
                        },
                        {
                            cmdName:'sorttable',
                            value:'orderbyasc'
                        },
                        {
                            cmdName:'sorttable',
                            value:'reversebyasc'
                        },
                        {
                            cmdName:'sorttable',
                            value:'orderbynum'
                        },
                        {
                            cmdName:'sorttable',
                            value:'reversebynum'
                        }
                    ]
                },
                {
                    group:'',
                    icon:'borderBack',
                    subMenu:[
                        {
                            cmdName:"interlacetable",
                            exec:function(){
                                this.execCommand("interlacetable");
                            }
                        },
                        {
                            cmdName:"uninterlacetable",
                            exec:function(){
                                this.execCommand("uninterlacetable");
                            }
                        },
                        {
                            cmdName:"settablebackground",
                            exec:function(){
                                this.execCommand("settablebackground",{repeat:true,colorList:["#bbb","#ccc"]});
                            }
                        },
                        {
                            cmdName:"cleartablebackground",
                            exec:function(){
                                this.execCommand("cleartablebackground");
                            }
                        },
                        {
                            cmdName:"settablebackground",
                            exec:function(){
                                this.execCommand("settablebackground",{repeat:true,colorList:["red","blue"]});
                            }
                        },
                        {
                            cmdName:"settablebackground",
                            exec:function(){
                                this.execCommand("settablebackground",{repeat:true,colorList:["#aaa","#bbb","#ccc"]});
                            }
                        }
                    ]
                },
                {
                    group:'',
                    icon:'aligntd',
                    subMenu:[
                        {
                            cmdName:'cellalignment',
                            value:{align:'left',vAlign:'top'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'center',vAlign:'top'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'right',vAlign:'top'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'left',vAlign:'middle'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'center',vAlign:'middle'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'right',vAlign:'middle'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'left',vAlign:'bottom'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'center',vAlign:'bottom'}
                        },
                        {
                            cmdName:'cellalignment',
                            value:{align:'right',vAlign:'bottom'}
                        }
                    ]
                },
                {
                    group:'',
                    icon:'aligntable',
                    subMenu:[
                        {
                            cmdName:'tablealignment',
                            className: 'left',
                            label:'',
                            value:"left"
                        },
                        {
                            cmdName:'tablealignment',
                            className: 'center',
                            label:'',
                            value:"center"
                        },
                        {
                            cmdName:'tablealignment',
                            className: 'right',
                            label:'',
                            value:"right"
                        }
                    ]
                },
                '-',
                {
                    label:'前插入段落',
                    cmdName:'insertparagraph',
                    value:true
                },
                {
                    label:'后插入段落',
                    cmdName:'insertparagraph'
                },
                {
                    cmdName:'copy'
                },
                {
                    cmdName:'paste'
                }
            ]
        };

        var um1 = UE.getEditor('E_edit1', toolbar1);
        var um2 = UE.getEditor('E_edit2', toolbar);
        var um3 = UE.getEditor('E_edit3', toolbar);
        //auto_timubar();


        //video upload


        $(document).on('click','.video_upload_btn',function () {
            $('#for_upload').attr('data-id',$(this).attr('data-timu-id'))
        });


        function showWindows()
        {
            //alert("show");
            document.getElementById("win").style.display="block";
            document.getElementById("mask").style.display="block";
            $('#divstartup').show();

        }

        function hideWindows(){
            document.getElementById("win").style.display="none";
            document.getElementById("mask").style.display="none";
        }

        var info = document.getElementById("info");
        var btn = document.getElementById("testupload");
        var btnStartUpload = document.getElementById("btnstart");
        var btnstar = document.getElementById("divstartup");
        var per = document.getElementById("percent");
        //var token = document.getElementById("token");
        var page = document.getElementById("pageNum");
        var pagecount = document.getElementById("pageCount");
        var delfile = document.getElementById("delfileid");
        var deltype = document.getElementById("deltype");
        var retoken=document.getElementById("retoken");
        var mgrurl=document.getElementById("mgrUrl");
        var vcop = new Q.vcopClient({
            uploadBtn:{
                dom:btn,
                btnH:"32px",
                btnW:"62px",
                btnT:"100px",
                btnL:"100px",
                btnZ:"999",
                hasBind:false},
            appKey:"45dad714d8ab40c0a7ee2c6b2d5a7c49",  // 填写申请的app key
            appSecret:"5d31c3797b779530288f9459bef315ef", // 填写app secret
            managerUrl:"http://openapi.iqiyi.com/",
            uploadUrl:"http://upload.iqiyi.com/",
            needMeta:false
        });
        var fileinfo = {};
        var _refresh=null;
        vcop.authtoken = '{{ $data['access_token'] }}';

        function getStatus(play_box,file_id) {
            vcop.getFileStatus({
                file_id: file_id
            }, function (data) {
                if (data.code == 'A00000') {
                    play_box.html("<embed id='myvid' src='" + data.data.swfurl + "' frameborder='0' allowfullscreen='true'></embed>");
                } else if (data.code == 'A00001') {
                    play_box.html('<h2>视频发布中</h2>');
                } else if (data.code == 'A00002') {
                    play_box.html('<h2>视频审核失败</h2>');
                } else if (data.code == 'A00003') {
                    play_box.html('<h2>视频不存在</h2>');
                } else if (data.code == 'A00004') {
                    play_box.html('<h2>视频上传中</h2>');
                } else if (data.code == 'A00006') {
                    play_box.html('<h2>用户取消上传</h2>');
                } else if (data.code == 'A00007') {
                    if (data.data.is_repeat == 1) {
                        var fileIdBefore = data.data.fileIdBefore;
                        var chang_fid = new Ajax();
                        // chang_fid.get('plugin.php?id=bookinfo:aiqiyi&pid={echo $pid}&fid=' + fileIdBefore, function () {
                        // });
                        vcop.getFileStatus({
                            file_id: data.data.fileIdBefore
                        }, function (s) {
                            play_box.html("<embed id='myvid' src='" + s.data.swfurl + "' frameborder='0' width='800px' height='600px' allowfullscreen='true'></embed>");
                        })
                    } else {
                        play_box.html('<h2>视频发布失败</h2>');
                    }
                } else {
                    play_box.html('<h2>失败</h2>');
                }

            });
        }

        function getEntAuth(){
            vcop.getAuthEnterprise(function (data) {
                if(data){
                    //info.innerHTML = JSON.stringify(data);
                    vcop.authtoken = data.data.access_token;
                    _refresh = data.data.refresh_token
                    if(/msie/.test(navigator.userAgent.toLowerCase())){
                        initUpload();
                    }
                    if(uoploader){
                        uoploader.initOneFile({btnW:"100px",btnH:"100px",btnT:"100px",btnL:"12px"});
                    }
                }
            });
        }

        var uoploader='';  // 上传
        function initUpload() {
            var video_name = $('#video_name').val();
            var video_descript = $('#video_descript').val();

            if(video_name.trim()=='' || video_descript.trim()==''){
                alert('填写视频名称和描述后即可上传');
                return false;
            }
            if (!vcop.authtoken) {
                getEntAuth();
            }
            else {
                uoploader=vcop.initUpload({
                        slicesize:1024*128,
                        access_token:vcop.authtoken,
                        device_id:"test",
                        uid:"test",
                        allowType:["xv","avi","dat","mpg","mpeg","vob","mkv","mov","wmv","asf","rm","rmvb","ram","flv","mp4","3gp","dv","qt","divx","cpk","fli","flc","m4v","pfv"]  // 重置类型
                    }, {
                        onSuccess:function (data) {
                            if(data && data.data){
                                var timuid = $('#for_upload').attr('data-id');
                                var insert_data = video_record(timuid,video_name,video_descript,data.data.file_id);
                                if(insert_data==0){
                                    alert('请刷新页面重新上传');
                                    return false;
                                }
                                //info.innerHTML = data.data.file_id;
                                //sartUpload();
                                showWindows();
                                fileinfo = data.data;
                                sartUpload(video_name,video_descript);
                                //btnstar.style.display = "block";
                                //btnStartUpload.style.display="block";
                            }
                        },
                        onError:function (data) {
                            if (data && data.msg) {
                                info.innerHTML = data.msg;
                            }
                            else{
                                info.innerHTML = "网络错误"
                            }
                        }}
                );
            }

        }

        function initUpload_before() {
            var video_name = $('#video_name').val();
            var video_descript = $('#video_descript').val();

            if(video_name.trim()=='' || video_descript.trim()==''){
                alert('填写视频名称和描述后即可上传');
                return false;
            }else{
                $('#testupload button').click();
            }
        }

        var breakdown=false;
        function sartUpload(video_name,video_descript) {
            // 20130819 需手工设置meta(调用setMeta函数),否则返回错误
            // 20130830 隐藏上传按钮
            uoploader.startUpload(fileinfo, {
                onFinish:function (data) {
                    if (data && data.manualFinish === true) {
                        uoploader.finishUpload({
                            onSuccess:function () {
                                info.innerHTML = "上传成功";
                                //$('#success_upload').hide();
                                setMeta(video_name,video_descript);
                                //hideWindows();
                                document.getElementById('delfileid').value = data.file_id;
                                hide_upload_box(video_name,data.file_id);
                            },
                            onError:function () {
                                info.innerHTML = "上传失败";
                            }
                        });
                    }
                    else
                        info.innerHTML = "上传成功";
                    setTimeout(function () {
                        uoploader.delLocal(fileinfo.file_name,fileinfo.file_id);     // 20141227
                        //resetPer();
                    }, 2000)
                },
                onError:function (data) {
                    if(data.msg){
                        info.innerHTML = data.msg;
                        // 续传
                        if(data.msg=='network break down'){
                            breakdown=true;
                            uoploader.pauseUpload();
                        }
                    }
                    else{
                        info.innerHTML = "上传失败";
                    }

                },
                onProgress:function (data) {    // 5/7 增加速度，剩余时间
                    per.style.width = data.percent + "%";
                    info.innerHTML="上传中....速度："+data.speed+"kb/s , 剩余时间："+data.remainTime + "s,请耐心等待上传完成";
                }
            });
            //btnStartUpload.style.display="none";
            // btn.style.display="none";
        }

        function pauseUpload() {
            uoploader.pauseUpload(function(data){
                breakdown=data;
            });
        }

        function resumeUpload() {
            uoploader.resumeUpload({
                onFinish:function (data) {
                    if (data && data.manualFinish === true) {
                        uoploader.finishUpload({
                            onSuccess:function () {
                                info.innerHTML = "上传成功";
                            },
                            onError:function () {
                                info.innerHTML = "上传失败";
                            }
                        });
                    }
                    else
                        info.innerHTML = "上传成功";
                    setTimeout(function () {
                        resetPer();
                    }, 600)
                },
                onError:function (data) {
                    info.innerHTML = "上传失败";
                },
                onProgress:function (data) {
                    per.style.width = data.percent + "%";
                    info.innerHTML="上传中....速度："+data.speed+"kb/s , 剩余时间："+data.remainTime + "s";
                }
            },breakdown);   // 续传传参
        }

        function resetPer() {
            per.style.width = "0%";
            btnstar.style.display = "none";
            info.innerHTML='';
            uoploader=null;
            btn.style.display='';

        }

        function startBreakPoint(){
            if(!breakdown){
                return;
            }
            var breakfile=fileinfo;
            breakfile.thefile = uoploader.uploader.currentFile;
            breakfile.forstart=breakdown.realend;
            breakfile.handler = {
                onFinish:function (data) {
                    if (data && data.manualFinish === true) {
                        uoploader.finishUpload({
                            onSuccess:function () {
                                info.innerHTML = "上传成功";
                            },
                            onError:function () {
                                info.innerHTML = "上传失败";
                            }
                        });
                    }
                    else
                        info.innerHTML = "上传成功";
                    setTimeout(function () {
                        uoploader.delLocal(fileinfo.file_name,fileinfo.file_id);    // 20141227
                        resetPer();
                    }, 600)
                },
                onError:function (data) {
                    info.innerHTML = "上传失败";
                },
                onProgress:function (data) {
                    per.style.width = data.percent + "%";
                    info.innerHTML="上传中....速度："+data.speed+"kb/s , 剩余时间："+data.remainTime + "s";
                }
            };
            uoploader.breakResume(breakfile);
        }

        function cancelUpload() {
            uoploader.cancelUpload({
                onSuccess:function (data) {
                    info.innerHTML = "已取消";
                    setTimeout(function () {
                        resetPer();
                    }, 600)
                },
                onError:function (data) {
                    if(data && data.code)
                        info.innerHTML = "取消失败";
                    else
                        info.innerHTML = "网络错误";
                }
            });
        }

        function setMeta(video_name,video_descript) {
            if(!uoploader){
                uoploader = true;
            }
            vcop.setMetadata({
                file_id:fileinfo.file_id,
                file_name:video_name,
                description:video_descript,
                tag:"05网精品视频",
                uploader:uoploader          // 20130819 需手工设置meta
            }, function (data) {
                info.innerHTML = data.code;
            })
        }

        function delVideo(file_id) {
            vcop.delVideoById({file_ids: file_id
            }, function (data) {
                if (data.code == 'A00000'){
                    $('#video-modal').modal('hide');
                    delVideoLocal(file_id);
                    $('.player[data-id="'+file_id+'"]').parents('.col-md-3').remove();
                }else{
                    alert('删除失败');
                }
            })

        }

        function delVideoLocal(file_id) {
            var postData = {
                'file_id': file_id,
                '_token': token
            };
            $.ajax({
                type: 'POST',
                data: postData,
                url: '{{ route('video_del') }}',
                dataType: 'json',
                success: function (s) {
                    $('button[data-vid='+file_id+']').removeClass('btn-danger check_upload_btn').addClass('video_upload_btn btn-primary').attr({'data-vid':'','data-target':'#for_upload'}).html('上传视频');
                    $('#for_uploaded').modal('hide');
                },
                error: function (s) {}
            });
        }


        //    $('.show_video').click(function () {
        //        var play_box = $(this).parent();
        //        var file_id = $(this).data('id');
        //
        //        var aaa = getStatus(play_box,file_id);
        //        alert(aaa);
        //    });


        function video_record(timu_id,name,description,vid) {
            var post_data = {
                'timu_id':timu_id,
                'name':name,
                'description':description,
                '_token':token,
                'vid':vid
            };
            $.ajax({
                type: 'post',
                url: "{{ route('video_add') }}",
                data: post_data,
                success: function (t) {
                    if(t.status==1){
                        return 1;
                    }else{
                        return 0;
                    }
                },
                error: function (t) {
                },
                dataType:'json',
                async:false
            });
        }

        function hide_upload_box(video_name,vid) {
            alert('上传成功');
            $('#video_name').val('');
            $('#video_descript').val('');
            hideWindows();
            let now_id = $('#for_upload').attr('data-id');
            $('button[data-timu-id='+now_id+']').removeClass('video_upload_btn btn-primary').addClass('btn-danger check_upload_btn').attr({'data-vid':vid,'data-target':'#for_uploaded'}).html('查看上传视频');
            $('#for_upload').modal('hide');
        }


        //play the video
        $(document).on('click','.check_upload_btn',function () {
            let vid = $(this).attr('data-vid');
            let video_id = '';
            axios.post('{{ route('video_get_vid') }}',{vid}).then(response=>{
                let video_data = response.data.data;
                video_id=video_data.vid;
                var play_box = $('#for_uploaded .modal-body');
                getStatus(play_box,video_id);

                $('#for_uploaded .modal-footer').html(`<div><a class="btn btn-success up_or_down" data-toggle="modal" data-target="#modify-modal">上下架</a><a class="btn btn-danger" id="confirm_del_btn">删除</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>`);
                $('#modify-modal .modal-header').attr({'data-vid':video_id})
                $('#modify-modal #video_name_confirm').val(video_data.name);
                $('#modify-modal #video_descript_confirm').val(video_data.description);
                $('#modify-modal #show_confirm_modify').val(video_data.show_status);
            });
        });

        //修改
        $(document).on('click','#confirm_modify_btn',function () {
            var file_id = $('#modify-modal .modal-header').attr('data-vid');
            var status =  $('#show_confirm_modify').val();
            var video_name = $('#video_name_confirm').val();
            var video_descript = $('#video_descript_confirm').val();
            var pos_data = {
                vid :file_id,
                name:video_name,
                description:video_descript,
                show_status :status,
                _token :token
            };
            $.ajax({
                type: 'post',
                data: pos_data,
                url: "{{ route('video_modify') }}",
                dataType:'json',
                success : function (s) {
                    if(s.status = 1){
                        fileinfo.file_id = file_id;
                        setMeta(video_name,video_descript);
                        $('#modify-modal').modal('hide');
                    }else{
                        alert('操作失败');
                    }
                },
                error: function (s) {

                }
            })
        });

        //删除
        $(document).on('click','#confirm_del_btn',function () {
            if(!confirm('确认删除此视频')){
                return false;
            }
            var file_id = $('#modify-modal .modal-header').attr('data-vid');
            delVideo(file_id);
        });


    </script>
@endpush