@extends('layouts.backend')

@section('lww_index','active')
@section('content')

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active"></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <!-- 右侧具体内容栏目 -->
            <div id="rightContent">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">系列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th style="width: 10px">id</th>
                                <th>系列名</th>
                                <th>书本数量<a href="{{ route('one_lww_index',[$data['district'],'numresult','asc']) }}">↑↑&nbsp;</a><a href="{{ route('one_lww_index',[$data['district'],'numresult','desc']) }}">↓↓</a></th>
                                <th>搜索量<a href="{{ route('one_lww_index',[$data['district'],'searchnum','asc']) }}">↑↑&nbsp;</a><a href="{{ route('one_lww_index',[$data['district'],'searchnum','desc']) }}">↓↓</a></th>
                                <th>访问量<a href="{{ route('one_lww_index',[$data['district'],'visit','asc']) }}">↑↑&nbsp;</a><a href="{{ route('one_lww_index',[$data['district'],'visit','desc']) }}">↓↓</a></th>
                                <th>省份<div class="input-group"><input class="form-control" value="{{ $data['district'] }}"/><a class="input-group-addon add_city btn btn-primary">搜索</a></div></th>
                                <th>搜索权重<a href="{{ route('one_lww_index',[$data['district'],'searchrate','asc']) }}">↑↑&nbsp;</a><a href="{{ route('one_lww_index',[$data['district'],'searchrate','desc']) }}">↓↓</a></th>
                                <th>管理</th>
                            </tr>
                            @foreach($data['list'] as $v)
            <tr data-id="{{ $v->id }}" data-name="{{ $v->name }}">
                <td>{{ $v->id }}</td>
                <td>{{ $v->sort_name }}</td>
                <td>{{ $v->numresult }}</td>
                <td>{{ $v->searchnum }}</td>
                <td>{{ $v->visit }}</td>
                <td style="width: 40%">{{ $v->province }}</td>
                <td>{{ $v->searchrate }}</td>
                <td>
                    <a type="button" class="btn btn-primary" href="{{ route('one_lww_booklist',[-1,-1,$v->sort,-1,-1,-1])  }}">查看</a>
                    {{--<button type="button" class="btn btn-success change_sort" data-toggle="modal" data-target="#myModal">修改</button>--}}
                    {{--<button type="button" class="btn btn-danger del_sort">删除</button>--}}
                </td>
            </tr>
           @endforeach
            </tbody>
            </table>
            </div>
            <!-- /.box-body -->
            </div>
            {{ $data['list']->links() }}



            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="form-control new_name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary update_name">确定</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
@endsection

@push('need_js')
<script>
    $(function(){
        $('#add_sort').click(function(){
            let sort_name=$('#sort_name').val();
            axios.post('{{ route('one_lww_ajax','add_sort') }}',{sort_name}).then(response=>{
                window.location.reload();
            })
        });

        $('.del_sort').click(function(){
            if(!confirm('确定要删除此系列？')){
                return false;
            }
            let id=$(this).parents('tr').attr('data-id');
            axios.post('{{ route('one_lww_ajax','del_sort') }}',{id}).then(response=>{
                window.location.reload();
            });
        });

        $('.change_sort').click(function(){
            let tr=$(this).parents('tr');
            let id=tr.attr('data-id');
            let sort_name=tr.attr('data-name');
            $('#myModal').attr('data-id',id);
            $('#myModal').find('.new_name').val(sort_name);
        });

        $('#myModal .update_name').click(function(){
            let id=$('#myModal').attr('data-id');
            let sort_name=$('#myModal').find('.new_name').val();
            axios.post('{{ route('one_lww_ajax','update_sort') }}',{id,sort_name}).then(response=>{
                window.location.reload();
            });
        })

        $('.add_city').click(function () {
            let city = $(this).prev().val();
            if(city=='') {
                window.location.href = '{{ route('one_lww_index') }}'
                    }else{
                window.location.href = '{{ route('one_lww_index') }}/' + city + '/{{ $data['order'] }}/{{ $data['asc'] }}'
            }
        })
    })
</script>
@endpush
