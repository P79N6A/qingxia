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
        z-index: 1050;
        min-width: 180px;
    }
    .book-chapter-left{
        position: fixed;
        left: 50px;
        top: 50px;
        z-index: 1050;
        min-width: 180px;
    }
    input[name='sort_name']{
        font-size: 1px;padding: 1px;
    }
    a.show_cover_photo{
        margin-bottom: 0;
    }
    .for-choose{
        margin-bottom: 0;
        height: 30px;
        line-height: 30px;
    }
    #sync-box{
        border-bottom: none;
    }
    .box-body{
        border-bottom: none;
    }
    #change_cover img{
        min-width: 150px;
        max-height: 250px;
        min-height: 250px;
    }
    .check_box{
        width: 20px;
        height: 15px;
    }
</style>
@endpush

@section('sort_name')
active
@endsection

@section('content')

<div class="modal fade" id="cover_photo">
    <div class="modal-dialog" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">
                    查看图片
                    <span><a class="photo_left btn btn-default">向左旋转</a><a class="photo_right btn btn-default">向右旋转</a></span>
                </h4>

            </div>
            <div class="modal-body">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="change_cover">
    <div class="modal-dialog" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">
                    选择封面
                </h4>
            </div>
            <div class="modal-body" style="display: flex;overflow: auto">

            </div>
        </div>
    </div>
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
            <h3 class="box-title"><i class="fa fa-tag"></i> sort_name 整理</h3>
        </div>
        <div class="box-body">
			{{--<ul class="nav nav-pills">--}}
                {{--href="@if($data['current_route']=='sort_name_all'){{ route($data['current_route'],[$data['id_now'],1,0,1]) }}@else {{ route($data['current_route'],[$data['id'],$data['id_now'],0,1]) }}@endif"--}}
                {{--<a class="btn btn-success pull-left show-all-box">全部</a>--}}
				{{--@if(isset($data['all_data']))--}}
                    {{--@foreach($data['all_data'] as $key=>$value)--}}
                        {{--<li>--}}
                                {{--<button data-toggle="dropdown"--}}
                                        {{--class="btn-get-menu btn @if($key==$data['press_now']) btn-danger @else btn-primary @endif">{{ $value['press_name'].'_'.$value['press_id'] }}</button><span><input data-press-id="{{ $value['press_id'] }}" class="check_box" type="checkbox"/></span>--}}
                                {{--<ul class="dropdown-menu"--}}
                                    {{--style="z-index:9;left:0;">--}}
                                    {{--href="@if($data['current_route']=='sort_name_all'){{ route($data['current_route'],[$data['id_now'],$key,$key1]) }}@else {{ route($data['current_route'],[$data['id'],$data['id_now'],$key]) }}@endif"--}}
                                    {{--@foreach($value['sort_name'] as $key1=>$value1)<li><a><label><i class="fa fa-circle-o">{{ $key1 }}</i><small class="label pull-right @if($key==$data['press_now'] and $key1==$data['sort_name_now'])bg-red @else bg-blue @endif">{{ count($value1) }}</small><input data-press-id="{{ $value['press_id'] }}" data-sort-name="{{ $key1 }}" class="check_box" type="checkbox"/></label></a></li>@endforeach</ul></li>@endforeach @endif--}}
			{{--</ul>--}}
            @if(count($data['book_now'])>0)
            @foreach($data['book_now'] as $edit)
                @if($loop->first)<div class="row">@endif<div class="col-md-3 col-xs-6 pull-left edit_box" data-id="{{ $edit->id }}" data-press-id="{{ $edit->press_id }}" data-sort-name="{{ $edit->sort_name }}" style="font-size: 12px;margin-bottom: 10px">
                    <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                        @if(starts_with($edit->cover_photo_thumbnail,'//') or starts_with($edit->cover_photo_thumbnail,'http'))<img class="img-responsive cover-img lazy-load" data-original="{{ $edit->cover_photo_thumbnail }}" >@else<img class="img-responsive cover-img lazy-load" big_cover="{{ config('workbook.workbook_url').$edit->cover_photo }}" data-original="{{ config('workbook.workbook_url').$edit->cover_photo_thumbnail }}" >@endif
                        </a>
                            <span class="front-operation" data-id="{{ $edit->id }}">
                            </span>
                    <input name="original_name" style="font-size: 1px;padding: 1px;" class="form-control " value="{{ $edit->bookname }}" />
                    <div class="input-group" style="width:100%">
                        <select data-name="version_year" style="width:25%" class="update_data form-control select2" tabindex="-1" aria-hidden="true"> @foreach(config('workbook.book_version') as $key=>$value) @if($edit->version_year==$value) @php $select='selected=selected'; @endphp @else @php $select = ''; @endphp @endif<option {{$select}} value="{{ $value }}">{{ $value }}</option>@endforeach</select>
                    <select style="width:75%" data-name="sort" class="update_data form-control sort_select"><option value="{{ $edit->sort_id }}">{{ $edit->sort_name_real }}</option></select>
                    </div>
                    <div class="input-group">
                        <input class="form-control sort-name-now" name="sort_name" value="{{ $edit->sort_name }}" />                    <a class="btn btn-primary input-group-addon choose-sortname">
                            选择
                        </a>
                    </div>

                    <div class="input-group" style="width:100%">
                        <select data-name="grade_id" class="update_data form-control select2 pull-left" style="width:25%" tabindex="-1" aria-hidden="true"><option selected="selected" value="{{ $edit->grade_id }}">{{ config('workbook.grade')[intval($edit->grade_id)] }}</option></select>
                        <select data-name="subject_id" class="update_data form-control select2" style="width:25%" tabindex="-1" aria-hidden="true"><option selected="selected" value="{{ $edit->subject_id }}">{{ config('workbook.subject_1010')[intval($edit->subject_id)] }}</option></select>
                        <select data-name="volumes_id" class="update_data form-control select2" style="width:25%"><option selected="selected" value="{{ $edit->volumes_id }}">{{ $data['all_volumes']->where('code',$edit->volumes_id)->first()->volumes }}</option></select>
                        <select data-name="version_id" class="update_data form-control select2" style="width:25%" tabindex="-1" aria-hidden="true"><option selected="selected" value="{{ $edit->version_id }}">{{ $data['all_version']->where('id',$edit->version_id)->first()->name }}</option></select>
                    </div>
                    <div class="input-group" style="width:100%">
                    <input name="district" class="form-control" style="width:50%" placeholder="地区信息" value="{{ $edit->district }}" />
                    <input name="isbn" class="form-control" style="width:50%" value="{{ $edit->isbn }}" />
                    </div>
                    <select style="width: 100%;" data-name="press_id" class="update_data form-control press_select"><option value="{{ $edit->press_id }}">{{ $edit->press_name.'_'.$edit->press_id }}</option></select>
                </div>
                @if(($loop->index+1)%4==0)
                    </div><div class="row">
                @endif
                @if($loop->last)
                    </div>
                @endif
            @endforeach
                <div class="pull-right">
                    @if(isset($data['has_paginate']))
                        {{ $data['book_now']->links() }}
                    @endif
                </div>
            @else
                <p>暂无信息</p>
            @endif
            <select id="all_sort_name_select" class="form-control" style="display: none">
                @if(isset($data['all_sort_name']))
                    <option>选择已有sort_name</option>
                    @foreach($data['all_sort_name'] as $value)
                        <option class="select_to_sort_name">{{ $value }}</option>
                    @endforeach
                @endif
            </select>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script>
    var token = '{{ csrf_token() }}';
    $(function () {

        //Initialize Select2 Elements
        $('select[data-name="grade_id"]').select2({ data: $.parseJSON('{!! $data['grade_select'] !!} '),});
        $('select[data-name="subject_id"]').select2({ data: $.parseJSON('{!! $data['subject_select'] !!} '),});
        $('select[data-name="volumes_id"]').select2({ data: $.parseJSON('{!! $data['volume_select'] !!} '),});
        $('select[data-name="version_id"]').select2({ data: $.parseJSON('{!! $data['version_select'] !!} '),});

        $('.front-operation').each(function (i) {
            var edit_id = $(this).attr('data-id');
            $(this).prepend(
                '<span><a class="btn btn-primary sort_all_select pull-left" style="width:33.3%;">全选</a> ' +
                '<a class="btn btn-danger sort_invert_select pull-left" style="width:33.3%;">反选</a> ' +
                '<a class="btn btn-primary sort_delete pull-left" style="width:33.3%">删除</a></span> ' +
                '<a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_'+edit_id+'.html" class="btn btn-success pull-left" style="width:30%">查看练习册</a><p class="bg-blue text-center for-choose" data-id="'+edit_id+'" style="width:45%;float: left">选中练习册</p>' +
                '<a class="btn btn-info pull-right change_cover_btn" style="width:25%;float: left" data-toggle="modal" data-target="#change_cover" data-id="'+edit_id+'">更换封面</a>');
        });


        $('.edit_box').append('<div class="input-group"> ' +
            '' +
            '<a class="btn btn-primary btn-xs sort_all_done pull-left" style="width:33%">统一选中sort</a> ' +
            '<a class="btn btn-success btn-xs pull-left sort_name_all_done" style="width:33%">统一选中sort_name</a> ' +
            '<a class="btn btn-danger btn-xs press_all_done pull-left" style="width:33%">统一选中出版社</a> ' +
            '<a class="btn btn-primary btn-xs pull-left cancel_all_select" style="width:33%">取消所有选中</a> ' +
            '<a class="all_done"></a> ' +
            '<a class="btn btn-success btn-xs page_select_done pull-left" style="width:33%">选中部分完成编辑</a> ' +
            '<a class="btn btn-danger btn-xs page_all_done pull-left" style="width:33%">全部完成编辑</a> ' +
            '</div>');

        //选择已有sortname
        $('.choose-sortname').click(function () {
            if($(this).parent().next().attr('id')=='all_sort_name_select'){
                $(this).parent().next().show()
            }else{
                var select = $('#all_sort_name_select').clone().show();
                $(this).parent().after(select);
            }
        });
        $(document).on('change','#all_sort_name_select',function () {
           $(this).prev().find('.sort-name-now').val($(this).find("option:selected").text());
           $(this).hide();
        });

        $('.show-all-box').click(function () {
            $('.check_box').attr('checked',false);
            $('.edit_box').show();
            $('.edit_box .for-choose').removeClass('bg-blue').removeClass('bg-red').addClass('bg-blue').html('选中练习册');
        });

        //选中练习册_top
        $('.check_box').click(function () {
            var check_box_len = $('.check_box').length;
            var s = 0;
            $('.check_box').each(function (i) {
                var press_id = $(this).attr('data-press-id');
                var sort_name = $(this).attr('data-sort-name');
                var sort_name_select = '';
                if(sort_name){
                    sort_name_select = '[data-sort-name="' + sort_name + '"]';
                }
                if(!$(this).is(':checked')){
                    s+=1;
                    $('.edit_box[data-press-id=' + press_id + ']'+sort_name_select+' .for-choose').removeClass('bg-blue').removeClass('bg-red').addClass('bg-blue').html('选中练习册');
                }
            });
            if(s!=check_box_len) {
                $('.check_box').each(function (i) {
                    var press_id = $(this).attr('data-press-id');
                    var sort_name = $(this).attr('data-sort-name');
                    var sort_name_select = '';
                    if (sort_name) {
                        sort_name_select = '[data-sort-name="' + sort_name + '"]';
                    }
                    if ($(this).is(':checked')) {
                        $('.edit_box[data-press-id=' + press_id + ']' + sort_name_select + ' .for-choose').removeClass('bg-blue').removeClass('bg-red').addClass('bg-red').html('已选中');
                    }
                })
            }
        });


        //选中练习册
        $('.for-choose').click(function () {
            var now = $(this);
            if(now.hasClass('bg-blue')){
                now.removeClass('bg-blue').addClass('bg-red').html('已选中');
            }else{
                now.removeClass('bg-red').addClass('bg-blue').html('选中练习册');
            }
        });

        //删除
        $(document).on('click','.sort_delete',function(){
           var book_id = $(this).parents('.edit_box').attr('data-id');
           var o = {_token:token,book_id:book_id};
           $.ajax({
               type:'post',
               dataType:'json',
               url:'{{ route('delete_this_book') }}',
               data:o,
               success:function (s) {
                   if(s.status==1){
                       $('.edit_box[data-id="'+book_id+'"]').remove();
                   }
               }
           })
        });

        //全选
        $(document).on('click','.sort_all_select,.sort_invert_select',function () {
            //var press_id = $(this).parents('.edit_box').attr('data-press-id');
            if($(this).hasClass('sort_all_select')){
                $('.for-choose').removeClass('bg-blue').removeClass('bg-red').addClass('bg-red');
                $('.for-choose').html('已选中');
            }else{
                $('.for-choose').each(function (i) {
                    if($(this).hasClass('bg-red')){
                        $(this).removeClass('bg-red').addClass('bg-blue');
                        $(this).html('选中练习册');
                    }else{
                        $(this).removeClass('bg-blue').addClass('bg-red');
                        $(this).html('已选中');
                    }
                });
            }

        });



        //取消选中
        $(document).on('click','.cancel_all_select',function () {
           $('.for-choose').removeClass('bg-red').addClass('bg-blue');
           $('#sync-sort ul li').remove();
           $('#sync-sort-name ul li').remove();
           $('#sync-press ul li').remove();
        });

        //获取练习册封面
        $(document).on('click','.change_cover_btn',function () {
            var book_id = $(this).attr('data-id');
            var o = {
                _token:token,
                book_id:book_id,
            };
            $('#change_cover .modal-body').html('');
            $('#change_cover .modal-body').attr('data-book-id','');
            $.ajax({
                type:'post',
                dataType:'json',
                url:'{{ route('get_workbook_cover') }}',
                data:o,
                success:function (s) {
                    if(s.status==1){
                        var now_pic_box = '';
                        var data_len = s.data.length;
                        for(var i=0;i<data_len;i++){
                            now_pic_box += '<a class="thumbnail for-change-cover"><img src="'+s.data[i]['img']+'"></a>';
                        }
                        $('#change_cover .modal-body').attr('data-book-id',book_id);
                        $('#change_cover .modal-body').html(now_pic_box);

                    }else{
                        $('#change_cover .modal-body').html('<p>暂无可替换封面</p>');
                    }
                }
            })
        });
        //更新图片
        $(document).on('click','#change_cover img',function () {
            var img = $(this).attr('src');
            var book_id = $(this).parents('.modal-body').attr('data-book-id');
            var o = {
                id:book_id,
                cover_photo_thumbnail:img,
                _token:token
            };
            $.ajax({
                type: 'post',
                url:'{{ route('workbook_update') }}',
                data:o,
                dataType:'json',
                success:function (s) {
                    if(s.status==1){
                        $('.edit_box[data-id="'+book_id+'"] .show_cover_photo img').attr('src',img);
                        $('#change_cover').modal('hide');
                    }else{
                        alert('替换失败');
                    }
                }
            })
        });


        //统一sort
        $(document).on('click', '.sort_all_done', function () {
            var now_box = $(this).parents('.edit_box');
            var now_select = now_box.find('select[data-name="sort"]');
            var sort_id = now_select.val();
            var sort_name = now_select.next().find('.select2-selection__rendered').html();
            $('.for-choose.bg-red').each(function (i) {
                var now_book_id = $(this).attr('data-id');
                if ($('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="sort"] option[value="' + sort_id + '"]').length == 0) {
                    var option = new Option(sort_name, sort_id);
                    option.selected = true;
                    $('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="sort"]').append(option).trigger("change");
                } else {
                    $('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="sort"]').val(sort_id).trigger("change");
                }
            });
        });
        //统一选中sort_name
        $(document).on('click','.sort_name_all_done',function () {
            var sort_name = $(this).parents('.edit_box').find('input[name="sort_name"]').val();
            $('.for-choose.bg-red').each(function (i) {
                var now_book_id = $(this).attr('data-id');
                $('.edit_box[data-id="'+now_book_id+'"]').find('input[name="sort_name"]').val(sort_name);
            });
        });
        //统一选中出版社
        $(document).on('click','.press_all_done',function () {
            var now_box = $(this).parents('.edit_box');
            var now_select = now_box.find('select[data-name="press_id"]');
            var press_id_now = now_select.val();
            var press_name_now = now_select.next().find('.select2-selection__rendered').html();
            $('.for-choose.bg-red').each(function (i) {
                var now_book_id = $(this).attr('data-id');
                if ($('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="press_id"] option[value="' + press_id_now + '"]').length == 0) {
                    var option = new Option(press_name_now, press_id_now);
                    option.selected = true;
                    $('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="press_id"]').append(option).trigger("change");
                } else {
                    $('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="press_id"]').val(press_id_now).trigger("change");
                }
            });
        });

        //show big photo
        $('.show_cover_photo').click(function () {
			var big_cover=$(this).find('img').attr('big_cover');
            var src_now = big_cover=='http://daan.1010pic.com/'?$(this).find('img').attr('src'):big_cover;
            $('.modal-body').html('<img class="img-responsive" src=' + src_now + '>');
        });

        $('.update_data').change(function () {
            update_info($(this));
        });

        function update_info(info) {
            var tr_now = $(info).parents('.edit_box');
            var id = tr_now.data('id');
            var now_name = $(info).data('name');
            var now_data = $(info).val();
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
        $(document).on('click','.all_done',function () {
            var data_not_alert = $(this).attr('data_not_alert');
            {{--var status_tab = '{{ $status }}';--}}
            var now_box = $(this).parents('.edit_box');
            var id = now_box.data('id');
            var district = now_box.find('input[name="district"]').val();
            var original_name = now_box.find('input[name="original_name"]').val();
            var sort_name = now_box.find('input[name="sort_name"]').val();
            var o = {
                'id': id,
                'original_name': original_name,
                'district':district,
                'sort_name':sort_name,
                '_token': token,
                'o_uid': '{{ Auth::user()->id }}'
            };

            $.ajax({
                type: "POST",
                url: "{{ route('workbook_done') }}",
                data: o,
                success: function (t) {
                    if (t.status == 1) {
//                        if (status_tab == 0) {
//                            $('#now_num').html(parseInt($('#now_num').html()) - 1);
//                            now_this.parents('.edit_box').remove();
//                        } else {
//                            if (data_not_alert != 0) {
//                                alert('更新成功');
//                            }
//                        }
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

        //选中/全部完成
        $(document).on('click','.page_all_done,.page_select_done',function () {
            if (confirm('确认完成编辑')) {
                $('.all_done').attr('data_not_alert', '0');
                if($(this).hasClass('page_select_done')){
                    $('.for-choose.bg-red').each(function (i) {
                        var now_id = $(this).attr('data-id');
                        $('.edit_box[data-id="'+now_id+'"]').find('.all_done').click();
                    });
                }else{
                    $('.all_done').click();
                }
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
                return '<option value="' + repo.id + '">' + repo.name || repo.text + '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo){
                //alert(repo.name || repo.text);
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
                return '<option value="' + repo.id + '">' + repo.name +'_'+repo.id+ '</option>';
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


        //旋转图片
        var step = 0;
        $('.photo_left,.photo_right').click(function () {
            if($(this).hasClass('photo_left')){
                step -= 1;
            }else{
                step += 1;
            }
            $(this).parents('.modal-content').find('img').css({'transform': 'rotate('+step*90+'deg)'});
        });
    });

</script>
@endpush
