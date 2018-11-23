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
    input[name='sort_name']{
        width:75% !important;
        float:right !important;
        font-size: 1px;padding: 1px;
    }
</style>
@endpush

@section('book_recycle')
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
        <li class="active">回收站</li>
    </ol>
</section>

<section class="content">

    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 练习册回收</h3>
            -->

        </div>
        <div class="box-body">
            <ul class="nav nav-tabs">
                <li><a href="">练习册回收</a></li>
            </ul>
            @if($edits->total()>0)
                @foreach($edits as $edit)

                    @if($loop->first)
                        <div class="row">
                            @endif
                            <div class="col-md-3 col-xs-6 pull-left edit_box" data-id="{{ $edit->id }}" style="font-size: 12px;margin-bottom: 10px">
                                <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                    @if($edit->cover_photo!='')
                                        <img class="img-responsive cover-img" data-original="{{ config('workbook.workbook_url').$edit->cover_photo }}">
                                    @else
                                        <img class="img-responsive cover-img" data-original="{{ config('workbook.workbook_url').$edit->cover_photo_thumbnail }}">
                                    @endif
                                </a>
                                <input name="original_name" disabled style="font-size: 1px;padding: 1px;" class="form-control " value="{{ $edit->bookname }}" />

                                <div class="input-group" style="width:100%">

                                    <select data-name="version_year" disabled style="width:25%" class="update_data form-control select2"
                                            tabindex="-1" aria-hidden="true">
                                        @foreach(config('workbook.book_version') as $key=>$value)
                                            @if($edit->version_year==$value)
                                                @php $select='selected=selected'; @endphp
                                            @else
                                                @php $select = ''; @endphp
                                            @endif<option {{$select}} value="{{ $value }}">{{ $value }}</option>@endforeach
                                    </select>
                                    {{--<select style="width:75%" data-name="sort" class="update_data form-control sort_select">--}}
                                    {{--<option value="{{ $edit->sort }}">{{ $edit->sort_name }}</option>--}}
                                    {{--</select>--}}
                                    <input class="form-control" disabled name="sort_name" value="{{ $edit->sort_name }}" />
                                </div>
                                <input class="form-control disabled" name="onlycode" value="{{ $edit->onlycode }}" />
                                <div class="input-group" style="width:100%">
                                    <select data-name="grade_id" disabled class="update_data form-control select2 pull-left" style="width:25%"
                                            tabindex="-1" aria-hidden="true">
                                        @foreach(config('workbook.grade') as $key=>$value)
                                            @if(intval($edit->grade_id)==$key)
                                                @php $select='selected=selected'; @endphp
                                            @else
                                                @php $select = ''; @endphp
                                            @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <select data-name="subject_id" disabled class="update_data form-control select2" style="width:25%" tabindex="-1" aria-hidden="true">
                                        @foreach(config('workbook.subject_1010') as $key=>$value)
                                            @if(intval($edit->subject_id)==$key)
                                                @php $select='selected=selected'; @endphp
                                            @else
                                                @php $select = ''; @endphp
                                            @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                    </select>
                                    <select data-name="volumes_id" disabled class="update_data form-control select2" style="width:25%">
                                        @foreach(config('workbook.volumes') as $key=>$value)
                                            @if(intval($edit->volumes_id)==$key)
                                                @php $select='selected=selected'; @endphp
                                            @else
                                                @php $select = ''; @endphp
                                            @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                    </select>
                                    <select data-name="version_id" disabled class="update_data form-control select2" style="width:25%">
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
                                    <input name="district" disabled class="form-control" style="width:50%" placeholder="地区信息" value="{{ $edit->district }}" />
                                    <input name="isbn" disabled class="form-control" style="width:50%" value="{{ $edit->isbn }}" />
                                </div>
                                <select style="width: 100%;" disabled data-name="press_id" class="update_data form-control press_select">
                                    <option value="{{ $edit->press_id }}">{{ $edit->press_name }}</option>
                                </select>
                                {{--<input disabled="disabled" class="form-control" name="press" value="{{ $edit->press_name }}" />--}}
                                <div class="input-group">
                                    <a class="btn btn-primary pull-left" target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $edit->id }}.html">查看练习册</a>
                                    <a class="btn btn-success  recovery-book pull-left">恢复</a>
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




        //删除
        $(document).on('click','.recovery-book ',function(){
            var book_id = $(this).parents('.edit_box').attr('data-id');
            var o = {_token:token,book_id:book_id};
            $.ajax({
                type:'post',
                dataType:'json',
                url:'{{ route('recovery_this_book') }}',
                data:o,
                success:function (s) {
                    if(s.status==1){
                        $('.edit_box[data-id="'+book_id+'"]').remove();
                    }
                }
            })
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



    });

</script>
@endpush
