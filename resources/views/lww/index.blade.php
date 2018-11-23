@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    .base_info .label{
        padding: 3px;
    }
</style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">05网练习册管理</li>
        </ol>
    </section>
    <section class="content">

        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 05网练习册管理</h3></div>
            <div class="box-body">
                <div class="input-group pull-left" style="width:20%">
                    <select id="grade_sel" class="form-control pull-left" style="width:50%">
                        <option value="0">全部年级</option>
                        @foreach(config('workbook.grade') as $key=>$value)
                            @if($key>0)
                            <option value="{{ $key }}"
                                    @if($key==$data['grade_id']) selected="selected"@endif>{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                    <select id="subject_sel" class="form-control pull-left"  style="width:50%">
                        <option value="0">全部科目</option>
                        @foreach(config('workbook.subject_1010') as $key=>$value)
                            @if($key>0)
                            <option value="{{ $key }}"
                                    @if($key==$data['subject_id']) selected="selected"@endif>{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="input-group pull-left" style="width:80%">
                    <input class="form-control" id="search_word" placeholder="练习册名称" type="text" value="{{ $data['word'] }}" />
                    <a class="input-group-addon btn btn-primary" id="search_book_btn">搜索</a>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th>id</th>
                        <th>类型</th>
                        <th>状态</th>
                        <th>练习册</th>
                        <th>基础信息</th>
                        <th>总页数</th>
                        <th>当前进度</th>
                        <th>创建信息</th>
                        @can('lxc_verify')<th>审核信息</th>@endcan
                        <th>管理</th>
                    </tr>
                    @foreach($data['all_book'] as $key => $value)
                    <tr data-id="{{ $value->id }}" data-uid="{{ $value->uid }}">
                        <td class="text-center"><a class="btn btn-xs btn-danger ">{{ $value->id }}</a></td>
                        <td>
                            @if($value->jiexi==1) <a class="label label-primary">解析</a> @endif
                            @if($value->diandu==1) <a class="label label-primary">点读</a> @endif
                            @if($value->gendu==1) <a class="label label-primary">跟读</a> @endif
                            @if($value->tingxie==1) <a class="label label-primary">听写</a> @endif
                        </td>
                        <td class="text-center">@if($value->status==1) <span class="label label-success">有效</span>@else <span class="label label-default">无效</span> @endif</td>
                        <td><a href="{{ route('lww_add',$value['id'])  }}">{{ $value->bookname }}</a></td>
                        <td style="width:25%">
                            <div class="base_info">
                            <a class="label label-primary">{{ config('workbook.grade')[$value->grade_id] }}</a>
                            <a class="label label-primary">{{ config('workbook.subject_1010')[$value->subject_id] }}</a>
                            <a class="label label-primary">{{ config('workbook.volumes')[$value->volumes_id] }}</a>
                            <a class="label label-primary">{{ $value->version_year }}</a>
                            <a class="label label-primary">{{ $value->version_name }}</a>
                            <a class="label label-primary">{{ $value->sort_name }}</a>
                            <a class="label label-primary">{{ $value->isbn }}</a>
                            </div>
                        </td>
                        <td><a class="label label-primary">{{ $value->max_page }}</a></td>
                        <td><a class="label label-warning">{{ $value->max_page_now }}</a></td>
                        <td>
                            <strong>
                                {{ $value->username }}<br />
                                创建时间:{{ $value->addtime }}<br />
                                @if($value->verify_status==0)
                                    @if($value->uid==Auth::id())
                                        <a class="btn btn-xs btn-primary verify_submit">提交审核</a>
                                    @endif
                                @elseif($value->verify_status==1)
                                    提审时间:{{ $value->verify_submit_time }}<br />
                                @elseif($value->verify_status==2)
                                    提审时间:{{ $value->verify_submit_time }}<br />
                                    开审时间:{{ $value->verify_start_time }}<br />
                                @elseif($value->verify_status==3)
                                    提审时间:{{ $value->verify_submit_time }}<br />
                                    开审时间:{{ $value->verify_start_time }}<br />
                                    完毕时间:{{ $value->verify_end_time }}<br />
                                @endif
                            </strong>
                        </td>
                        @can('lxc_verify')
                        <td>
                            将练习册权限转移给
                            <div class="input-group change_user_box" data-id="{{ $value->id }}" style="width: 100%">
                            <select class="form-control select2" data-name="user_select" style="width: 100%">
                            </select>
                            <a class="input-group-addon btn btn-primary btn-xs confirm_change_user">确认</a>
                            </div>

                            @if($value->verify_status==1)
                            <a class="btn btn-xs btn-primary verify_start">开始审核</a>
                            @elseif($value->verify_status==2)
                                <a class="btn btn-xs btn-danger verify_reject">不通过</a>
                                <a class="btn btn-xs btn-success verify_end">确认无误</a>
                            @elseif($value->verify_status==3)
                                开审时间:{{ $value->verify_start_time }}<br />
                                完毕时间:{{ $value->verify_end_time }}<br />
                            @endif

                        </td>@endcan
                        <td><a class="btn btn-default" target="_blank" href="{{ route('lww_chapter',$value->id) }}">章管理</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div>
                    {{ $data['all_book']->links() }}
                </div>
                <hr>
                <a target="_blank" class="btn btn-danger" href="{{ route('lww_add') }}">创建练习册</a>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    $(function () {

        @can('lxc_verify')
        $('select[data-name="user_select"]').select2({ data: $.parseJSON('{!! $data['all_user'] !!} '),});
        $('.confirm_change_user').click(function () {
           if(confirm('确认更改练习册权限')){
                let now_book_id = $(this).parents('.change_user_box').attr('data-id');
                let now_uid = $(`.change_user_box[data-id="${now_book_id}"] select`).val();
                axios.post('{{ route('lww_change_user') }}',{now_uid,now_book_id}).then(response=>{
                    if(response.data.status===1){
                        alert('更改成功');
                    }
                }).catch(function (error) { console.log(error); });
           }
        });
        @endcan

        $('#search_book_btn').click(function () {
            let grade_id = $('#grade_sel').val();
            let subject_id = $('#subject_sel').val();
            let word = $.trim($('#search_word').val());
            window.location.href='{{ route('lww_index') }}'+'/sid/'+subject_id+'/gid/'+grade_id+'/word/'+word;
        });

        //提交审核
        $(document).on('click','.verify_submit',function () {
            let book_id = $(this).parents('tr').attr('data-id');
            axios.post('{{ route('lww_book_verify') }}',{book_id,type:'verify_submit'}).then(response=>{
                if(response.data.status===1){
                    $(this).before(`提审时间:${response.data.time}<br />`);
                    $(this).remove();
                }else{
                    alert('操作失败');
                }
            }).catch(function (error) {
                console.log(error);
            });
        });

        //开始审核
        $(document).on('click','.verify_start',function () {
            let book_id = $(this).parents('tr').attr('data-id');
            let uid = $(this).parents('tr').attr('data-uid');
            axios.post('{{ route('lww_book_verify') }}',{uid,book_id,type:'verify_start'}).then(response=>{
                if(response.data.status===1){
                    $(this).before(`
                    <a class="btn btn-xs btn-danger verify_reject">不通过</a>
                    <a class="btn btn-xs btn-success verify_end">确认无误</a>
                    `);
                    $(this).remove();
                }else{
                    alert('操作失败');
                }
            }).catch(function (error) {
                console.log(error);
            });
        });

        //不通过
        $(document).on('click','.verify_reject',function () {
            let book_id = $(this).parents('tr').attr('data-id');
            let uid = $(this).parents('tr').attr('data-uid');
            axios.post('{{ route('lww_book_verify') }}',{uid,book_id,type:'verify_reject'}).then(response=>{
                if(response.data.status===1){
                    $(this).parent().html('');
                }else{
                    alert('操作失败');
                }
            }).catch(function (error) {
                console.log(error);
            });
        });

        //结束审核
        $(document).on('click','.verify_end',function () {
            let book_id = $(this).parents('tr').attr('data-id');
            let uid = $(this).parents('tr').attr('data-uid');
            axios.post('{{ route('lww_book_verify') }}',{uid,book_id,type:'verify_end'}).then(response=>{
                if(response.data.status===1){
                    let td = $(this).parent();
                    td.html(`开审时间:${response.data.time}<br />确认时间:${response.data.time}<br />`);
                }else{
                    alert('操作失败');
                }
            }).catch(function (error) {
                console.log(error);
            });
        });
    })
</script>

@endpush