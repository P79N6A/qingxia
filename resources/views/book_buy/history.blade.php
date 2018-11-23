@extends('layouts.backend')

@section('new_book_buy','active')

@push('need_css')
    <style>
        .cover_img img{
            max-height: 200px;
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
            <li class="active">该类历史练习册查看</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 该类历史练习册查看</h3></div>
            <div class="box-body">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="box-body">
                            <div class="col-md-6">{{ $now_select_name }}</div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="{{ '2018年'.$now_select_name }}" />
                                    <a data-id="999999999|{{ $data['version_id'] }}_{{ $data['grade_id'] }}_{{ $data['subject_id'] }}" class="btn btn-primary input-group-addon" id="new_buy">新增购买</a>
                                    <a class="btn btn-danger input-group-addon" href="{{ route('taobao_book',[$search['sort'],$search['all']]) }}" target="_blank">搜书</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @forelse($buy_book as $book)
                            <div class="col-md-6">
                                    <a class="thumbnail cover_img">
                                        <img src="http://via.placeholder.com/150x200" alt="" class="initial loaded" data-was-processed="true">
                                    </a>
                                <p><a>{{ $book->bookname }}<i class="badge bg-red">已购买</i><em class="badge bg-success">2018</em><em class="badge bg-red">0</em><em class="badge bg-red">0</em></a></p>
                            </div>
                            @endforeach
                        @forelse($all_book as $book)
                            @if($loop->first || $loop->index%2==0) <div class="row>"> @endif
                            <div class="col-md-6">
                                <a href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank" class="thumbnail cover_img">
                                    <img src="{{ $book->cover }}" alt="">
                                </a>
                                <p><a href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank">{{ $book->bookname }}<em class="badge bg-success">{{ $book->version_year }}</em><em class="badge bg-red">{{ $book->collect_count }}</em><em class="badge bg-red">{{ $book->concern_num }}</em></a></p>
                                @if($book->version_year<2018 && count($book->has_answer)>0)
                                    <a class="btn btn-danger upgrade_book" data-id="{{ $book->id }}">2018年答案一致,一键升级</a>
                                @endif
                                <a class="btn btn-success" href="{{ route('audit_answer_detail',$book->id) }}" target="_blank">编辑此练习册</a>

                                @if(count($book->has_answer)>0)
                                    <div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false">
                                        <div class="carousel-inner" >
                                            @foreach($book->has_answer as $key => $answer)
                                                @php  $answers = explode('|',$answer->answer); @endphp
                                                @if(is_array($answers))
                                                    @foreach($answers as $answer_img)
                                                        <div class="item @if ($loop->first && $key==0) active  @endif">
                                                            <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                <img class="answer_pic img-responsive" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}" alt="First slide">
                                                            </a>
                                                            <div class="carousel-caption text-orange">{{ $answer->textname }}</div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="item @if ($loop->first && $key==0) active @endif">
                                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                            <img class="answer_pic img-responsive" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer->answer) }}" alt="First slide">
                                                        </a>
                                                        <div class="carousel-caption text-orange FontBig">{{ $answer->textname }}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <a class="carousel-control  left" href="#myCarousel_{{ $book->id }}"
                                           data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                        <a class="carousel-control right" href="#myCarousel_{{ $book->id }}"
                                           data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                    </div>
                                @else
                                    @if($book->id>5000000)
                                        <p>与之前年代答案一致，已跳转升级</p>
                                    @else
                                        <p>暂无对应答案</p>
                                    @endif
                                @endif
                            </div>
                                @if($loop->last || $loop->index%2==1)</div><div class="clearfix"></div>@endif
                        @endforeach
                    </div>
                </div>


                <table class="table table-bordered hide">
                    <tbody>
                    <tr>
                        <th>年代</th>
                        <th>已买练习册</th>
                        <th>已存在练习册</th>
                    </tr>
                    @forelse($version_all_years as $year)
                        <tr>
                            <td>{{ $year }}</td>
                            <td>
                                @forelse($buy_book as $book)
                                    @if($book->version_year==$year)
                                    <p><a>{{ $book->bookname }}</a></p>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @forelse($all_book as $book)
                                    @if($book->version_year==$year)
                                    <p><a href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank">{{ $book->bookname }}<em class="badge bg-red">{{ $book->collect_count }}</em></a></p>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script>
    $(function () {
        //图片加载
        const sort = '{{ $data['sort'] }}';
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


        //upgrade
        $('.upgrade_book').click(function () {
            let book_id = $(this).attr('data-id');
            axios.post('{{ route('book_buy_upgrade_book') }}',{book_id}).then(response=>{
                if(response.data.status===1){
                    alert('升级成功');
                    windo.location.reload();
                }else{
                    alert(response.data.msg);
                }
            }).catch(error=>{
                alert(error);
            })
        });
        //新增购买
        $(document).on('click','#new_buy',function () {
            let id = $(this).attr('data-id');
            let bookname = $(this).prev().val();
            if(bookname.length<10){
                alert('请检查书名');
                return false;
            }
            axios.post('{{ route('new_book_buy_api','mark_status') }}',{id,sort,bookname}).then(response=>{
                if(response.data.status===1){
                    if(response.data.type==='cancel'){

                    }else{
                        $('.panel-body').prepend(`<div class="col-md-6">
                                <a class="thumbnail cover_img">
                                    <img src="http://via.placeholder.com/150x200" alt="" class="initial loaded" data-was-processed="true">
                                </a>
                                <p><a>${response.data.new_name}<em class="badge bg-success">2018</em><em class="badge bg-red">0</em><em class="badge bg-red">0</em></a></p></div>`);
                    }

                }
            }).catch(function (error) {
                console.log(error);
            })
        });
    })
    </script>

@endpush