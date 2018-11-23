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
    #bdcs-search-form{
        display: none;
    }
    .voice_box{
        margin-bottom: 10px;
    }
    .tab-content{
        max-height: 1000px;
        overflow-y: scroll;
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
                    <div class="col-md-7">
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
                    <div class="col-md-5">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#chapter_tab" data-toggle="tab">设置时间段</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="voice_tab">
                                    <div>
                                        <p>当前播放:<a id="now_auido_play"></a></p>
                                        <audio style="width:100%" id="audio_now" data-id="1" src="" controls controlsList="nodownload"></audio>
                                        <select id="all_audio" class="form-control">
                                            <option value="">选择音频</option>
                                            @if(!empty($data['audios']))
                                                @foreach($data['audios'] as $value)
                                                <option value="{{ iconv('gbk','utf-8',$value) }}">{{ iconv('gbk','utf-8',$value) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <hr>
                                    <div id="all_voice"></div>
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
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/pageeditor/jquery-hotspotter.min.js') }}"></script>
<script src="{{ asset('js/pageeditor/editor.js') }}"></script>


<script>
    const bookid = '{{ $data['book_id'] }}';
    const chapterid = '{{ $data['chapter_id'] }}';
    const request_url = '{{ route('lww_page_question_about') }}';
    const token = '{{ csrf_token() }}';
    const uid = '{{ Auth::id() }}';
    {{--//const scale = '{{ $data['bookinfo']->scale }}';--}}
</script>

<script>
    let ajaxurl = '{{ route('lww_diandu_edit') }}';
    let pageid = 1;
    let add_timuid = '';
    let cut_page_dir = '{{ asset('storage/all_book_pages/'.$data['book_id'].'/cut_pages/') }}';
    $(function () {
        $('#ed-img').width('100%');
        let bookpage = {};
        $(".fenye p a").click(function () {
            $('#timu_box').html('');
            pageid = $(this).attr('data-page');
            if ($(this).attr('class') == 'on') return;
            Draw.indexsort = 1;
            if(pageid!=1){
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
            $('#all_voice').html('');
            $.getJSON(ajaxurl, {a: 'editorload', bookid: bookid,chapterid:chapterid, pageid: bookpage.page,type:'diandu'}, function (response) {
                let s = response.pos;
                if (s.length > 0) {
                    let now_img_width = Editor.$edImg.width();
                    let now_img_height = Editor.$edImg.height();
                    let loadspot = {};
                    for (let i in s) {
                        let newSpotObj = new Spot("red-spot", s[i].pleft*now_img_width, s[i].ptop*now_img_height);
                        newSpotObj.dim[0] = s[i].pwidth*now_img_width;
                        newSpotObj.dim[1] = s[i].pheight*now_img_height;
                        newSpotObj.$edSpot.width(s[i].pwidth*now_img_width);
                        newSpotObj.$edSpot.height(s[i].pheight*now_img_height);
                        Draw.indexsort = s[i].sort;
                        Draw.timu_page = s[i].timu_page;
                        Editor.newSpot(newSpotObj);//加载已保存区域
                        if(s[i].austart==null) { s[i].austart = '';}
                        if(s[i].auend==null) { s[i].auend = '';}
                        if(s[i].mp3==null || s[i].mp3==1) { s[i].mp3 = '<em class="label label-info">暂无关联</em>';}

                        $('#all_voice').append(`
                            <p>关联音频:<strong class="related_audio" data-id="${s[i].id}">${s[i].mp3}</strong></p>
                            <div class="input-group voice_box" data-id="${s[i].id}">
                            <label class="input-group-addon">${s[i].sort}</label>
                            <label class="input-group-addon btn btn-primary set_start_time">设置起始时间</label>
                            <input type="text" class="form-control start_time" value="${s[i].austart}" />
                            <label class="input-group-addon btn btn-primary set_end_time">设置结束时间</label>
                            <input type="text" class="form-control end_time" value="${s[i].auend}" />
                            <label class="input-group-addon btn btn-primary voice_confirm">确认</label>
                        </div>`)
                    }
                    Draw.timu_page = 0;
                }
            });
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
            let now_page_id = $(".fenye p a.on").attr('data-id');
            let pageid=[],timuid=[];

            let cuts = [];
            let img_w = Math.round($("#ed-img").width()), img_h = Math.round($("#ed-img").height());
            for (let i in Editor.spotPool)//框选信息
            {
                let p = Editor.spotPool[i];//console.log(p.$edSpot.find('.timu_sort').val());
                cuts[i] = Math.round(p.coord[0]) + " " + Math.round(p.coord[1]) + " " + Math.round(p.dim[0]) + " " + Math.round(p.dim[1]) + " " + p.$edSpot.find('.timu_sort').val() + " " + p.$edSpot.find('.timu_page').val();//左 上 宽 高 题目排序 题目所属页面 为点读区域
                //console.log(p.coord[0]/img_w,p.coord[1]/img_h,p.dim[0]/img_w,p.dim[1]/img_h);//按照比例
            }
            //console.log(cuts);//return;
            $.getJSON(ajaxurl, {
                a: 'editorsave',
                width: img_w,
                height: img_h,
                bookid: bookid,
                chapterid:chapterid,
                now_page_id:now_page_id,
                pageid: pageid,
                timuid:timuid,
                data: cuts.join(',')
            }, function (s) {
                if(s.suc==1){
                    alert('保存成功');
                    $(`.fenye p a[data-page=${now_page_id}]`).removeClass('on').click();
                }else{
                    alert('保存失败');
                }
            });
//            if ($(".fenye p a.on").next().length == 0) alert('本书编辑完成');
//            else $(".fenye p a.on").next().click();//保存后自动加载下一页
        });
        $(".fenye p a:eq(0)").click();//默认打开第一页

        //确认音频位置
        $(document).on('click','.voice_confirm',function () {
            let now_id = $(this).parents('.voice_box').attr('data-id');
            let start_time = $('.voice_box[data-id="'+now_id+'"] .start_time').val();
            let end_time = $('.voice_box[data-id="'+now_id+'"] .end_time').val();
            let mp3 = $('.related_audio[data-id="'+now_id+'"]').html();
            if(parseFloat(end_time)<=parseFloat(start_time)){
                alert('结束时间不得小于起始时间');
                return false;
            }
            axios.post('{{ route('lww_voice_post') }}',{id:now_id,type:'post_voice',start_time:start_time,end_time:end_time,mp3:mp3}).then(response=>{
                if(response.data.status==1){
                    alert('更新成功');
                }
            }).catch(function (error) {
                console.log(error);
            })
        });

        //设置音频位置
        $(document).on('click','.set_start_time,.set_end_time',function () {
            let now_id = $(this).parents('.voice_box').attr('data-id');
            $('.related_audio[data-id="'+now_id+'"]').html($('#audio_now').attr('src'));
            $(this).next().val($('#audio_now')[0].currentTime);

        });

        //音频切换
        $(document).on('change','#all_audio',function () {
            $('#audio_now').attr('src','/'+$(this).val());
            $('#now_auido_play').html($(this).val());
        });



//        //图片统一大小
//        $('#image_big').click(function () {
//            let now_width = $('#ed-img').width();
//            $('#ed-img').width(parseInt(now_width*(1.1)));
//        });
//        $('#image_small').click(function () {
//            let now_width = $('#ed-img').width();
//            $('#ed-img').width(parseInt(now_width*(0.9)));
//        });
        {{--$('#image_cut').click(function () {--}}
            {{--//alert('图片正在完成统一，请不要刷新页面');--}}
            {{--let real_width = $('.pg a:first').attr('data-width');--}}
            {{--let real_height = $('.pg a:first').attr('data-height');--}}
            {{--let now_width = $('#ed-img').width();--}}
            {{--let scale = now_width/real_width;--}}
            {{--axios.post('{{ route('lww_set_image_size') }}',{book_id:'{{ $data['book_id'] }}',width:real_width,height:real_height,scale:scale}).then(response=>{--}}
                {{--if(response.data.status==1){--}}
                    {{--window.location.reload();--}}
                {{--}--}}
            {{--}).catch(function (error) {--}}
                {{--//console.log(error);--}}
            {{--})--}}
        {{--});--}}

    });



</script>

@endpush