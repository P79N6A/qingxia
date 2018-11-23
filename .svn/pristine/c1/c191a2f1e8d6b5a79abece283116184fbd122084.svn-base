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
                <ul class="media" style="list-style: none;">
                    @foreach($data['list'] as $k=>$v)
                        <li style="width: 150px;height:260px;float: left;margin-left: 30px;border: 1px solid #e0e0e0;">
                            <div class="media-body">
                                <a target="_blank" href="{{ route('part_time_workbook',$v['bookinfo']['id']) }}" class="ad-click-event" >
                                    <img src="{{ $v['bookinfo']['cover'] }}"  class="media-object" style="margin: 5px auto;max-width: 130px;display: block;">
                                    <div class="bookname" style="text-align: center;display: block;width: 150px;font-size:10px;">{{ $v['bookinfo']['bookname'] }}</div>
                                </a>
                             </div>
                         </li>
                     @endforeach
                </ul>
            </div>
        </div>
          {{ $data['list']->links() }}
    </div>
    </div>
</section>

@endsection


