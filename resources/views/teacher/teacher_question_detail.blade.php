@extends('layouts.teacher')

@section('header')
    答题
@endsection

@push('need_css')
    <link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
    <style>
        .zijin_button a {
            width: 50%;
        }

        #talk_img {
            width: 100%;
        }

        #now_photo {
            position: absolute;
            z-index: 999;
            width: 100%;
            height: 100%;
        }

        .show_img {
            float: left;
        }

        #talk_img {
            position: fixed;
            z-index: 998;
        }

        .is_playing {
            background: #000000;
        }

        .audio_body {
            width: 37px;
            float: left;
        }

        .topic{
            padding: 0;
        }
        .texts {
            display: block;
            position: relative;
        }
    </style>
@endpush


@section('content')

    {{-- 放大图片 --}}
    <div class="weui-gallery">
        <span class="weui-gallery__img"
              style="background-image: url({{ config('weixin.M_PIC').$data['question']->img }});"></span>
        <div class="weui-gallery__opr">
            <a href="javascript:" class="weui-gallery__del">
                <i class="weui-icon-delete weui-icon_gallery-delete"></i>
            </a>
        </div>
    </div>
    {{-- 通用录音 --}}
    <div id="voice_record" class="weui-popup__container">
        <div class="weui-popup__overlay"></div>
        <div class="weui-popup__modal">
            <div>
                <div class="" id="test">
                    <canvas id="now_photo"></canvas>
                    <a class="thumbnail" style="width: 100%">
                        <img id="talk_img" style="max-width: 100%"
                             src="{{ config('weixin.M_PIC').$data['question']->img }}" alt=""
                             class="img-responsive"/>
                    </a>
                    <img src="/images/voice_now.png">
                    <span style="position: fixed;z-index: 999;bottom: 10px;width: 100%;text-align: center;">
                        <a class="close-popup weui-btn weui-btn_mini weui-btn_primary">返回</a>
                    </span>
                </div>
            </div>
        </div>
    </div>


    <!--学生-->
    <div class="weui_cells weui_cells_access zijin_clelrt ">
        <div class="weui_cell">
            <div class="weui_cell_hd"><img src="{{ asset('images/teacher/touxiang.png') }}" alt=""></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p class="user_name">学生姓名</p>
                <p class="time">{{ $data['question']->created_at }}</p>
            </div>
            <div class="weui_cell_biaoti_homework">{{ $data['question']->grade_id.'-'.$data['question']->subject_id }}</div>
        </div>
        <p class="text">
            {{ $data['question']->content }}
        </p>
        <div class="topic">
            <img class="record_img" data-img="{{ $data['question']->img }}"
                 src="{{ config('weixin.M_PIC').$data['question']->img }}" alt=""/>
        </div>

    </div>
    <!--学生-->
    <!--老师-->
    @if(count($data['answer_about'])>0)
        @foreach($data['answer_about'] as $key=>$value)
            <div class="weui_cells weui_cells_access zijin_clelrt">
                @if($value['uid'] !== $data['question']->uid)
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><img src="{{ asset('images/teacher/touxiang.png') }}" alt=""></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p class="user_name">胡老师</p>
                            <p class="time">{{ $value->created_at }}</p>
                        </div>
                        <div class="weui_cell_biaoti_homework">{{ $data['question']->grade_id.'-'.$data['question']->subject_id }}</div>
                    </div>
                    {{--<div class="topic">--}}
                    {{--<img src="{{ config('weixin.M_PIC').$value->img }}" alt=""/>--}}
                    {{--<i></i>--}}
                    {{--</div>--}}
                    <div class="texts">
                        <img class="record_img" data-img="{{ $value->answer_pic }}" style="width: 100%" src="{{ config('weixin.M_PIC').$value->answer_pic }}"
                             alt=""/>
                    </div>

                    <div class="audio_box">
                        @if($value->has_audio)
                            @foreach($data['answer_about'][$key]['voice'] as $single_voice)
                                <div class="audio_body">
                                    <audio src="{{ config('weixin.M_PIC').$single_voice->voice_location }}"></audio>
                                    <img class="remote_audio" src="{{ asset('images/teacher/yuyin1.png') }}" alt="">
                                </div>
                            @endforeach
                        @endif
                    </div>

                @else
                <!--追问-->
                    <div class="weui_cells zijin_zhui">
                        <a class="weui_cell" href="javascript:;">
                            <div class="weui_cell_hd"><img src="{{ asset('images/teacher/zhuiwen.png') }}" alt=""></div>
                            <div class="weui_cell_bd weui_cell_primary ">
                                <p>{{ $value->created_at }}</p>
                            </div>
                        </a>
                        <p class="texts">
                            <img class="record_img" data-img="{{ $value->answer_pic }}" style="width: 100%"
                                 src="{{ config('weixin.M_PIC').$value->answer_pic }}" alt=""/>
                        </p>
                    </div>
                    <!--追问-->
                @endif
            </div>
            @if($loop->last)
                @if($value->uid==$data['question']->uid)
                    <div class="zijin_footer">
                        <div class="bd spacing zijin_spacing">
                            <a href="{{ route('teacher_question_reply') }}"
                               class="weui_btn weui_btn_plain_primary">回复追问</a>
                            <a href="answer1.html" class="weui_btn weui_btn_plain_primary">关闭问题</a>
                        </div>
                    </div>
                @else
                    <div style="width: 100%;display: inline-block;">
                        <div class="topic_tree">
                            <ul id="img-box">
                                @if($value->img)
                                    @foreach(explode('|',$value->img) as $single_img)
                                        <li>
                                            <div class="item show_img edit" data-img="/images/teacher/tili.png"
                                                 style="background-image: url({{ config('weixin.M_PIC').$single_img }});"></div>
                                            <i class="del_this"></i></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="weui_cells weui_cells_access zijin_clelrt">
                        <div class="weui_cell_bd weui_cell_primary zijin_textarea">
                            <strong class="texts">为问题新增图片或语音解释</strong>
                        </div>
                        <div class="zijin_button"><a class="insert_img"><img
                                        src="{{ asset('images/teacher/tupian.png') }}" alt=""></a> <a
                                    href="javascript:;" style="display: none" class="open-popup"
                                    data-target="#voice_record"><img src="{{ asset('images/teacher/yuyin_ni.png') }}"
                                                                     alt=""></a></div>

                    </div>
                    <div class="zijin_label">
                        <div class="weui_cells_checkbox zijin_checkbox">
                            <!--<label class="weui_cell weui_check_label" for="s1">-->
                            <!--<div class="weui_cell_hd">-->
                            <!--<input type="checkbox" class="weui_check" name="checkbox1" id="s1">-->
                            <!--<i class="weui_icon_checked"></i> </div>-->
                            <!--<p>移动到操场</p>-->
                            <!--</label>-->
                        </div>
                        <div class="bd spacing zijin_bds"><a id="all_done_confirm"
                                                             class="weui_btn weui_btn_primary btn_gray">提交答案</a></div>
                    </div>
                @endif
            @endif
        @endforeach
    @else
        <div class="weui_cells weui_cells_access zijin_clelrt">
            <div class="weui_cell_bd weui_cell_primary zijin_textarea">
                <textarea class="weui_textarea" placeholder="请简要描述你的回答..." rows="3"></textarea>
            </div>
            <div class="zijin_button"><a class="insert_img"><img src="{{ asset('images/teacher/tupian.png') }}" alt=""></a>
                <a><img src="{{ asset('images/teacher/yuyin_ni.png') }}" alt=""></a></div>
        </div>
        <div class="zijin_label">
            <div class="weui_cells_checkbox zijin_checkbox">

            </div>
            <div class="bd spacing zijin_bds"><a onclick="javascript:c1();" class="weui_btn weui_btn_primary btn_gray">提交答案</a>
            </div>
        </div>
    @endif
    <!--老师-->
    <div class="zijin_footer"></div>
@endsection

@push('need_js')
    <script>
        const now_token = '{{ csrf_token() }}';
        const qid = '{{ $data['question']->id }}';
        const oss_url = '{{ config('weixin.M_PIC') }}';
        let page_voices_string;
        let all_pos = [];
    </script>
    <script src="{{ asset('js/teacher/jquery-2.1.4.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        $(function () {
//            $('.now_photo').each(function () {
//                $(this).width($(this).next().width());
//                $(this).height($(this).next().height());
//            });
//            $('.now_photo').on('touchstart', function(event) {
//                $(this).parent().addClass('record_now_div');
//                //获取触摸起始坐标
//                left_pos_px = event.originalEvent.changedTouches[0].pageX - $(this)[0].offset().left;
//                top_pos_px = event.originalEvent.changedTouches[0].pageY - $(this)[0].offset().top;
//                //获取相对坐标比例
//                left_pos = (left_pos_px / $(this).width()).toFixed(5);
//                top_pos = (top_pos_px / $(this).height()).toFixed(5);
//                console.log(x= event);
//            });

//            $('.now_photo').each(function () {
//                $(this).width($(this).next().width());
//                $(this).height($(this).next().height());
//            });

//            $('.now_photo').each(function () {
//                $(this).width($(this).next().find('img').width());
//                $(this).height($(this).next().find('img').height());
//            });
            $(document).on('click', '.has_voice', function () {
                let has_play = $(this).attr('data-has-play');
                let now_voice = $(this).attr('data-sid');
                if (has_play === 0 || has_play === undefined) {
                    wx.playVoice({localId: now_voice});
                    $(this).attr('data-has-play', 1).addClass('is_playing');
                } else {
                    wx.pauseVoice({localId: now_voice});
                    $(this).removeAttr('data-has-play').removeClass('is_playing');
                }
            });
