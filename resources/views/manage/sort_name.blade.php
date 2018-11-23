@extends('layouts.backend')

@section('sort_name')
    active
@endsection


@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">sort_name整理</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> sort_name整理</h3>
                <a href="{{ route('sort_name_v2') }}" class="btn btn-primary pull-right">sort_name遗漏整理</a>
            </div>
            <div class="box-body">
            <ul class="nav nav-pills nav-stacked">
                @foreach($data['all_sort'] as $value)
                <li>
                    <span href="#">
                        <a target="_blank" href="{{ route('sort_name_all',[$value->id_now,1,0,1]) }}" class="btn btn-info">
                            {{ $value->name }}
                        </a>
                        <br>
                        @if(isset($data['final_sort_array'][$value->id_now]))
                        @foreach($data['final_sort_array'][$value->id_now] as $value1)
                            <a class="btn btn-xs btn-primary" target="_blank" href="{{ route('sort_name_detail',[$value->id_now,$value1['name'],0,1]) }}">{{ $value1['name'] }}({{ $value1['count'] }})</a>
                        @endforeach
                        @endif
                        <span class="label label-danger pull-right">{{ $value->total }}</span></span>
                </li>
                @endforeach
            </ul>
            <div>
                {{ $data['all_sort']->links() }}
            </div>

        </div>
        </div>
    </section>
@endsection

