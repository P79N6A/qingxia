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

</style>
@endpush

@section('lxc_now')
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
            <strong>{{ $onlyname }}</strong>
            <p>当前系列专版信息：</p>
            @if($edits->total()>0)
            <p>{{ $edits[0]->sort_note }}</p>
            @endif
        </div>
        <div class="box-body table-responsive">
            <ul class="nav nav-tabs">
                <li role="presentation" @if($status==0) class="active" @endif>
                    <a href="{{ route('lxc_edit',[$onlyname]) }}">
                        <span>未整理</span>
                        @if($status==0)
                        <span id="now_num" class="label label-danger ">{{ $edits->total() }}</span>
                        @endif
                    </a>
                </li>
                <li role="presentation" @if($status==1) class="active" @endif>
                    <a href="{{ route('lxc_edit',[$onlyname,1]) }}">
                        <span>已整理</span>
                        @if($status==1)
                        <span id="now_num" class="label label-danger">{{ $edits->total() }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            <div class="row">
                @if($edits->total()>0)
                <div class="col-md-12">
                    <table id="example1" class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>书本图片</th>
                            <th>信息编辑</th>
                            <th>版次</th>
                            <th>参考答案</th>
                            <th>操作</th>
                        </tr>
                        @foreach($edits as $edit)
                        <tr data-id="{{ $edit->id }}">
                            <td style="width: 15%;">

                                <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                    <img class="img-responsive" src="{{ config('workbook.cover_url').$edit->cover_photo }}">
                                </a>

                            </td>
                            <td>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-addon bg-gray">
                                            原书名
                                        </div>
                                        <input name="original_name" class="form-control" value="{{ $edit->name }}" />
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-addon bg-gray">
                                            拼接名
                                        </div>
                                        <div class="new_book_name form-control">
                                            <a class="new_sort_name btn btn-primary btn-xs">{{ $edit->sort_name }}</a>
                                            <a class="new_grade btn btn-primary btn-xs">{{ config('workbook.grade')[$edit->grade_id] }}</a>
                                            <a class="new_subject btn btn-primary btn-xs">{{ config('workbook.subject')[$edit->subject_id] }}</a>
                                            <a class="new_volumes btn btn-primary btn-xs">{{ config('workbook.volumes')[$edit->volumes] }}</a>
                                            <a class="new_book_version btn btn-primary btn-xs">{{ $version[$edit->book_version_id]->name }}</a>
                                            @if($edit->version)
                                            <a class="new_version_year btn btn-primary btn-xs">{{ $edit->version }}</a>
                                            @endif
                                            @if($edit->special_info)
                                            <a class="new_special_info btn btn-primary btn-xs">{{ $edit->special_info }}</a>
                                            @endif
                                            @if($edit->special_info_2)
                                            <a class="new_special_info btn btn-primary btn-xs">{{ $edit->special_info_2}}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
		                    <div class="form-group col-md-4">
                                        <input class="form-control" disabled="disabled" name="sort" value="{{ $edit->sort_name }}" />
                                    </div>
                                <div class="form-group col-md-2">
                                    <select data-name="grade_id" class="update_data form-control select2" style="width: 100%;"
                                            tabindex="-1" aria-hidden="true">
@foreach(config('workbook.grade') as $key=>$value)
@if($edit->grade_id==$key)
                                        @php $select='selected=selected'; @endphp
                                        @else
                                        @php $select = ''; @endphp
@endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <select data-name="subject_id" class="update_data form-control select2" style="width: 100%;"
                                            tabindex="-1" aria-hidden="true">
@foreach(config('workbook.subject') as $key=>$value)
@if($edit->subject_id==$key)
                                        @php $select='selected=selected'; @endphp
                                        @else
                                        @php $select = ''; @endphp
@endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <select data-name="volumes" class="update_data form-control select2">
                                        @foreach(config('workbook.volumes') as $key=>$value)
                                        @if($edit->volumes==$key)
                                        @php $select='selected=selected'; @endphp
                                        @else
                                        @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $key }}">{{ $value }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <select data-name="book_version_id" class="update_data form-control select2" style="width: 100%;"
                                            tabindex="-1" aria-hidden="true">
                                        @foreach($version as $value)
                                        @if($edit->book_version_id==$value->id)
                                        @php $select='selected=selected'; @endphp
                                        @else
                                        @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $value->id }}">{{ $value->name }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <input name="special_info" class="form-control" placeholder="答案不同专版信息" value="{{ $edit->special_info }}"/>
                                </div>
                                <div class="form-group col-md-3">
                                    <input name="special_info_2" class="form-control" placeholder="答案相似专版信息" value="{{ $edit->special_info_2 }}"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <input disabled="disabled" class="form-control" name="press" value="{{ $edit->press_name }}" />

                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select data-name="version" class="update_data form-control select2" style="width: 100%;"
                                            tabindex="-1" aria-hidden="true">
                                        <option selected="selected"
                                                value="{{ $edit->version }}">{{ $edit->version }}</option>
                                        @foreach(config('workbook.book_version') as $key=>$value)
                                        @if($edit->version==$key)
                                        @php $select='selected=selected'; @endphp
                                        @else
                                        @php $select = ''; @endphp
                                        @endif<option {{$select}} value="{{ $value }}">{{ $value }}</option>@endforeach
                                    </select>
                                </div>
                            </td>
                            <td><a class="btn btn-primary btn-xs btn-block" target="_blank" data-target="#answer_photo" data-toggle="modal" href="{{ route('lxc_answer',$edit->id) }}">查看答案</a>
                            </td>
                            <td>
                                <a class="btn btn-success btn-xs btn-block all_done">完成编辑</a>
                                <a class="btn btn-danger btn-xs btn-block page_all_done">全部完成编辑</a>
                                @if($status==1)
                                <a>
                                    <strong>{{ \App\User::Where('id',$edit->o_uid)->select('name')->get('name')[0]['name'] }}</strong>
                                    <p>编辑于：</p>
                                    <strong>{{ $edit->updated_at }}</strong>
                                </a>
                                @endif
                                {{--<a class="btn btn-success btn-xs change_info" data-id="{{ $edit->id }}">更改</a>--}}
                                {{--<a class="btn btn-xs btn-danger">删除</a>--}}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>


                    <div class="pull-right">
                        {{ $edits->links() }}
                    </div>
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
<script src="/adminlte/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/adminlte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script>
    var token = '{{ csrf_token() }}';
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //show big photo
        $('.show_cover_photo').click(function () {
            $('.modal-body').html($(this).html());
        });		
        //for_sorts
        $('.sorts').click(function () {
            if ($(this).find('option').length == 1) {
                var sort_length = $('#for_sorts').children().length;
                var sort_clone = $('#for_sorts').clone(true);
                for (var i = 0; i < sort_length; i++) {
                    $(this).children('.sorts_option').append(sort_clone.children(i));
                }
            }
        });
		
		

        $('.update_data').change(function () {
            update_info($(this));
        });

        function update_info(info) {
            var tr_now = $(info).parents('tr');
            var id = tr_now.data('id');
            var now_name = $(info).data('name');
            var now_data = $(info).val();
            var select_name = $(info).find('option:selected').text();
            if(now_name=='sort'){
                $(tr_now).find('.new_sort_name').html(select_name);
            }else if(now_name=='grade_id'){
                $(tr_now).find('.new_grade').html(select_name);
            }else if(now_name=='subject_id'){
                $(tr_now).find('.new_subject').html(select_name);
            }else if(now_name=='volumes'){
                $(tr_now).find('.new_volumes').html(select_name);
            }else if(now_name=='book_version_id'){
                $(tr_now).find('.new_book_version').html(select_name);
            }else if(now_name=='version'){
                $(tr_now).find('.new_version_year').html(select_name);
            }

            var post_data = {
                'id':id,
                '_token':token,
                'o_uid':'{{ $user->id }}'
            };
            post_data[now_name] = now_data;
            $.ajax({
                type: "POST",
                url: "{{ route('lxc_update') }}",
                data: post_data,
                success: function (t) {

                },
                error: function (t) {
                    var errors = t.responseJSON;
                    var errorsHtml = '<div class="alert alert-danger">' +
                        '<span class="close" data-dismiss="alert">&times;</span>' +
                        '<ul>';
                    $.each( errors , function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html( errorsHtml );
                },
                dataType: "json"
            })
        }

        $('.all_done').click(function () {
            var data_not_alert = $(this).attr('data_not_alert');
            var status_tab = '{{ $status }}';
            var now_this = $(this);
            var id = now_this.parents('tr').data('id');

            var special_info = $(this).parents('tr').find('input[name="special_info"]').val();
            var special_info_2= $(this).parents('tr').find('input[name="special_info_2"]').val();
            var original_name = $(this).parents('tr').find('input[name="original_name"]').val();
            var o = {
                'id':id,
                'original_name':original_name,
                'special_info':special_info,
                'special_info_2':special_info_2,
                '_token':token,
                'o_uid':'{{ $user->id }}'
            };
            $.ajax({
                type: "POST",
                url: "{{ route('lxc_done') }}",
                data: o,
                success: function (t) {
                    if(t.status==1){
                        if(status_tab==0){
                            $('#now_num').html(parseInt($('#now_num').html())-1);
                            now_this.parents('tr').remove();
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

        $('.page_all_done').click(function () {
            if(confirm('确认全部完成编辑')){
                $('.all_done').attr('data_not_alert','0');
                $('.all_done').click();
            }
        });

        //clear the modal data
        $('#answer_photo').on('hidden.bs.modal', function () {
            $(this).removeData('bs.modal');
        })
    });



</script>
@endpush
