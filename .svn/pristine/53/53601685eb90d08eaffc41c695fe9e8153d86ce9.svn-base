@extends('layouts.backend')

@section('new_buy_again','active')

@push('need_css')

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
            <div class="box-body">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered table-hover table-responsive">
                                <tr>
                                    <th>年代</th>
                                    @forelse($data['all_only'] as $only)
                                        <th>
                                            <div>
                                            {{ $only->newname }}<em class="badge bg-red">{{ count($only->hasBooks) }}</em>
                                            </div>
                                            <div class="input-group">
                                                <textarea class="form-control">{{ $only->newname }}</textarea>
                                                <a class="btn btn-primary input-group-addon change_newname" data-id="{{ $only->id }}">更改</a>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                                @php $now_width = 1/count($data['all_only'])*100 @endphp
                                @forelse(range(cache('now_bought_params')->where('uid',auth()->id())->first()->version_year,2014,-1) as $year)
                                    <tr>
                                        <td>{{ $year }}</td>
                                        @forelse($data['all_only'] as $only)
                                            <td style="width: {{ $now_width }}%">
                                                @forelse($only->hasBooks as $book)
                                                    @if(intval($book->version_year)==$year)
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <a>{{ $book->isbn }}</a>&nbsp;&nbsp;&nbsp;
                                                            <a target="_blank" href="{{ route('audit_answer_detail',$book->id) }}">{{ $book->bookname }}<em class="badge bg-red">{{ $book->collect_count }}</em></a>
                                                            <a class="thumbnail">
                                                                <img class="answer_pic" src="{{ $book->cover }}" alt="">
                                                            </a>
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
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                    @if($loop->last)
                                        <tr>
                                            <td>其它</td>
                                            @forelse($data['all_only'] as $only)
                                                <td style="width: {{ $now_width }}%">
                                                    @forelse($only->hasBooks as $book)
                                                        @if(intval($book->version_year)<2014)
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <a>{{ $book->isbn }}</a>&nbsp;&nbsp;&nbsp;
                                                                    <a target="_blank" href="{{ route('audit_answer_detail',$book->id) }}">{{ $book->bookname }}</a>
                                                                    <a class="thumbnail">
                                                                        <img class="answer_pic" src="{{ $book->cover }}" alt="">
                                                                    </a>
                                                                    @if(!empty($book->hasAnswers))
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
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>

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
            //更改newname
            $(document).on('click','.change_newname',function () {
                let now_id = $(this).attr('data-id');
                let now_name = $(this).prev().val();
                if(!confirm('确认保存newname')){
                    return false;
                }
                axios.post('{{ route('ajax_new_buy','change_newname') }}',{now_id,now_name}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                })
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