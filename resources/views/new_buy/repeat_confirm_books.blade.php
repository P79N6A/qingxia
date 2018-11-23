@extends('layouts.backend')

@section('all_repeat_book','active')

@push('need_css')

@endpush

@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">重复练习册查看</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>onlyname</th>
                        <th>相关练习册</th>
                    </tr>
                    @forelse($data['all_repeat'] as $key => $repeat)
                        <tr>
                            <td>{{ $repeat->newname }}</td>
                            <td>
                                <div class="col-md-9">
                                @forelse($data['all_repeat_books'][$key] as $book)
                                    <p>
                                        id:<a href="{{ 'http://www.1010jiajiao.com/daan/bookid_'.$book->id.'.html' }}" target="_blank">{{ $book->id }}</a>
                                        @if(strpos($book->cover,'/pic19/') && !strpos($book->cover,'/new/'))
                                            <a class="label label-danger">jiajiao</a>
                                        @else
                                            <a class="label label-info">其它</a>
                                        @endif
                                        <a href="{{ route('audit_answer_detail',$book->id) }}" target="_blank">{{ $book->bookname }}<em class="badge bg-red">{{ $book->collect_count }}</em></a><a class="btn btn-xs btn-default">答案页数：{{ count($book->hasAnswers) }}</a></p>

                                @endforeach
                                </div>
                                <div class="col-md-3">
                                        <a href="{{ route('new_buy_repeat_detail',[$repeat->newname]) }}" target="_blank" class="btn btn-primary btn-block">查看</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div>
                    {{ $data['all_repeat']->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection