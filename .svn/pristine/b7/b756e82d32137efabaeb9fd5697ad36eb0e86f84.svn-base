@extends('layouts.backend')

@section('lww_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-hotspotter.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-ui-1.9.2.custom.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/pageeditor/editor.css') }}">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <style>
        #edui_fixedlayer{
            z-index: 2000 !important;
        }
        #myModal .book-box {
            width: 200px;
            height: 200px;
            background: #eee;
            padding: 5px 8px;
            border: 1px #ccc solid;
            float: left;
        }

        #myModal .book-box .img {
            width: 180px;
            height: 180px;
            float: left;
        }

        #myModal .book-box .img img {
            display: block;
            margin: auto;
            max-width: 180px;
        }

        #myModal .handle {
            float: right;
        }


        #img_operate_box_body.modal-body{
            padding: 0;
        }
        #edit-area.thumbnail{
            padding: 0;
        }
        #edit-area.thumbnail img{
            margin-left: 0;
        }
        .ed-top{
            text-align: unset;
            position: unset;
        }

    </style>
@endpush


@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent
    <div class="modal fade" id="img_operate_box">
        <div class="modal-dialog" style="width: 70%;">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body" id="img_operate_box_body">
                    <div class="ed-top">
                                                <span class="spot-options">
                                                    <button id="clone-btn" title="复制"><span class="btn-icon"><img src="{{ asset('images/pageeditor/clone.png') }}"/></span></button>
                                                    <button id="del-btn" title="删除"><span class="btn-icon"><img
                                                                src="{{ asset('images/pageeditor/del.png') }}"/></span></button>
                                                    {{--<button id="show_detail"><span class="btn-icon">预览</span></button>--}}
                                                </span>
                    </div>
                    <div>
                        <a class="thumbnail" id="edit-area" style="width: 100%;">
                            <img  id="ed-img" class="responsive " style="max-width: 100%;" data-src="http://user.1010pic.com/pic18/user_photo/20170902/08433d363f9cd5cfe64eed25b4c886e6.jpg?t=1509671440000" src="http://user.1010pic.com/pic18/user_photo/20170902/08433d363f9cd5cfe64eed25b4c886e6.jpg?t=1509671440000"/></a>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>

    </div>
<section class="content-header">
    <h1>控制面板</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
        <li class="active"></li>
    </ol>
