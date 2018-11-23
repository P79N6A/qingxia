@extends('layouts.backend')

@section('part_time_index','active')

@push('need_css')
   {{-- <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">--}}
    <link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush


@section('content')

<section class="content-header">
    <h1>我的任务</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
        <li class="active"></li>
    </ol>
</section>
<section class="content">
    <div class="box box-default color-palette-box">
        <div id="rightContent">
        <div class="book_box">
            <div id="box-header" class="box-header">
                <a class="btn btn-primary @if($data['status']==0) active @endif" href="{{ route('part_time_booklist',0) }}">全部</a>
                <a class="btn btn-primary @if($data['status']==1) active @endif" href="{{ route('part_time_booklist',1) }}">未完成</a>
                <a class="btn btn-primary @if($data['status']==2) active @endif" href="{{ route('part_time_booklist',2) }}">已完成</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>bookid</th>
                            <th>onlyid</th>
                            <th>书名</th>
                            <th>兼职老师</th>
                            <th>分配时间</th>
                            @if($data['status']==2)
                            <th>完成时间</th>
                            <th>操作</th>
                            @endif
                        </tr>
                        @foreach($data['list'] as $k=>$v)
                            <tr>
                                <td>{{ $v['bookinfo']['id'] }}</td>
                                <td>{{ $v['onlyid'] }}</td>
                                <td> <a target="_blank" href="{{ route('part_time_workbook',$v['bookinfo']['id']) }}">{{ $v['bookinfo']['bookname'] }}</a></td>
                                <td>{{ $v['part_time_name'] }}</td>
                                <td>{{ $v['created_at'] }}</td>
                                @if($data['status']==2)
                                    <td>{{ $v['done_at'] }}</td>
                                    <td>
                                        @if(empty($v['confirm_at']))
                                            <button class="btn btn-primary lookup" data-id="{{ $v['id'] }}">未查看</button>
                                        @else
                                            <button class="btn btn-primary disabled">已查看</button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
          {{ $data['list']->links() }}
    </div>
    </div>
</section>

@endsection

@push('need_js')
<script>
    $(function(){
        $('lookup').click(function(){
            var btn=$(this);
            var id=$(this).attr('data-id');
            axios.post('{{ route('part_time_confirm') }}',{id}).then(response=>{
                if(response.data.status===1){
                    btn.addClass('disabled').html('已查看');
                }
            })
        })
    })
</script>

@endpush
