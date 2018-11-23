@extends('layouts.backend')

@section('new_book_buy','active')

@push('need_css')
<style>
    #show_cover_photo img{
        width:100%
    }
</style>
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
            <li class="active">新练习册答案查看</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 新练习册答案查看</h3>

                <span class="pull-right">
                    {{--<a class="btn btn-danger save_order">保存排序</a>--}}
                    <a class="btn btn-default hide" id="set_image_size">按第一张图片整理大小</a>
                    <a class="btn btn-default hide" id="set_pages_number">一键整理页码</a>
                </span>

            </div>
            <div class="box-body">
                @if($data['all_pages'])
                    @foreach($data['all_pages'] as $value)
                        <span class="col-md-3 text-center page_box">
                        <a class="thumbnail cover_photo" data-target="#show_cover_photo" data-toggle="modal" title="{{ $value }}">
                            <img src="{{  $value }}"  style="height: 500px" alt="{{ $value }}" title="{{ $value }}" />
                        </a>
                            {{--<span class="input-group">--}}
                                {{--<label for="input" class="input-group-addon">第</label>--}}
                                {{--<input data-id="{{ $value->id }}" class="form-control page_now text-center" value="{{ $value->page }}" />--}}
                                {{--<label for="input" class="input-group-addon">页</label>--}}
                                {{--<a class="btn btn-primary input-group-addon save_order" data-one="one">保存</a>--}}
                                {{--<a class="btn btn-danger input-group-addon del_page" data-id="{{ $value->id }}">删除</a>--}}
                            {{--</span>--}}
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
<script>
    //var lazy = new LazyLoad();

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

</script>

@endpush