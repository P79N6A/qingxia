@extends('layouts.backend')

@section('lxc_now_v2')
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
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="box-body">
                    @foreach(config('workbook.subject') as $k=>$v)
                        <a href="{{ route('lxc_v2',$k) }}" class="btn @if($k==$subject) btn-primary @else btn-default @endif">{{ $v }}</a>
                    @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>onlyname</th>
                                <th>待处理练习册数量</th>
                                <th>操作</th>
                            </tr>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->onlyname }}</td>
                                    <td>
                                        {{ $book->total }}
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" target="_blank" href="{!! route('lxc_edit_v2',[$book->onlyname,1]) !!}" >编辑</a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                        <div class="pull-right">
                            {{ $books->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection