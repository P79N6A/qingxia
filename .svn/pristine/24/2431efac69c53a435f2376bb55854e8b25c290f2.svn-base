@extends('layouts.backend')

@section('book_new_only')
    active
@endsection

@section('content')
    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册整理</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 练习册整理</h3>
                <a href="{{ strstr(url()->full(),'page')===false?url()->full().'?flush=1':url()->full().'&flush=1' }}" class="btn btn-danger pull-right">强制刷新</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="box-body">
                        @foreach(config('workbook.subject_1010') as $k=>$v)
                            <a href="{{ route('book_new_only',$k) }}" class="btn @if($k==$data['subject']) btn-primary @else btn-default @endif">{{ $v }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>系列/子系列/年级/科目/卷册/版本</th>
                                <th>练习册数量</th>
                                <th>操作</th>
                            </tr>
                            @foreach($data['books'] as $book)
                                <tr>
                                    <td>
                                        <a class="label label-success" data-id="{{ $book->id }}">{{ $book->has_sub_sort->pname }}</a>
                                        <a class="label label-info" data-id="{{ $book->id }}">{{ $book->has_sub_sort->name }}</a>
                                        <a class="label label-info">{{ config('workbook.grade')[$book->grade_id] }}</a>
                                        <a class="label label-info">{{ config('workbook.subject_1010')[$book->subject_id] }}</a>
                                        <a class="label label-info">{{ config('workbook.volumes')[$book->volumes_id] }}</a>
                                        <a class="label label-info">{{ $book->has_version->name }}</a>
                                    </td>
                                    <td>
                                        {{ $book->num }}
                                    </td>
                                    <td>
                                       <a class="btn btn-primary btn-xs" target="_blank" href="{!! route('book_new_only_detail',[$book->sort,$book->ssort_id,$book->grade_id,$book->subject_id,$book->volumes_id,$book->version_id,]) !!}">编辑</a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                        <div class="pull-right">
                            {{ $data['books']->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection