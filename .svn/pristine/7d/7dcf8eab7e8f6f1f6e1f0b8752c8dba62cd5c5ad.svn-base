@extends('layouts.backend')

@section('video_manage')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
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
</style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">视频管理</li>
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
                            <select id="book_confirm" class="select2 form-control" style="width: 50%;">
                                @foreach($data['book_list'] as $value)
                                    <option value="{{ $value->id }}">{{ $value->bookName }}</option>
                                @endforeach
                            </select>
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
                            <button type="button" class="btn btn-success" onclick="initUpload_before()">上传视频</button>
                        </div>
                        <div id="testupload" class="text-center">
                            <button type="button" class="btn btn-success hide" onclick="initUpload();">上传视频</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div><a class="btn btn-danger">确认</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="video-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"></div>
                    <div class="modal-body text-center"></div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="video-sort">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">章节顺序修改<span class="pull-right close" data-dismiss="modal">&times;</span></div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer"></div>
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
                        <label for="book_confirm_modify"></label><select id="book_confirm_modify" class="select2 form-control" style="width:100%;">
                            <option>请选择课本</option>
                            @foreach($data['book_list'] as $value)
                                <option value="{{ $value->id }}">{{ $value->bookName }}</option>
                            @endforeach
                        </select>
                        <hr>
                        <label for="show_confirm_modify"></label>
                        <select id="show_confirm_modify" class="select2 form-control" style="width:100%">
                            <option>请选择状态</option>
                            <option value="1">上架</option>
                            <option value="0">不上架</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <div><a class="btn btn-danger" id="confirm_modify_btn">确认</a><a id="show-video-chapter" data-target="#video-sort" data-toggle="modal" class="btn btn-success">修改章节顺序(拖动排序)</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title pull-left"><i class="fa fa-tag"></i> 视频相关
                </h3>
                <ul class="nav nav-pills pull-left" style="margin-left: 30px;">
                    <li>
                        <button data-toggle="dropdown" class="btn btn-primary">选择练习册</button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('video_manage') }}">全部练习册</a></li>
                            @foreach($data['all_video_info'] as $video)
                                <li><a href="{{ route('video_book_show',[$video->book_id]) }}">{{ $data['book_list']->find($video->book_id)->bookName }}<small class="label pull-right bg-red ">{{ $video->num }}</small></a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <button data-toggle="modal" data-target="#for_upload" class="btn btn-primary btn-bg pull-right video_upload_btn">上传视频</button>
            </div>
            <div class="box-body" id="video_list">
                @if(!empty($data['video_list_other']))
                    @foreach($data['video_list_other'] as $key=>$value)

                        <div class="col-md-3">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">{{ $data['video_list_other_fid'][$key]['name'] }}</h3></div>
                                <div class="box-body">
                                    <div align="center">
                                        <div>
                                            @if($value->code=='A00000')
                                                <p>视频处理完成</p>
                                            @elseif($value->code=='Q00001')
                                                失败
                                            @elseif($value->code=='A00001')
                                                视频发布中
                                            @elseif($value->code=='A00002')
                                                视频审核失败
                                            @elseif($value->code=='A00003')
                                                视频不存在
                                            @elseif($value->code=='A00004')
                                                视频上传中
                                            @elseif($value->code=='A00006')
                                                用户取消上传
                                            @elseif($value->code=='A00007')
                                                视频发布失败
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="overlay">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                            <div class="player"  data-id="{{ $data['video_list_other_fid'][$key]['key']}}">
									<span>
										<button class="btn btn-xs btn-danger video_del_btn" data-target="#video-modal" data-toggle="modal">删除</button>
									</span>
                            </div>
                        </div>

                    @endforeach
                @endif
                @if(!empty($data['video_list_online']))
                    @foreach($data['video_list_online']->data as $key=>$value)
                        <div class="col-md-3">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <span class="box-title video_title" title="{{ $value->fileName }}">{{ str_limit($value->fileName,20,'...') }}</span>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool get_video" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div align="center">
                                        <div class="player" data-id="{{ $value->fileId }}" data-book="{{ $data['ids_books'][$value->fileId] }}" data-status="{{ $data['ids_status'][$value->fileId] }}">
                                            <a class="thumbnail show_video">
                                                <img src="{{ $value->img }}" title="{{ $value->fileName }}" alt="{{ $value->description }}">
                                            </a>
                                            <span>
                                            @if($data['ids_status'][$value->fileId]==1)
                                                    <p class="text-center bg-blue">已上架</p>
                                                @else
                                                    <p class="text-center bg-red">暂未上架</p>
                                                @endif
                                                <button class="btn btn-xs btn-success video_play_btn" data-target="#video-modal" data-toggle="modal">播放</button>
                                            <button class="btn btn-xs btn-danger video_del_btn" data-target="#video-modal" data-toggle="modal">删除</button>
                                            <button class="btn btn-xs btn-primary video_modify_btn" data-target="#modify-modal" data-toggle="modal">修改状态</button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div>
                {{ $data['video_list_local']->links() }}
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="/js/sdkbase_min.js"></script>
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
    $('.select2').select2();
    var post_token = '{{ csrf_token() }}';
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
    var token = document.getElementById("token");
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
                    chang_fid.get('plugin.php?id=bookinfo:aiqiyi&pid={echo $pid}&fid=' + fileIdBefore, function () {
                    });
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
                            var book_id = $('#book_confirm').val();
                            var insert_data = video_record(book_id,video_name,video_descript,data.data.file_id);
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
            '_token': post_token
        };
        $.ajax({
            type: 'POST',
            data: postData,
            url: '{{ route('video_del') }}',
            dataType: 'json',
            success: function (s) {},
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


    function video_record(book_id,name,description,vid) {
        var post_data = {
            'book_id':book_id,
            'name':name,
            'description':description,
            '_token':post_token,
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
        $('#for_upload').modal('hide');
        $('#video_list').prepend('<div class="col-md-3"> ' +
            '<div class="box box-primary"> ' +
            '<div class="box-header"> ' +
            '<h3 class="box-title">'+video_name+'</h3> </div> ' +
            '<div class="box-body text-center">视频发布中 </div> ' +
            '<div class="overlay"> ' +
            '<i class="fa fa-refresh fa-spin"></i> ' +
            '</div> </div> <div class="player"  data-id="'+vid+'"> ' +
            '<span> <button class="btn btn-xs btn-danger video_del_btn" data-target="#video-modal" data-toggle="modal">删除</button> ' +
            '</span></div></div>');
    }

    $(document).on('click','.video_play_btn, .video_del_btn, .video_modify_btn,.video_upload_btn',function () {
        var file_id = $(this).parents('.player').attr('data-id');
        var play_box = $('#video-modal .modal-body');
        var header_box = $('#video-modal .modal-header');
        header_box.attr('data-vid',file_id);
        var footer_box = $('#video-modal .modal-footer');
        if($(this).hasClass('video_play_btn')){
            header_box.html('播放视频<span class="pull-right close" data-dismiss="modal">&times;</span>');
            getStatus(play_box,file_id);
            footer_box.html();
        }else if($(this).hasClass('video_del_btn')){
            header_box.html('确认删除视频?<span class="pull-right close" data-dismiss="modal">&times;</span>');
            play_box.html('');
            footer_box.html('<div><a id="confirm_del_btn" class="btn btn-danger">确认</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>')
        }else if($(this).hasClass('video_modify_btn')){
            $('#modify-modal .modal-header').attr('data-vid',file_id);
            var book_id = $('.player[data-id="'+file_id+'"]').attr('data-book');
            var show_status = $('.player[data-id="'+file_id+'"]').attr('data-status');
            $('#book_confirm_modify').val(book_id).trigger("change");
            $('#show-video-chapter').attr('data-book-id',book_id);
            $('#show_confirm_modify').val(show_status).trigger("change");
            $('#video_name_confirm').val($('.player[data-id="'+file_id+'"] img').attr('title'));
            $('#video_descript_confirm').val($('.player[data-id="'+file_id+'"] img').attr('alt'));
        }else if($(this).hasClass('video_upload_btn')){

//            header_box.html('视频上传<span class="pull-right close" data-dismiss="modal">&times;</span>');
//            play_box.html($('#for_upload').html());
//            footer_box.html('<div><a class="btn btn-danger">确认</a><a class="btn btn-default" data-dismiss="modal">取消</a></div>');

        }
    });

    $(document).on('click','#confirm_del_btn',function () {
        var file_id = $('#video-modal .modal-header').attr('data-vid');
        delVideo(file_id);
    });

    $(document).on('click','#confirm_modify_btn',function () {
        var file_id = $('#modify-modal .modal-header').attr('data-vid');
        var book_id =  $('#book_confirm_modify').val();
        var status =  $('#show_confirm_modify').val();
        var video_name = $('#video_name_confirm').val();
        var video_descript = $('#video_descript_confirm').val();
        var pos_data = {
            vid :file_id,
            book_id :book_id,
            name:video_name,
            description:video_descript,
            show_status :status,
            _token :post_token
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
                    $('.player[data-id="'+file_id+'"]').parents('.col-md-3').find('.video_title').html(video_name);
                    $('.player[data-id="'+file_id+'"] img').attr({'title':video_name,'alt':video_descript});
                    $('.player[data-id="'+file_id+'"]').attr({'data-book':book_id,'data-status':status});
                    if(status==1){
                        $('.player[data-id="'+file_id+'"]').find('p').removeClass('bg-red').addClass('bg-blue').html('已上架');
                    }else{
                        $('.player[data-id="'+file_id+'"]').find('p').removeClass('bg-blue').addClass('bg-red').html('暂未上架');
                    }
                    $('#modify-modal').modal('hide');
                }
            },
            error: function (s) {

            }
        })
    });

    //展示章节
    $(document).on('click','#show-video-chapter',function () {
        var book_id = $(this).attr('data-book-id');
        var o = {
            'book_id':book_id,
            '_token': post_token
        };
        $('#modify-modal').modal('hide');
        $.ajax({
            type:'POST',
            url:'{{ route('get_video_chapter') }}',
            data:o,
            dataType:'json',
            success:function (s) {
                if(s.status==1){
                    var data_now = s.data;
                    var data_len = data_now.length;
                    var chapter_list = '<ul id="sortable">';
                    for(var i =0; i<data_len;i++){
                        chapter_list += '<li class="chapter_drag" data-vid="'+data_now[i]['vid']+'"><a>'+data_now[i]['name']+'</a></li>'
                    }
                    chapter_list += '</ul>';
                    $('#video-sort .modal-body').html(chapter_list);
                    //$("#video-sort .modal-body").find('.chapter_drag').draggable();
                    $( "#sortable" ).sortable({
                        stop:function (event,ui) {
                            var ids = '';
                            var sorts= '';
                            $('#sortable li').each(function (x) {
                                ids += $(this).attr('data-vid')+'|';
                            });
                            $.post('{{ route('set_chapter_sort') }}',{_token:post_token,vids:ids},function () {});
                        },
                        revert: true
                    });
                }else{
                    $('#video-sort .modal-body').html('<p>'+s.msg+'</p>')
                }
            },
            error:function () {

            }
        });


    });





    //    $('.get_video').click(function () {
    //        alert('qwe');
    //    })
</script>
@endpush