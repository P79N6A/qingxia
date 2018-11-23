@extends('layouts.backend')

@section('book_now_v2')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .dropdown-submenu {
            position: relative;
        }
        .dropdown-submenu button{
            width: 100%;
        }
        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            -webkit-border-radius: 0 6px 6px 6px;
            -moz-border-radius: 0 6px 6px;
            border-radius: 0 6px 6px 6px;
        }
        .dropdown-submenu:hover > .dropdown-menu {
            display: block;
        }
        .cover-img {
            min-height: 170px;
            max-height: 170px;
        }
        .answer-img{
            min-height:250px;
            max-height:250px;
            /*min-width:540px;*/
            /*max-width:540px;*/
        }
    </style>
@endpush

@section('content')

    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">课本整理</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 课本整理</h3>
        </div>
        <div class="box-body">
            <div class="main-sidebar-2">
                <ul class="nav nav-pills">
                    @foreach(config('workbook.grade') as $k=>$v)
                        <li @if(!empty($data['sort_grade'][$k])) style="position: relative" @endif>
                            @if(!empty($data['sort_grade'][$k]))
                                <button data-toggle="dropdown" class="btn-get-menu btn @if($k==$data['grade']) btn-danger @else btn-primary @endif">{{ $v }}</button>
                                <ul class="dropdown-menu" style="z-index:9;left:0;">
                                    @foreach($data['sort_grade'][$k] as $key=>$value)
                                        <li>
                                            <a href="{{ route('book_arrange',[$key,$k]) }}">
                                                <i class="fa fa-circle-o">{{ config('workbook.subject_1010')[intval($key)] }}</i>
                                                <span class="pull-right-container">
                                                      <small class="label pull-right @if($key==$data['subject'] and $k==$data['grade'])bg-red @else bg-blue @endif">{{ count($value) }}</small>
                                                    </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <button class="btn btn-default disabled">{{ $v }}</button>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <hr />
            </div>
        </div>
        <div class="box-body">
            <div class="box">
                @foreach($data['all_distinct_version'] as $version_key=>$version_value)
                    <h2>
                        <span>{{ $version_value->name }}</span>
                    </h2>
                    <div class="box-body">
                    @foreach($data['all_book_now'][$version_value->version_id] as $key1=>$book)
                        @if($loop->first)
                            <div class="row">
                        @endif
                        <div class="col-md-2 col-xs-6 edit_box" data-id="{{ $book->id }}" style="font-size: 12px;margin-bottom: 20px">
                            <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                <img class="img-responsive cover-img" data-src="{{ config('workbook.workbook_url').$book->cover_photo }}" src="{{ config('workbook.workbook_url').$book->cover_photo_thumbnail }}">
                            </a>
                            <input name="original_name" style="font-size: 1px;padding: 1px;" class="original_name form-control " value="{{ $book->bookname }}" />
                            <div class="input-group" style="width:100%">
                                <select data-name="grade_id" class="update_data form-control select2 pull-left" style="width:50%"
                                        tabindex="-1" aria-hidden="true">
                                    @foreach(config('workbook.grade') as $key=>$value)
                                        @if($book->grade_id==$key)
                                            @php $select='selected=selected'; @endphp
                                        @else
                                            @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <select data-name="subject_id" class="update_data form-control select2" style="width:50%">
                                    tabindex="-1" aria-hidden="true">
                                    @foreach(config('workbook.subject_1010') as $key=>$value)
                                        @if($book->subject_id==$key)
                                            @php $select='selected=selected'; @endphp
                                        @else
                                            @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                </select>

                            </div>
                            <div class="input-group" style="width:100%">
                                <select data-name="volumes_id" class="update_data form-control select2" style="width:50%">
                                    @foreach($data['all_volumes'] as $key=>$value)
                                        @if($book->volumes_id==$value->id)
                                            @php $select='selected=selected'; @endphp
                                        @else
                                            @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $value->id }}">{{ $value->volumes }}</option>@endforeach
                                </select>
                                <select data-name="version_id" class="update_data form-control select2" style="width:50%">
                                    tabindex="-1" aria-hidden="true">
                                    @foreach($data['all_version'] as $value)
                                        @if($book->version_id==$value->id)
                                            @php $select='selected=selected'; @endphp
                                        @else
                                            @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $value->id }}">{{ $value->name }}</option>@endforeach
                                </select>
                            </div>
                            <div class="input-group" style="margin: 5px">
                                @if($book->book_confirm==1)
                                    <a class="input-control book_done btn btn-xs btn-success">已选中为标准课本</a>
                                @else
                                    <a class="input-control book_done btn btn-xs btn-default">选中为标准课本</a>
                                @endif
                                @if($data['has_answer'][$version_value->version_id][$key1] !=0)
                                    <a href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank" class="input-control page_all_done btn btn-xs btn-primary">有答案</a>
                                @else
                                    <a class="input-control btn btn-xs btn-danger disabled">无答案</a>
                                @endif
                            </div>
                            @if(!empty($data['all_answer'][$version_value->version_id][$key1]['answers']))

                                <div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false">
                                    <div class="carousel-inner" >
                                        <p class="bg-blue text-center">答案共{{ $data['all_answer'][$version_value->version_id][$key1]['answers_num'] }}页</p>
                                        @foreach($data['all_answer'][$version_value->version_id][$key1]['answers'] as $key => $answer)
                                            @if(is_array($answer))
                                                @foreach($answer as $answer_img)
                                                    <div class="item @if ($loop->first && $key==0) active  @endif">
                                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                                            <img class="answer-img img-responsive" src="{{ url(config('workbook.workbook_url').$answer_img) }}"
                                                                 alt="First slide">
                                                        </a>
                                                        <div class="carousel-caption text-orange">{{ $data['all_answer'][$version_value->version_id][$key1]['textname'][$key] }}</div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="item @if ($loop->first && $key==0) active @endif">
                                                    <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                                        <img class="answer-img img-responsive" src="{{ url('http://121.199.15.82/standard_answer/'.$answer) }}" alt="First slide">
                                                    </a>
                                                    <div class="carousel-caption text-orange FontBig">{{ $data['all_answer'][$version_value->version_id][$key1]['textname'][$key] }}</div>
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
                                <p class="bg-blue text-center">暂无对应答案</p>
                            @endif
                        </div>
                        @if(($loop->index+1)%6==0)
                        </div><div class="row">
                        @endif
                        @if($loop->last)
                        </div>
                        @endif
                    @endforeach
                    </div>

                @endforeach

            </div>
            {{--<div class="pull-left">--}}
                {{--{{ $data['all_book']->links() }}--}}
            {{--</div>--}}
        </div>
        </div>
    </div>
    </section>
@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    $(function () {
        var token = '{{ csrf_token() }}';
        //Initialize Select2 Elements
        $(".select2").select2();

        $('.update_data').change(function () {
            update_info($(this));
        });
        function update_info(info) {
            var tr_now = $(info).parents('.edit_box');
            var id = tr_now.data('id');
            var now_name = $(info).data('name');
            var now_data = $(info).val();
            var post_data = {
                'id':id,
                '_token':token
            };
            post_data[now_name] = now_data;
            $.ajax({
                type: "POST",
                url: "{{ route('book_update') }}",
                data: post_data,
                success: function (t) {

                },
                error: function (t) {
                },
                dataType: "json"
            })
        }

        $('.book_done').click(function () {
            var now_this = $(this);
            var id = now_this.parents('.edit_box').data('id');
            var bookname = $(this).parents('.edit_box').find('input[name="original_name"]').val();
            var o = {
                'id': id,
                'bookname': bookname,
                '_token':token,
            };
            $.ajax({
                type:'post',
                data:o,
                url: '{{route('book_done')}}',
                success: function (s) {
                    if(s.status==1){
                        if(now_this.hasClass('btn-default')){
                            now_this.removeClass('btn-default').addClass('btn-success');
                            now_this.html('已选中为标准课本');
                            alert('设定成功');
                        }else{
                            now_this.removeClass('btn-success').addClass('btn-default');
                            now_this.html('选中为标准课本');
                            alert('取消设定成功');
                        }
                    }
                },
                error: function (s) {

                },
                dataType:'json'
            });
        });
    });
</script>
@endpush