@extends('layouts.backend')

@section('book_now_v2')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}" />
<style>
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu button {
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

    .answer-img {
       min-height: 520px;
		max-height: 520px;
		min-width: 420px;
    }
    .answer-box{
        width:300px;
        text-align: center;
        margin: 10px;
    }

    .lazy-load-cover {
        min-width: 150px;
    }

    .main-sidebar-2 {
        padding: 20px;
    }
</style>
@endpush

@section('content')
    <div class="modal fade" id="cover_photo">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">查看图片</h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">课本整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 课本整理</h3></div>
            <div class="box-body">
                <div class="main-sidebar-2">
                    <ul class="nav nav-pills">
@foreach($data['all_version'] as $v)
<li @if(!empty($data['sort_version'][$v->id])) style="position: relative" @endif>@if(!empty($data['sort_version'][$v->id]))
<button data-toggle="dropdown" class="btn-get-menu btn @if($v->id==intval($data['version'])) btn-danger @else btn-primary @endif">{{ $v->name }}</button>
<ul class="dropdown-menu" style="z-index:9;left:0;">@foreach($data['sort_version'][$v->id] as $key=>$value)<li><a href="{{ route('book_chapter',[$v->id,intval($key)]) }}"><i class="fa fa-circle-o">{{ config('workbook.grade')[intval($key)] }}</i><small class="label pull-right @if($v->id==$data['version'] and $key==$data['grade'])bg-red @else bg-blue @endif">{{ count($value) }}</small></a></li>@endforeach</ul>@else<button class="btn btn-default disabled">{{ $v->name }}</button>@endif</li>
@endforeach
                    </ul>
                    <hr/>
                </div>
            </div>
            <div class="box-body">
                <div class="box">
@foreach(config('workbook.grade') as $grade_key=>$grade_value)@if(isset($data['all_book_now'][$grade_key]))
<h2 id="grade_{{ $grade_key }}"><span>{{ $grade_value }}</span></h2>
<div class="box-body">
@foreach($data['all_book_now'][$grade_key] as $key1=>$book)
<div class="row">
<div class="col-md-2 col-xs-6 edit_box pull-left" data-id="{{ $book->id }}" style="font-size: 12px;margin-bottom: 20px">
<a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">@if(starts_with($book->cover_photo_thumbnail,'//') or starts_with($book->cover_photo_thumbnail,'http'))<img class="img-responsive cover-img lazy-load" data-original="{{ $book->cover_photo_thumbnail }}">@else<img class="img-responsive cover-img lazy-load" data-original="{{ config('workbook.workbook_url').$book->cover_photo_thumbnail }}">@endif</a>
<a class="btn btn-xs btn-primary">{{ $book->bookname }}</a></div>
<div class="pull-left">
<div style="min-width: 250px;">
<button type="button" class="btn btn-success btn-sm" onclick="demo_create({{ $book->id }});"><i class="glyphicon glyphicon-asterisk"></i> 新增</button>
<button type="button" class="btn btn-warning btn-sm" onclick="demo_rename({{ $book->id }});"><i class="glyphicon glyphicon-pencil"></i> 重命名</button>
{{--<button type="button" class="btn btn-danger btn-sm" onclick="demo_delete({{ $book->id }});"><i class="glyphicon glyphicon-remove"></i> 删除</button>--}}
<button type="button" class="btn btn-danger btn-sm" onclick="wrong_mark({{ $book->id }});"><i class="glyphicon glyphicon-remove"></i> 错误标记</button>
<button type="button" class="btn btn-primary btn-sm" onclick="demo_save({{ $book->id }});"><i class="glyphicon glyphicon-ok"></i> 保存</button>
</div>
@if($book->wrong_chapter==1) <p class="chapter-wrong text-center bg-red">已标记为错误章节</p>@endif

<div id="jstree_demo_{{ $book->id }}" class="jstree_show demo pull-left" style="margin-top:1em; min-height:200px;"></div>

</div>
<div class="cover-box" data-book-id="{{ $book->id }}" id="cover-box-{{ $book->id }}" style="overflow-y: auto;display: flex">
<div style="display: flex">
@if(isset($data['all_answer'][$book->id]))
@foreach($data['all_answer'][$book->id] as $workbook_key => $workbook_value)
<div class="answer-box"><a href="http://www.1010jiajiao.com/daan/bookid_{{ $workbook_key }}.html" target="_blank" data-id="{{ $workbook_key }}">{{ $data['all_books_info'][$book->id][$workbook_key]['bookname'] }}</a><p class="bg-blue text-center">答案共{{ $workbook_value['answers_num'] }}页</p><div id="myCarousel_{{ $workbook_key }}" class="clear carousel slide" data-interval="false"><div class="carousel-inner" >
@foreach($workbook_value['answers'] as $key => $answer)
@if(is_array($answer))
@foreach($answer as $answer_img)<div class="item @if ($loop->first && $key==0) active  @endif"><a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo"><img class="answer-img img-responsive" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}" alt="First slide"></a><div class="carousel-caption text-orange">{{ $workbook_value['textname'][$key] }}</div></div>@endforeach @else<div class="item @if ($loop->first && $key==0) active @endif">
<a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
<img class="answer-img img-responsive" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer) }}" alt="First slide">
</a><div class="carousel-caption text-orange FontBig">{{ $workbook_value['textname'][$key] }}</div></div>@endif
@endforeach
</div><a class="carousel-control  left" href="#myCarousel_{{ $workbook_key }}" data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a><a class="carousel-control right" href="#myCarousel_{{ $workbook_key }}" data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a></div>
@if($data['all_books_info'][$book->id][$workbook_key]['chapter_confirm']==0)<a data-book-id="{{ $book->id }}" data-workbook-id="{{ $workbook_key }}" class="btn btn-primary make_workbook_chapter">生成章节</a>
@else<a data-book-id="{{ $book->id }}" data-workbook-id="{{ $workbook_key }}" class="btn btn-danger make_workbook_chapter">已生成章节</a>
@endif</div>@endforeach
@else<p class="bg-blue text-center">暂无对应答案</p>
@endif
</div></div></div>@endforeach</div>@endif @endforeach</div>
            </div>
            <div class="paginate-bar">
                {{ $data['all_book_now'][intval($data['grade'])]->links() }}
            </div>
        </div>

    </section>
