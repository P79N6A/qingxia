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
            <div class="btn btn-group">
                <a id="show_zero_book" class="btn btn-primary">隐藏/取消隐藏无答案</a>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>课本</th>
                        <th>相关练习册</th>
                    </tr>
                    @forelse($data['all_repeat'] as $key => $repeat)
                        <tr class="now_repeat_tr">
                            <td>{{ config('workbook')['grade'][$repeat->grade_id].config('workbook')['subject_1010'][$repeat->subject_id].config('workbook')['volumes'][$repeat->volumes_id].cache('all_version_now')->where('id',$repeat->version_id)->first()->name }}</td>
                            <td>
                                <div class="col-md-9 all_repeat_book">
                                @forelse($data['all_repeat_books'][$key] as $key1=>$book)
                                    <p class="single_book" data-confirm="{{ $book->book_confirm }}" data-count="{{ count($book->hasAnswers) }}">
                                        @if($book->book_confirm===1)
                                            <a class="label label-danger">book_confirm</a>
                                        @else
                                            <a class="label label-info">not book_confirm</a>
                                        @endif
                                        id:<a href="{{ 'http://www.1010jiajiao.com/daan/bookid_'.$book->id.'.html' }}" target="_blank">{{ $book->id }}</a>
                                        &nbsp;&nbsp;&nbsp;isbn:{{ $book->isbn }}
                                        @if(strpos($book->cover,'/pic19/') && !strpos($book->cover,'/new/'))
                                            <a class="label label-danger">jiajiao</a>
                                        @else
                                            <a class="label label-info">其它</a>
                                        @endif
                                        <a href="{{ route('audit_answer_detail',$book->id) }}" target="_blank">{{ $book->bookname }}<em class="badge bg-red">{{ $book->collect_count }}</em></a><a class="btn btn-xs btn-default">答案页数：{{ count($book->hasAnswers) }}</a></p>

                                @endforeach
                                </div>
                                <div class="col-md-3">
                                        <a href="{{ route('new_buy_repeat_detail_books',[$repeat->grade_id,$repeat->subject_id,$repeat->volumes_id,$repeat->version_id,]) }}" target="_blank" class="btn btn-primary btn-block">查看</a>
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

@push('need_js')
    <script>
        $(function () {
            let box = [];
            $('#show_zero_book').click();
            $('#show_zero_book').click(function () {
                $('.all_repeat_book').each(function (i) {
                    $(this).find('.single_book[data-confirm=0]').each(function (j) {
                        box.push(parseInt($(this).attr('data-count')));
                    });
                    let a = Math.max.apply(null,box);
                    if(parseInt(a)===0){
                        if($(this).parents('.now_repeat_tr').is(':visible')){
                            $(this).parents('.now_repeat_tr').hide()
                        }else{
                            $(this).parents('.now_repeat_tr').show()
                        }
                    }
                    box = []
                });
            });
        })
    </script>
@endpush