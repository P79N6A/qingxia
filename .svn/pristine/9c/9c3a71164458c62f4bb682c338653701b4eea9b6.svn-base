@extends('layouts.backend')

@section('manage_new_local_answer','active')

@push('need_css')
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">本地答案整理</li>
        </ol>
    </section>
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
        </div>

        <div class="box-body">
            <ul class="list-group">
                @forelse($data['all_dict'] as $dict)
                    <li class="list-group-item"><a target="_blank" href="{{ route('manage_new_local_list',$dict) }}">{{ $dict }}</a><i class="badge bg-red">
                            @if($loop->index%5===0)
                                苏蕾
                            @elseif($loop->index%5===1)
                                张连荣
                            @elseif($loop->index%5===2)
                                肖高萍
                            @elseif($loop->index%5===3)
                                印娜
                            @elseif($loop->index%5===4)
                                张玲莉
                            @endif
                        </i></li>
                @endforeach
            </ul>

        </div>
    </div>
@endsection