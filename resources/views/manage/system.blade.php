@extends('layouts.backend')

@section('system_manage')
    active
@endsection

@push('need_css')
<link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <div class="modal fade" id="add-new">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="pull-left"></p>
                    <span class="close pull-right" data-dismiss="modal">&times;</span>
                </div>
                <div class="modal-body">
                    <input id="for_name" class="form-control" value="" placeholder="name(英文标识)"/>
                    <input id="for_label"  class="form-control" value="" placeholder="名称"/>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary confirm-add">确认</a>
                    <a class="btn btn-default" data-dismiss="modal">取消</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">权限管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 权限管理</h3><a class="btn btn-danger pull-right hide">新增用户</a></div>

            <div class="box-body">

                    <div class="box all-role-box">
                        <div class="box-header">
                            <h3>角色分配</h3>
                            <span id="role-box">
                            @foreach($data['all_roles'] as $value)
                                <a class="btn btn-default role-btn" data-id="{{ $value->id }}"  data-name="{{ $value->name }}">{{ $value->label }}</a>
                            @endforeach
                            </span>
                            <a class="btn btn-success pull-right" id="add-role" data-target="#add-new" data-toggle="modal">新增角色</a></div>
                        <div class="box-body">
                            <table class="table table-bordered pull-left">
                                <tr>
                                    <th style="width:20%">用户名</th>
                                    <th>角色</th>
                                </tr>
                                @foreach($data['all_user'] as $value)
                                <tr>
                                    <td class="primary_id" data-uid="{{ $value->id }}">{{ $value->name }}</td>
                                    <td class="has-role-box">
                                        @foreach($data['role_about'][$value->id] as $roles)
                                            <a class="btn btn-xs btn-primary role-now-btn" data-id="{{ $roles->id }}" data-name="{{ $roles->name }}" data-type="role"><strong class="role_about">{{ $roles->label }}</strong><i class="fa fa-times del_this"></i></a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>


                    <div class="box all-permission-box">
                        <div class="box-header">
                            <h3>权限分配</h3>
                            <span id="permission-box">
                            @foreach($data['all_permissions'] as $value)
                                <a class="btn btn-xs btn-default permission-btn" data-id="{{ $value->id }}" data-name="{{ $value->name }}">{{ $value->label }}</a>
                            @endforeach
                            </span>
                            <a class="btn btn-success pull-right" id="add-permission" data-target="#add-new" data-toggle="modal">新增权限</a>

                        </div>
                        <div class="box-body">
                            <table class="table table-bordered pull-left">
                                <tr>
                                    <th style="width:20%">角色</th>
                                    <th>权限</th>
                                </tr>
                                @foreach($data['all_roles'] as $value)
                                    <tr>
                                        <td class="primary_id" data-rid="{{ $value->id }}">{{ $value->name }}({{ $value->label }})</td>
                                        <td class="has-permission-box">
                                            @foreach($data['permission_about'][$value->id] as $permissions)
                                                <a class="btn btn-xs btn-primary permission-now-btn" data-id="{{ $permissions->id }}" data-name="{{ $permissions->name }}" data-type="permission"><strong class="role_about">{{ $permissions->label }}</strong><i class="fa fa-times del_this"></i></a>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    var token = '{{ csrf_token() }}';
    $(function () {
        //新增角色或权限
        $('#add-role').click(function () {
            $('#add-new .modal-header p').html('新增角色');
            $('#add-new .confirm-add').attr('data-type','add_role');
        });
        $('#add-permission').click(function () {
            $('#add-new .modal-header p').html('新增权限');
            $('#add-new .confirm-add').attr('data-type','add_permission');
        });

        $('.confirm-add').click(function () {
            var type = $(this).attr('data-type');
            var name_now = $('#for_name').val();
            var label_now = $('#for_label').val();
            var o = {
                _token: token,
                name: name_now,
                type: type,
                label: label_now
            };

            $.ajax({
                type: 'post',
                data: o,
                url: '{{ route('add_role_permission') }}',
                success: function (s) {
                    if (s.status == 1) {
                        $('#add-new').modal('hide');
                        $('#for_name,#for_label').val('');
                        if (type == 'add_role') {
                            $('#role-box').append('<a class="btn btn-default role-btn ui-draggable ui-draggable-handle" data-name="' + name_now + '">' + label_now + '</a>')
                        } else {
                            $('#permission-box').append('<a class="btn btn-xs btn-default role-btn ui-draggable ui-draggable-handle" data-name="' + name_now + '">' + label_now + '</a>')
                        }
                    }
                },
                error: function () {

                },
                dataType: 'json'
            });
        });

        //拖动分配角色
        $('.all-role-box .role-btn').draggable({
            containment:'.all-role-box',
            revert: true,
            helper: "clone",
            stop: function(event, ui) {
                console.log(event);
                console.log(ui);
            }
        });

        $('.all-role-box .has-role-box').droppable({drop: function(e, ui) {
            var role_label = ui.draggable.html();
            var role_name = ui.draggable.attr('data-name');
            var role_id = ui.draggable.attr('data-id');
            var uid = $(this).prev().attr('data-uid');
            if($(this).find('a[data-name="'+role_name+'"]').length==0){
                var now_role_box = $(this);
                var o = {
                    _token:token,
                    role_name:role_name,
                    type:'role',
                    did:uid
                };
                $.ajax({
                    type:'post',
                    data:o,
                    url:'{{ route('grant_role_permission') }}',
                    success:function (s) {
                        if(s.status==1){
                            now_role_box.append('<a class="btn btn-xs btn-primary role-now-btn" data-type="role" data-id="'+role_id+'" data-name="'+role_name+'"><strong class="role_about">'+role_label+'</strong><i class="fa fa-times del_this"></i></a>');
                        }

                    },
                    error:function () {

                    },
                    dataType:'json'
                })
            }


        }});

        //拖动分配权限
        $('.all-permission-box .permission-btn').draggable({
            containment:'.all-permission-box',
            revert: true,
            helper: "clone",
            stop: function(event, ui) {
                console.log(event);
                console.log(ui);
            }
        });

        $('.all-permission-box .has-permission-box').droppable({drop: function(e, ui) {
            var permission_label = ui.draggable.html();
            var permission_name = ui.draggable.attr('data-name');
            var permission_id = ui.draggable.attr('data-id');
            var rid = $(this).prev().attr('data-rid');
            if($(this).find('a[data-name="'+permission_name+'"]').length==0) {
                if($(this).find('a[data-name="'+permission_name+'"]').length==0){
                    var now_permission_box = $(this);
                    var o = {
                        _token:token,
                        role_name:permission_name,
                        type:'permission',
                        did:rid
                    };
                    $.ajax({
                        type:'post',
                        data:o,
                        url:'{{ route('grant_role_permission') }}',
                        success:function (s) {
                            if(s.status==1){
                                now_permission_box.append('<a class="btn btn-xs btn-primary permission-now-btn" data-type="permission" data-id="'+permission_id+'" data-name="' + permission_name + '"><strong class="role_about">' + permission_label + '</strong><i class="fa fa-times del_this"></i></a>')
                            }
                        },
                        error:function () {

                        },
                        dataType:'json'
                    })
                }
            }
        }});

        //删除角色或权限
        $(document).on('click','.del_this',function () {
            var now_parent = $(this).parent();
            var type_now = now_parent.attr('data-type');
            console.log(type_now);
            var name_now = now_parent.attr('data-name');
            if(type_now=='role'){
                var primary_id = now_parent.parents('tr').find('td.primary_id').attr('data-uid')
            }else{
                var primary_id = now_parent.parents('tr').find('td.primary_id').attr('data-rid')
            }
            var now_id = now_parent.attr('data-id');
            var o = {
                _token : token,
                type:type_now,
                primary_id:primary_id,
                now_id:now_id
            };
            console.log(o);

            $.ajax({
                type:'post',
                data:o,
                url:'{{ route('del_role_permission') }}',
                success:function (s) {
                    if(s.status==1){
                        now_parent.remove();
                    }
                },
            });
        })
    });
</script>
@endpush