</section>
<section class="content">
    <div class="box box-default color-palette-box">
        <div class="box-header text-center">
            <div id="book_info_box" class="panel panel-default col-md-2">
                <div class="panel-heading"><a target="_blank" href="{{ 'http://www.1010jiajiao.com/daan/bookid_'.$data['book_detail']->id.'.html' }}">{{ $data['book_detail']->bookname }}</a></div>
                <div>{{ $data['book_detail']->isbn }}</div>
                <a class="thumbnail"><img src="{{ $data['book_detail']->cover }}" /></a>
            </div>
            <div class="col-md-10 col-md-offset-2" style="position: absolute;bottom: 0;">
                <div style="bottom:0">
                <div id="page_index_box">
                @foreach($data['all_analysis_answers'] as $analysis)
                    <a class="btn btn-default now_page" data-page="{{ $analysis->page }}" data-pid="{{ $analysis->pid }}">{{ $analysis->page }}</a>
                @endforeach
                </div>
                <hr>
                <div>
                    <a class="btn btn-default" target="_blank" href="{{ route('lww_chapter',[$data['onlyid'],$data['year'],$data['volume_id'],$data['bookid']]) }}">章节划分</a>
                <a class="btn btn-primary save_page_index">保存排序</a>
                <a class="btn btn-primary add_page">新增页码</a>
                <a class="btn btn-primary del_page">删除页码</a>
                    <a class="btn btn-danger btn-large" id="convert_all_page">确认解析完毕,生成所有图片</a>
                </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="editor_box col-md-6">
            <div type="text/plain" id="E_add_analysis" name='question'
                 style="width:100%;"></div>
                <div class="btn btn-group">
                <button class="btn btn-danger margin save_message" style="float: left;">保存内容</button>
                <button class="btn btn-primary margin preview_content" style="float: left;">预览图片</button>
                <button class="btn btn-success margin create_analysis_img" style="float: left;">生成图片</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box collapsed-box upload_content_box">
                    <div class="box-header">
                        <h3 class="box-title">当前已上传内容页</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool show_upload_content" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="height: 500px;overflow: scroll"></div>
                </div>
                <hr>
                <div class="box show_answer_pic">
                    <div class="box-header">
                        <h3 class="box-title">答案图片展示</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="overflow: scroll">
                        @forelse($data['only_detail'] as $only_book)
                            @if($only_book->hasWorkbooks)
                                @forelse($only_book->hasWorkbooks as $books)
                                    @if($books->volumes_id==$data['volume_id'])
                                        <div class="box">
                                            <div class="box-header"><a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $books->id }}.html">{{ $books->bookname }}</a>
                                                <div class="box-tools pull-right">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="box-body" style="overflow-y: auto;display: flex">
                                                @forelse($books->hasAnswers as $answer)
                                                    <div style="min-width: 600px">
                                                    <a class="edit-area thumbnail offical_pic"><img class="ed-img" src="{{ config('workbook.user_image_url').$answer->answer }}" alt=""></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('need_js')
    <script src="{{ asset('js/jstree.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="{{ asset('js/pageeditor/jquery-hotspotter.min.js') }}"></script>
    <script src="{{ asset('js/pageeditor/editor_many_1.js').'?t='.time() }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/ueditor.config.js?t=111", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/ueditor1.all.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/lang/zh-cn/zh-cn.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/addKityFormulaDialog.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/getKfContent.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/defaultFilterFix.js", 0) }}"></script>
    <script>
        var toolbar = {
            toolbars: [[
                'source', '|', 'undo', 'redo',
                'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist','justifyleft','justifycenter','justifyright'
            ]], 'scaleEnabled': true, 'initialFrameHeight': 600
        };
        var ue = UE.getEditor(`E_add_analysis`, toolbar);
        ue.ready(function () {
            $(`#E_add_analysis`).find(".edui-toolbar").prepend('<div style="float:left;">此处填写答案</div>');
        });


        $(function () {
            //切换页面
            $(document).on('click','.now_page',function () {
                $('.now_page').removeClass('bg-red');
                $(this).addClass('bg-red');
                let now_pid = $(this).attr('data-pid');
                axios.post('{{ route('no_chapter_ajax',['get_message']) }}',{now_pid}).then(response=>{
                    if(response.data.status===1){
                        ue.setContent(response.data.data.message_html);
                    }
                })
            })


            //展示已上传内容页
            $('.show_upload_content').click(function () {
                if($('.upload_content_box').hasClass('collapsed-box') && $('.upload_content_box .box-body').html().length<1){
                    let year = '{{ $data['year'] }}';
                    let volume = '{{ $data['volume_id'] }}';
                    let bookid = '{{ $data['onlyid'] }}';
                    axios.post('{{ route('lww_get_bookimgs') }}',{year,volume,bookid}).then(response=>{
                        if(response.data.status==0){alert(response.data.msg);return;}
                        var str='';
                        $.each(response.data,function(i,e){
                            str+='<p class="thumbnail col-md-6 page_box" data-page="'+i+'"><img  alt="'+e+'"  class="answer_pic" src="http://daan.1010pic.com/'+e+'?t='+Date.parse(new Date())+'"></p>';
                        });
                        $('.upload_content_box .box-body').html(str);
                    });
                }
            });

            $('#page_index_box').sortable();

            //新增页码
            $('.add_page').click(function () {
                let bookid = '{{ $data['bookid'] }}';
                axios.post('{{ route('no_chapter_ajax','add_page') }}',{bookid}).then(response=>{
                    if(response.data.status===1){
                        let now_data = response.data.data;
                        $('#page_index_box').append(`<a class="btn btn-default now_page" data-page="${now_data.page}" data-pid="${now_data.pid}">${now_data.page}</a>`);
                    }
                })
            });

            //删除页码
            $('.del_page').click(function () {
                let bookid = '{{ $data['bookid'] }}';
                let now_pid = $('.now_page.bg-red');
                if (now_pid.length==0) {
                    alert('选择页码删除!');
                    return;
                }else{
                    now_pid = now_pid.attr('data-pid');
                }
                if(!confirm('确认删除页码?')){
                    return false;
                }
                axios.post('{{ route('no_chapter_ajax','del_page') }}',{bookid,now_pid}).then(response=>{
                    if(response.data.status===1){
                        $(`.now_page[data-pid="${now_pid}"]`).remove();
                        $('.save_page_index').click();
                        alert('删除成功,若已关联章节,请及时更新章节关联页码');
                    }else{
                        alert('删除失败');
                    }
                })
            });
            
            //保存排序
            $('.save_page_index').click(function () {
                if(!confirm('确认更新排序?')){
                    return false;
                }
                let page_index_box = []
                $('#page_index_box a').each(function (i) {
                    let now_page = i+1;
                    let now_pid = $(this).attr('data-pid');
                    page_index_box.push([now_page,now_pid])
                });
                if(page_index_box.length==0){
                    return false;
                }
                axios.post('{{ route('no_chapter_ajax','update_page_index') }}',{page_index_box}).then(response=>{
                    if(response.data.status===1){
                        $('#page_index_box a').each(function (i) {
                            let now_page = i+1;
                            $(this).attr({'data-page':now_page}).html(now_page);
                        });
                    }
                })
            })

            //保存内容
            $(".save_message").click(function () {
                var message = '';
                let now_pid = $('.now_page.bg-red');
                if (now_pid.length==0) {
                    alert('选择页码保存!');
                    return;
                }else{
                    now_pid = now_pid.attr('data-pid');
                }
                message = ue.getContent();
                // if (volume_id == 1) message = ue1.getContent();
                // if (volume_id == 2) message = ue2.getContent();
                // if (volume_id == 3) message = ue3.getContent();
                axios.post('{{ route('no_chapter_ajax','save_message') }}',{now_pid,message}).then(response=>{
                    if(response.data.status===1){
                        alert('已保存');
                    }
                })
            });

            //内容页移动
            $('.upload_content_box').draggable();
            $('.upload_content_box').resizable();

            //移动答案图片
            $('.show_answer_pic').draggable();
            $('.show_answer_pic').resizable();

            //预览
            $('.preview_content').click(function () {
                let now_pid = $('.now_page.bg-red');
                if (now_pid.length==0) {
                    alert('选择页码预览!');
                    return;
                }else{
                    now_pid = now_pid.attr('data-pid');
                }
                window.open(`http://handler.05wang.com/htm2pic/thread_preview/${now_pid}`);
            });

            //生成图片
            $('.create_analysis_img').click(function () {
                let now_pid = $('.now_page.bg-red');
                if (now_pid.length==0) {
                    alert('选择页码预览!');
                    return;
                }else{
                    now_pid = now_pid.attr('data-pid');
                }
                if(now_pid!=0){
                    let res = axios.post('{{ route('one_lww_ajax','renew_chapter_pic') }}',{'chapter_id':now_pid}).then(response=>{
                        if(response.data.code!=0){
                            alert('生成失败')
                        }
                    })
                }

            });

            //默认点击第一页
            $('#page_index_box a:first').click();




            Editor.init();
            Editor.initNewImage($('#ed-img').attr('src'));

            $(document).on('mousedown','.ed-img',function () {
                $('.edit-area').show();
                $('#edit-area').attr('data-id',$(this).parent().attr('data-id'));
                console.log($('#ed-img').attr('src'));
                console.log($(this).attr('src'));
                console.log(Editor.spotPool);
                Editor.spotPool = [];
                $('#ed-img').attr('src',$(this).attr('src'));
                $('#img_operate_box_body').appendTo($(this).parent().parent());
                $(this).parent().hide();
                console.log(($(this)).attr('src'));
//                  let now_src = $(this).attr('src');
//                  $('#edit-area').attr('data-id',$(this).parent().attr('data-id'));
//                  $('#edit-area img').attr('src',now_src);
                $('.red-spot').remove();
            });

            $('#img_operate_box').on('hide.bs.modal',function () {
                $('.red-spot').remove();
            });


            $(document).on('click','.add_to_ue',function () {
                let now_img_width = Editor.$edImg.width();
                let now_img_height = Editor.$edImg.height();
                let now_src = Editor.$edImg.attr('src');
                let sort_id = $(this).attr('data-id');
                if(Editor.spotPool[sort_id-1]!=undefined){
                    Editor.spotPool[sort_id-1].flushOptions();
                }
                let p = Editor.spotPool[sort_id-1];
                let image = new Image();
                image.src = now_src;
                let naturalWidth = image.width;
                let scale = naturalWidth/now_img_width;
                console.log(scale)
                let real_x = Math.round(p.coord[0]*scale);
                let real_y = Math.round(p.coord[1]*scale);
                let real_width = Math.round(p.dim[0]*scale);
                let real_height = Math.round(p.dim[1]*scale);


                let o = {now_img_width,now_img_height,now_src};

                let final_img_url = `<img src="${now_src}?x-oss-process=image/crop,x_${real_x},y_${real_y},w_${real_width},h_${real_height}"/>`;
                ue.setContent(final_img_url,1);
            });

            $(document).on('click','#convert_all_page',function () {
                if(!confirm('确认全部页面解析完毕?')){
                    return false;
                }
                $('.now_page').each(function (i) {
                    let now_pid = $(this).attr('data-pid');
                    axios.post('{{ route('one_lww_ajax','renew_chapter_pic') }}',{'chapter_id':now_pid});
                })
            })
        })

    </script>
@endpush