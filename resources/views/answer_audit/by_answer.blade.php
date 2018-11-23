@extends('layouts.backend')

@section('audit_answer','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">答案审核</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">已通过求助列表</div>
            <div class="box-body">
                <div class="nav nav-pills">
                    @foreach($data['now_book'] as $answer)
                        <a class="btn btn-app" target="_blank" href="{{ route('audit_answer_detail',$answer->to_book_id) }}">
                            <span class="badge bg-aqua">{{ $answer->num }}</span>
                            <i class="fa fa-edit"></i> {{ $answer->has_offical_book?$answer->has_offical_book->bookname:'' }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection