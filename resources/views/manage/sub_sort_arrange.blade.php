@extends('layouts.backend')

@section('book_now')
    active
@endsection

@push('need_css')
<style>
    .thumbnail img{
        height: 170px;
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
        </div>
    </div>
    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">vzy子系列整理情况</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> vzy子系列整理情况</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>sort</th>
                            <th>系列名</th>
                            <th>书本图片</th>
                            <th>封面大字</th>
                            <th>子系列名</th>
                            <th>查看</th>
                        </tr>
                        @if(!empty($sort_about['sort_info']))
                            @foreach($sort_about['sort_info'] as $key => $value)
                                <tr data-sort="{{ $value->id }}">
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @foreach($sort_about['img'][$key] as $key1=>$value1)
                                            <a class="show_cover_photo thumbnail pull-left" data-toggle="modal" data-target="#cover_photo"><img data-big="{{ config('workbook.cover_url').$value1->cover_photo }}" src="{{ config('workbook.cover_url_thumbnail').$value1->cover_photo_thumbnail }}" /></a>
                                        @endforeach
                                    </td>
                                    <td>
                                    <span id="main-word-group">
                                    @if(!empty($value->main_word))
                                            @foreach(explode(',',$value->main_word) as $value1)
                                                <a class="btn btn-xs btn-primary"><strong class="main_word_btn">{{ $value1 }}</strong><i class="fa fa-times del_main_word"></i></a>
                                            @endforeach
                                        @endif
                                    </span>
                                        <div class="input-group" >
                                            <input class="input-xs form-control" value="" placeholder="新增封面大字"/>
                                            <a id="btn-add-main-word" class="btn btn-xs btn-danger input-group-addon">新增</a>
                                        </div>
                                    </td>
                                    <td>
                                    <span id="sub-sort-group">
                                    @if(!empty($value->sub_sort))
                                            @foreach(explode(',',$value->sub_sort) as $value1)
                                                <a class="btn btn-xs btn-primary"><span class="sub_sort_btn">{{ $value1 }}</span><i class="fa fa-times del_sub_sort"></i></a>
                                            @endforeach
                                        @endif
                                    </span>
                                        <div class="input-group">
                                            <input class="input-xs form-control" value="" placeholder="新增子系列"/>
                                            <a id="btn-add-sub-sort" class="btn btn-xs btn-danger input-group-addon">新增</a>
                                        </div>
                                    </td>
                                    <td><a class="btn btn-xs btn-default" target="_blank" href="{{ route('lxc_sort',[$value->id,1,1]) }}">查看</a></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="pull-right">
                        @if(!empty($sort_about['sort_info']))
                            {{ $sort_about['sort_info']->links() }}
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </section>
@endsection

@push('need_js')
<script>
    var token = '{{ csrf_token() }}';
    $(function () {
        //新增大字和子系列
        $('#btn-add-main-word,#btn-add-sub-sort').click(function () {
            var now_word = $(this).prev().val();
            var now_tr = $(this).parents('tr');
            var now_sort = now_tr.attr('data-sort');
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
                                now_tr.find('#main-word-group').append('<a class="btn btn-xs btn-primary"><span class="main_word_btn">'+now_word+'</span><i class="fa fa-times del_main_word"></i></a>');
                            }
                        }else{
                            $('#sub-sort-group .sub_sort_btn').each(function () {
                                if($(this).html()==now_word){
                                    had_word = 1;
                                }
                            });
                            if(had_word==0) {
                                now_tr.find('#sub-sort-group').append('<a class="btn btn-xs btn-primary"><span class="sub_sort_btn ">' + now_word + '</span><i class="fa fa-times del_sub_sort"></i></a>');
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

        //删除大字或子系列
        $(document).on('click','.del_main_word,.del_sub_sort',function () {
            var main_word_btn = $(this).parent();
            var now_word = $(this).prev().html();
            var now_tr = $(this).parents('tr');
            var now_sort = now_tr.attr('data-sort');
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

        $('.show_cover_photo').click(function () {
            var src_big = $(this).find('img').attr('data-big');
            $('.modal-body').html('<img class="img-responsive" src='+src_big+'>');
        });

        //clear the modal data
        $('#answer_photo').on('hidden.bs.modal', function () {
            $(this).removeData('bs.modal');
        });

    });



</script>
@endpush