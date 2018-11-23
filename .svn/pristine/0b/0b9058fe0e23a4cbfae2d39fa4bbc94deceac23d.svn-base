@extends('layouts.backend')

@section('isbn_temp_index','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .nav-tabs-custom{
            box-shadow:none !important;
        }
        .tab-pane a.thumbnail{
            height: 300px;
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
            <h3 class="box-title"><i class="fa fa-tag"></i> <a href="{{ route('isbn_temp_index',['list','arrange']) }}" class="btn @if($data['buy']=='arrange') btn-danger @else btn-default @endif">isbn_temp整理</a><a href="{{ route('isbn_temp_index',['list','buy']) }}" class="btn  @if($data['buy']=='buy') btn-danger @else btn-default @endif">isbn_temp购买</a></h3>
        </div>

        <div class="box-body">
            @component('components.modal',['id'=>'show_img','title'=>'查看图片'])
                @slot('body','')
                @slot('footer','')
            @endcomponent


            @forelse($data['all_isbn'] as $isbn)
            <div class="panel panel-default" data-id="{{ $isbn->id }}" data-press="{{ $isbn->press }}" data-isbn="{{ $isbn->isbn }}">
                <div class="panel-heading">
                    <div class="row">
                    <div class="col-md-12">
                        <h3>{{ $isbn->isbn }}<i class="badge bg-red">id:{{ $isbn->id }}</i><i class="badge bg-red">searchnum: {{ $isbn->searchnum }}</i><i class="badge bg-red">resultcount:{{ $isbn->resultcount }}</i>
                            @if(Auth::id()===2 || Auth::id()===5)
                                @if(in_array($isbn->id,$data['task'][0]))
                                    肖高萍
                                @elseif(in_array($isbn->id,$data['task'][1])))
                                印娜
                                @elseif(in_array($isbn->id,$data['task'][2]))
                                    张玲莉
                                    {{--@elseif(in_array($isbn->id,$other[3]))--}}
                                    {{--印娜--}}
                                    {{--@elseif(in_array($isbn->id,$other[4]))--}}
                                    {{--张玲莉--}}
                                @endif
                            @else
                                {{ Auth::user()->name }}
                            @endif
                        </h3>
                    </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="nav-tabs-custom" data-isbn="{{ $isbn->isbn }}" data-id="{{ $isbn->id }}">
                        <ul class="nav nav-tabs">
                            <li class="@if($data['buy']==='arrange') active @endif"><a data-toggle="tab" data-type="userTemp" class="search_nav" href="#{{ $isbn->id }}_userTemp">All</a></li>
                            <li class="hide "><a data-toggle="tab" data-type="hdTemp" class="search_nav" href="#{{ $isbn->id }}_hdTemp">test</a></li>
                            <li class="hide"><a data-toggle="tab" data-type="1010Temp" class="search_nav" href="#{{ $isbn->id }}_1010Temp">1010</a></li>

                            <li class="hide @if($data['buy']==='buy') active @endif"><a data-toggle="tab" data-type="taobaoTemp" class="search_nav" href="#{{ $isbn->id }}_taobaoTemp">淘宝</a></li>
                        </ul>
                        <div class="tab-content">
                            {{--id,sort_name,sort_id,grade_id,subject_id,volumes_id,version_id,cover_img--}}
                            <div class="tab-pane @if($data['buy']==='arrange') active @endif" id="{{ $isbn->id }}_userTemp">
                                @if($data['buy']==='arrange')
                                    @forelse($isbn->has_user_book as $book)
                                        <div class="col-md-2">
                                            <a>{{ $book->sort_name }}</a>
                                            <a class="thumbnail">
                                            <img class="answer_pic" src="{{ config('workbook.user_image_url').$book->cover_img }}" alt="">
                                            </a>
                                            @if($book->sort_id>=0)
                                            @php $sort_now = cache('all_sort_now')->where('id',$book->sort_id)->first() @endphp
                                            <a class="btn btn-xs btn-primary book_sort">{{ $sort_now?$sort_now->name:'' }}</a>
                                            @endif
                                            @if($book->grade_id>0 && $book->grade_id<=14)
                                            <a class="btn btn-xs btn-primary book_grade">{{ config('workbook.grade')[$book->grade_id] }}</a>
                                            @endif
                                            @if($book->subject_id>0)
                                            <a class="btn btn-xs btn-primary book_subject">{{ config('workbook.subject')[$book->subject_id] }}</a>
                                            @endif
                                            @if($book->volumes_id>0 && $book->volumes_id<79)
                                            <a class="btn btn-xs btn-primary book_volumes">{{ config('workbook.volumes')[$book->volumes_id] }}</a>
                                            @endif
                                            @if($book->version_id>=0)
                                            <a class="btn btn-xs btn-primary book_version">{{ cache('all_version_now')->where('id',$book->version_id)->first()->name }}</a>
                                            @endif
                                        </div>
                                    @endforeach
                                    @php $books = App\LocalModel\TaobaoTemp500::where('isbn',$isbn->isbn)->select('id','title','pic_url','detail_url','view_price','view_fee')->take(6)->get() @endphp
                                    @forelse($books as $book)
                                        <div class="col-md-2">
                                            <a href="https://detail.tmall.com/item.htm?id={{ $book->detail_url }}" target="_blank">{{ $book->title }}<i class="badge bg-red">价格: {{ $book->view_price }}</i><i class="badge bg-red">运费: {{ $book->view_fee }}</i></a>
                                            <a class="thumbnail">
                                                <img class="answer_pic" src="{{ $book->pic_url }}" alt="">
                                            </a>
                                        </div>
                                    @endforeach
                                    @forelse($isbn->has_hd_book as $book)
                                        @php
                                            $now_version = cache('all_version_now')->where('id',[$book->bookVersionId])->first();
                                            $now_version = $now_version?$now_version->name:'未选择';
                                            $grade_now = isset(config('workbook.grade')[$book->gradeId])?config('workbook.grade')[$book->gradeId]:"未选择";
                                            $subject_now = isset(config('workbook.subject')[$book->subjectId])?config('workbook.subject')[$book->subjectId]:"未选择";
                                            $volumes_now = isset(config('workbook.volumes')[$book->volumes])?config('workbook.volumes')[$book->volumes]:"未选择";
                                            $sort_name_now = $book->has_sort?$book->has_sort->name:$book->sortId;
                                        @endphp
                                            <div class="col-md-2"><a>{{ $book->bookName }}</a><a class="badge bg-red">{{ $sort_name_now }}</a><a class="thumbnail"><img class="answer_pic" src="http://image.hdzuoye.com/{{ $book->coverImage }}" alt=""></a><a class="btn btn-xs btn-primary book_grade">{{ $grade_now }}</a><a class="btn btn-xs btn-primary book_subject">{{ $subject_now }}</a><a class="btn btn-xs btn-primary book_volumes">{{ $volumes_now }}</a><a class="btn btn-xs btn-primary book_version">{{ $now_version }}</a></div>
                                    @endforeach
                                 @endif
                            </div>
                            <div class="tab-pane" id="{{ $isbn->id }}_hdTemp"></div>
                            <div class="tab-pane" id="{{ $isbn->id }}_1010Temp"></div>
                            <div class="tab-pane @if($data['buy']==='buy') active @endif" id="{{ $isbn->id }}_taobaoTemp"> @if($data['buy']==='buy') @php  $books = App\LocalModel\TaobaoTemp500::where('isbn',$isbn->isbn)->select('id','title','pic_url','detail_url','view_price','view_fee')->take(6)->get() @endphp
                                @forelse($books as $book)
                                <div class="col-md-2">
                                    <a href="https://detail.tmall.com/item.htm?id={{ $book->detail_url }}" target="_blank">{{ $book->title }}<i class="badge bg-red">价格: {{ $book->view_price }}</i><i class="badge bg-red">运费: {{ $book->view_fee }}</i></a>
                                <a class="thumbnail">
                                <img class="answer_pic" src="{{ $book->pic_url }}" alt="">
                                </a>
                                </div>
                                @endforeach @endif </div>

                            {{--<div class="tab-pane" id="{{ $isbn->id }}_1010Temp">--}}
                                {{--@forelse($isbn->has_offical_book as $book)--}}
                                    {{--<div class="col-md-2">--}}
                                        {{--<a>{{ $book->bookname }}</a>--}}
                                        {{--<a class="thumbnail">--}}
                                            {{--<img class="answer_pic" src="{{ $book->cover }}" alt="">--}}
                                        {{--</a>--}}
                                        {{--@if($book->sort>=0)--}}
                                            {{--@php $sort_now = cache('all_sort_now')->where('id',$book->sort)->first() @endphp--}}
                                            {{--<a class="book_sort">{{ $sort_now?$sort_now->name:'' }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->grade_id>0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_grade">{{ config('workbook.grade')[$book->grade_id] }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->subject_id>0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_subject">{{ config('workbook.subject_1010')[$book->subject_id] }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->volumes_id>0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_volumes">{{ config('workbook.volumes')[$book->volumes_id] }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->version_id>=0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_version">{{ cache('all_version_now')->where('id',$book->version_id)->first()->name }}</a>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--@endforeach--}}
                            {{--</div>--}}

                            {{--<div class="tab-pane" id="{{ $isbn->id }}_userTemp">--}}
                                {{--id,sort_name,sort_id,grade_id,subject_id,volumes_id,version_id,cover_img--}}
                                {{--@forelse($isbn->has_user_book as $book)--}}
                                    {{--<div class="col-md-2">--}}
                                        {{--<a>{{ $book->sort_name }}</a>--}}
                                        {{--<a class="thumbnail">--}}
                                            {{--<img class="answer_pic" src="{{ config('workbook.thumb_image_url').$book->cover_img }}" alt="">--}}
                                        {{--</a>--}}
                                        {{--@if($book->sort_id>=0)--}}
                                            {{--@php $sort_now = cache('all_sort_now')->where('id',$book->sort_id)->first() @endphp--}}
                                            {{--<a class="btn btn-xs btn-primary book_sort">{{ $sort_now?$sort_now->name:'' }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->grade_id>0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_grade">{{ config('workbook.grade')[$book->grade_id] }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->subject_id>0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_subject">{{ config('workbook.subject')[$book->subject_id] }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->volumes_id>0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_volumes">{{ config('workbook.volumes')[$book->volumes_id] }}</a>--}}
                                        {{--@endif--}}
                                        {{--@if($book->version_id>=0)--}}
                                            {{--<a class="btn btn-xs btn-primary book_version">{{ cache('all_version_now')->where('id',$book->version_id)->first()->name }}</a>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--@endforeach--}}
                            {{--</div>--}}
                            {{--<div class="tab-pane" id="{{ $isbn->id }}_taobaoTemp">--}}
                                {{--@forelse($isbn->has_taobao_book as $book)--}}
                                    {{--<div class="col-md-2">--}}
                                        {{--<a>{{ $book->title }}</a>--}}
                                        {{--<a class="thumbnail">--}}
                                            {{--<img class="answer_pic" src="{{ $book->pic_url }}" alt="">--}}
                                        {{--</a>--}}
                                    {{--</div>--}}
                                {{--@endforeach--}}
                            {{--</div>--}}
                        </div>
                        <br>

                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control sort_name click_to select2" multiple="multiple">
                                    @if(intval($isbn->sort)>0)
                                        @if(is_array(explode(',',$isbn->sort)))
                                            @foreach(explode(',',$isbn->sort) as $sort_single)
                                                @php $sort_now = cache('all_sort_now')->where('id',$sort_single)->first() @endphp
                                                <option value="{{ $sort_single }}" data-title="{{ $sort_now?$sort_now->name:'' }}" selected="selected">{{ $sort_now?$sort_now->name:'' }}</option>
                                            @endforeach
                                        @else
                                            @php $sort_now = cache('all_sort_now')->where('id',$sort_single)->first() @endphp
                                            <option value="{{ $isbn->sort }}" data-title="{{ $sort_now?$sort_now->name:'' }}" selected="selected">{{ $sort_now?$sort_now->name:'' }}</option>
                                        @endif
                                    @endif
                                </select>
                                <a class="input-group-addon save_sort btn btn-primary" data-id="{{ $isbn->id }}">保存</a>
                                <a class="input-group-addon combine_sort btn btn-primary" data-id="{{ $isbn->id }}">合并</a>
                            </div>
                            <div class="input-group">
                                <input type="text" value="" class="form-control">
                                <a class="input-group-addon add_new_sort btn btn-primary"  data-id="{{ $isbn->id }}">新增系列</a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
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
                                <a class="input-group-addon save_other btn btn-primary" data-type="grade" data-id="{{ $isbn->id }}">保存</a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
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
                                <a class="input-group-addon save_other btn btn-primary" data-type="subject" data-id="{{ $isbn->id }}">保存</a>
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
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
                                <a class="input-group-addon save_other btn btn-primary" data-type="volumes" data-id="{{ $isbn->id }}">保存</a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
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
                                <a class="input-group-addon save_other btn btn-primary" data-type="version" data-id="{{ $isbn->id }}">保存</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 like_box ">
                        @if(intval($isbn->sort)>0)
                            @if(is_array(explode(',',$isbn->sort)))
                                @foreach(explode(',',$isbn->sort) as $sort_single)
                                    @php $now_sort = \App\AWorkbook1010::where([['sort',$sort_single],['isbn','like','%'.$isbn->press.'%']])->select('id','bookname','cover','sort','isbn')->with('has_sort:id,name')->take(6)->get(); @endphp
                                    <div class="row">
                                        <h4>{{ cache('all_sort_now')->where('id',$sort_single)->first()->name }}</h4>
                                    @forelse($now_sort as $sort_info)
                                        <a class="thumbnail col-md-1"><img class="answer_pic" src="{{ $sort_info->cover }}"/><p>{{ $sort_info->has_sort->name }}</p><p>{{ $sort_info->bookname }}</p><p class="badge bg-red">{{ $sort_info->isbn }}</p></a>
                                    @endforeach
                                    </div>
                                @endforeach
                            @endif
                        @endif


                    </div>
                    </div>
                </div>
            </div>
            @endforeach
                <div class="panel-footer">
                    {{ $data['all_isbn']->links() }}
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



            //搜索其它类
            $(document).on('click','.search_nav',function () {
                let nav_tabs = $(this).parents('.nav-tabs-custom');
                let now_isbn = nav_tabs.attr('data-isbn');
                let now_type = $(this).attr('data-type');
                let now_id = nav_tabs.attr('data-id');
                let now_html = $(`#${now_id}_${now_type}`).html();
                if($.trim(now_html).length>10){
                    return false;
                }
                axios.post('{{ route('isbn_temp_api','get_nav') }}',{now_isbn,now_type,buy_status}).then(response=>{
                    $(`#${now_id}_${now_type}`).html(response.data.books_html)
                }).catch();
            });

            //保存sort
            $('.save_sort').click(function() {
                let box = $(this).parents('.panel-default');
                let now_id = box.attr('data-id');
                let now_sort = box.find('.sort_name').val();
                if(now_sort.length!==1){
                    alert('请先确认sort');
                    return false;
                }
                axios.post('{{ route('isbn_temp_api','save_sort') }}',{now_id,now_sort}).then(response=>{

                }).catch();
            });

            //保存其它信息
            $('.save_other').click(function () {
                let box = $(this).parents('.panel-default');
                let now_id = box.attr('data-id');
                let now_type = $(this).attr('data-type');
                let now_value = '';
                if(now_type==='grade'){
                    now_value = box.find('select[data-name="grade"]').val();
                }else if(now_type==='subject'){
                    now_value = box.find('select[data-name="subject"]').val();
                }else if(now_type==='volumes'){
                    now_value = box.find('select[data-name="volumes"]').val();
                }else if(now_type==='version'){
                    now_value = box.find('select[data-name="version"]').val();
                }
                if(now_value.length===0){
                    alert('请确认保存值');
                }

                axios.post('{{ route('isbn_temp_api','save_info') }}',{now_id,now_type,now_value}).then(response=>{
                    if(response.data.status===0){
                        alert('保存失败');
                    }
                }).catch();
            });

            //保存isbn
            $(document).on('click','.add_isbn',function () {
                let box = $(this).parents('.nav-tabs-custom');
                let add_isbn = box.attr('data-isbn');
                let now_id = $(this).attr('data-id');
                $(this).prev().val($(this).prev().val()+'|'+add_isbn);
                if(!confirm('确认追加')){
                    return false;
                }
                axios.post('{{ route('isbn_temp_api','add_isbn') }}',{now_id,add_isbn}).then(response=>{
                    alert(response.data.msg);
                }).catch();
            });

            //新增系列
            $(document).on('click','.add_new_sort',function () {
                let now_id = $(this).attr('data-id');
                let sort_name = $(this).prev().val();
                if(sort_name.length<3){
                    return false;
                }
                axios.post('{{ route('isbn_temp_api','add_new_sort') }}',{now_id,sort_name}).then(response=>{
                    alert(response.data.msg);
                }).catch();
            });

            $(document).on('click','.combine_sort',function () {
                let box = $(this).parents('.panel-default');
                let sorts = box.find('.sort_name').val();
                axios.post('{{ route('isbn_temp_api','combine_sort') }}',{sorts}).then(response=>{

                }).catch();
            });

            {{--$(document).on('click','.select2-selection__choice',function () {--}}
                {{--let box = $(this).parents('.panel-default');--}}
                {{--let select_now = box.find('.sort_name');--}}
                {{--let now_index = $(this).index();--}}
                {{--let now_sort = [];--}}
                {{--let isbn = box.attr('data-isbn');--}}
                {{--now_sort.push(select_now.children(`option:eq(${now_index})`).attr('value'));--}}
                {{--let sort = now_sort;--}}
                {{--let press = box.attr('data-press');--}}
                {{--axios.post('{{ route('isbn_temp_api','search_old_sort') }}',{sort,press}).then(response=>{--}}
                    {{--let books = response.data.books;--}}
                    {{--if(books.length>0){--}}
                        {{--box.find('.like_box').html('');--}}
                        {{--let html = '';--}}
                        {{--for (let i in books){--}}
                            {{--html += `<a class="thumbnail col-md-1"><img class="answer_pic" src="${books[i].cover}"/><p>${books[i].has_sort.name}</p><p>${books[i].bookname}</p>`;--}}
                            {{--if(books[i].isbn===isbn){--}}
                                {{--html += `<p class="badge bg-red">${books[i].isbn}</p></a>`;--}}
                            {{--}else{--}}
                                {{--html += `<p>${books[i].isbn}</p></a>`;--}}
                            {{--}--}}
                        {{--}--}}
                        {{--box.find('.like_box').append(html)--}}
                    {{--}else{--}}
                        {{--box.find('.like_box').html('');--}}
                    {{--}--}}
                {{--}).catch();--}}


                //let sort_name = $(this).attr('title')

                //select_now.find('option')
//            })

        })
    </script>
@endpush