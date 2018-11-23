@extends('layouts.backend')

@section('new_buy_again','active')

@push('need_css')
    <style>
        .answer_pic{
            min-height: 500px;
            max-height: 700px;
        }
    </style>
@endpush

@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册收藏统计</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-body table-responsive">

                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered table-hover">
                                @php $now_width = 1/count($data['all_repeat_books'])*100 @endphp
                                <tr>
                                    @forelse($data['all_repeat_books'] as $book)
                                        <td style="width: {{ $now_width }}%" data-id="{{ $book->id }}" class="single_book_box">
                                            <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="input-group" style="width: 100%">
                                                            <label class="input-group-addon">跳转id</label>
                                                            <input type="text" class="form-control book_redirect_id" value="{{ $book->redirect_id }}">
                                                            <a class="input-group-addon btn btn-primary save_redirect_id" data-id="{{ $book->id }}" >保存</a>
                                                            <a class="@if($loop->first) hide @endif input-group-addon btn btn-primary save_redirect" data-type="left">左侧id保存</a>
                                                            <a class="@if($loop->last) hide @endif input-group-addon btn btn-primary save_redirect" data-type="right">右侧id保存</a>

                                                        </div>
                                                        @if(strpos($book->cover,'/pic19/') && !strpos($book->cover,'/new/'))
                                                            <a class="label label-danger">jiajiao</a>
                                                        @else
                                                            <a class="label label-info">其它</a>
                                                        @endif
                                                        <a>id: {{ $book->id }}</a>
                                                        <a>isbn: {{ $book->isbn }}</a>&nbsp;&nbsp;&nbsp;
                                                        <a target="_blank" href="{{ route('audit_answer_detail',$book->id) }}">{{ $book->bookname }}<em class="badge bg-red">{{ $book->collect_count }}</em>
                                                        </a>
                                                        <div class="clear carousel slide" id="cover_carousel_{{ $book->id }}" data-interval="false">
                                                            <div class="carousel-inner" >
                                                                <div class="item active">
                                                                    <a class="thumbnail">
                                                                        <img class="answer_pic" src="{{ $book->cover }}" alt="">
                                                                    </a>
                                                                </div>
                                                                <div class="item">
                                                                    <a class="thumbnail">
                                                                        <img class="answer_pic" src="{{ $book->cip_photo }}" alt="">
                                                                    </a>
                                                                </div>
                                                                <a class="carousel-control  left" href="#cover_carousel_{{ $book->id }}"
                                                                   data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                                <a class="carousel-control right" href="#cover_carousel_{{ $book->id }}"
                                                                   data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                        </div>
                                                        </div>
                                                        @if(count($book->hasAnswers)>0)
                                                            <a class="btn btn-block btn-default">共{{ count($book->hasAnswers) }}页</a>
                                                            <div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false">
                                                                <div class="carousel-inner" >
                                                                    @foreach($book->hasAnswers as $key => $answer)
                                                                        <div class="item @if ($loop->first && $key==0) active  @endif">
                                                                            <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                                                                <img class="answer-img answer_pic img-responsive" data-original="{{ config('workbook.workbook_url').$answer->answer }}"
                                                                                     alt="First slide">
                                                                            </a>
                                                                            <div class="carousel-caption text-orange">{{ $answer->textname }}</div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <a class="carousel-control  left" href="#myCarousel_{{ $book->id }}"
                                                                   data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                                <a class="carousel-control right" href="#myCarousel_{{ $book->id }}"
                                                                   data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                            </div>
                                                        @else
                                                            <p>暂无对应答案</p>
                                                        @endif
                                                    </div>
                                                </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>

                        </div>
                    </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script>
        $(function () {
            //更改redirect_id
            $(document).on('click','.save_redirect_id',function () {
                let book_id = $(this).attr('data-id');
                let redirect_id = $(this).prev().val();
                axios.post('{{ route('audit_api','update_redirect') }}',{book_id,redirect_id}).then(response=>{
                    if(response.data.status===1){
                        alert('保存成功');
                    }
                }).catch();
            });

            $(document).on('click','.save_redirect',function () {
               let now_type = $(this).attr('data-type');
               let box = $(this).parents('.single_book_box');
               let redirect_id = 0;
               if(now_type==='left'){
                    redirect_id = box.prev().attr('data-id');
               }else{
                   redirect_id = box.next().attr('data-id');
               }
               box.find('.book_redirect_id').val(redirect_id);
               box.find('.save_redirect_id').click();
            });


            //答案显示
            var cHeight = 0;

            $('.carousel').on('slide.bs.carousel', function(e) {
                var $nextImage = $(e.relatedTarget).find('img');
                $activeItem = $('.active.item', this);
                // prevents the slide decrease in height
                if (cHeight == 0) {
                    cHeight = $(this).height();
                    $activeItem.next('.item').height(cHeight);
                }
                // prevents the loaded image if it is already loaded
                var src = $nextImage.attr('data-original');
                if (typeof src !== "undefined" && src != "") {
                    $nextImage.attr('src', src);
                    $nextImage.attr('data-original', '');
                }
            });
            var lazy = new LazyLoad();






        });
    </script>

@endpush