@extends('layouts.backend')

@section('all_new_index','active')

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册收藏管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 练习册收藏管理</h3></div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>
                                <p>练习册名称</p>
                                <p>
                                <a href="{{ route('new_index_all',['jj',$data['volumes_id'],$data['need_buy']]) }}"><em class="badge @if($data['sort']==='jj')bg-red @endif">家教↓</em></a>
                            <a href="{{ route('new_index_all',['other',$data['volumes_id'],$data['need_buy']]) }}"><em class="badge  @if($data['sort']==='other')bg-red @endif">参考↓</em></a>
                                <a href="{{ route('new_index_all',['baidu',$data['volumes_id'],$data['need_buy']]) }}"><em class="badge  @if($data['sort']==='baidu')bg-red @endif">百度↓</em></a>
                                    <a href="{{ route('new_index_all',[$data['sort'],$data['volumes_id'],$data['need_buy']?0:1]) }}"><em class="badge @if($data['need_buy']==1) bg-red @endif">√需要购买</em></a>
                                </p>
                                <p>
                                <a href="{{ route('new_index_all',[$data['sort'],2,$data['need_buy']]) }}"><em class="badge @if($data['volumes_id']==2) bg-red @endif">下册</em></a>
                                <a href="{{ route('new_index_all',[$data['sort'],1,$data['need_buy']]) }}"><em class="badge @if($data['volumes_id']==1) bg-red @endif">上册</em></a>
                                <a href="{{ route('new_index_all',[$data['sort'],0],$data['need_buy']) }}"><em class="badge @if($data['volumes_id']==0) bg-red @endif">不区分</em></a>
                                </p>
                            <th>相关练习册</th>
                            <th>购买情况</th>
                        </tr>
                        @forelse($data['all_book'] as $key => $book_now)
                            <tr>
                                <td>
                                    @if($data['sort']==='jj' or $data['sort']==='other')
                                    <a href="http://www.1010jiajiao.com/daan/bookid_{{ $book_now->id }}.html" target="_blank">{{ $book_now->bookname }}
                                    <i class="badge bg-red">{{ $book_now->collect_count }}</i>/<i class="badge bg-red">{{ $book_now->concern_num }}</i></a>
                                        <a class="btn btn-success" href="{{ route('new_book_buy_detail',$book_now->sort) }}" target="_blank">查看该系列</a>
                                    @else
                                        <a href="http://www.1010jiajiao.com/daan/bookid_{{ $book_now->book_id }}.html" target="_blank">{{ $book_now->has_main_book->bookname }}
                                            <i class="badge bg-green">{{ $book_now->num }}</i>
                                            <i class="badge bg-red">{{ $book_now->has_main_book->collect_count }}</i>/<i class="badge bg-red">{{ $book_now->has_main_book->concern_num }}</i></a>
                                        <a class="btn btn-success" href="{{ route('new_book_buy_detail',$book_now->has_main_book->sort) }}" target="_blank">查看该系列</a>

                                    @endif
                                </td>
                                <td>
                                    @forelse($data['all_book'][$key]['related_book'] as $related_book)
                                        <p><a href="http://www.1010jiajiao.com/daan/bookid_{{ $related_book->id }}.html" target="_blank">{{ $related_book->bookname }}<i class="badge bg-red">{{ $related_book->collect_count }}</i>/<i class="badge bg-red">{{ $related_book->concern_num }}</i></a></p>
                                    @endforeach
                                </td>
                                <td>
                                    @forelse($data['all_book'][$key]['buy_status'] as $related_book)
                                        <p><a>{{ $related_book->bookname }}</a></p>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    {{ $data['all_book']->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection