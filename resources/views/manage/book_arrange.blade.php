@extends('layouts.backend')

@section('book_now_v2')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    .dropdown-submenu {  position: relative;  }
    .dropdown-submenu button{  width: 100%;  }
    .dropdown-submenu > .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
        -webkit-border-radius: 0 6px 6px 6px;
        -moz-border-radius: 0 6px 6px;
        border-radius: 0 6px 6px 6px;
    }
    .dropdown-submenu:hover > .dropdown-menu {  display: block;  }
    .cover-img {  min-height: 170px;  max-height: 170px;  }
    .answer-img{  min-height:250px;  max-height:250px;  }
    .lazy-load-cover{  min-width: 150px; max-height: 250px; }
    .main-sidebar-2{  padding:20px;  }
</style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">课本整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 课本整理</h3><a href="{{ route('book_chapter') }}" class="pull-right btn btn-lg btn-danger">章节答案整理</a></div>
            <div class="box-body">
                <div class="main-sidebar-2">
                    <ul class="nav nav-pills">@foreach($data['all_version'] as $v)<li @if(!empty($data['sort_version'][$v->id])) style="position: relative" @endif>@if(!empty($data['sort_version'][$v->id]))<button data-toggle="dropdown" class="btn-get-menu btn @if($v->id==intval($data['version'])) btn-danger @else btn-primary @endif">{{ $v->name }}</button><ul class="dropdown-menu" style="z-index:9;left:0;">@foreach($data['sort_version'][$v->id] as $key=>$value)@if($loop->first)<li><a href="{{ route('book_arrange',[$v->id,0]) }}"><i class="fa fa-circle-o">全部</i><small class="label pull-right @if($v->id==$data['version'] and $data['grade']==0)bg-red @else bg-blue @endif">{{ collect($data['sort_version'][$v->id])->collapse()->count() }}</small></a></li>@endif<li><a href="{{ route('book_arrange',[$v->id,intval($key)]) }}"><i class="fa fa-circle-o">{{ config('workbook.grade')[intval($key)] }}</i><small class="label pull-right @if($v->id==$data['version'] and $key==$data['grade'])bg-red @else bg-blue @endif">{{ count($value) }}</small></a></li>@endforeach</ul>@else<button class="btn btn-default disabled">{{ $v->name }}</button>@endif</li>@endforeach</ul>
                    <hr />
                </div>
            </div>
            <div class="box-body"><div class="box">@foreach(config('workbook.grade') as $grade_key=>$grade_value)@if(isset($data['all_book_now'][$grade_key]))<h2 id="grade_{{ $grade_key }}"><span >{{ $grade_value }}</span></h2><div class="box-body">@foreach($data['all_book_now'][$grade_key] as $key1=>$book)<div class="row"><div class="col-md-2 col-xs-6 edit_box" data-id="{{ $book->id }}" style="font-size: 12px;margin-bottom: 20px"><a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">@if(starts_with($book->cover_photo_thumbnail,'//') or starts_with($book->cover_photo_thumbnail,'http'))<img class="img-responsive cover-img lazy-load" data-original="{{ $book->cover_photo_thumbnail }}" >@else<img class="img-responsive cover-img lazy-load" data-original="{{ config('workbook.workbook_url').$book->cover_photo_thumbnail }}" >@endif</a><input name="original_name" style="font-size: 1px;padding: 1px;" class="original_name form-control " value="{{ $book->bookname }}" /><div class="input-group"><input name="isbn"  class="isbn form-control " placeholder="isbn" value="{{ $book->isbn }}" /><span class="change-isbn-btn bg-blue input-group-addon">更新</span></div><div class="input-group"><input name="cover-url"  class="isbn form-control " placeholder="封面地址" value="{{ $book->cover_photo_thumbnail }}" /><span class="change-cover-btn bg-red input-group-addon">更新</span></div><div class="input-group" style="width:100%"><select data-name="grade_id" class="update_data form-control pull-left" style="width:50%" tabindex="-1" aria-hidden="true"><option selected="selected" value="{{ $book->grade_id }}">{{ config('workbook.grade')[intval($book->grade_id)] }}</option></select><select data-name="subject_id" class="update_data form-control select2" style="width:50%"><option selected="selected" value="{{ $book->subject_id }}">{{ config('workbook.subject_1010')[intval($book->subject_id)] }}</option></select></div><div class="input-group" style="width:100%"><select data-name="volumes_id" class="update_data form-control select2" style="width:50%"><option selected="selected" value="{{ $book->volumes_id }}">{{ $data['all_volumes']->where('code',$book->volumes_id)->first()->volumes }}</option></select><select data-name="version_id" class="update_data form-control select2" style="width:50%" tabindex="-1" aria-hidden="true"><option selected="selected" value="{{ $book->version_id }}">{{ $data['all_version']->where('id',$book->version_id)->first()->name }}</option></select></div><div class="input-group" style="margin: 5px">@if($book->book_confirm==1)<a class="input-control book_done btn btn-xs btn-success">已选中为标准课本</a>@else<a class="input-control book_done btn btn-xs btn-default">选中为标准课本</a>@endif @if($data['has_answer'][$grade_key][$key1] !=0)<a href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank" class="input-control page_all_done btn btn-xs btn-primary">有答案</a>@else<a class="input-control btn btn-xs btn-danger disabled">无答案</a>@endif</div>@if(!empty($data['all_answer'][$grade_key][$key1]['answers']))<div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false"><div class="carousel-inner" ><p class="bg-blue text-center">答案共{{ $data['all_answer'][$grade_key][$key1]['answers_num'] }}页</p>
                                        @foreach($data['all_answer'][$grade_key][$key1]['answers'] as $key => $answer)@if(is_array($answer))@foreach($answer as $answer_img)<div class="item @if ($loop->first && $key==0) active  @endif"><a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo"><img class="answer-img img-responsive lazy-load" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}" alt="First slide"></a><div class="carousel-caption text-orange">{{ $data['all_answer'][$grade_key][$key1]['textname'][$key] }}</div></div>@endforeach
                                        @else<div class="item @if ($loop->first && $key==0) active @endif"><a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo"><img class="answer-img img-responsive lazy-load" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer) }}" alt="First slide"></a><div class="carousel-caption text-orange FontBig">{{ $data['all_answer'][$grade_key][$key1]['textname'][$key] }}</div></div>@endif
                                        @endforeach
                                                    </div>
                                                    <a class="carousel-control  left" href="#myCarousel_{{ $book->id }}" data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                    <a class="carousel-control right" href="#myCarousel_{{ $book->id }}" data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                </div>
                                            @else
                                                <p class="bg-blue text-center">暂无对应答案</p>
                                            @endif
                                        </div>
                                        <div class="cover-box" data-book-id = "{{ $book->id }}" id="cover-box-{{ $book->id }}" style="overflow-y: auto;display: flex">@if(isset($data['isbn'][$grade_key][$key1]))@foreach($data['isbn'][$grade_key][$key1] as $isbn)@if($isbn)<a class="thumbnail"><img class="lazy-load-cover" data-original="{{ $isbn->img }}"/></a>@endif @endforeach @endif</div></div>@endforeach</div>@endif @endforeach
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script>
    $(function () {
        var token = '{{ csrf_token() }}';
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
        //grade_choice
        $('select[data-name="grade_id"]').select2({ data: $.parseJSON('{!! $data['grade_select'] !!} '),});
        $('select[data-name="subject_id"]').select2({ data: $.parseJSON('{!! $data['subject_select'] !!} '),});
        $('select[data-name="volumes_id"]').select2({ data: $.parseJSON('{!! $data['volume_select'] !!} '),});
        $('select[data-name="version_id"]').select2({ data: $.parseJSON('{!! $data['version_select'] !!} '),});

        //更新图片
        $(document).on('click','.lazy-load-cover',function () {
            var img = $(this).attr('src');
            var book_id = $(this).parents('.cover-box').attr('data-book-id');
            var o = {
                id:book_id,
                cover_photo_thumbnail:img,
                _token:token
            };
            $.ajax({
                type: 'post',
                url:'{{ route('book_update') }}',
                data:o,
                dataType:'json',
                success:function (s) {
                    if(s.status==1){
                        $('.edit_box[data-id='+book_id+'] .show_cover_photo img').attr('src',img);
                        $('.edit_box[data-id='+book_id+'] input[name=cover-url]').val(img);
                        $('#change-cover-modal').modal('hide');
                    }else{
                        alert('替换失败');
                    }
                }
            })
        });
        //更新isbn或封面
        $(document).on('click','.change-isbn-btn,.change-cover-btn',function () {
            var now_btn = $(this);
            var now_book_id = now_btn.parents('.edit_box').attr('data-id');
            var o ={ id:now_book_id, _token : token};
            if(now_btn.hasClass('change-isbn-btn')){
                o['isbn'] = now_btn.prev().val();
            }else{
                o['cover_photo_thumbnail'] = now_btn.prev().val();
            }
            $.ajax({
                type: 'post',
                url:'{{ route('book_update') }}',
                data:o,
                dataType:'json',
                success:function (s) {
                    if(s.status==1){
                        if(now_btn.hasClass('change-cover-btn')){
                            now_btn.parents('.edit_box').find('.cover-img').attr('src',now_btn.prev().val());
                        }
                    }else{alert('替换失败');}
                }
            })
        });

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

        var lazyLoadInstances = [];
        var lazyLazy = new LazyLoad({
            elements_selector: ".cover-box",
            callback_set: function(el) {
                var oneLL = new LazyLoad({
                    container: el
                });
                lazyLoadInstances.push(oneLL);
            }
        });
        var lazy = new LazyLoad();
//        var aaaaa = [];
//        var lazy = new LazyLoad({
//            elements_selector: ".item",
//            callback_set: function(el) {
//                var oneLL = new LazyLoad({
//                    container: el
//                });
//                aaaaa.push(oneLL);
//            }
//        });
    });
</script>
@endpush