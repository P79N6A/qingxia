@extends('layouts.backend')

@section('isbn_temp_index','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .nav-tabs-custom{
            box-shadow:none !important;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">isbn_temp整理</li>

        </ol>
    </section>
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> <a href="{{ route('isbn_temp_by_sort') }}">isbn_temp_sort</a><a href="{{ route('isbn_temp_index',['list','arrange']) }}" class="btn @if($data['buy']=='arrange') btn-danger @else btn-default @endif">isbn_temp整理</a><a href="{{ route('isbn_temp_index',['list','buy']) }}" class="btn  @if($data['buy']=='buy') btn-danger @else btn-default @endif">isbn_temp购买</a></h3>
        </div>

        <div class="box-body">
            @component('components.modal',['id'=>'show_img','title'=>'查看图片'])
                @slot('body','')
                @slot('footer','')
            @endcomponent


            @forelse($data['all_temp_sort'] as $sort)
            <div class="panel panel-default" data-sort="{{ $sort->sort }}">
                <div class="panel-heading">
                    <div class="row">
                    <div class="col-md-12 isbn_info_box">
                        <h3>{{ $sort->has_sort->name.'_'.$sort->sort }}<i class="badge bg-red">num:{{ $sort->num }}</i>
                            @if(Auth::id()===2 || Auth::id()===5)
                                @if($sort->sort%3==0)
                                    肖高萍
                                @elseif($sort->sort%3==1))
                                    印娜
                                @elseif($sort->sort%3==2)
                                    张玲莉
                                    {{--@elseif(in_array($isbn->id,$other[3]))--}}
                                    {{--印娜--}}
                                    {{--@elseif(in_array($isbn->id,$other[4]))--}}
                                    {{--张玲莉--}}
                                @endif
                            @else
                                {{ Auth::user()->name }}
                            @endif
                            <a class="btn btn-primary btn-xs hide">加载更多</a>
                        </h3>
                    </div>
                    </div>
                </div>
                <div class="panel-body">
                    @if($data['sort'])
                        @php $now_all_sort = $sort->has_sort_detail @endphp
                    @else
                        @php $now_all_sort = $sort->has_sort_detail->take(6) @endphp
                    @endif
                    @forelse($now_all_sort as $isbn)
                    <div class="col-md-2">
                        <a class="thumbnail">
                            <img class="answer_pic" src="{{ $isbn->cover_photo }}" alt="">
                        </a>
                        <p>{{ $isbn->isbn }}<em class="badge bg-red">{{ $isbn->searchnum }}</em></p>
                        <div class="col-md-12">
                            <div class="">
                                <div class="input-group">
                                    <select class="form-control sort_name click_to select2" multiple="multiple">
                                        @if(intval($isbn->sort)>0)
                                            @if(is_array(explode(',',$isbn->sort)))
                                                @foreach(explode(',',$isbn->sort) as $sort_single)
                                                    @php $sort_now = cache('all_sort_now')->where('id',$sort_single)->first() @endphp
                                                    <option value="{{ $sort_single }}" selected="selected">{{ $sort_now?$sort_now->name:'' }}</option>
                                                @endforeach
                                            @else
                                                @php $sort_now = cache('all_sort_now')->where('id',$sort_single)->first() @endphp
                                                <option value="{{ $isbn->sort }}" selected="selected">{{ $sort_now?$sort_now->name:'' }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                <select class="select2 form-control" name="grade[]" data-name="grade" multiple="multiple">
                                    @if(intval($isbn->grade_id)>0)
                                        @if(is_array(explode(',',$isbn->grade_id)))
                                            @foreach(explode(',',$isbn->grade_id) as $grade_single)
                                                @php if($grade_single<=0 || $grade_single>=14){$grade_single = 0;} @endphp
                                                <option selected="selected"
                                                        value="{{ $grade_single }}">{{ config('workbook.grade')[intval($grade_single)] }}</option>
                                            @endforeach
                                        @else
                                            <option selected="selected"
                                                    value="{{ $isbn->grade_id }}">{{ config('workbook.grade')[intval($isbn->grade_id)] }}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="">
                                <select class="select2 form-control" name="subject[]" data-name="subject" multiple="multiple">
                                    @if(intval($isbn->subject_id)>0)
                                        @if(is_array(explode(',',$isbn->subject_id)))
                                            @foreach(explode(',',$isbn->subject_id) as $subject_single)
                                                @php if(intval($subject_single)<=0 || $subject_single>11) $subject_single = 0 @endphp
                                                <option selected="selected"
                                                        value="{{ $subject_single }}">{{ config('workbook.subject_1010')[abs(intval($subject_single))] }}</option>
                                            @endforeach
                                        @else
                                            <option selected="selected"
                                                    value="{{ $isbn->subject_id }}">{{ config('workbook.subject_1010')[intval($isbn->subject_id)] }}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="">
                                <select class="select2 form-control" name="volumes[]" data-name="volumes" multiple="multiple">
                                    @if(intval($isbn->volumes_id)>0)
                                        @if(is_array(explode(',',$isbn->volumes_id)))
                                            @foreach(explode(',',$isbn->volumes_id) as $volumes_single)
                                                @if(intval($volumes_single)>0)
                                                    <option selected="selected"
                                                            value="{{ $volumes_single }}">{{ isset(config('workbook.volumes')[intval($isbn->volumes_id)])?config('workbook.volumes')[intval($isbn->volumes_id)]:$isbn->volumes_id }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option selected="selected"
                                                    value="{{ $isbn->volumes_id }}">{{ isset(config('workbook.volumes')[intval($isbn->volumes_id)])?config('workbook.volumes')[intval($isbn->volumes_id)]:$isbn->volumes_id }}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="">
                                <select class="select2 form-control" name="version[]" data-name="version" multiple="multiple">
                                    @if(intval($isbn->version_id)>=0)
                                        @if(is_array(explode(',',$isbn->version_id)))
                                            @foreach(explode(',',$isbn->version_id) as $version_single)
                                                <option selected="selected"
                                                        value="{{ $version_single }}">{{ cache('all_version_now')->where('id',$version_single)->first()->name }}</option>
                                            @endforeach
                                        @else
                                            <option selected="selected"
                                                    value="{{ $isbn->version_id }}">{{ cache('all_version_now')->where('id',$isbn->version_id)->first()->name }}</option>
                                        @endif
                                    @endif

                                </select>
                            </div>
                            <a class="btn btn-block btn-primary save_all" data-isbn="{{ $isbn->isbn }}">保存</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer">

                </div>
            </div>
            @endforeach
                <div class="panel-footer">
                    {{ $data['all_temp_sort']->links() }}
                </div>
        </div>
    </div>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
            const buy_status = '{{ $data['buy'] }}';
            $('.select2').select2();

            $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['grade_select'] !!} '),});
            $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['subject_select'] !!} '),});
            $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['volume_select'] !!} '),});
            $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});
            //选择系列
            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('workbook_sort','sort') }}",
                    dataType: 'json',
                    delay: 100,
                    data: function (params) {
                        return {
                            word: params.term, // search term 请求参数
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items,//itemList
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // 自定义格式化防止xss注入
                minimumInputLength: 1,//最少输入多少个字符后开始查询
                templateResult: function formatRepo(repo) {
                    if (repo.loading) return repo.text;
                    return '<option value="' + repo.id + '">' + repo.name + '_' + repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });

            //系列切换后显示对应旧版
            $('.sort_name').change(function () {

                let box = $(this).parents('.panel-default');
                let sort = $(this).val();
                let isbn = box.attr('data-isbn');
                let press = box.attr('data-press');
                if(sort.length!==1){
                    if(sort.length===0){
                        box.find('.like_box').html('');
                    }
                    return false;
                }

                axios.post('{{ route('isbn_temp_api','search_old_sort') }}',{sort,press}).then(response=>{
                    let books = response.data.books;
                    if(books.length>0){
                        box.find('.like_box').html('')
                        let html = '';
                        for (let i in books){
                            html += `<a class="thumbnail col-md-1"><img class="answer_pic" src="${books[i].cover}"/><p>${books[i].has_sort.name}</p><p>${books[i].bookname}</p>`
                            if(books[i].isbn===isbn){
                                html += `<p class="badge bg-red">${books[i].isbn}</p></a>`;
                            }else{
                                html += `<p>${books[i].isbn}</p></a>`;
                            }
                        }
                        box.find('.like_box').append(html)
                    }
                }).catch();
            });



            //保存该isbn全部信息
            $('.save_all').click(function () {
                let box = $(this).parents('.isbn_info_box');
                let isbn = $(this).attr('data-isbn');
                let sort = box.find('.sort_name').val();
                let grade = box.find('select[data-name="grade"]').val();
                let subject = box.find('select[data-name="subject"]').val();
                let volumes = box.find('select[data-name="volumes"]').val();
                let version = box.find('.select[data-name="version"]').val();
                axios.post('{{ route('isbn_temp_api','save_isbn_info') }}').then(response=>{

                }).catch();
            })

        })
    </script>
@endpush