@endsection

@push('need_js')

<script src="{{ asset('js/jstree.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script>
    var token = '{{ csrf_token() }}';

    function wrong_mark(book_id) {
        $.ajax({
            type:'post',
            url:'{{ route('mark_book') }}',
            data:{_token:token,id:book_id},
            dataType:'json',
            success: function (s) {
                if(s.status==1){
                    if($('#jstree_demo_'+book_id).prev().hasClass('chapter-wrong')){
                        $('#jstree_demo_'+book_id).prev().remove();
                    }else{
                        $('#jstree_demo_'+book_id).before('<p class="chapter-wrong text-center bg-red">已标记为有误章节</p>')
                    }

                }else{
                    alert('更新失败');
                }
            }

        })
    }

    function demo_create(book_id) {
        var ref = $('#jstree_demo_'+book_id).jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        //sel = ref.create_node(sel, {"type":"file"});
        sel = ref.create_node(sel, {"type":"default"});
        if(sel) {
            ref.edit(sel);
        }
    }
    function demo_rename(book_id) {
        var ref = $('#jstree_demo_'+book_id).jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        ref.edit(sel);
    }
    function demo_delete(book_id) {
        var ref = $('#jstree_demo_'+book_id).jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        ref.delete_node(sel);
    }
    function demo_save(book_id) {
        var chapter_data = [];
        var $tree = $('#jstree_demo_'+book_id);
        $($tree.jstree().get_json($tree, {
            flat: true
        }))
            .each(function(index, value) {
                //console.log(value.text);
                var node = $tree.jstree().get_node(this.id);
                var lvl = node.parents.length;

                var idx = index;

                chapter_data[idx] = {
                    index: idx,
                    level: lvl,
                    text: value.text,
                };
            });

        $.ajax({
            type:'post',
            url:'{{ route('set_chapter') }}',
            data:{_token:token,id:book_id,chapters:JSON.stringify(chapter_data)},
            dataType:'json',
            success: function (s) {
                if(s.status==1){
                    alert('更新成功');
                }else{
                    alert('更新失败');
                }
            }
            
        })
    }
    $(function () {
       // var to = false;
//        $('#demo_q').keyup(function () {
//            if(to) { clearTimeout(to); }
//            to = setTimeout(function () {
//                var v = $('#demo_q').val();
//                $('#jstree_demo_137765').jstree(true).search(v);
//            }, 250);
//        });
        //初始化章节列表
        $('.jstree_show').jstree({
            "core" : {
                "animation" : 0,
                "check_callback" : true,
                "themes" : { "stripes" : true },
                'data' : {
                    'url' : function (node) {
                        //return node.id === '#' ? '/ajax_demo_roots.json' : '/ajax_demo_children.json';

                        return '/manage/api/get_book_chapter/'+this.element.attr('id').substring(12);
                    },
                    'data' : function (node) {
                        //return { 'id' : node.id };
                        return node;
                    },
                    "state" : "open",
                }
            },
            "types" : {
                "#" : {
                    "max_children" : 1,
                    "max_depth" : 7,
                    "valid_children" : ["root"],
                    "state":["open"]
                },
                "root" : {
                    "icon" : "fa fa-share",
                    "valid_children" : ["default"],
                    "state":"open"
                },
                "default" : {
                    "icon" : "fa fa-circle-o text-aqua",
                    "valid_children" : ["default"],
                    "state":"open"

                },
            },
            "plugins" : [
                "contextmenu", "dnd", "search",
                "state", "types", "wholerow"
            ]
        });

        //生成练习册章节
        $('.make_workbook_chapter').click(function () {
            var this_btn = $(this);
           var o = {
               _token:token,
               book_id :$(this).attr('data-book-id'),
               workbook_id :$(this).attr('data-workbook-id'),
           };
           $.ajax({
               type:'post',
               data:o,
               dataType:'json',
               url:'{{ route('set_workbook_chapter') }}',
               success:function (s) {
                    if(s.status==1){
                        this_btn.removeClass('btn-primary').addClass('btn-danger').html('已生成章节');
                    }
               }
           })
        });

//        $("#jstree_demo_137692").bind('ready.jstree', function(event, data) {
//            var $tree = $('#jstree_demo_137692');
//            $($tree.jstree().get_json($tree, {
//                flat: true
//            }))
//                .each(function(index, value) {
//                    var node = $("#jstree_demo_137692").jstree().get_node(this.id);
//                    var lvl = node.parents.length;
//                    var idx = index;
//                    console.log('node index = ' + idx + ' level = ' + lvl);
//                });
//        });

        //show big photo
        $('.show_cover_photo').click(function () {
            var src_now = $(this).find('img').attr('src');
            $('.modal-body').html('<img class="img-responsive" src='+src_now+'>');
        });
    });
</script>
<script>
    $(function () {
        var token = '{{ csrf_token() }}';
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
                $nextImage.attr('src', src)
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


    });
</script>
@endpush