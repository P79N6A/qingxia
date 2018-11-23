@extends('layouts.backend')

@section('lww_index','active')

@push('need_css')
    <style>
        .book_box{
            border: 1px solid #000;
            display: flex;
            height: 250px;
        }
        .book_box .img_box img{
            max-width: 150px;
            height: auto;
        }
        .bookname_box button,.bookname_box span{
            width: 150px;
            white-space: inherit;
        }
    </style>
@endpush



@section('content')

    @component('components.modal',['id'=>'show_img'])
    @slot('title')
    <strong>查看图片</strong>
    @endslot
    @slot('body','')
    @slot('footer')
    @endcomponent

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active"></li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
            <div id="rightContent">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">封面对应</h3>
                        <button class="btn btn-primary save_pic">扫描图片</button>
                        <div class="text_box"></div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding col-md-12">
                        @foreach($data as $v)
                        <div class="book_box col-md-6" data-id="{{ $v->id }}">
                            <div class="bookname_box col-md-4">
                                @foreach($v->books as $book)
                                    @if($v->status==1)
                                        <span>{{ $book->bookname }}</span>
                                    @else
                                        <button class="btn btn-default" data-bookid="{{ $book->id }}">{{ $book->bookname }}</button>
                                    @endif
                                @endforeach
                            </div>
                            <div class="img_box col-md-8">
                                <img class="cover" src="{{ $v->cover }}"/>
                                <img class="cip" src="{{ $v->cip }}"/>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button class="btn btn-success is_check">本页已核对</button>
                    <div class="box-footer">
                        {{ $data->links() }}
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
        </div>
    </section>
@endsection

@push('need_js')
   <script>
       $(function(){
           $('.bookname_box button').click(function(){
               if(confirm('确认将封面和cip复制到这本书的目录？')){
                    var coverid=$(this).parents('.book_box').attr('data-id');
                    var bookid=$(this).attr('data-bookid');
                    var btn=$(this);
                    axios.post('{{ route('choose_book') }}',{coverid,bookid}).then(response=>{
                        if(response.data.status===1){
                            alert('复制成功！');
                            btn.siblings().remove();
                            btn.addClass('disabled');
                        }else{
                            alert('复制失败！');
                        }
                    })
               }
           })

        //显示大图
           $(document).on('click','.cover,.cip',function () {
               let img = $(this).attr('src');
               $('#show_img').modal('show');
               $('#show_img .modal-body').html(`<img width="100%" src="${img}" />`);
           });


           $('.save_pic').click(function(){
               $('.text_box').html('正在扫描...请等待！！');
                axios.post('{{ route('save_pic_to_cover_isbn') }}').then(response=>{
                    $('.text_box').html('正在识别...');
                    recognition();
                });
           });

            function recognition(){
                axios.post('{{ route('cip_recognition') }}').then(response=>{
                    var str=$('.text_box').html();
                    if(response.data.status==1){
                        str+='<p>cip地址：'+response.data.cip+'   ISBN：'+response.data.isbn+'</p>';
                        $('.text_box').html(str);
                        recognition();
                    }else{
                        str+='<p>识别完成，开始匹配和复制图片...</p>';
                        $('.text_box').html(str);
                        copy_cover();
                    }
                });
            }

            function copy_cover(){
                axios.post('{{ route('copy_cover') }}').then(response=>{
                    var str=$('.text_box').html();
                    if(response.data.status==1){
                        str+='<p>封面地址：'+response.data.cover+'   对应书本：'+response.data.bookname+'</p>';
                        $('.text_box').html(str);
                        copy_cover();
                    }else if(response.data.status==0){
                        alert('操作完成！');
                        window.location.reload();
                    }else if(response.data.status==2){
                        alert(response.data.msg);return;
                    }
                });
            }

            $('.is_check').click(function(){ //标记为已审核
                var id_arr=[];
                $(".book_box").each(function () {
                    id_arr.push($(this).attr('data-id'));
                });
                axios.post('{{ route('is_check') }}',{id_arr}).then(response=>{
                    window.location.reload();
                })
            })
       })
   </script>
@endpush