@extends('layouts.backend')


@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush



@section('content')
    <section class="content-header">
        <h1>买书</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">买书首页</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">买书统计</div>
            <div class="box-body">
                <div class="nav nav-pills">
                    @foreach($lists as $item)
                        <a class="btn btn-app" target="_blank" href="{{ route('tao_cartList',$item->uid) }}">
                            <span class="badge bg-aqua">{{ $item->num }}</span>
                            <i class="fa fa-edit"></i> {{ $item->username }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection