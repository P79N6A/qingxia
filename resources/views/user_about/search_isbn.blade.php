@extends('layouts.backend')

@section('user_feedback','active')

@push('need_css')
    <style>
        .answer_pic{
            min-width: 150px;
            max-height: 350px;
            min-height:200px;
        }
    </style>
@endpush

@section('content')

    @component('components.modal',['id'=>'show_img','title'=>'查看图片'])
        @slot('body','')
        @slot('footer','')
    @endcomponent

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">用户反馈-isbn相关答案</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> isbn相关答案</h3>
            </div>
            <div class="box-body">
                @forelse($data['all_books'] as $book)
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{ $book->bookName }}<a class="btn btn-xs btn-danger img_all_choose">全选/反选</a></div>
                        <div class="panel-body">
                            <div class="col-md-3" data-id="{{ $book->objectId }}">
                                <a class="thumbnail">
                                    <img src="http://authimage.hdzuoye.com/{{ auth_url($book->coverImage) }}">
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <div style="overflow-y: auto;display: flex">
                                @forelse($book->has_answers as $key=> $answer)
                                    <div class="img_box" data-id="{{ $answer->id }}">
                                        <button class="btn btn-xs btn-primary img_choose">选中</button>
                                        <a class="thumbnail">
                                            @if($book->answerType===2)
                                                <img class="answer_pic" data-id="offical_{{ $answer->id.'_'.$key }}"  src="{{ 'http://authimage.hdzuoye.com/'.auth_url($answer->answerPathImage) }}">
                                            @else
                                                <img class="answer_pic" data-id="offical_{{ $answer->id.'_'.$key }}"  src="@if(starts_with($answer->answerPathImage,'zone/answer')) {{  'http://authimage.hdzuoye.com/'.auth_url($answer->answerPathImage) }} @else {{  'http://authimage.hdzuoye.com/'.auth_url('zone/answer/'.$answer->answerPathImage) }} @endif">
                                            @endif
                                        </a>
                                    </div>

                                    @endforeach
                            </div>
                        </div>
                    </div>

                    @endforeach
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script>
        $(function () {
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
            //选中答案移除
            $(document).on('click','.del_single_img',function () {
                let now_img = $(this).next().find('img').attr('data-id');
                $(`img[data-id="${now_img}"]`).parents('.img_box').find('.img_choose').click();

            });
            //清空
            $('#clear_all_pic').click(function () {
                $('.img_choose.btn-danger').click();
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
            $(document).on('click','.answer_pic',function () {
                let img = $(this).attr('src');
                $('#show_img').modal('show');
                $('#show_img .modal-body').html(`<img width="100%" src="${img}" />`);
            });

            //全选
            $('.img_all_choose').click(function () {
                $(this).parent().parent().find('.img_choose').each(function () {
                    $(this).click();
                })
            });
        });



    </script>
@endpush