//            $('.now_photo').on('touchstart', function(event){
//                alert('asdasdasd');
//
//                $(this).parent().addClass('record_now_div');
//                //获取触摸起始坐标
//                left_pos_px = event.originalEvent.changedTouches[0].pageX - $(this).offset().left;
//                top_pos_px = event.originalEvent.changedTouches[0].pageY - $(this).offset().top;
//                //获取相对坐标比例
//                left_pos= (left_pos_px/$(this).width()).toFixed(5);
//                top_pos = (top_pos_px/$(this).height()).toFixed(5);
//                //阻止调用默认事件
//                event.preventDefault();
//                //记录起始时间
//                start_time = new Date().getTime();
//
//                recordTimer = setTimeout(function(){
//                    wx.startRecord({
//                        success: function(){
//                            localStorage.rainAllowRecord = 'true';
//                        },
//                        cancel: function () {
//                            alert('用户拒绝授权录音');
//                        }
//                    });
//                },300);
//            });

            wx.config({
                debug: false,
                appId: "{{ $data['wx_js']['appId'] }}", // 必填，公众号的唯一标识
                timestamp: "{{ $data['wx_js']['timestamp'] }}", // 必填，生成签名的时间戳
                nonceStr: "{{ $data['wx_js']['nonceStr'] }}", // 必填，生成签名的随机串
                signature: "{{ $data['wx_js']['signature'] }}",// 必填，签名，见附录1
                jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'chooseImage',
                    'previewImage',
                    'uploadImage',
                    'downloadImage', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
            wx.ready(function () {
                let localId_now;//当前本地id
                let localId_category;
                let left_pos_px, left_pos, top_pos_px, top_pos, start_time, end_time;

//                $('.now_photo').each(function () {
//                    $(this).width($(this).next().width());
//                    $(this).height($(this).next().height());
//                });
//                canvas.width = $('#talk_img').width();
//                canvas.height = $('#talk_img').height();

//                if(page_voices_string!==0){
//                    for(let i in page_voices_string){
//                        let {p_left,p_top} =  page_voices_string[i];
//                        $('#test').append(`<img class="has_voice" style="position: fixed;z-index: 9991;width:32px;height:32px;left:${p_left*canvas.width-16}px;top:${p_top*canvas.height-16}px"  src="${M_SITE}/images/voice_now.png" />`);
//                    }
//                }
                //监听录音自动停止接口
                wx.onVoiceRecordEnd({
                    // 录音时间超过一分钟没有停止的时候会执行 complete 回调
                    complete: function (res) {
                        localId_now = res.localId;
                        wx.uploadVoice({
                            localId: localId_now,
                            isShowProgressTips: 1,
                            success: function (res) {
                                let now_img = $('#talk_img').attr('data-img');
                                let now_img_width = $('#talk_img').width();
                                let now_img_height = $('#talk_img').height();
                                let original_width = $(`.record_img[data-img='${now_img}']`).width();
                                let original_height = $(`.record_img[data-img='${now_img}']`).height();
                                let o = {
                                    qid: qid,
                                    p_left: left_pos,
                                    p_top: top_pos,
                                    voice_id: res.serverId,
                                    localId: localId_now,
                                    now_img: now_img,
                                };

                                $('.record_now_div').append(`<img class="has_voice" style="position: absolute;z-index: 9991;width:32px;height:32px;left:${left_pos*now_img_width - 16}px;top:${top_pos*now_img_height - 16}px" data-sid="${localId_now}" src="/images/voice_now.png" />`);
                                $(`.record_img[data-img='${now_img}']`).parent().append(`<img class="has_voice" data-left="${left_pos}" data-top="${top_pos}" style="position: absolute;z-index: 9;width:32px;height:32px;left:${left_pos*original_width - 16}px;top:${top_pos*original_height - 16}px" data-sid="${localId_now}" src="/images/voice_now.png" />`);
                                all_pos.push(o);
                                $('.record_now_div').removeClass('record_now_div');
                            }
                        });
                    }
                });

                //监听录音播放完毕接口
                wx.onVoicePlayEnd({
                    success: function (res) {
                        let localId = res.localId; // 返回音频的本地ID
                        $('.has_voice[data-sid="' + localId + '"]').removeAttr('data-has-play').removeClass('is_playing');
                    }
                });

                $('#now_photo').on('touchstart', function (event) {
                    $(this).parent().addClass('record_now_div');
                    //获取触摸起始坐标
                    left_pos_px = event.originalEvent.changedTouches[0].pageX - $(this).offset().left;
                    top_pos_px = event.originalEvent.changedTouches[0].pageY - $(this).offset().top;
                    //获取相对坐标比例
                    left_pos = (left_pos_px / $(this).width()).toFixed(5);
                    top_pos = (top_pos_px / $(this).height()).toFixed(5);

                    event.preventDefault();
                    //记录起始时间
                    start_time = new Date().getTime();

                    recordTimer = setTimeout(function () {
                        wx.startRecord({
                            success: function () {
                                localStorage.rainAllowRecord = 'true';
                            },
                            cancel: function () {
                                alert('用户拒绝授权录音');
                            }
                        });
                    }, 300);

                });
                //松手结束录音
                $('#now_photo').on('touchend', function (event) {
                    event.preventDefault();
                    end_time = new Date().getTime();
                    if ((end_time - start_time) < 300) {
                        end_time = 0;
                        start_time = 0;
                        //小于300ms，不录音
                        clearTimeout(recordTimer);

                    } else {
                        event.preventDefault();
                        wx.stopRecord({
                            success: function (res) {
                                //获取本地音频id
                                localId_now = res.localId;
                                wx.uploadVoice({
                                    localId: localId_now,
                                    isShowProgressTips: 1,
                                    success: function (res) {
                                        //上传至微信服务器后只保存三天，记录至自有服务器。
                                        let now_img = $('#talk_img').attr('data-img');
                                        let now_img_width = $('#talk_img').width();
                                        let now_img_height = $('#talk_img').height();
                                        let original_width = $(`.record_img[data-img='${now_img}']`).width();
                                        let original_height = $(`.record_img[data-img='${now_img}']`).height();
                                        let o = {
                                            qid: qid,
                                            p_left: left_pos,
                                            p_top: top_pos,
                                            voice_id: res.serverId,
                                            localId: localId_now,
                                            now_img: now_img,
                                        };
                                        $('.record_now_div').append(`<img class="has_voice" style="position: absolute;z-index: 9991;width:32px;height:32px;left:${left_pos*now_img_width - 16}px;top:${top_pos*now_img_height - 16}px" data-sid="${localId_now}" src="/images/voice_now.png" />`);
                                        $(`.record_img[data-img='${now_img}']`).parent().append(`<img class="has_voice" data-left="${left_pos}" data-top="${top_pos}" style="position: absolute;z-index: 9;width:32px;height:32px;left:${left_pos*original_width - 16}px;top:${top_pos*original_height - 16}px" data-sid="${localId_now}" src="/images/voice_now.png" />`);
                                        all_pos.push(o);
                                        $('.record_now_div').removeClass('record_now_div');
                                    }
                                });
                            },
                            fail: function (res) {
                                alert(JSON.stringify(res));
                            }
                        });
                    }
                });


                $(document).on('click', '#upload_all_voice', function () {
                    $.ajax({
                        url: '{{ route('teacher_voice_upload') }}',
                        type: 'post',
                        data: {all_pos: all_pos, _token: now_token},
                        dataType: "json",
                        success: function (data) {
                            if (data.status === 1) {
                                for (let i in all_pos) {
                                    $('.audio_box').append(`
                                    <div class="audio_body">
                                        <img class="has_voice" data-sid="${all_pos[i].localId}" src="/images/teacher/yuyin1.png" alt="">
                                    </div>
                                    `);
                                }
                            }
                        },
                        error: function (xhr, errorType, error) {
                            alert(error);
                        }
                    });
                });

                //插入图片
                $(document).on('click', '.insert_img', function () {
                    var x = wx.chooseImage({
                        count: 9, // 默认9
                        sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                        success: function (res) {
                            var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                            //return localIds;
                            var id_len = localIds.length;
                            for (var i = 0; i < id_len; i++) {
                                wx.uploadImage({
                                    localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                                    isShowProgressTips: 1, // 默认为1，显示进度提示
                                    success: function (res) {
                                        //var serverId = res.serverId; // 返回图片的服务器端ID
                                        download_image(res.serverId);
                                    }
                                });
                            }
                        }
                    });

                });
            });

            //下载图片至服务器
            function download_image(serverId) {
                let o = {
                    serverId: serverId,
                    _token: now_token,
                };
                $.ajax({
                    type: 'POST',
                    url: '{{ route('teacher_img_download') }}',
                    data: o,
                    dataType: 'json',
                    success: function (s) {
                        if (s.status === 1) {
                            $('#img-box .insert_img').parent().parent().remove();
                            $('#img-box').append(`<li><div data-img="${s.img}" class="item show_img" style="background-image: url(${oss_url + s.img});"></div><i class="del_this"></i></li>`);
                        }
                        if ($('#img-box .insert_img').length == 0) {
                            $('#img-box').append('<li><div class="item" style=""><p class="insert_img">继续上传</p></div></li>')
                        }
                    },
                    error: function (s) {
                    }
                });
            }

            //图片点击查看
            function show_img(src, edit) {
                let show_html = `<div class="weui-gallery" style="display:block;">
        <span class="weui-gallery__img" style="background-image: url(${src});"></span>`;
                if (edit === 'edit') {
                    show_html += `<div class="weui-gallery__opr">
            <a href="javascript:" class="weui-gallery__del">
                <i class="weui-icon-delete weui-icon_gallery-delete"></i>
            </a>
        </div>`;
                }
                show_html += '</div>';
                $('body').append(show_html);
            }

            //上传所有音频
            function upload_all_voice() {
                $.ajax({
                    url: '{{ route('teacher_voice_upload') }}',
                    type: 'post',
                    data: {all_pos: all_pos, _token: now_token},
                    dataType: "json",
                    success: function (data) {
                        if (data.status === 1) {
                            for (let i in all_pos) {
                                $('.audio_box').append(`
                                    <div class="audio_body">
                                        <img class="has_voice" data-sid="${all_pos[i].localId}" src="/images/teacher/yuyin1.png" alt="">
                                    </div>
                                    `);
                            }
                        }
                    },
                    error: function (xhr, errorType, error) {
                        alert(error);
                    }
                });
            }

//            $(document).on('click','.now_photo',function () {
//                let type = 'show';
//                let img = $(this).next().attr('src');
//                if(!img){
//                    img = $(this).next()[0].style.backgroundImage;
//                    if(img.match(/url\(/)){
//                        img = img.substring(5,img.length-2);
//                    }
//                }
//                if($(this.next()).hasClass('edit')) {
//                    type = 'edit';
//                }
//                alert(img);
//                show_img(img,type);
//            });

            $(document).on('click', '.show_img', function () {
                let type = 'show';
                let img = $(this).attr('src');
                if (!img) {
                    img = $(this)[0].style.backgroundImage;
                    if (img.match(/url\(/)) {
                        img = img.substring(5, img.length - 2);
                    }
                }
                if ($(this).hasClass('edit')) {
                    type = 'edit';
                }
                show_img(img, type);
            });

            $(document).on('click', '.weui-gallery__img', function () {
                $('.weui-gallery').fadeOut();
            });

            $(document).on('click', '.del_this', function () {
                $(this).parent().remove();
            });

            $(document).on('click', '#all_done_confirm', function () {
                $.confirm({
                    title: '确认解答完毕',
                    text: '确认完成此次回答',
                    onOK: function () {
                        //上传图片
                        let img_all = [];
                        $('#img-box .show_img').each(function () {
                            img_all.push($(this).attr('data-img'));
                        });
                        let o = {
                            qid: qid,
                            img: img_all,
                            _token: now_token
                        };
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('teacher_img_update') }}',
                            data: o,
                            dataType: 'json',
                            success: function (s) {
                                if (s.status === 1) {
                                    $.toast('解答成功');
                                    window.location.reload();
                                }
                            },
                            error: function (error) {

                            }
                        });
                        upload_all_voice();
                        //window.location.reload();
                    },
                    onCancel: function () {

                    }
                });
            });

            $(document).on('click', '.audio_body', function () {
                $(this).find('audio')[0].play();
            });

            $(document).on('click','.record_img',function () {
                $('#voice_record').popup();
                $('#talk_img').attr('src',$(this).attr('src'));
                $('#talk_img').attr('data-img',$(this).attr('data-img'));
//                setTimeout(function () {
                $('#now_photo').width($('#talk_img').width());
                $('#now_photo').height($('#talk_img').height());
                //清除坐标和追加坐标
                $('#now_photo').parent().find('.has_voice').each(function () {
                    $(this).remove();
                });
                $(this).parent().find('.has_voice').each(function () {
                    let left_pos = $(this).attr('data-left')*$('#talk_img').width()-16;
                    let top_pos = $(this).attr('data-top')*$('#talk_img').height()-16;
                    let data_sid = $(this).attr('data-sid');
                    $('#now_photo').parent().append(`<img class="has_voice" style="position: absolute;z-index: 9991;width:32px;height:32px;left:${left_pos}px;top:${top_pos}px" data-sid="${data_sid}" src="/images/voice_now.png" />`);
                });

//                },300);
            });
        });
    </script>
@endpush