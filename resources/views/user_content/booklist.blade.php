@extends('layouts.backend')

@section('audit_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
    <style>
        .panel-body img{
            height: 400px;
        }
    </style>
@endpush



@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">用户上传答案审核</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                用户上传答案列表
                <a type="button" class="btn btn-primary @if($data['type']==0) active @endif" href="{{ route('audit_content_booklist',[0]) }}">未审核</a>
                <a type="button" class="btn btn-primary @if($data['type']==1) active @endif" href="{{ route('audit_content_booklist',[1]) }}">已审核</a>
            </div>
            <div class="box-body no-padding">
                <table class="table table-condensed">
                    <tbody>
                    <tr>
                        <th style="width: 10px">bookid</th>
                        <th>书名</th>
                        <th>Isbn</th>
                        <th>时间</th>
                        @if($data['type']==1)
                            <th>处理人</th>
                        @endif
                        <th>操作</th>
                    </tr>
                    @foreach($data['list'] as $v)
                    <tr>
                        <td>{{ $v->book_id }}</td>
                        <td>{{ $v->bookname }}</td>
                        <td>{{ $v->isbn }}</td>
                        <td>{{ $v->addtime }}</td>
                        @if($data['type']==1)
                        <td>{{ $v->has_user->name }}</td>
                        @endif
                        <td><a class="btn btn-primary" href="{{ route('user_content_audit',[$v->book_id,$data['type'],$data['page']]) }}">查看</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $data['list']->links() }}
    </section>
@endsection

