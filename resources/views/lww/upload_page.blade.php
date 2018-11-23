@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
<style>
    #show_cover_photo img{
        width:100%
    }
</style>
<link href="{{ asset('css/uploadfile.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="modal fade" id="show_cover_photo">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">查看图片</h4></div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">05网练习册管理</li>
        </ol>
    </section>
    <section class="content">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @component('components.modal',['id'=>'upload_img','title'=>'图片上传'])
            @slot('body')
                <div id="fileuploader">Upload</div>
                <div id="done_img" class="row"></div>
            @endslot
            @slot('footer')@endslot
        @endcomponent

        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 05网练习册管理</h3>
                <div class="input-group" style="width:50%" id="school_year_box">
                    <select class="form-control">
                        @foreach(range(17,21) as $year)
                        <option value="{{ $year.substr($data['real_book_id'],-1) }}" @if(substr($data['real_book_id'],-3,2)==$year) selected @endif>{{ $year }}学年</option>
                        @endforeach

                    </select>
                    <a class="input-group-addon btn btn-default" id="check_school_year">查看</a>
                    <a class="input-group-addon btn btn-primary" id="upgrade_school_year">升级</a>
                </div>
                <span class="pull-right">
                    {{--<a class="btn btn-danger save_order">保存排序</a>--}}
                    <a class="btn btn-default hide" id="set_image_size">按第一张图片整理大小</a>
                    <a class="btn btn-default hide" id="set_pages_number">一键整理页码</a>
                    <a class="btn btn-primary" data-toggle="modal" data-target="#upload_img">上传图片</a>
                    <a class="btn btn-default" href="{{ route('lww_chapter',[substr($data['real_book_id'],0,-3),substr($data['real_book_id'],-3,2),substr($data['real_book_id'],-1,1)]) }}">返回</a>
                </span>
            </div>
            <div class="box-body">
                @if($data['all_pages'])
                    @foreach($data['all_pages'] as $value)
                        <span class="col-md-3 text-center page_box" >
                        <a class="thumbnail cover_photo" data-target="#show_cover_photo" data-toggle="modal" title="{{ $value }}">
                            {{--<img data-original="{{ Storage::url('storage/'.$value).'?t='.time() }}"  style="height: 500px" alt="{{ $value }}" title="{{ $value }}" />--}}
                            <img data-original="{{ Storage::url($value).'?t='.time() }}"  style="height: 500px" alt="{{ $value }}" title="{{ $value }}" />
                        </a>
                            <span class="input-group">
                                <a>当前页码：{{ str_replace('.jpg','',array_last(explode('/',$value))) }}</a>
                                {{--<label for="input" class="input-group-addon">第</label>--}}
                                {{--<input data-id="{{ $value->id }}" class="form-control page_now text-center" value="{{ $value->page }}" />--}}
                                {{--<label for="input" class="input-group-addon">页</label>--}}
                                {{--<a class="btn btn-primary input-group-addon save_order" data-one="one">保存</a>--}}
                                <a class="btn btn-danger input-group-addon del_page_online" data-id="{{ $value }}">删除</a>
                            </span>
                        </span>
                    @endforeach
                @endif
                <div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery.uploadfile.min.js') }}"></script>
<script>

$(function(){
    const now_book_id = '{{ substr($data['real_book_id'],0,-3) }}'
    $('#check_school_year').click(function(){
        let now_version_year = $('#school_year_box select').val();
        window.location.href = '{{ route('lww_upload_page') }}/'+now_book_id+now_version_year;
    })

    $('#upgrade_school_year').click(function(){
        let upgrade_book_id = '{{ $data['real_book_id'] }}';
        if(confirm('确认升级此学年至新学年?')){
            alert('请耐心等待，后台将完成升级过程');
            axios.post('{{ route('lww_upgrade_year') }}',{upgrade_book_id}).then(response=>{

            })
        }

    })



})

    var lazy = new LazyLoad();

    $('.cover_photo').click(function () {
        var now_img_src = $(this).find('img').attr('data-original');
        $('#show_cover_photo').find('.modal-body').html(
            '<img src="'+now_img_src+'">'
        )
    });

    //重新生成文件名
    $('#set_pages_number').click(function () {
        axios.post('{{ route('lww_set_pages_number') }}',{book_id:'{{ $data['book_id'] }}'}).then(response=>{
            if(response.data.status==1){
                window.location.reload()
            }
        }).catch(function (error) {
            console.log(error);
        })
    });

    //统一图片大小
    {{--$('#set_image_size').click(function () {--}}
       {{--axios.post('{{ route('lww_set_image_size') }}',{book_id:'{{ $data['book_id'] }}'}).then(response=>{--}}
           {{--if(response.data.status==1){--}}
               {{--alert('操作完毕');--}}
           {{--}--}}
       {{--}).catch(function (error) {--}}
           {{--console.log(error);--}}
       {{--})--}}
    {{--});--}}

    $('.save_order').click(function () {
        let one = $(this).attr('data-one');
        let [id,page] = [];
        let orders = [];
        if(one==='one'){
            id = $(this).prev().prev().attr('data-id');
            page = $(this).prev().prev().val();
            orders[0] = {id:id,page:page};
        }else{
            $('.page_now').each(function (i) {
                id = $(this).attr('data-id');
                page = $(this).val();
                orders[i] = {id:id,page:page};
            });
        }

        axios.post('{{ route('lww_set_pages_order') }}',orders).then(response=>{
            alert(response.data.msg);
        }).catch(function (error) {
            console.log(error)
        })
    });

    $('.del_page').click(function () {
        let id = $(this).parents('.page_box').attr('data-id');
        let book_id = '{{ $data['book_id'] }}';
        axios.post('{{ route('lww_del_page') }}',{id:id,book_id:book_id}).then(response=>{
            if(response.data.status==1){
                $(this).parents('.page_box').remove();
            }else{
                alert('删除失败');
            }
        }).catch(function (error) {
            console.log(error)
        })
    });

    $('.del_page_online').click(function () {
            let img_path = $(this).attr('data-id');
              axios.post('{{ route('lww_del_page_online') }}',{img_path}).then(response=>{
                  if(response.data.status===1){
                      $(this).parents('.page_box').remove();
                  }
              })
        });


    $('#upload_img').on('hidden.bs.modal',function(){
        window.location.reload();
    })

    $("#fileuploader").uploadFile({
        url:"{{ route('upload_book_page',[$data['book_id']]) }}",
        fileName:"myfile",
        allowedTypes:"jpg,png,gif",
        showStatusAfterSuccess:false,
        onSuccess:function(files,data,xhr,pd)
        {
            if(data.status===1){
                $('#done_img').append(`<div class="col-md-3"><span class="close del_upload_img">&times;</span><a class="thumbnail"><img class="answer_pic" src="${data.img}"></a>`);
            }
        },
    });
</script>

@endpush