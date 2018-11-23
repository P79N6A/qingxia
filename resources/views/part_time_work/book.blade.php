@extends('layouts.backend')

@section('part_time_index','active')

@push('need_css')
   {{-- <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">--}}
    <link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


<style type="text/css">
    #getVerifyCode{cursor: pointer; outline: none;}
    .alert {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        min-width: 200px;
        margin-left: -100px;
        z-index: 99999;
        padding: 15px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }

    .alert-warning {
        color: #8a6d3b;
        background-color: #fcf8e3;
        border-color: #faebcc;
    }

    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
    .ce2{
        position: fixed;
        right:0;
        top:0;
    }
</style>

@endpush


@section('content')

<section class="content-header">
    <h1>编写答案</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
        <li class="active"></li>
    </ol>
</section>
<section class="content">
    <div class="box box-default color-palette-box">
        <div id="rightContent">
        <div class="box">
            <h3 class="FontBig">{{ $data['bookname'] }}</h3>
            <button class="btn btn-success book_success" style="float: right;">本书完成</button>
            <div class="nav-tabs-custom" style="margin-top: 50px;">
               <div class="row" style="margin-bottom: 10px;">
                   <div id="chapter_box"  style="margin:0 auto; width:600px;">
                        <button class="btn btn-primary col-md-2 chapter_btn left" data-type="prev">上一章</button>
                        <div class="carousel-inner col-md-8" style="text-align: center; width:62%">
                            @foreach($data['chapter_arr'] as $k=>$v)
                            <div class="item chapter @if($k==0) active @endif" data-id="{{ $v['id'] }}">{{ $v['name'] }}</div>
                            @endforeach
                        </div>
                        <button class="btn btn-primary col-md-2 chapter_btn right" data-type="next">下一章</button>
                    </div>
               </div>

               <div class="row">
                    <div class="col-md-7">
                        <div id="myCarousel" class="clear carousel slide" data-interval="false">
                            <div class="carousel-inner" >
                                @foreach($data['content'] as $k=>$img)
                                    <div class="item @if($k==1) active @endif">
                                        <a style="overflow-x: scroll" class="thumbnail show_big">
                                            <img  src="{{ "http://daan.1010pic.com/".$img  }}" />
                                        </a>
                                    </div>
                                    @endforeach
                            </div>
                            <span class="carousel-control left" data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></span>
                            <span class="carousel-control right" data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></span>
                        </div>
                    </div>
                    <div class="col-md-5" id="ue_box" >
                        <div type="text/plain" id="E_add_1" name='question' style="width:100%;"></div>
                        <button class="btn btn-primary save_message" style="float: right;">保存本章内容</button>
                    </div>

                </div>
            </div>


            <div class="alert"></div>

        </div>
    </div>
    </div>
</section>

@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script type="text/javascript" src="{{ asset("ueditor/ueditor.config.js", 0) }}"></script>
<script type="text/javascript" src="{{ asset("ueditor/ueditor1.all.js", 0) }}"></script>
<script type="text/javascript" src="{{ asset("ueditor/lang/zh-cn/zh-cn.js", 0) }}"></script>
<script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/addKityFormulaDialog.js", 0) }}"></script>
<script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/getKfContent.js", 0) }}"></script>
<script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/defaultFilterFix.js", 0) }}"></script>

<script>
    var toolbar = {
        toolbars: [[
            'source', '|', 'undo', 'redo',
            'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist'
        ]], 'scaleEnabled': true, 'initialFrameHeight': 400,
    };
    var ue1 = UE.getEditor('E_add_1', toolbar);
    ue1.ready(function () {
        $("#E_add_1").find(".edui-toolbar").prepend('<div style="float:left;">此处填写解析答案</div>');
    });


    init();

    $(function(){
        $('.skin-blue').addClass('sidebar-collapse');

        $('#myCarousel .carousel-control').click(function(){
            var type=$(this).attr('data-slide');
            var show_imgbox=$('#myCarousel .carousel-inner .active');
            if(type=='prev'){
                if(show_imgbox.prev().length>0){
                    show_imgbox.prev().addClass('active');
                    show_imgbox.removeClass('active');
                }
            }else{
                if(show_imgbox.next().length>0){
                    show_imgbox.next().addClass('active');
                    show_imgbox.removeClass('active');
                }

            }
        });

        $('#chapter_box .chapter_btn').click(function(){
            var type=$(this).attr('data-type');
            var chapter=$('#chapter_box .active');
            if(type=='prev'){
                if(chapter.prev().length>0){
                    chapter.prev().addClass('active');
                    chapter.removeClass('active');
                }
            }else{
                if(chapter.next().length>0){
                    chapter.next().addClass('active');
                    chapter.removeClass('active');
                }

            }
            init();
        });


        $(".save_message").click(function () {
            var message = ue1.getContent();
            var id = $(this).attr('data-id');
            /*if(!confirm('确认保存对此章节的编辑吗？')){
                return false;
            }*/
            axios.post('{{ route('one_lww_ajax','save_message') }}',{id,message}).then(response=>{
                if(response.data.status===1){
                    $('.alert').html('保存成功').addClass('alert-success').show().delay(2000).fadeOut();
                    $('#chapter_box .right').click();
                }
            })
        });

        $(".book_success").click(function(){
            var bookid={{ $data['bookid'] }};
            axios.post('{{ route('book_success') }}',{bookid}).then(response=>{
                if(response.data.status===1){
                    window.location.href=`{{ route('part_time_booklist',1) }}`;
                }
            });
        });

    })

    function init(){

        var id=$('#chapter_box .active').attr('data-id');
        $('.save_message').attr('data-id',id);
        axios.post('{{ route('one_lww_ajax','get_message') }}',{id}).then(response=>{
            if(response.data.status===1){
                ue1.setContent(response.data.data.message_html);
            }
        });
    }



    $(window).scroll(function () {
        var top = $(window).scrollTop();
        if (top > 230) {
            $("#ue_box").addClass("ce2");
        }
        else {
            $("#ue_box").removeClass("ce2");
        }
    });
</script>

@endpush

