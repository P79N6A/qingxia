@extends('layouts.backend')

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}" />
<style>
    table td {
        min-width: 80px;
        padding: 3px;
    }
    .form-group{
        margin-bottom: 7px;
        padding-left: 0;
        padding-right: 0;
    }
    .answer-img{
        min-height:640px;
        max-height:640px;
        min-width:540px;
        max-width:540px;
    }
    .cover-img{
        min-height:370px;
        max-height:370px;
    }

    .col-md3, .col-xs-6{
        padding:5px;
    }
    .book-chapter-right{
        position: fixed;
        right: 20px;
        top: 50px;
        z-index: 99999;
        min-width: 160px;
    }
    .book-chapter-left{
        position: fixed;
        left: 50px;
        top: 50px;
        z-index: 99999;
        min-width: 160px;
    }

</style>
@endpush

@section('workbook_now')
    active
@endsection

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

    <div class="modal fade" id="answer_photo">
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

    <div id="form-errors">

    </div>


    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册编辑</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 练习册编辑</h3>
                -->
                @if(!isset($no_onlyname))
                    <strong>{{ $onlyname }}</strong>
                    <p>当前系列专版信息：</p>
                    @if($edits->total()>0)
                        @foreach($edits as $edit)
                            @if($loop->first)
                                {{ $edit->note }}
                            @endif
                        @endforeach
                    @endif
                @else
                    <strong>无onlyname</strong>
                    <p>当前科目：{{ config('workbook.subject_1010')[$subject] }}</p>
                @endif
            </div>
            <div class="box-body">

                <div class="main-sidebar-2">
                    <ul class="nav nav-pills">@foreach($data['all_version'] as $v)<li @if(!empty($data['sort_version'][$v->id])) style="position: relative" @endif>@if(!empty($data['sort_version'][$v->id]))<button data-toggle="dropdown" class="btn-get-menu btn @if($v->id==intval($data['version'])) btn-danger @else btn-primary @endif">{{ $v->name }}</button><ul class="dropdown-menu" style="z-index:9;left:0;">@foreach($data['sort_version'][$v->id] as $key=>$value)@if($loop->first)<li><a href="{{ route('book_arrange',[$v->id,0]) }}"><i class="fa fa-circle-o">全部</i><small class="label pull-right @if($v->id==$data['version'] and $data['grade']==0)bg-red @else bg-blue @endif">{{ collect($data['sort_version'][$v->id])->collapse()->count() }}</small></a></li>@endif<li><a href="{{ route('book_arrange',[$v->id,intval($key)]) }}"><i class="fa fa-circle-o">{{ config('workbook.grade')[intval($key)] }}</i><small class="label pull-right @if($v->id==$data['version'] and $key==$data['grade'])bg-red @else bg-blue @endif">{{ count($value) }}</small></a></li>@endforeach</ul>@else<button class="btn btn-default disabled">{{ $v->name }}</button>@endif</li>@endforeach</ul>
                    <hr />
                </div>

                <div class="book-chapter-right"  id="book-chapter-box">
                    <div class="box box-warning box-solid">
                        @if($book_id_now)
                            <div class="box-header with-border">
                                <h3 class="box-title">课本章节</h3>
                                <div class="box-tools pull-right">
                                    <a class="btn btn-default" id="book-chapter-btn">靠左</a>
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="get-book-chapter" class="box-body">
                                <div data-book-id="{{ $book_id_now }}" id="jstree_demo_book_chapter" class="jstree_show demo pull-left" style="margin-top:1em; min-height:200px;"></div>
                            </div>
                        @else
                            <div>暂无对应课本章节</div>
                        @endif
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <li role="presentation" @if($status==0) class="active" @endif>
                        <a @if(!isset($no_onlyname)) href="{{ route('workbook_edit',[$onlyname]) }} @endif">
                            @if(!isset($no_onlyname))
                                <span>未整理</span>
                            @else
                                <span>无onlyname</span>
                            @endif
                            @if($status==0)
                                <span id="now_num" class="label label-danger ">{{ $edits->total() }}</span>
                            @endif
                        </a>
                    </li>
                    @if(!isset($no_onlyname))
                        <li role="presentation" @if($status==1) class="active" @endif>
                            <a href="{{ route('workbook_edit',[$onlyname,1]) }}">
                                <span>已整理</span>
                                @if($status==1)
                                    <span id="now_num" class="label label-danger">{{ $edits->total() }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    <li><a>{{ $no_answer_num }}本无答案</a></li>
                </ul>

                @if($edits->total()>0)
                    @foreach($edits as $edit)

                        @if($loop->first)
                            <div class="row">
                                @endif
                                <div class="col-md-3 col-xs-6 pull-left edit_box" data-id="{{ $edit->id }}" style="font-size: 12px;margin-bottom: 10px">
                                    <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                        @if($edit->cover_photo!='')
                                            <img class="img-responsive cover-img" data-original="{{ config('workbook.cover_url').$edit->cover_photo }}">
                                        @else
                                            <img class="img-responsive cover-img" data-original="{{ config('workbook.workbook_url').$edit->cover_photo_thumbnail }}">
                                        @endif
                                    </a>
                                    <input name="original_name" style="font-size: 1px;padding: 1px;" class="form-control " value="{{ $edit->bookname }}" />

                                    <div class="input-group" style="width:100%">
                                        <select data-name="version_year" style="width:45%" class="update_data form-control select2"
                                                tabindex="-1" aria-hidden="true">
                                            @foreach(config('workbook.book_version') as $key=>$value)
                                                @if($edit->version_year==$value)
                                                    @php $select='selected=selected'; @endphp
                                                @else
                                                    @php $select = ''; @endphp
                                                @endif<option {{$select}} value="{{ $value }}">{{ $value }}</option>@endforeach
                                        </select>
                                        <select style="width:55%" data-name="sort" class="update_data form-control sort_select">
                                            <option value="{{ $edit->sort }}">{{ $edit->sort_name }}</option>
                                        </select>
                                        {{--<input class="form-control" style="width:65%" disabled="disabled" name="sort" value="{{ $edit->sort_name }}" />--}}

                                    </div>

                                    <div class="input-group" style="width:100%">
                                        <select data-name="grade_id" class="update_data form-control select2 pull-left" style="width:25%"
                                                tabindex="-1" aria-hidden="true">
                                            @foreach(config('workbook.grade') as $key=>$value)
                                                @if(intval($edit->grade_id)==$key)
                                                    @php $select='selected=selected'; @endphp
                                                @else
                                                    @php $select = ''; @endphp
                                                @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <select data-name="subject_id" class="update_data form-control select2" style="width:25%">
                                            tabindex="-1" aria-hidden="true">
                                            @foreach(config('workbook.subject_1010') as $key=>$value)
                                                @if(intval($edit->subject_id)==$key)
                                                    @php $select='selected=selected'; @endphp
                                                @else
                                                    @php $select = ''; @endphp
                                                @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                        </select>
                                        <select data-name="volumes_id" class="update_data form-control select2" style="width:25%">
                                            @foreach(config('workbook.volumes') as $key=>$value)
                                                @if(intval($edit->volumes_id)==$key)
                                                    @php $select='selected=selected'; @endphp
                                                @else
                                                    @php $select = ''; @endphp
                                                @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                        </select>
                                        <select data-name="version_id" class="update_data form-control select2" style="width:25%">
                                            tabindex="-1" aria-hidden="true">
                                            @foreach($version as $value)
                                                @if($edit->version_id==intval($value->id))
                                                    @php $select='selected=selected'; @endphp
                                                @else
                                                    @php $select = ''; @endphp
                                                @endif<option {{$select}} value="{{ intval($value->id) }}">{{ $value->name }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input name="special_info" class="form-control" style="width:50%" placeholder="答案不同专版信息" value="{{ $edit->special_info }}"/>
                                        <input name="special_info_2" class="form-control" style="width:50%" placeholder="答案相似专版信息" value="{{ $edit->special_info_2 }}"/>
                                    </div>
                                    <select style="width: 100%;" data-name="press_id" class="update_data form-control press_select">
                                        <option value="{{ $edit->press_id }}">{{ $edit->press_name }}</option>
                                    </select>
                                    {{--<input disabled="disabled" class="form-control" name="press" value="{{ $edit->press_name }}" />--}}

                                    @if(!empty($edit->answers))
                                        <div id="myCarousel_{{ $edit->id }}" class="clear carousel slide" data-interval="false">
                                            <div class="carousel-inner" >
                                                @foreach($edit->answers as $key => $answer)
                                                    @if(is_array($answer))
                                                        @foreach($answer as $answer_img)
                                                            <div class="item @if ($loop->first && $key==0) active  @endif">
                                                                <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                                                    <img class="answer-img img-responsive" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}"
                                                                         alt="First slide">
                                                                </a>
                                                                <div class="carousel-caption text-orange">{{ $edit->textname[$key] }}</div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="item @if ($loop->first && $key==0) active @endif">
                                                            <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                                                <img class="answer-img img-responsive" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer) }}" alt="First slide">
                                                            </a>
                                                            <div class="carousel-caption text-orange FontBig">{{ $edit->textname[$key] }}</div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <a class="carousel-control  left" href="#myCarousel_{{ $edit->id }}"
                                               data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                            <a class="carousel-control right" href="#myCarousel_{{ $edit->id }}"
                                               data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                        </div>
                                    @else
                                        <p>暂无对应答案</p>
                                    @endif

                                    <div class="input-group">
                                        <a class="btn btn-success btn-xs all_done pull-left">完成编辑</a>
                                        <a class="btn btn-danger btn-xs page_all_done pull-right">全部完成编辑</a>
                                    </div>
                                </div>
                                @if(($loop->index+1)%4==0)
                            </div><div class="row">
                                @endif
                                @if($loop->last)
                            </div>
                        @endif

                    @endforeach
                    <div class="pull-right">
                        {{ $edits->links() }}
                    </div>
                @else
                    <p>暂无信息</p>
                @endif
            </div>
        </div>
        </div>

    </section>
@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="{{ asset('js/jstree.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script>
    var token = '{{ csrf_token() }}';
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //show big photo
        $('.show_cover_photo').click(function () {
            var src_now = $(this).find('img').attr('src');
            $('.modal-body').html('<img class="img-responsive" src=' + src_now + '>');
        });
        //for_sorts
//        $('.sorts').click(function () {
//            if ($(this).find('option').length == 1) {
//                var sort_length = $('#for_sorts').children().length;
//                var sort_clone = $('#for_sorts').clone(true);
//                for (var i = 0; i < sort_length; i++) {
//                    $(this).children('.sorts_option').append(sort_clone.children(i));
//                }
//            }
//        });


        $('.update_data').change(function () {
            update_info($(this));
        });

        function update_info(info) {
            var tr_now = $(info).parents('.edit_box');
            var id = tr_now.data('id');
            var now_name = $(info).data('name');
            var now_data = $(info).val();
            console.log(info);
            var post_data = {
                'id': id,
                '_token': token,
                'o_uid': '{{ Auth::user()->id }}'
            };
            post_data[now_name] = now_data;
            $.ajax({
                type: "POST",
                url: "{{ route('workbook_update') }}",
                data: post_data,
                success: function (t) {

                },
                error: function (t) {
                    var errors = t.responseJSON;
                    var errorsHtml = '<div class="alert alert-danger">' +
                        '<span class="close" data-dismiss="alert">&times;</span>' +
                        '<ul>';
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(errorsHtml);
                },
                dataType: "json"
            })
        }

        //完成编辑
        $('.all_done').click(function () {
            var data_not_alert = $(this).attr('data_not_alert');
            var status_tab = '{{ $status }}';
            var now_this = $(this);
            var id = now_this.parents('.edit_box').data('id');

            var special_info = $(this).parents('.edit_box').find('input[name="special_info"]').val();
            var special_info_2 = $(this).parents('.edit_box').find('input[name="special_info_2"]').val();
            var original_name = $(this).parents('.edit_box').find('input[name="original_name"]').val();
            var o = {
                'id': id,
                'original_name': original_name,
                'special_info': special_info,
                'special_info_2': special_info_2,
                '_token': token,
                'o_uid': '{{ Auth::user()->id }}'
            };
            $.ajax({
                type: "POST",
                url: "{{ route('workbook_done') }}",
                data: o,
                success: function (t) {
                    if (t.status == 1) {
                        if (status_tab == 0) {
                            $('#now_num').html(parseInt($('#now_num').html()) - 1);
                            now_this.parents('.edit_box').remove();
                        } else {
                            if (data_not_alert != 0) {
                                alert('更新成功');
                            }
                        }
                    }
                },
                error: function (t) {
                    var errors = t.responseJSON;
                    var errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(errorsHtml);
                },
                dataType: "json"
            })
        });

        //全部完成
        $('.page_all_done').click(function () {
            if (confirm('确认全部完成编辑')) {
                $('.all_done').attr('data_not_alert', '0');
                $('.all_done').click();
            }
        });

        //clear the modal data
        $('#answer_photo').on('hidden.bs.modal', function () {
            $(this).removeData('bs.modal');
        });

        //获取系列
        $(".sort_select").select2({
            language: "zh-CN",
            ajax: {
                type: 'GET',
                url: "{{ route('workbook_sort') }}",
                dataType: 'json',
                delay: 100,
                data: function (params) {
                    return {
                        word: params.term, // search term 请求参数
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,//itemList
                    };
                },
                cache: true
            },

            escapeMarkup: function (markup) {
                return markup;
            }, // 自定义格式化防止xss注入
            minimumInputLength: 1,//最少输入多少个字符后开始查询
            templateResult: function formatRepo(repo) {
                if (repo.loading) return repo.text;
                return '<option value="' + repo.id + '">' + repo.name + '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo){
                return repo.name || repo.text;
            },

        });


        //获取出版社
        $(".press_select").select2({
            language: "zh-CN",
            ajax: {
                type: 'GET',
                url: "{{ route('workbook_press') }}",
                dataType: 'json',
                delay: 100,
                data: function (params) {
                    return {
                        word: params.term, // search term 请求参数
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,//itemList
                    };
                },
                cache: true
            },

            escapeMarkup: function (markup) {
                return markup;
            }, // 自定义格式化防止xss注入
            minimumInputLength: 1,//最少输入多少个字符后开始查询
            templateResult: function formatRepo(repo) {
                return '<option value="' + repo.id + '">' + repo.name + '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo){
                return repo.name || repo.text;
            },

        });

        //图片加载
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

        //获取课本章节
        $('.jstree_show').jstree({
            "core" : {
                "animation" : 0,
                "check_callback" : true,
                "themes" : { "stripes" : true },
                'data' : {
                    'url' : function (node) {
                        //return node.id === '#' ? '/ajax_demo_roots.json' : '/ajax_demo_children.json';

                        return '/manage/api/get_book_chapter/'+this.element.attr('data-book-id');
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

        //课本章节靠左靠右
        $('#book-chapter-btn').click(function () {
            if($('#book-chapter-box').hasClass('book-chapter-right')) {
                $(this).html('靠右');
                $('#book-chapter-box').removeClass('book-chapter-right').addClass('book-chapter-left');
            }else{
                $('#book-chapter-box').removeClass('book-chapter-left').addClass('book-chapter-right');
                $(this).html('靠左');
            }
        })
    });

</script>
@endpush
