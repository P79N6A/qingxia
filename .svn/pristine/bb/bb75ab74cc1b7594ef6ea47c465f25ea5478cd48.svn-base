@extends('layouts.backend')

@section('audit_index','active')
    <style>
        .answer_pic {
            min-width: 150px;
            max-height: 350px;
            min-height: 200px;
        }
    </style>

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
    <style>
        .panel-body img{
            height: 400px;
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


    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">用户上传答案审核</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                用户上传答案审核
            </div>
            <div class="box-body no-padding">
               <div style="width: 200px; float: left">
                   <img class="img-responsive" src="{{ $data['bookinfo']->cover }}" style="width:150px;height: auto;">
               </div>
                <div>
                    <h3><a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $data['bookid'] }}.html">{{ $data['bookinfo']->bookname }}</a></h3>
                </div>
                <div>
                    <h4>Isbn:{{ $data['bookinfo']->isbn }}</h4>
                    @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')
                    @php
                    try{
                    echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$data['bookinfo']->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                    }catch (Exception $e){
                    echo '无法生成此isbn的条形码';
                    }
                    @endphp
                </div>
                <div style="margin-top: 30px;">
                    <a type="button" href="{{ route('audit_content_booklist',$data['type']).'?page='.$data['page'] }}" class="btn  btn-primary btn-lg">返回到列表</a>
                </div>
            </div>

        </div>



        @foreach($data['usercontent'] as $v)
            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="box-header">
                        <div class="info" style="float: left;width: 200px;">
                            <p>上传用户：{{ $v->hasUserInfo?$v->hasUserInfo->nickname:'' }}</p>
                            <p>上传时间：{{ $v->addtime }}</p>
                            <p>QQ：<a href="tencent://message/?Menu=yes&uin={{ $v->hasUserInfo?$v->hasUserInfo->qq:'' }}& Service=300&sigT=45a1e5847943b64c6ff3990f8a9e644d2b31356cb0b4ac6b24663a3c8dd0f8aa12a595b1714f9d45">{{ $v->hasUserInfo?$v->hasUserInfo->qq:'' }}</a></p>
                        </div>
                        @if($data['type']!=1 && $v->status==0)
                        <div class="btn-box"  style="float:right;" data-id="{{ $v->id }}" data-uid="{{ $v->up_uid }}" data-bookid="{{ $v->book_id }}">
                            <button type="button" class="btn  btn-success btn-lg pass">通过</button>
                            <button type="button" class="btn  btn-danger btn-lg not_pass">不通过</button>
                            <button type="button" class="btn  btn-primary btn-lg imperfect">内容不完整</button>
                        </div>
                         @elseif($data['type']==1)
                            <div class="btn-box"  style="float:right;" data-id="{{ $v->id }}"  data-bookid="{{ $data['bookid'] }}" data-upuid="{{ $v->up_uid }}">
                                <button type="button" class="btn  btn-primary btn-lg  cancel_pass">撤销通过</button>
                            </div>
                         @elseif($data['type']==0 && $v->status==2)
                            <div class="btn-box"  style="float:right;" data-id="{{ $v->id }}" >
                            <button type="button" class="btn  btn-primary btn-lg imperfect disabled">答案不完整</button>
                                <button type="button" class="btn  btn-primary btn-lg  recall">撤回不完整</button>
                            </div>
                        @endif
                    </div>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div style="overflow-y: auto;display: flex;" class="all_answer_now">

                        @foreach(explode('|',rtrim($v->content_img,'|')) as $k=>$img)
                        <div class="img_box" >
                            <button class="btn btn-xs btn-primary" onclick="rotate(this,270)">左转</button>
                            <button class="btn btn-xs btn-warning" onclick="rotate(this,90)">右转</button>
                            <button class="btn btn-xs btn-success" onclick="rotate(this,180)">倒置</button>
                            <a class="thumbnail">
                                @php
                                    if(!starts_with($img,'http://')){
                                    $img = config('workbook.user_image_url').$img;
                                    }
                                @endphp

                                <img class="answer_pic" data-id="{{ $v->id }}_{{ $k }}"  data-src="{{ $img }}" src="{{ $img.'?'.time() }}">
                            </a>
                            <button class="btn btn-xs btn-primary img_move" data-type="left">左移</button>
                            <button class="btn btn-xs btn-warning img_move" data-type="right">右移</button>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endforeach

        @if($data['type']==0) {{ $data['usercontent']->links() }} @endif
    </section>
@endsection

@push('need_js')
<script>
    function rotate(obj,degree){
        var url=$(obj).parent('.img_box').find('.answer_pic').attr("src");
        axios.post('{{ route('rotate_img') }}',{url,degree}).then(response=>{
            if(response.data.status===1){
                window.location.reload();
            }
        })
    }

    $(function(){
        $('.img_move').click(function () {
            var move_type = $(this).attr('data-type');
            if (move_type === 'right') {
                $(this).parent().next().insertBefore($(this).parent());
            } else {
                $(this).parent().insertBefore($(this).parent().prev());
            }
        });
        //翻页
        $('.page_now').click(function () {

            let page_to = $(this).attr('data-type');
            let now_img = $(this).parents('.modal-dialog').find('img');

            if(page_to=='prev'){
                var prev_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').prev().find('img');
                if(prev_img.length>0){
                    now_img.attr({'src':prev_img.attr('src'),'data-id':prev_img.attr('data-id')});
                }
            }else{
                var next_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').next().find('img');
                if(next_img.length>0){
                    now_img.attr({'src':next_img.attr('src'),'data-id':next_img.attr('data-id')});
                }
            }

        });

        $('.not_pass').click(function(){//不通过
            if(!confirm('确认不通过此答案?')){
                return false;
            }
            var id=$(this).parent('.btn-box').attr('data-id');
            var status=3;
            axios.post('{{ route('UpdateContentStatus') }}',{id,status}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                }
            })
        });

        $('.imperfect').click(function(){ //不完整
            if(!confirm('确认标记为不完整?')){
                return false;
            }
            var id=$(this).parent('.btn-box').attr('data-id');
            var status=2;
            axios.post('{{ route('UpdateContentStatus') }}',{id,status}).then(response=>{
                if(response.data.status===1){
                     window.location.reload();
                 }
            })
        });

        $('.recall').click(function(){ //撤回不完整
            var id=$(this).parent('.btn-box').attr('data-id');
            var status=0;
            axios.post('{{ route('UpdateContentStatus') }}',{id,status}).then(response=>{
                if(response.data.status===1){
                 window.location.reload();
                }
            })
        });

        $('.pass').click(function(){ //通过
            $(this).addClass('disabled');
            var box_info=$(this).parents('.box-info');
            var btn_box=$(this).parent('.btn-box');
            var id=btn_box.attr('data-id');
            var uid=btn_box.attr('data-uid');
            var bookid=btn_box.attr('data-bookid');
            var all_img = [];
            box_info.find('.answer_pic').each(function () {
                all_img.push($(this).attr('data-src'));
            });
            axios.post('{{ route('ContentPass') }}',{id,uid,bookid,all_img}).then(response=>{
                if(response.data.status===1){
                    window.location.href=`{{ route('audit_content_booklist',[0]) }}`;
                }
            })
        });


        $('.cancel_pass').click(function(){ //撤销通过
            var btn_box=$(this).parent('.btn-box');
            var id=btn_box.attr('data-id');
            var bookid=btn_box.attr('data-bookid');
            var up_uid=btn_box.attr('data-upuid');
            var type='cancel';

            axios.post('{{ route('Content_cancel') }}',{id,bookid,up_uid,type}).then(response=>{
                if(response.data.status===1){
                    window.location.href=`{{ route('audit_content_booklist',[1]).'?page='.$data['page']  }}`;
                }
            });

        });


    })






</script>
@endpush