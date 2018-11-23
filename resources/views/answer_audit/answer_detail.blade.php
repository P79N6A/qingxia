@extends('layouts.backend')

@section('audit_answer','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<link href="{{ asset('css/uploadfile.css') }}" rel="stylesheet">
    <style>
        .answer_pic{
            min-width: 150px;
            max-height: 350px;
            min-height:200px;
        }
        #img_download_box img{
            max-width: 200px;
        }
        #show_img{
            z-index:1051;
        }
        #show_img .modal-dialog{
            width:80%;
        }
        #upload_img .modal-dialog{
            width: 70%
        }
        .for_isbn_input{
            font-size: 24px;
        }
        #done_img{
            overflow: auto;
            max-height: 600px;
        }
        #done_img .col-md-3{
            max-height: 300px;
            overflow: auto;
        }
    </style>
@endpush

@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title')
            <strong>查看图片</strong>
            <span class="pull-right">
                <a class="page_now btn btn-primary" data-type="prev">上一页</a>
                <a class="page_now btn btn-primary" data-type="next">下一页</a>
            </span>
        @endslot
        @slot('body','')
        @slot('footer')
            <span class="pull-right">
                <a class="page_now btn btn-primary" data-type="prev">上一页</a>
                <a class="page_now btn btn-primary" data-type="next">下一页</a>
            </span>
        @endslot
    @endcomponent

    @component('components.modal',['id'=>'upload_img','title'=>'答案上传(拖动排序)'])
        @slot('body')
            <div id="fileuploader">Upload</div>
            <a onclick="$('#fileuploader ').submit();return false" class="btn btn-danger">上传</a>
            <div id="done_img" class="row"></div>
        @endslot
        @slot('footer')
            <a class="btn btn-warning confirm_answer" data-type="append">确认无误,按当前顺序追加答案</a>
            <a class="btn btn-danger confirm_answer" data-type="update">确认无误,按当前顺序替换答案</a>
        @endslot
    @endcomponent

    @component('components.modal',['id'=>'replace_img','title'=>'替换'])
        @slot('body')
            <div id="fileuploader_single">Upload</div>
            <div id="done_img" class="row"></div>
        @endslot
        @slot('footer','')
    @endcomponent

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">答案审核</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header">已有答案</div>
            <div class="box-body">
                <h3>练习册信息
                    <a class="btn btn-lg btn-success" target="_blank" href="{{ route('user_isbn_search',str_replace(['-','|'],'',$data['book_info']->isbn)) }}">isbn相关答案查看</a>
                    <a class="btn btn-lg btn-info" target="_blank" href="{{ route('new_book_history',[$data['book_info']->id,$data['book_info']->grade_id,$data['book_info']->subject_id,$data['book_info']->volumes_id,$data['book_info']->version_id,$data['book_info']->sort]) }}">不同年代练习册查看</a>
                    <a class="btn btn-lg btn-danger" id="confirm_done">确认处理完毕</a>
                    <a class="btn btn-lg btn-warning" id="not_need_deal">无需处理</a>
                </h3>
                <div class="box-body well single_book_info" data-id="{{ $data['book_info']->id }}">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#now_book" data-toggle="tab">{{ $data['book_info']->bookname }}<i class="badge bg-blue">{{ $data['book_info']->version_year }}</i><i class="badge bg-red">{{ $data['book_info']->collect_count }}</i><i class="badge bg-red">{{ $data['book_info']->concern_num }}</i></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="now_book">
                                <div class="row">
                                    <div class="col-md-6">

                                        @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')

                                        @if(strpos($data['book_info']->isbn,'|'))
                                            @forelse(explode('|',$data['book_info']->isbn) as $isbn)
                                                @php
                                                    try{
                                                    echo '<p class="text-center"><img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$isbn), $barcodeGenerator::TYPE_EAN_13)) . '"></p>';
                                                }catch (Exception $e){
                                                echo '无法生成此isbn的条形码';
                                                }
                                                @endphp
                                            @endforeach
                                        @else
                                            @php
                                            try{
echo '<p class="text-center"><img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$data['book_info']->isbn), $barcodeGenerator::TYPE_EAN_13)) . '"></p>';
                                            }catch (Exception $e){
                                                echo '无法生成此isbn的条形码';
                                            }
                                            @endphp
                                        @endif

                                                <div class="col-md-12">
                                                    <div>
                                                    <p class="img_box btn-group" data-id="cover" style="width: 50%;">

                                                        <a class="btn btn-danger btn-block img_replace" data-toggle="modal" data-target="#replace_img">替换封面</a>
                                                    </p>
                                                    <p class="img_box btn-group" data-id="cip" style="width: 46%;">
                                                        <a class="btn btn-danger btn-block img_replace" data-toggle="modal" data-target="#replace_img">替换cip</a>
                                                    </p>
                                                    </div>
                                                    <div>
                                                        <a class="thumbnail col-md-6" id="" data-target="#show_big_pic" data-hd-cover="none" data-toggle="modal">
                                                            <img data-id="offical_cover" class="answer_pic" src="{{ $data['book_info']->cover }}" alt="">
                                                        </a>
                                                        <a class="thumbnail col-md-6">
                                                            <img data-id="offical_cip" class="answer_pic"
                                                                 @if(starts_with($data['book_info']->cip_photo,'http://'))src="{{ $data['book_info']->cip_photo }}" @else @if($data['book_info']->cip_photo) src="{{ 'http://image.hdzuoye.com/'.$data['book_info']->cip_photo }}"  @else  src="" @endif  @endif alt="cip_信息页">
                                                        </a>
                                                    </div>
                                                </div>
                                    </div>
                                   {{-- <div class="col-md-6 book_info_box" data-id="{{ $data['book_info']->id }}">
                                        <div>
                                            <p><strong>练习册id:{{ $data['book_info']->id }}
                                                    @if($data['book_info']->id>10000000)<i class="badge bg-red">{{ $data['book_info']->collect_count }}</i><i class="badge bg-red">{{ $data['book_info']->concern_num }}</i>@endif
                                                </strong>
                                                <a class="btn btn-danger" href="https://s.taobao.com/search?q={{ str_replace(['-','|'],'',$data['book_info']->isbn) }}" target="_blank">淘宝搜索</a>{{ str_replace(['-','|'],'',$data['book_info']->isbn) }}
                                            </p>
                                            <a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $data['book_info']->id }}.html">{{ $data['book_info']->bookname }}</a>
                                        </div>
                                        <div class="input-group" style="width: 100%">
                                            <label class="input-group-addon">跳转id</label>
                                            <input type="text" class="form-control book_redirect_id" value="{{ $data['book_info']->redirect_id }}">
                                            <a class="input-group-addon btn btn-primary save_redirect_id" data-id="{{ $data['book_info']->id }}" >保存</a>
                                        </div>
                                        <div class="input-group" style="width: 100%">
                                            <label class="input-group-addon">书名</label>
                                            <input type="text" class="form-control book_name" value="{{ $data['book_info']->bookname }}">
                                        </div>
                                        <div class="input-group" style="width: 100%">
                                            <label class="input-group-addon">isbn</label>
                                            <input maxlength="17" class="for_isbn_input form-control isbn" value="{{ $data['book_info']->isbn }}">
                                        </div>
                                        <div class="input-group">
                                            <label class="input-group-addon">年份</label>
                                            <input type="text" maxlength="4" class="form-control version_year" value="{{ $data['book_info']->version_year }}">
                                        </div>
                                        <div class="input-group pull-left">
                                            <label class="input-group-addon">年级</label>
                                            <select data-name="grade" class="grade_id form-control select2 pull-left " >
                                                @forelse(config('workbook.grade') as $key => $grade)
                                                    <option value="{{ $key }}" @if($data['book_info']->grade_id==$key) selected="selected" @endif>{{ $grade }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group pull-left">
                                            <label class="input-group-addon">科目</label>
                                            <select data-name="subject" class="subject_id form-control select2">
                                                @forelse(config('workbook.subject_1010') as $key => $subject)
                                                    <option value="{{ $key }}" @if($data['book_info']->subject_id==$key) selected="selected" @endif>{{ $subject }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group pull-left">
                                            <label class="input-group-addon">卷册</label>
                                            <select data-name="volumes" class="volumes_id form-control select2">
                                                @forelse(config('workbook.volumes') as $key => $volume)
                                                    <option value="{{ $key }}" @if($data['book_info']->volumes_id==$key) selected="selected" @endif>{{ $volume }}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        <div style="width: 100%">
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">版本</label>
                                                <select data-name="version" class="version_id form-control select2">
                                                    @forelse(cache('all_version_now') as $key => $version)
                                                        <option value="{{ $version->id }}" @if($data['book_info']->version_id==$version->id) selected="selected" @endif>{{ $version->name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <label class="input-group-addon">系列</label>
                                            <select data-name="sort" class="form-control sort_name select2">
                                                <option value="{{ $data['book_info']->sort }}">{{ cache('all_sort_now')->where('id',$data['book_info']->sort)->first()?cache('all_sort_now')->where('id',$data['book_info']->sort)->first()->name:'待定' }}</option>
                                            </select>
                                        </div>

                                        <div class="btn btn-group">
                                            <a data-id="{{ $data['book_info']->id }}" class="save_book btn btn-danger">保存</a>
                                            <a class="btn btn-primary hide generate_name">生成</a>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="panel panel-primary">
                    <div class="panel-heading">现有答案
                        <a class="btn btn-xs btn-danger img_all_choose">全选/反选</a>&nbsp;
                        <a class="btn btn-danger move_to_trash" data-id="{{ $data['book_info']->id }}">将此练习册所有答案移至回收站</a>
                        <a class="btn btn-success append_last">上传答案</a>
                        <a class="btn btn-primary save_answer_order">按当前顺序保存答案</a>
                    </div>
                    <div class="panel-body">
                        <h3>当前显示答案</h3>
                        <div style="overflow-y: auto;display: flex" class="all_answer_now">
                            @foreach($data['offical_answer'] as $offical_answer)
                                <div class="img_box" data-id="{{ $offical_answer->id }}">
                                    <button data-toggle="modal" data-target="#replace_img" class="btn btn-xs btn-success img_replace">替换</button>
                                    <button class="btn btn-xs btn-danger img_delete">删除</button>
                                    <button class="btn btn-xs btn-primary img_choose">选中</button>
                                    <button class="btn btn-xs btn-warning img_append">追加</button>
                                    <a class="thumbnail">
                                        <img class="answer_pic" data-answer_id="{{ $offical_answer->id }}" data-id="offical_{{ $offical_answer->id }}"  src="{{ config('workbook.thumb_image_url').$offical_answer->answer }}">
                                    </a>
                                    <button class="btn btn-xs btn-primary img_move" data-type="left">左移</button>
                                    <button class="btn btn-xs btn-warning img_move" data-type="right">右移</button>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <h3>回收站答案</h3>
                        <div id="recycle_box" style="overflow-y: auto;display: flex">
                            @foreach($data['offical_answer_recycle'] as $offical_recycle_answer)
                                <div class="img_box" >
                                    <button class="btn btn-xs btn-primary img_choose">选中</button>
                                    <a class="thumbnail">
                                        <img class="answer_pic" data-id="offical_recycle_{{ $offical_recycle_answer->id }}" src="{{ config('workbook.thumb_image_url').$offical_recycle_answer->answer }}">
                                    </a>
                                    <a class="recovery_it" data-id="{{ $offical_recycle_answer->id }}">恢复</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="panel panel-danger">
                    <div class="panel-heading">用户上传答案<a class="btn btn-xs btn-danger img_all_choose">全选/反选</a></div>
                    <div class="panel-body">
                        @foreach($data['user_answer'] as $user_key=> $user_answer)
                            @php
                                $imgs = explode('|',$user_answer->answer_img)
                            @endphp
                            <div class="col-md-8" style="overflow-y: auto;display: flex">
                                @forelse($imgs as $key=> $img)
                                    @if(strlen($img)>20)
                                        <div class="img_box" data-id="user_{{ $user_answer->up_uid }}_{{ $key }}">
                                            <button class="btn btn-xs btn-primary img_choose">选中</button>
                                            <a class="thumbnail">
                                                <img class="answer_pic" data-id="user_{{ $user_answer->up_uid }}_{{ $key }}" src="{{ config('workbook.thumb_image_url').$img }}">
                                            </a>
                                        </div>
                                    @endif
                                    @endforeach
                            </div>
                            <div class="col-md-4">
                                <div class="box box-warning direct-chat direct-chat-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">审核反馈</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>

                                    <div class="box-body">
                                        <div class="direct-chat-messages">
                                            @forelse($data['user_message'][$user_key] as $key1=>$value1)
                                                <div class="direct-chat-msg @if($value1->uid != $value1->huid) right @endif ">
                                                    <div class="direct-chat-info clearfix">
                                                        <span class="direct-chat-timestamp pull-right">{{ $value1->add_time }}</span>
                                                    </div>
                                                    <div class="direct-chat-text">
                                                        {{ $value1->msg }}
                                                    </div>
                                                </div>
                                                @endforeach
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="input-group" data-id="{{ $data['book_id'] }}" data-up-id = "{{ $user_answer->up_uid }}">
                                            <input type="text" name="message" placeholder="输入审核意见" class="form-control">
                                            <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning btn-flat send_msg">发送</button>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-id="{{ $data['book_id'] }}" data-up-id="{{ $user_answer->up_uid }}">
                                <a class="hide btn btn-info">通过此答案</a>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
            <div style="position: fixed;bottom: 20px;right:10px;z-index: 999;">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">已选择答案<a class="btn btn-xs btn-primary" id="download_all_pic">下载</a>&nbsp;<a class="btn btn-xs btn-danger" id="clear_all_pic">清空</a></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="img_download_box" style="display: flex;width: 800px;overflow-y: auto;">
                            @foreach($data['offical_answer'] as $offical_answer)
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery.uploadfile.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {

            var lazyLoadInstances = [];
            var lazyLazy = new LazyLoad({
                elements_selector: ".cover-box",
                callback_set: function(el) {
                    var oneLL = new LazyLoad({
                        container: el
                    });
                    lazyLoadInstances.push(oneLL);
                }
            });
            var lazy = new LazyLoad();
            $('.select2').select2();
            const book_id = '{{ $data['book_id'] }}';
            //答案选中
            $('.img_choose').click(function () {
                let now_img = $(this).parents('.img_box').find('img').attr('data-id');
                if($(`#img_download_box img[data-id="${now_img}"]`).length>0){
                    $(`#img_download_box img[data-id="${now_img}"]`).parent().parent().remove();
                    $(this).removeClass('btn-danger').addClass('btn-primary').html('选中');
                }else{
                    $('#img_download_box').append(`<div class="single_download">
                    <span class="pull-right del_single_img" style="cursor: pointer">&times;</span>
                        <a class="thumbnail">${$(this).parents('.img_box').find('img').parent().html()}</a>
                    </div>`);
                    $(this).removeClass('btn-primary').addClass('btn-danger').html('已选中');
                }
            });

            //全选
            $('.img_all_choose').click(function () {
                $(this).parent().parent().find('.img_choose').each(function () {
                    $(this).click();
                })
            });

            //选中答案移除
            $(document).on('click','.del_single_img',function () {
                let now_img = $(this).next().find('img').attr('data-id');
                $(`img[data-id="${now_img}"]`).parents('.img_box').find('.img_choose').click();

            });

            //清空
            $('#clear_all_pic').click(function () {
                $('.img_choose.btn-danger').click();
            });

            //将所有答案移至回收站
            $('.move_to_trash').click(function () {
               if(confirm('确认将所有答案移至回收站')){
                   axios.post('{{ route('audit_api','move_to_trash') }}',{book_id}).then(response=>{
                       if(response.data.status===1){
                           window.location.reload();
                       }
                   }).catch(function () {});
               }
            });

            //下载选中答案
            $('#download_all_pic').click(function () {
                let book_id = '{{ $data['book_id'] }}';
                let all_img = [];
                $('#img_download_box .answer_pic').each(function () {
                    all_img.push($(this).attr('src'));
                });
                axios.post('{{ route('audit_api','download_img') }}',{book_id,all_img}).then(response=>{
                    if(response.data.status===1){
                        window.open(response.data.zip);
                    }else{
                        alert(response.data.msg);
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });
            //显示大图
//            $(document).on('click','.answer_pic',function () {
//                let img = $(this).attr('src');
//                $('#show_img').modal('show');
//                $('#show_img .modal-body').html(`<img width="100%" src="${img}" />`);
//            });

            //排序
            $( "#done_img" ).sortable();

            //上传
            $("#fileuploader").uploadFile({
                url:"{{ route('upload_now',$data['book_id']) }}",
                fileName:"myfile[]",
                multiple:true,
                allowedTypes:"jpg,png,gif",
                autoSubmit:1,
                showPreview:1,
                showStatusAfterSuccess:false,
                onSubmit: function (files, data) {
                    $('#done_img').append(`<div class="col-md-3" data-img="${files[0]}"><span class="close del_upload_img">&times;</span><a class="thumbnail"><img class="answer_pic" src=""></a>`);
                },
                onSuccess:function(files,data,xhr,pd)
                {
                    if(data.status===1){
                        $(`#done_img .col-md-3[data-img="${files[0]}"] img`).attr('src',data.img);
                    }
                },
            });

            //替换
            //上传
            $("#fileuploader_single").uploadFile({
                url:"{{ route('upload_now',$data['book_id']) }}",
                fileName:"myfile",
                allowedTypes:"jpg,png,gif",
                multiple:false,
                showStatusAfterSuccess:false,
                onSuccess:function(files,data,xhr,pd)
                {
                    if(data.status===1){
                        let now_img = data.img;
                        let answer_id = $('#replace_img').attr('data-id');
                        axios.post('{{ route('audit_api','replace_img') }}',{book_id,answer_id,now_img})
                            .then(response=>{

                            }).catch(function (error) {
                            console.log(error);
                        });
                        $('#replace_img').modal('hide');
                        $(`img[data-id="offical_${answer_id}"]`).attr('src',now_img);
                    }
                },
            });


            //删除已上传答案
            $(document).on('click','.del_upload_img',function () {
                $(this).parent().remove();
            });

            $('.append_last').click(function () {
                $('#upload_img').removeAttr('data-id');
                $('#upload_img').modal('show');
            });
            //更新答案
            $('.confirm_answer').click(function ()  {
                let update_type = $(this).attr('data-type');
                let append_id = $('#upload_img').attr('data-id');
                if(!append_id){
                    append_id = 0;
                }
                if(update_type==='update'){
                    if(append_id){
                        alert('当前为追加模式');
                        return false;
                    }else{
                        if(!confirm('确认覆盖当前答案')){
                            return false;
                        }
                    }

                }else{
                    if(!confirm('确认追加答案')){
                        return false;
                    }
                }
                let all_img = [];
                $('#done_img .answer_pic').each(function () {
                    all_img.push($(this).attr('src'));
                });
                if(all_img.length<1){
                    alert('请先上传图片');return false;
                }
                axios.post('{{ route('audit_api','update_answer') }}',{append_id,book_id,all_img,update_type})
                    .then(response=>{
                        if(response.data.status===1){
                            alert(response.data.msg);
                            window.location.reload();
                        }
                    }).catch(function (error) {
                    console.log(error);
                })
            })

            //发送消息给上传者
            $('.send_msg').click(function () {
                let box = $(this).parents('.direct-chat').find('.direct-chat-messages');
                let book_id = $(this).parent().parent().attr('data-id');
                let up_uid = $(this).parent().parent().attr('data-up-id');
                let msg = $(this).parent().prev().val();
                axios.post('{{ route('audit_api','send_msg') }}',{book_id,up_uid,msg}).then(response=>{
                    if(response.data.status===1){
                        box.append(`<div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-timestamp pull-right">{{ date('Y-m-d H:i:s',time()) }}</span>
                                        </div>
                                        <div class="direct-chat-text">${msg}</div>
                                    </div>`);
                        $(this).parent().prev().val('');
                    }else{
                        alert('发送失败,请重试');
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });

            //删除答案
            $('.img_delete').click(function () {
                let answer_id = $(this).parents('.img_box').attr('data-id');
                axios.post('{{ route('audit_api','delete_answer') }}',{book_id,answer_id}).then(
                    response=>{
                        if(response.data.status===1){
                            $(this).parents('.img_box').find('.img_replace').remove();
                            $(this).parents('.img_box').find('.img_delete').remove();
                            $(`.img_box[data-id=${answer_id}]`).prependTo($('#recycle_box'));
                        }else{
                            alert('删除失败');
                        }
                    }
                ).catch(function (error) {
                    console.log(error);
                })
            });
            //替换答案
            $('.img_replace').click(function () {
                let answer_id = $(this).parents('.img_box').attr('data-id');
                $('#replace_img').attr('data-id',answer_id);
            });
            //追加答案
            $('.img_append').click(function () {
                let answer_id = $(this).parents('.img_box').attr('data-id');
                $('#upload_img').modal('show');
                $('#upload_img').attr('data-id',answer_id)
            });


            //选择系列
            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('workbook_sort','sort') }}",
                    dataType: 'json',
                    delay: 100,
                    data: function (params) {
                        return {
                            word: params.term, // search term 请求参数
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items,//itemList
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // 自定义格式化防止xss注入
                minimumInputLength: 1,//最少输入多少个字符后开始查询
                templateResult: function formatRepo(repo) {
                    if (repo.loading) return repo.text;
                    return '<option value="' + repo.id + '">' + repo.name + '_' + repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });

            //isbn
            $('.for_isbn_input').bind('input propertychange', function() {

                if($(this).val().length===3){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length===5){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length>6) {
                    let now_start = $(this).val()[6];
                    if (now_start <= 3) {
                        if ($(this).val().length === 9) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start > 3 && now_start <= 5) {
                        if ($(this).val().length === 10) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '8') {
                        console.log($(this).val().length);
                        if ($(this).val().length === 11) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '9') {
                        if ($(this).val().length === 12) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    }
                    if ($(this).val().length === 15) {
                        $(this).val($(this).val() + '-');
                        this.selectionStart = this.selectionEnd = this.value.length + 1
                    }
                }
            });

            $('.img_move').click(function () {
                let move_type = $(this).attr('data-type');
                if (move_type === 'right') {
                    $(this).parent().next().insertBefore($(this).parent());
                } else {
                    $(this).parent().insertBefore($(this).parent().prev());
                }
            });
            //按当前排序保存答案
            $('.save_answer_order').click(function () {
                let img_ids = [];
                var from="{{ $data['type'] }}";
                $('.all_answer_now .img_box').each(function () {
                    img_ids.push($(this).attr('data-id'));
                });
                if(img_ids.length<1){
                    alert('保存失败');
                    return false;
                }
                axios.post('{{ route('audit_api','save_order') }}',{book_id,img_ids,from}).then(response=>{
                    alert('保存成功');
                }).catch();
            });


            //保存练习册信息
            $('.save_book').click(function () {
                let now_book_id = $(this).attr('data-id');
                let now_book_box = $(this).parents('.book_info_box');
                let book_name = now_book_box.find('.book_name').val();
                let redirect_id = now_book_box.find('.book_redirect_id').val();
                let isbn = now_book_box.find('.for_isbn_input').val();
                let version_year = now_book_box.find('.version_year').val();
                let grade_id = now_book_box.find('.grade_id').val();
                let subject_id = now_book_box.find('.subject_id').val();
                let volumes_id = now_book_box.find('.volumes_id').val();
                let version_id = now_book_box.find('.version_id').val();
                let sort = now_book_box.find('.sort_name').val();
                axios.post('{{ route('audit_api','save_book') }}',{redirect_id,now_book_id,book_name,isbn,version_year,grade_id,subject_id,volumes_id,version_id,sort}).then(response=>{
                    if(response.data.status===0){
                        alert('保存失败');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
                console.log()
            });


            //处理完毕
            $('#confirm_done').click(function () {
                if(confirm('确认处理完毕')){
                    axios.post('{{ route('audit_api','confirm_done') }}',{book_id}).then(response=>{

                    }).catch(function (error) {
                        console.log(error);
                    })
                }
            });

            //无需处理
            $('#not_need_deal').click(function () {
                if(confirm('确认无需处理')){
                    axios.post('{{ route('audit_api','not_need_deal') }}',{book_id}).then(response=>{

                    }).catch(function (error) {
                        console.log(error);
                    })
                }
            });

            //翻页
            $('.page_now').click(function () {
                let page_to = $(this).attr('data-type');
                let now_img = $(this).parents('.modal-dialog').find('img')

                if(page_to=='prev'){
                    let prev_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').prev().find('img');
                    if(prev_img.length>0){
                        now_img.attr({'src':prev_img.attr('src'),'data-id':prev_img.attr('data-id')});
                    }
                }else{
                    let next_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').next().find('img');
                    if(next_img.length>0){
                        now_img.attr({'src':next_img.attr('src'),'data-id':next_img.attr('data-id')});
                    }
                }
//                $(`img[data-status='now_modal_content']`).removeAttr('data-status');
//                now_img.attr('data-status','now_modal_content');
            })

            $('.select2').change(function () {
                $(this).parents('.book_info_box').find('.generate_name').click();
            });
            //生成书名
            $('.generate_name').click(function () {
                let book_info_box = $(this).parents('.book_info_box');
                let version_year = book_info_box.find('.version_year').val();
                let sort_name = '';

                let now_name = book_info_box.find('.sort_name').select2('data')[0].name;
                let now_text = book_info_box.find('.sort_name').select2('data')[0].text;
                if(now_name!==undefined){
                    sort_name = now_name
                }else{
                    sort_name = now_text
                }

                let grade_name = book_info_box.find('.grade_id option:selected').text();
                let subject_name = book_info_box.find('.subject_id option:selected').text();
                let volume_name = book_info_box.find('.volumes_id option:selected').text();
                let version_name = book_info_box.find('.version_id option:selected').text();
                let book_name = version_year+'年'+sort_name+grade_name+subject_name+volume_name+version_name;

                book_name = book_name.replace('上册','下册');
                book_name = book_name.replace('全一册上','全一册下');
                book_name = book_name.replace('全一册','全一册下');
                book_name = book_name.replace('思想品德','道德与法治');
                book_info_box.find('.book_name').val(book_name)
            });


            //保存跳转id
            $(document).on('click','.save_redirect_id',function () {
                let book_id = $(this).attr('data-id');
                let redirect_id = $(this).prev().val();
                axios.post('{{ route('audit_api','update_redirect') }}',{book_id,redirect_id}).then(response=>{

                }).catch();
            });

            $('.recovery_it').click(function () {
                let answer_id = $(this).attr('data-id');
                axios.post('{{ route('audit_api','recovery_answer') }}',{answer_id}).then(response=>{
                    $(this).parents('.img_box').remove();
                })
            })
        });
    </script>
@endpush