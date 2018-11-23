@extends('layouts.backend')

@section('book_buy_wait')
    active
@endsection

@push('need_css')
<style>
    .table-bordered>tbody>tr>td{
        border: 1px solid #ddd;
    }
    .cover-img{
        max-height: 200px;
        min-height: 200px;
    }
</style>
@endpush

@section('content')
    <div class="modal fade" id="show_book">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册购买管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 练习册购买管理</h3>
                <button id="confirm_buy_done" class="pull-right btn btn-danger">选中部分练习册确认购买完毕</button>
            </div>
            <div class="box-body" style="overflow: auto; width: 100%;">
                <ul class="nav nav-pills">
                    @foreach($data['all_shops'] as $key =>$value)
                        @if(count($value)>2)
                            <li>
                            <button data-toggle="dropdown" data-shop-id="{{ $key }}" title="{{ array_first($value)[0]->shop_name }}" class="check_shop btn btn-xs btn-primary">{{ array_first($value)[0]->shop_name }}<em class="badge">{{ count($value) }}</em></button>
                            <ul class="dropdown-menu hide">
                                @foreach($value as $isbn=>$book)
                                    <li data-code="{{ $book[0]->bar_code }}"><a>{{ $book[0]->bar_code }}<em class="badge">{{ count($book) }}</em></a>
                                    <span class="all_books_here" data-shop-id="{{ $key }}" data-code="{{ $book[0]->bar_code }}">
                                         @foreach($book as $value1)
                                             @php
                                                $value1->url = str_replace('//detail.m.tmall.com','//detail.tmall.com',$value1->url);
                                             @endphp
                                        <a target="_blank" href="{{ $value1->url }}" class="col-md-6 pull-left thumbnail text-center">
                                            <img class="img-responsive" data-original="{{ $value1->img }}" />
                                            <em class="badge">{{ $value1->price }}</em>
                                        </a>
                                        @endforeach
                                    </span></li>
                                @endforeach
                            </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <hr>
                <table class="table">
                    @foreach($data['all_books'] as $book)
                        @if($loop->first)
                            <div class="row">
                        @endif
                        @if($loop->index%6==0 && $loop->index!=0)
                            </div><div class="row">
                        @endif
                        <div class="col-md-2">
                            <span class="close pull-right del_this" data-id="{{ $book->book_id }}">&times;</span>
                            <span class="single-book-box text-center col-md-12" data-code="{{ isset($book->isbn)?$book->isbn:'123123123' }}" data-id="{{ $book->book_id }}">
                                <a class="thumbnail text-center" title="{{ $book->book_name }}">{{ $book->isbn }}
                                @if(starts_with($book->cover_photo_thumbnail,'//'))
                                    <img class="cover-img img-responsive" src="{{ $book->cover_photo_thumbnail }}" alt="">                            @else
                                    <img class="cover-img img-responsive" src="http://121.199.15.82/book_photo_path/{{ $book->cover_photo_thumbnail }}" alt="{{ $book->book_name }}">
                                @endif
                                </a>
                                <a>{{ $book->book_name }}</a>
                                <div class="has_shop">

                                </div>
                            </span>
                        </div>
                        @if($loop->last)
                            </div>
                        @endif
                    @endforeach
                </table>

            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>

<script>
    const token = '{{ csrf_token() }}';
    let lazy = new LazyLoad();

    $('#confirm_buy_done').click(function () {
        let ids = '';
        $('.single-book-box').each(function () {
            if($(this).find('.has_shop a').length>0){
                ids += $(this).attr('data-id') + ',';
            }
        });
        if(ids.trim()==''){
            console.log('no shop');
            return false;
        }
        let o = {
            _token:token,
            ids:ids
        };
        $.post('{{ route('api_book_buy_done') }}',o,function (s) {
            if(s.status==1){
                $('.single-book-box').each(function () {
                    if($(this).find('.has_shop a').length>0){
                        $(this).remove();
                    }
                });

            }
        })
    });

    //显示练习册
    $(document).on('click','.show_shop_books',function () {
        let now_data_code = $(this).attr('data-code');
        let now_shop_id = $(this).attr('data-shop-id');
        let now = $('.all_books_here[data-code="'+now_data_code+'"][data-shop-id="'+now_shop_id+'"]')[0].cloneNode(true);
        $('.modal-body').html(now);
        $('.modal-body span img').attr('src',$('.modal-body span img').attr('data-original'));
        $('.modal-body span').show();
    })

    $('#show_book').on('hide.bs.modal',function () {
        $('.modal-body').html('');
    })

    //选中店家
    $('.check_shop').click(function () {
        let now_shop_name = $(this).attr('title');
        let now_shop_id = $(this).attr('data-shop-id');
        let now_code_box = $(this).next()
        $(now_code_box).find('li').each(function () {
            let now_code = $(this).attr('data-code');
            //$('.single-book-box[data-code="'+now_code+'"]').addClass('bg-red');
            if($('.single-book-box[data-code="'+now_code+'"] .has_shop a[data-shop-id="'+now_shop_id+'"]').length==0){
                $('.single-book-box[data-code="'+now_code+'"] .has_shop').append('<a data-code="'+now_code+'" data-shop-id="'+now_shop_id+'" class="show_shop_books btn btn-xs btn-danger" data-toggle="modal" data-target="#show_book">'+now_shop_name+'</a><i data-shop-id="'+now_shop_id+'" class="fa fa-times del_this_shop"></i>');
            }else{
                $('.single-book-box[data-code="'+now_code+'"] .has_shop i[data-shop-id="'+now_shop_id+'"]').remove();
                $('.single-book-box[data-code="'+now_code+'"] .has_shop a[data-shop-id="'+now_shop_id+'"]').remove();

            }

        })
    });

    $(document).on('click','.del_this_shop',function () {
        $(this).prev().remove();
        $(this).remove();
    })

    //删除此书
    $(document).on('click','.del_this',function () {
        let book_id = $(this).attr('data-id');
        axios.post('{{ route('api_book_delete') }}',{book_id:book_id}).then(response=>{
            if(response.data.status===1){
                $(this).parents('.col-md-2').remove();
            }
        }).catch(function (error) {
            console.log(error);
        })
    });

</script>
@endpush