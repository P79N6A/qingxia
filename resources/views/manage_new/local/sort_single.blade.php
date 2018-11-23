@extends('layouts.backend')

@section('manage_new_local_test_answer','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    {{--<link rel="stylesheet" href="{{ asset('js/magnify/css/magnify.css') }}">--}}
    <style>
        .answer_box img{
            /*max-width: 70%;*/
        }
        .answer_box a{
            min-width: 500px;
            /*min-width: 300px;*/
            /*max-width: 300px;*/
            /*max-height: 400px;*/
            /*overflow: auto;*/
        }
        .like_answer{
            border: 3px solid red
        }
    </style>
@endpush

@section('content')
    @component('components.modal',['id'=>'show_img','title'=>'查看图片'])
        @slot('body','')
        @slot('footer','')
    @endcomponent

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">唯一表整理</li>
        </ol>
    </section>
    <section class="content">
        @component('components.modal')
            @slot('id','show_big_photo')
            @slot('title','查看图片')
            @slot('body','')
            @slot('footer','')
        @endcomponent
        <div class="box">
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    @forelse($data['all_version'] as $version)
                        @php $now_version = cache('all_version_now')->where('id',$version->version_id)->first() @endphp
                        <li @if($loop->first) class="active" @endif><a>{{ $now_version?$now_version->name:'' }}</a></li>

                    @empty
                    <div class="input-group">
                        <select class="select2">
                            @forelse(cache('all_version_now') as $version)
                            <option value="{{ $version->id }}">{{ $version->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endforelse
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered">
                                <tr><th>年级/科目</th>
                                    <th class="col-md-3">语文</th>
                                    <th class="col-md-3">数学</th>
                                    <th class="col-md-3">英语</th>
                                    <th class="col-md-2">其它</th>
                                </tr>
                                @foreach([3,4,5,6,7,8,9] as $grade)
                                <tr>
                                    <td>{{ config('workbook.grade')[$grade] }}</td>
                                    <td>
                                    @if($data['new_sort_books']->where('grade_id',$grade)->where('subject_id',1)->count()>0)

                                            @forelse(File::files('//QINGXIA23/book4_new/2169_综合能力训练\2018年化学能力训练八年级化学下册鲁教版五四制_1217201\cover') as $file)
                                    <a class="thumbnail col-md-6">
                                                <img class="answer_pic" src="{{ 'http://192.168.0.117/book4_new/2169_综合能力训练\2018年化学能力训练八年级化学下册鲁教版五四制_1217201\cover/'.File::basename($file) }}">
                                    </a>
                                            @empty
                                                <em>{{ config('workbook.grade')[$grade].config('workbook.subject_1010')[1] }}</em>
                                            @endforelse

                                    @endif
                                    </td>
                                    <td>
                                    @if($data['new_sort_books']->where('grade_id',$grade)->where('subject_id',2)->count()>0)

                                            @forelse(File::files('//QINGXIA23/book4_new/2169_综合能力训练\2018年化学能力训练八年级化学下册鲁教版五四制_1217201\cover') as $file)
                                                <a class="thumbnail col-md-6">
                                                <img class="answer_pic" src="{{ 'http://192.168.0.117/book4_new/2169_综合能力训练\2018年化学能力训练八年级化学下册鲁教版五四制_1217201\cover/'.File::basename($file) }}">
                                                </a>
                                            @empty
                                                <em>{{ config('workbook.grade')[$grade].config('workbook.subject_1010')[2] }}</em>
                                            @endforelse

                                    @endif
                                    </td>
                                    <td>
                                    @if($data['new_sort_books']->where('grade_id',$grade)->where('subject_id',3)->count()>0)

                                            @forelse(File::files('//QINGXIA23/book4_new\2169_综合能力训练\2018年化学能力训练八年级化学下册鲁教版五四制_1217201\cover') as $file)
                                                <a class="thumbnail col-md-6">
                                                <img class="answer_pic" src="{{ 'http://192.168.0.117/book4_new/2169_综合能力训练\2018年化学能力训练八年级化学下册鲁教版五四制_1217201\cover/'.File::basename($file) }}">
                                                </a>
                                            @empty
                                                <em>{{ config('workbook.grade')[$grade].config('workbook.subject_1010')[3] }}</em>
                                            @endforelse

                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

                </div>
            </div>

    </section>
@endsection

@push('need_js')

@endpush