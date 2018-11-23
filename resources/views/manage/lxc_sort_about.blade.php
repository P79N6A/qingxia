@extends('layouts.backend')

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
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
        min-height:170px;
        max-height:170px;
    }

    .col-md3, .col-xs-6{
        padding:5px;
    }


</style>
@endpush

@section('book_now')
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
        <li class="active">系列整理</li>
    </ol>
</section>

<section class="content">

    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 系列</h3>
            -->
            <strong><a class="main_word_click btn btn-xs btn-primary">{{ $data['sort_info']->name }}</a></strong>
            <br />
            <span>当前系列专版信息：</span>
            <strong>{{ $data['sort_info']->note }}</strong>
            <br />
            <span>当前系列已整理封面大字:</span>
            <strong>
                <span id="main-word-group">
                @if(!empty($data['sort_info']->main_word))
                    @foreach(explode(',',$data['sort_extra']->main_word) as $value)
                            <a class="btn btn-xs btn-primary"><strong class="main_word_btn">{{ $value }}</strong><i class="fa fa-times del_main_word"></i></a>
                    @endforeach
                @endif
                </span>
                <div class="input-group" style="width: 30%">
                    <input class="input-xs form-control" value="" placeholder="新增封面大字"/>
                    <a id="btn-add-main-word" data-sort="{{ $data['sort_info']->id }}" class="btn btn-xs btn-danger input-group-addon">新增</a>
                </div>
            </strong>
            <br />

            <span>当前系列已整理子系列:</span>
            <strong>
                <span id="sub-sort-group">
                @if(!empty($data['sort_extra']->sub_sort))
                    @foreach(explode(',',$data['sort_extra']->sub_sort) as $value)
                            <a class="btn btn-xs btn-primary"><span class="sub_sort_btn">{{ $value }}</span><i class="fa fa-times del_sub_sort"></i></a>
                    @endforeach
                @endif
                </span>
                    <div class="input-group" style="width: 30%">
                        <input class="input-xs form-control" value="" placeholder="新增子系列"/>
                        <a id="btn-add-sub-sort" class="btn btn-xs btn-danger input-group-addon">新增</a>
                    </div>
            </strong>

        </div>

        <div class="box">
            <div class="box-body">
                <div class="main-sidebar-2">
                    <div >
                        <ul class="nav nav-pills">
                    @foreach(config('workbook.grade') as $k=>$v)
                        <li @if(!empty($data['sort_grade'][$k])) style="position: relative" @endif>
                            @if(!empty($data['sort_grade'][$k]))
                                <button data-toggle="dropdown" class="btn-get-menu btn @if($k==$data['grade']) btn-danger @else btn-primary @endif">{{ $v }}</button>
                                <ul class="dropdown-menu" style="z-index:9;left:0;">
                                    @foreach($data['sort_grade'][$k] as $key=>$value)
                                        <li>
                                            <a href="{{ route('lxc_sort',[$data['sort_info']->id,$key,$k]) }}">
                                                <i class="fa fa-circle-o">{{ config('workbook.subject')[$key] }}</i>
                                                <span class="pull-right-container">
                                                  <small class="label pull-right @if($key==$data['subject'] and $k==$data['grade'])bg-red @else bg-blue @endif">{{ $value }}</small>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                @foreach($data['all_sort_book'] as $value)
                    <div class="col-md-2 col-xs-6 pull-left edit_box" data-id="{{ $value->id }}" style="font-size: 12px;margin-bottom: 10px">
                        <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                            <img class="img-responsive cover-img" data-src="{{ config('workbook.cover_url').$value->cover_photo }}" src="{{ config('workbook.cover_url_thumbnail').$value->cover_photo_thumbnail }}">
                        </a>
                        <input name="original_name" disabled="disabled" style="font-size: 1px;padding: 1px;" class="original_name form-control " value="{{ $value->name }}" />
                        <input class="form-control" name="main_word" placeholder="封面大字" type="text" value="{{ $value->main_word }}" />
                        <input class="form-control" name="sub_sort" placeholder="子系列" type="text" value="{{ $value->sub_sort }}" />
                        <div class="input-group" style="margin: 5px">
                            {{--<a class="input-control btn btn-xs btn-primary make_main_word">生成封面大字</a>--}}
                            <a class="input-control all_done btn btn-xs btn-success">完成编辑</a>
                            <a class="input-control page_all_done btn btn-xs btn-danger">全部完成</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pull-left">
                {{ $data['all_sort_book']->links() }}
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
<script>
    var token = '{{ csrf_token() }}';
    $(function () {

        $('.main_word_click').click(function () {
            $('#btn-add-main-word').prev().val($(this).html());
            $('#btn-add-main-word').click();
        });

        //新增大字和子系列
        $('#btn-add-main-word,#btn-add-sub-sort').click(function () {
            var now_word = $(this).prev().val();
            var now_sort = $('#btn-add-main-word').attr('data-sort');
            var update_type = '';
            if($(this).attr('id')=='btn-add-main-word'){
                update_type='main_word';
            }else{
                update_type='sub_sort';
            }
            var o = {
                'type':update_type,
                'sort':now_sort,
                'update_word':now_word,
                '_token':token,
            };
            $.ajax({
                type: "POST",
                url: "{{ route('sort_update') }}",
                data: o,
                success: function (t) {
                    if(t.status==1) {
                        alert('更新成功');
						var had_word = 0;
						if(update_type=='main_word'){
                            $('#main-word-group .main_word_btn').each(function () {
                                if($(this).html()==now_word){
                                    had_word = 1;
                                }
                            });
                            if(had_word==0){
                                $('#main-word-group').append('<a class="btn btn-xs btn-primary"><span class="main_word_btn">'+now_word+'</span><i class="fa fa-times del_main_word"></i></a>');
                            }
                        }else{
                            $('#sub-sort-group .sub_sort_btn').each(function () {
                                if($(this).html()==now_word){
                                    had_word = 1;
                                }
                            });
                            if(had_word==0) {
                                $('#sub-sort-group').append('<a class="btn btn-xs btn-primary"><span class="sub_sort_btn ">' + now_word + '</span><i class="fa fa-times del_sub_sort"></i></a>');
                            }
                        }

                    }else{
                        alert('更新失败');
                    }

                },
                error: function (t) {
//                    var errors = t.responseJSON;
//                    var errorsHtml = '<div class="alert alert-danger"><ul>';
//
//                    $.each( errors , function( key, value ) {
//                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
//                    });
//                    errorsHtml += '</ul></div>';
//
//                    $('#form-errors').html( errorsHtml );
                },
                dataType: "json"
            });
        });

        $('#btn-add-sub-sort').click(function () {
            var now_word = $(this).prev().val();
            var had_word = 0;
            $('#sub-sort-group .sub_sort_btn').each(function () {
                if($(this).html()==now_word){
                    had_word = 1;
                }
            });
            if(had_word==1){
                alert('已有子系列');
                return false;
            }
            var has_word = 0;
            $('.original_name').each(function () {
                if($(this).next().next().val()==''){
                    $(this).next().next().val(now_word);
                    has_word = 1;
                }
            });
            if(has_word==1){
                $('#sub-sort-group').append('<a class="btn btn-xs btn-primary"><span class="sub_sort_btn ">'+now_word+'</span><i class="fa fa-times del_sub_sort"></i></a>');
            }else{
                alert('未匹配到子系列');
            }
        });




        //点击大字
        $(document).on('click','.main_word_btn',function () {
            var now_word = $(this).html();
            var has_word = 0;
            $('.original_name').each(function () {
                if($(this).val().indexOf(now_word)!=-1){
                    $(this).next().val(now_word);
                    has_word = 1;
                }
            });
            if(has_word==1){
                var had_word = 0;
                $('#main-word-group .main_word_btn').each(function () {
                    if($(this).html()==now_word){
                        had_word = 1;
                    }
                });
                if(had_word==0){
                    $('#main-word-group').append('<a class="btn btn-xs btn-primary"><span class="main_word_btn">'+now_word+'</span><i class="fa fa-times del_main_word"></i></a>');
                }else{
                    alert('匹配成功');
                }
            }else{
                alert('未匹配到封面大字');
            }
        });

        //删除大字或子系列
        $(document).on('click','.del_main_word,.del_sub_sort',function () {
            var main_word_btn = $(this).parent();
            var now_word = $(this).prev().html();
            var now_sort = $('#btn-add-main-word').attr('data-sort');
            var del_type = '';
            if($(this).hasClass('del_main_word')){
                del_type='main_word';
            }else{
                del_type='sub_sort';
            }
            var o = {
                'type':del_type,
                'sort':now_sort,
                'update_word':now_word,
                '_token':token,
            };
            $.ajax({
                type: 'POST',
                url: '{{ route("sort_del") }}',
                data: o,
                success: function (t) {
                    console.log(t.status);
                    if(t.status==1) {
                        alert('删除成功');
                        main_word_btn.remove();
                    }else{
                        alert('删除失败');
                    }
                },
                error: function (t) {

                },
                dataType: "json"
            })
        });

        //show big photo
        $('.show_cover_photo').click(function () {
            var src_big = $(this).find('img').attr('data-src');
            $('.modal-body').html('<img class="img-responsive" src='+src_big+'>');
        });

        //生成封面大字
//        $('.main_word_btn').click(function () {
//            var main_word= $(this).html();
//            $('input[name="main_word"]').val(main_word);
//        });

        $('.sub_sort_btn').click(function () {
            var sub_sort= $(this).html();
            $('input[name="sub_sort"]').val(sub_sort);
        });

        //完成编辑
        $('.all_done').click(function () {
            var data_not_alert = $(this).attr('data_not_alert');
            var status_tab = 1;
            var now_this = $(this);
            var id = now_this.parents('.edit_box').data('id');
            var sub_sort= $(this).parents('.edit_box').find('input[name="sub_sort"]').val();
            var main_word= $(this).parents('.edit_box').find('input[name="main_word"]').val();

            var o = {
                'id':id,
                'sub_sort':sub_sort,
                'main_word':main_word,
                '_token':token,
                'o_uid':'{{ Auth::user()->id }}'
            };
            $.ajax({
                type: "POST",
                url: "{{ route('sort_done') }}",
                data: o,
                success: function (t) {
                    if(t.status==1){
                        if(status_tab==0){
//                            $('#now_num').html(parseInt($('#now_num').html())-1);
                            now_this.parents('.edit_box').remove();
                        }else{
                            if(data_not_alert!=0)
                            {
                                alert('更新成功');
                            }
                        }
                    }
                },
                error: function (t) {
                    var errors = t.responseJSON;
                    var errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each( errors , function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html( errorsHtml );
                },
                dataType: "json"
            })
        });



        //全部完成
        $('.page_all_done').click(function () {
            if(confirm('确认全部完成编辑')){
                $('.all_done').attr('data_not_alert','0');
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
    });



</script>
@endpush
