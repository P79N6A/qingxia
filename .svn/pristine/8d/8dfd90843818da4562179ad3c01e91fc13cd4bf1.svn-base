@extends('layouts.backend')

@section('manage_new_oss','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
    {{--<link rel="stylesheet" href="{{ asset('js/magnify/css/magnify.css') }}">--}}
    <style>
        .answer_box{
            display: none;
            overflow: auto;
            margin: 10px;
            padding: 10px;
            border: 2px solid grey;
        }
        .answer_box img{
            /*max-width: 70%;*/
        }
        .answer_box a{
            min-width: 500px;
            max-width: 500px;
            /*min-width: 300px;*/
            /*max-width: 300px;*/
            /*max-height: 400px;*/
            /*overflow: auto;*/
        }
        .like_answer{
            border: 4px solid red !important;
        }
        .answer_now_single{
            /*height: 50px;*/
        }
        .like_answer_box a{
            min-width: 400px;
        }
        .like_answer_box .panel-body{
            display: flex;
            overflow: auto;
            margin: 10px;
            padding: 10px;
            border: 2px solid grey;
        }
        .confirm_like_answer , .confirm_answer_done{
            margin: 20px;
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
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> cip整理</h3>
            <div class="input-group hide" style="width: 40%">
                <input type="text" value="" class="form-control"/>
                <a class="input-group-addon btn btn-primary" id="to_book_id">跳转至练习册id</a>
            </div>
                <div class="form-group">
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i>
                    </span>
                            <i class="fa fa-caret-down"></i>{{substr($data['start'],0,10)}}~{{substr($data['end'],0,10)}}
                        </button>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li @if($data['type']==='isbn_null') class="active" @endif><a href="{{ route('manage_new_oss',['isbn_null',$data['start'],$data['end']]) }}">isbn为空</a></li>
                        <li @if($data['type']==='isbn_problem') class="active" @endif><a href="{{ route('manage_new_oss',['isbn_problem',$data['start'],$data['end']]) }}">cip与封面不符</a></li>
                        <li @if($data['type']==='answer_problem') class="active" @endif><a href="{{ route('manage_new_oss',['answer_problem',$data['start'],$data['end']]) }}">答案集合整理</a></li>
                        <li @if($data['type']==='book_problem') class="active" @endif><a href="{{ route('manage_new_oss',['book_problem',$data['start'],$data['end']]) }}">练习册答案匹配</a></li>
                        <li @if($data['type']==='sort_null') class="active" @endif><a href="{{ route('manage_new_oss',['sort_null',$data['start'],$data['end']]) }}">系列未设置</a></li>
                        @can('lxc_verify')
                            <li class="hide"><a target="_blank" href="{{ route('manage_new_oss_status') }}">整理状况查看</a></li>
                        @endcan
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            @forelse($data['all_isbn'] as $key => $isbn)
                                <div class="single_box_info col-md-12" data-id="{{ $isbn->id }}">
                                    <div class="box-header">
                                        <i class="badge bg-red">{{ $isbn->id }}</i>
                                        <a href="http://www.1010jiajiao.com/daan/bookid_{{ $isbn->id }}.html" target="_blank">{{ $isbn->bookname }}</a>
                                        <a class="btn btn-primary">
                                            @if(Auth::id()===2 || Auth::id()===5)
                                                @if(in_array($isbn->id,$data['other'][0]))
                                                    肖高萍
                                                @elseif(in_array($isbn->id,$data['other'][1]))
                                                    印娜
                                                @elseif(in_array($isbn->id,$data['other'][2]))
                                                    张玲莉
                                                {{--@elseif(in_array($isbn->id,$data['other'][3]))--}}
                                                    {{--印娜--}}
                                                {{--@elseif(in_array($isbn->id,$data['other'][4]))--}}
                                                    {{--张玲莉--}}
                                                @endif
                                            @else
                                                {{ Auth::user()->name }}
                                            @endif
                                        </a>
                                        @if(strlen(str_replace(['-','|'],'',$isbn->isbn))==13)
                                            @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')

                                            @php
                                            try{
                                                echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$isbn->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                            }catch (Exception $e){
                                                echo '无法生成此isbn的条形码';
                                            }
                                            @endphp


                                        @endif
                                        @if($isbn->answer_not_complete==1)
                                            <a class="btn btn-danger mark_answer">已标记此练习册答案不全</a>
                                        @else
                                            <a class="btn btn-primary mark_answer">标记此练习册答案不全</a>
                                        @endif
                                        @if($data['type']==='done')
                                            <span>
                                                @can('lxc_verify')
                                            @if($isbn->verified_at)
                                                <i class="badge bg-blue">已审核：{{ $isbn->verified_at }}</i>
                                            @else
                                                <i class="badge bg-red">待审核</i>
                                                <a class="btn btn-danger btn-xs verify_confirm" data-book-id="{{ $isbn->id }}">确认无误,审核通过</a>
                                            @endif
                                                @endcan
                                            </span>
                                        @endif
                                    </div>
                                    <div class="box-body">
                                        <div class="col-md-4 book_info_box">
                                            <a class="thumbnail show_big">
                                                <img src="{{ config('workbook.hd_url').$isbn->cover_photo }}" alt="">
                                            </a>
                                            <div class="input-group">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" maxlength="4" class="form-control version_year" value="{{ $isbn->version_year?$isbn->version_year:'2018' }}">
                                            </div>
                                            <div class="input-group for_relate_sort">
                                                <select class="form-control related_sort select2">
                                                    <option value="-999" selected>选择系列</option>
                                                    @if(isset($data['all_isbn'][$key]['related_sort']))
                                                        @forelse($data['all_isbn'][$key]['related_sort'] as $sort)
                                                            <option value="{{ $sort->id }}">{{ $sort->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <a class="input-group-addon">相关系列查看</a>
                                            </div>
                                            <div class="input-group" style="width: 100%">
                                                <select class="form-control sort_name click_to select2">
                                                    @if($isbn->sort>0)
                                                        <option value="{{ $isbn->sort }}">{{ App\Sort::find($isbn->sort)?App\Sort::find($isbn->sort)->name:'' }}</option>
                                                    @endif
                                                    <option value="-999">搜索系列</option>
                                                </select>
                                            </div>
                                            <a class="btn btn-block btn-primary save_sort_single">保存系列</a>

                                            <br>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade" class="grade_id form-control select2 pull-left " >
                                                    @forelse(config('workbook.grade') as $key => $grade)
                                                        @if($key>=1)
                                                        <option value="{{ $key }}" @if($isbn->grade_id==$key) selected="selected"@endif>{{ $grade }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2">
                                                    @forelse(config('workbook.subject_1010') as $key => $subject)
                                                        <option value="{{ $key }}" @if($isbn->subject_id==$key) selected="selected"@endif>{{ $subject }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    @forelse(config('workbook.volumes') as $key => $volume)
                                                        @if($key>=1)
                                                        <option value="{{ $key }}" @if($isbn->volumes_id>0 && $isbn->volumes_id==$key) selected="selected" @elseif($isbn->volumes_id==0 && $key==2) selected="selected" @endif>{{ $volume }}</option>
                                                        @endif
                                                     @endforeach
                                                </select>
                                            </div>

                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">版本</label>
                                                <select data-name="version" class="version_id form-control select2">
                                                    @forelse(cache('all_version_now') as $key => $version)
                                                        @if($version->id>=0)
                                                        <option value="{{ $version->id }}" @if($isbn->version_id==$version->id) selected="selected"@endif>{{ $version->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="input-group" data-id="{{ $isbn->id }}" style="width: 100%">
                                                <input maxlength="17" class="for_isbn_input form-control input-lg" style="font-size: 24px" value="{{ $isbn->isbn?$isbn->isbn:'978-7-' }}" />
                                                <a class="btn btn-danger input-group-addon add_isbn">保存isbn</a>

                                            </div>
                                            <div class="col-md-12">
                                            @if(isset($isbn->search_isbn))
                                            @forelse($isbn->search_isbn as $like_isbn)
                                                        <a class="thumbnail col-md-3"><img class="answer_pic" src="{{ $like_isbn->cover_photo }}" alt=""><strong class="like_isbn" data-isbn="{{ convert_isbn($like_isbn->isbn) }}">{{ convert_isbn($like_isbn->isbn) }}</strong></a>
                                            @endforeach
                                            @endif
                                            </div>

                                            <div class="input-group">
                                                <textarea type="text" class="form-control now_name" rows="3">{{ $isbn->bookname }}</textarea>
                                                <a class="btn btn-primary input-group-addon generate_name">生成</a>
                                            </div>

                                            <div>
                                                @forelse($isbn->offical_isbn as $now)
                                                    <a class="badge bg-success change_all_value" data-grade="{{ $now->grade_id }}"  data-subject="{{ $now->subject_id }}"  data-volumes="{{ $now->volumes_id }}"  data-version="{{ $now->version_id }}">{{ $now->bookname }}</a>
                                                @endforeach

                                            </div>

                                            <hr>
                                        </div>
                                        <div class="col-md-8">
                                            <a class="col-md-8 thumbnail show_big">
                                                @if(strpos($isbn->cip_photo,'|')>0)
                                                    @forelse(explode('|',$isbn->cip_photo) as $cip)
                                                        <img data-original="{{ config('workbook.hd_url').$cip }}" alt="{{ $isbn->isbn }}">
                                                    @endforeach
                                                @else
                                                <img data-original="{{ config('workbook.hd_url').$isbn->cip_photo }}" alt="{{ $isbn->isbn }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <a class="btn btn-danger show_answer">显示答案</a>
                                        <div class="all_answer_box">
                                            {{--@if(count($isbn->answers_other)>0)--}}
                                                {{--@forelse(explode('|',$isbn->tid) as $key=> $tid)--}}
                                                    {{--<a class="btn btn-primary choose_one_answer" data-id="{{ $isbn->id }}" data-tid="{{ $tid }}">选取第{{ intval($key)+1 }}排答案为标准答案</a>--}}
                                                {{--@endforeach--}}
                                            {{--@endif--}}
                                        @if(isset($isbn->answers) && count($isbn->answers)>0)
                                            <div class="answer_box" style="" data-tid="0">
                                                @forelse($isbn->answers as $answer)
                                                    <a class="thumbnail @if(strpos($answer->tid,'|')!==false) like_answer @endif" @if((!$loop->last && $isbn->answers[$loop->index+1]->addtime == $answer->addtime) || (!$loop->first && $isbn->answers[$loop->index-1]->addtime == $answer->addtime)) style="border: 4px solid green;"  @endif>
                                                        <img data-id="{{ $answer->id }}" class="answer_pic real_pic" data-magnify-src="{{ config('workbook.hd_url').$answer->answer }}" data-original="{{ config('workbook.hd_url').$answer->answer }}" alt="">
                                                        <i>{{ $answer->addtime }}</i>
                                                        <i class="badge bg-blue delete_this">移除</i>
                                                        <i class="badge bg-red exchange" data-type="left">与左图交换</i>
                                                        <i class="badge bg-red exchange" data-type="right">与右图交换</i>
                                                        {{--@if((!$loop->last && $isbn->answers[$loop->index+1]->addtime == $answer->addtime))--}}
                                                        {{--<i class="badge bg-red exchange" data-type="right">与右图交换</i>--}}
                                                        {{--@endif--}}
                                                        {{--@if(!$loop->first && $isbn->answers[$loop->index-1]->addtime == $answer->addtime))--}}
                                                        {{--<i class="badge bg-red exchange" data-type="left">与左图交换</i>--}}
                                                        {{--@endif--}}
                                                    </a>
                                                @endforeach
                                            </div>
                                                <a class="btn btn-danger confirm_answer_done btn-lg btn-block">确认答案信息无误</a>
                                        @endif

                                        @if(isset($isbn->answers_other) && count($isbn->answers_other)>0)
                                        @forelse($isbn->answers_other as $key_now =>$item_now)
                                            <div class="answer_box answer_others" @if(count($item_now)>0) data-tid="{{ $item_now[0]->tid }}"  @endif>
                                            @forelse($item_now as $answer)
                                                <a class="thumbnail" data-tid="{{ $answer->tid }}">
                                                    <i class="text-center">tid:{{ $answer->tid }}</i>
                                                    <img data-id="{{ $answer->id }}" class="answer_pic real_pic" data-magnify-src="{{ config('workbook.hd_url').$answer->answer }}" data-original="{{ config('workbook.hd_url').$answer->answer }}" alt="" >
                                                    <i>{{ $answer->addtime }}</i>
                                                    <i class="badge bg-blue delete_this">移除</i>
                                                    <i class="badge bg-red exchange" data-type="left">与左图交换</i>
                                                    <i class="badge bg-red exchange" data-type="right">与右图交换</i>
                                                </a>
                                            @endforeach
                                            </div>
                                                <a class="btn btn-danger confirm_answer_done btn-lg btn-block">确认答案信息无误</a>
                                        @endforeach
                                        @endif
                                        </div>

                                        <a class="btn btn-danger hide confirm_book_done btn-lg btn-block">确认练习册和答案信息无误</a>
                                    </div>
                                </div>
                            @endforeach

                            @if($data['type']==='answer_problem')
                                <div class="input-group" style="width: 50%;margin: 20px;">
                                    <input type="text" class="form-control" />
                                    <a class="input-group-addon btn btn-primary mix_tids">混合(输入tid以,隔开)</a>
                                </div>
                            @endif
                            @forelse($data['all_like_answers'] as $key=>$now_answer)
                                    @if(count($now_answer)>0)
                                    <div class="like_answer_box row">
                                        <div class="panel panel-primary col-md-12">
                                            <div class="panel-heading">
                                                <h4>{{ $key }}</h4>
                                            </div>
                                            <div class="panel-body">
                                            @forelse($now_answer as $answer)
                                                <a class="thumbnail col-md-4"><img data-id="{{ $answer->id }}" class="answer_pic real_pic" data-magnify-src="{{ config('workbook.hd_url').$answer->answer }}" data-original="{{ config('workbook.hd_url').$answer->answer }}" alt="">
                                                    <select data-id="{{ $answer->id }}" class="select2 form-control" multiple="multiple">
                                                        @forelse(collect(explode('|',$key))->unique()->toArray() as $key_now)
                                                            <option  @if(strpos($answer->tid,$key_now)!==false) selected @endif value="{{ $key_now }}">{{ $key_now }}</option>
                                                        @endforeach
                                                    </select>
                                                </a>
                                            @endforeach
                                            </div>
                                            <div class="panel-footer">
                                                @forelse(explode('|',$key) as $single_tid)
                                                    <a class="btn btn-danger confirm_single_done" data-related="{{ $key }}" data-tid="{{ $single_tid }}">{{ $single_tid }}选取完成</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                            @endforeach


                        </div>
                    </div>
                </div>
                <div>
                    @if(count($data['all_isbn'])>0)
                    {{ $data['all_isbn']->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>
    {{--<script src="{{ asset('js/magnify/js/jquery.magnify.js') }}"></script>--}}
    <script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
    <script  src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
    <script>
        const now_type = '{{ $data['type'] }}';
        $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                    '今天'       : [moment(), moment()],
                    '昨天'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '最近7天' : [moment().subtract(6, 'days'), moment()],
                    '最近30天': [moment().subtract(29, 'days'), moment()],
                    '本月'  : [moment().startOf('month'), moment().endOf('month')],
                    '上个月'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens : 'right', //日期选择框的弹出位置
                startDate: moment("{{$data['start']}}").utc().subtract(-1,'days'),
                endDate  : moment("{{$data['end']}}").utc().subtract(-1,'days'),
                format : 'YYYY/MM/DD', //控件中from和to 显示的日期格式
                locale : {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '起始时间',
                    toLabel : '结束时间',
                    customRangeLabel : '自定义',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                        '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                    firstDay : 1
                }
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('YYYY/MM/DD') + ' ~ ' + end.format('YYYY/MM/DD'));
                window.location.href = '{{ route('manage_new_oss') }}/'+now_type+'/'+start.format("YYYY-MM-DD")+"/"+end.format("YYYY-MM-DD");
            }
        )
    </script>

    <script>
        $(function () {
            var lazy = new LazyLoad();
            var lazyLoadInstances = [];
            var lazyLoadInstances1 = [];
            var lazyLazy = new LazyLoad({
                elements_selector: ".answer_box",
                callback_set: function(el) {
                    var oneLL = new LazyLoad({
                        container: el
                    });
                    lazyLoadInstances.push(oneLL);
                }
            });
            var lazyLazy1 = new LazyLoad({
                elements_selector: ".like_answer_box .panel-body",
                callback_set: function(el) {
                    var oneLL = new LazyLoad({
                        container: el
                    });
                    lazyLoadInstances1.push(oneLL);
                }
            });
            $('.select2').select2();
            $('.select2').change(function () {
               $(this).parents('.book_info_box').find('.generate_name').click();
            });
//            //放大镜
//            $('.zoom').magnify();
            $('.show_big').click(function () {
                let data_id = $(this).parents('.single_box_info').attr('data-id');
                let now_value = $(this).parents('.single_box_info').find('input').val();
                $('#show_img').modal('show');
                $('#show_img #modify_footer').attr('data-id',data_id);
                $('#show_img #modify_footer input').val(now_value);
                $('#show_img .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
            });

            //保存isbn
            $(document).on('click','.add_isbn',function () {
                let box = $(this).parents('.book_info_box');
                let book_id = $(this).parent().attr('data-id');
                let isbn = $(this).prev().val();
                let now_type = $(this).attr('data-type');
                axios.post('{{ route('book_new_isbn_api','save_isbn') }}', {book_id, isbn,now_type}).then(response => {
                    if(response.data.status===0){
                        box.find('.for_isbn_input').parent().css({'border':'1px solid red'});
                    }else{
                        box.find('.for_isbn_input').parent().css({'border':'1px solid blue'});
                    }
                }).catch();
            });


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

            //系列选择获取对应旧版练习册
            $(document).on('change', '.sort_name,.related_sort,.grade_id,.subject_id,.volumes_id,.version_id', function () {
                let box = $(this).parents('.book_info_box');
                if($(this).attr('no_change')==='1'){
                    return false;
                }
                if($(this).hasClass('sort_name') && box.find('.related_sort').val()!=="-999"){
                    box.find('.related_sort').attr('no_change','1').val(-999).trigger('change');
                    box.find('.related_sort').removeAttr('no_change');
                }else if($(this).hasClass('related_sort') && box.find('.sort_name').val()!=="-999"){
                    //box.attr('not_relating','1');
                    box.find('.sort_name').attr('no_change','1').val(-999).trigger('change');
                    box.find('.sort_name').removeAttr('no_change');
                }else{}

                let sort_id = box.find('.related_sort').val();
                if(sort_id<=0){
                    sort_id = box.find('.sort_name').val();
                }
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                axios.post('{{ route('manage_new_api','get_related_book') }}', {sort_id,grade_id,subject_id}).then(response => {
                    if (response.data.status === 1) {
                        let related_books = response.data.related_book;
                        box.find(`.old_related_box`).remove();
                        box.append(`<div class="input-group old_related_box pull-left" style="width: 50%"></div>`);
                        if (related_books.length > 1) {
                            for (let book of related_books) {
                                box.find('.old_related_box').append(`<p class="badge bg-blue old_names">${book.newname}</p>`)
                            }
                        } else {
                            box.find('.old_related_box').append(`<p class="badge bg-red">暂无对应旧版练习册</p>`)
                        }
                        //box.find('.old_related_box .select2').select2();
                    }
                }).catch(function (error) {
                        console.log(error);
                });
            });

            //旧版书更换
            $(document).on('click','.old_names',function () {
               let box = $(this).parents('.book_info_box');
               let version_year = box.find('.version_year').val();
               box.find('.now_name').val(version_year+'年'+$(this).html());
            });


            //间隔
            //$('.for_isbn_input')
            $('.for_isbn_input').bind('input propertychange', function() {
                if($(this).val().length===3){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length===5){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length>6) {
                    let now_start = $(this).val()[6];
                    if (now_start <= 3) {
                        if ($(this).val().length === 9) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start > 3 && now_start <= 5) {
                        if ($(this).val().length === 10) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start>=6 && now_start <= 8) {
                        console.log($(this).val().length);
                        if ($(this).val().length === 11) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '9') {
                        if ($(this).val().length === 12) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    }
                    if ($(this).val().length === 15) {
                        $(this).val($(this).val() + '-');
                        this.selectionStart = this.selectionEnd = this.value.length + 1
                    }

                    if($(this).val().length===17){
                        let box = $(this).parents('.book_info_box');
                        box.find('.add_isbn').click();
                        let isbn = $(this).val();
                        //get_related_sort
                        axios.post('{{ route('manage_new_api','get_related_sort') }}',{isbn}).then(response=>{
                            if(response.data.status===1){
                                let related_sort = response.data.related_sort;
                                if(related_sort.length>0){
                                    box.find(".for_relate_sort").html('');
                                    box.find('.for_relate_sort').append('<select class="form-control related_sort select2"><option value="-999" selected>选择系列</option></select>');
                                    for(let item of related_sort){
                                        $('.related_sort').append(`<option value="${item.id}">${item.name}</option>`)
                                    }
                                    $('.related_sort').select2();
                                }

                            }
                        }).catch(function () {

                        })
                    }

                }
            });

            //排序
            $( ".answer_box" ).sortable();

            //交换位置
            $(document).on('click','.exchange',function () {
                let move_type = $(this).attr('data-type');
                if (move_type === 'right') {
                    $(this).parent().next().insertBefore($(this).parent());
                } else {
                    $(this).parent().insertBefore($(this).parent().prev());
                }
            });
            //移除
            $(document).on('click','.delete_this',function () {
                if(confirm('确认移除此图片')){
                    $(this).parent().remove();
                }
            });

            //生成书名
            $('.generate_name').click(function () {
                let version_year = $(this).parents('.book_info_box').find('.version_year').val();
                let sort_name = '';
                if($(this).parents('.book_info_box').find('.sort_name').select2('data')[0].id>0){
                    let now_name = $(this).parents('.book_info_box').find('.sort_name').select2('data')[0].name;
                    let now_text = $(this).parents('.book_info_box').find('.sort_name').select2('data')[0].text;
                    if(now_name!==undefined){
                        sort_name = now_name
                    }else{
                        sort_name = now_text
                    }
                }else{
                    sort_name = $(this).parents('.book_info_box').find('.related_sort option:selected').text();
                }
                let grade_name = $(this).parents('.book_info_box').find('.grade_id option:selected').text();
                let subject_name = $(this).parents('.book_info_box').find('.subject_id option:selected').text();
                let volume_name = $(this).parents('.book_info_box').find('.volumes_id option:selected').text();
                let version_name = $(this).parents('.book_info_box').find('.version_id option:selected').text();
                let book_name = version_year+'年'+sort_name+grade_name+subject_name+volume_name+version_name;

                book_name = book_name.replace('上册','下册');
                book_name = book_name.replace('全一册上','全一册下');
                book_name = book_name.replace('全一册','全一册下');
                book_name = book_name.replace('思想品德','道德与法治');
                $(this).prev().val(book_name);
            });

            //完成所有信息
            $('.confirm_book_done').click(function () {
                if(!confirm('确认完成编辑')){
                    return false;
                }
                let single_box_info = $(this).parents('.single_box_info');
                let now_id = single_box_info.attr('data-id');
                let bookname = single_box_info.find('.now_name').val();
                bookname = bookname.replace('上册','下册');
                bookname = bookname.replace('全一册上','全一册下');
                bookname = bookname.replace('全一册','全一册下');
                bookname = bookname.replace('思想品德','道德与法治');
                let version_year = single_box_info.find('.version_year').val();
                let sort_id = -999;
                if(single_box_info.find('.sort_name').val()>0){
                    sort_id = single_box_info.find('.sort_name').val();
                }else if(single_box_info.find('.related_sort').val()>0){
                    sort_id = single_box_info.find('.related_sort').val();
                }else{
                    alert('请选择系列');return false;
                }
                let grade_id = single_box_info.find('.grade_id').val();
                let subject_id = single_box_info.find('.subject_id').val();
                let volume_id = single_box_info.find('.volumes_id').val();
                let version_id = single_box_info.find('.version_id').val();
                let isbn = single_box_info.find('.for_isbn_input').val();
                let answer_all = [];
                single_box_info.find('.real_pic').each(function (i) {
                    answer_all[i] = $(this).attr('data-id');
                });
                if(sort_id<=0){
                    alert('系列未选择');
                    return false;
                }
                if(isbn.length!==17){
                    alert('isbn未填写完整');
                    return false;
                }
                //single_box_info.find('.add_isbn').click();
                axios.post('{{ route('manage_new_api','confirm_done') }}',{now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all}).then(response=>{
                    if(response.data.status===1){
                        single_box_info.remove();
                    }else{
                        alert(response.data.msg);
                    }
                }).catch(function () {});

            });

            //确认答案信息
            $('.confirm_answer_done').click(function () {
                if(!confirm('确认完成编辑')){
                    return false;
                }
                let single_box_info = $(this).parents('.single_box_info');
                let answer_box = $(this).prev();
                let now_id = single_box_info.attr('data-id');
                let now_tid = answer_box.attr('data-tid');
                let answer_all = [];
                single_box_info.find('.real_pic').each(function (i) {
                    answer_all[i] = $(this).attr('data-id');
                });
                axios.post('{{ route('manage_new_api','confirm_answer_done') }}',{now_id,now_tid,answer_all}).then(response=>{
                    if(response.data.status===1){
                        single_box_info.remove();
                    }else{
                        alert(response.data.msg);
                    }
                }).catch(function () {});
            });

            //审核通过
            $(document).on('click','.verify_confirm',function () {
                let now_id = $(this).attr('data-book-id');
                axios.post('{{ route('manage_new_api','verify_done') }}',{now_id}).then(response=>{
                    if(response.data.status===1){
                        $(this).parent().html(`
                           <i class="badge bg-blue">已审核：{{ date('Y-m-d H',time()) }}</i>
                           `)
                    }
                }).catch()
            });
            //标记答案不全
            $(document).on('click','.mark_answer',function () {
                if(!confirm('确认操作')){
                    return false;
                }
                let now_id = $(this).parents('.single_box_info').attr('data-id');
                axios.post('{{ route('manage_new_api','mark_answer') }}',{now_id}).then(response=>{
                    if(response.data.status===1){
                        if($(this).hasClass('btn-primary')){
                            $(this).removeClass('btn-primary').addClass('btn-danger').html('已标记此练习册答案不全');
                        }else{
                            $(this).removeClass('btn-danger').addClass('btn-primary').html('标记此练习册答案不全');
                        }

                    }
                }).catch(function () {})
            });

            //跳转
            $('#to_book_id').click(function () {
                let book_id = $(this).prev().val();
                window.open('{{ route('manage_new_oss','done') }}'+'/'+book_id);
            });

            //更改所有
            $('.change_all_value').click(function () {
                let grade_id = parseInt($(this).attr('data-grade'));
                let subject_id = parseInt($(this).attr('data-subject'));
                let volumes_id = parseInt($(this).attr('data-volumes'));
                let version_id = parseInt($(this).attr('data-version'));
                let box = $(this).parents('.book_info_box');
                box.find('.grade_id').attr('no_change','1');
                box.find('.subject_id').attr('no_change','1');
                box.find('.volumes_id').attr('no_change','1');
                box.find('.grade_id').val(grade_id).trigger('change');
                box.find('.subject_id').val(subject_id).trigger('change');
                box.find('.volumes_id').val(volumes_id).trigger('change');
                box.find('.grade_id').removeAttr('no_change');
                box.find('.subject_id').removeAttr('no_change');
                box.find('.volumes_id').removeAttr('no_change');
                box.find('.version_id').val(version_id).trigger('change');
                let version_year = box.find('.version_year').val();
                let now_name = $(this).html();
                now_name = now_name.replace('2014年','');
                now_name = now_name.replace('2015年','');
                now_name = now_name.replace('2016年','');
                now_name = now_name.replace('2017年','');
                now_name = now_name.replace('2013年','');
                box.find('.now_name').val(version_year+'年'+now_name);

            });

            //保存系列
            $('.save_sort_single').click(function () {
                let box = $(this).parents('.single_box_info');
                let book_id = box.attr('data-id');
                let sort_id = -1;
                if(box.find('.sort_name').val()>0){
                    sort_id = box.find('.sort_name').val();
                }else if(box.find('.related_sort').val()>0){
                    sort_id = box.find('.related_sort').val();
                }else{
                    alert('请选择系列');return false;
                }
                axios.post('{{ route('manage_new_api','save_sort') }}',{book_id,sort_id}).then(response=>{
                    if(response.data.status===1){
                        alert('保存成功');
                    }else{
                        alert('保存失败');
                    }
                })
            });

            //
            $(document).on('click','.like_isbn',function () {
              let box = $(this).parents('.book_info_box');
              let isbn = $(this).attr('data-isbn');
              box.find('.for_isbn_input').val(isbn);
            });

            //显示答案
            $('.show_answer').click(function () {
                if($(this).html()=='显示答案'){
                    $(this).html('隐藏答案');
                    $(this).next().find('.answer_box').css({'display':'flex'});
                }else{
                    $(this).html('显示答案');
                    $(this).next().find('.answer_box').css({'display':'none'});
                }

            });

            //选择答案
            $('.choose_one_answer').click(function () {
                if(!confirm('确认选择答案')){
                    return false;
                }
                let now_id = $(this).attr('data-id');
                let now_tid = $(this).attr('data-tid');
                axios.post('{{ route('manage_new_api','choose_answer') }}',{now_id,now_tid}).then(response=>{
                    if(response.data.status===1){
                        $(`.single_box_info[data-id=${now_id}]`).remove();
//                        let all_other_ids = response.data.other_id.split('|');
//                        let all_other_ids_len = all_other_ids.length;
//                        for(let i =0;i<all_other_ids_len;i++){
//                            $(`.single_box_info[data-id=${response.data.other_id}]`).remove();
//                        }
                        alert('保存成功');
                    }else{
                        alert('保存失败');
                    }
                })
            });

            //显示答案
            if(now_type==='book_problem' || now_type==='answer_problem'){
                $('.show_answer').click();
            }

            //标记答案移除
            $('.mark_this').click(function () {
                if($(this).hasClass('btn-primary')){
                    $(this).removeClass('btn-primary').addClass('btn-danger').html('已标记');
                }else{
                    $(this).removeClass('btn-danger').addClass('btn-primary').html('标记移除');
                }
            });

        //提交答案
        {{--$('.confirm_like_answer').click(function () {--}}
            {{--if(!confirm('确认移除上述标记答案')){--}}
                {{--return false;--}}
            {{--}--}}
            {{--let tid = $(this).attr('data-tid');--}}
            {{--let answers_del = [];--}}
            {{--$('.mark_this.btn-danger').each(function (i) {--}}
                {{--answers_del.push($(this).attr('data-id'));--}}
            {{--});--}}
            {{--axios.post('{{ route('manage_new_api','confirm_like_answer') }}',{tid,answers_del}).then(response=>{--}}
                {{--if(response.data.status===1){--}}
                    {{--window.location.reload();--}}
                {{--}else{--}}
                    {{--alert(response.data.msg);--}}
                {{--}--}}
            {{--}).catch(function () {});--}}
        {{--});--}}

        //提取单个tid
        $('.confirm_single_done').click(function () {
            let now_related = $(this).attr('data-related');
            let now_tid = $(this).attr('data-tid');
            let now_ids = [];
            let not_finish = 0;
            $(this).parents('.like_answer_box').find('select').each(function () {
                console.log($(this).val());
                let now_tids = $(this).val();
                if(now_tids.length===1 && now_tids[0]===now_tid){
                    console.log('bingo');
                    now_ids.push($(this).attr('data-id'));
                }
                if(now_tids.length>1 && $.inArray(now_tid,now_tids)>=0){
                    not_finish = 1;
                }
            });
            if(not_finish===1){
                alert('当前tid还有多种混合未处理');
                return false;
            }
            if(now_ids.length<1){
                alert('当前无图片属于该tid');
                return false;
            }
            if(!confirm('确认提取操作')){
                return false;
            }
            axios.post('{{ route('manage_new_api','confirm_single_done') }}',{now_tid,now_related,now_ids}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                }else{
                    alert(response.data.msg);
                }
            }).catch(function () {});
        });

        //混合tid
        $('.mix_tids').click(function () {
            let now_tids = $(this).prev().val();
            if(now_tids.indexOf(',')<=0){
                alert('请按规范分割tid');
                return false;
            }
            if(!confirm('确认混合答案集合')){
                return false;
            }
            axios.post('{{ route('manage_new_api','mix_tids') }}',{now_tids}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                }else{
                    alert(response.data.msg);
                }
            }).catch(function () {});
        })
    });

    </script>

@endpush