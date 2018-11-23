@extends('layouts.backend')

@section('manage_new_local_answer','active')

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
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>

                    <select class="select2" id="answer_sort_dict">
                        <option value="0">选择答案系列</option>
                        @forelse($data['all_sorts'] as $sort)
                            <option value="{{ $sort->sort_name }}">{{ $sort->sort_name }}</option>
                        @endforeach
                    </select>
                <div class="input-group" style="width: 100%">
                    <select class="form-control related_sort select2" style="width: 50%">
                        @if(count($data['related_sorts'])===1)
                            @forelse($data['related_sorts'] as $key=>$value)
                                <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        @else
                        <option value="-999" selected>选择系列</option>
                            @forelse($data['related_sorts'] as $key=>$value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <select class="form-control sort_name click_to select2" style="width: 50%">
                        <option value="-999">搜索并选择录入系列</option>
                    </select>
                </div>

            </div>

            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li @if($data['type_now']==='pending') class="active" @endif><a href="{{ route('manage_new_local_list',[$data['now_dir'],'pending']) }}">未整理</a></li>
                        <li @if($data['type_now']==='done') class="active" @endif><a href="{{ route('manage_new_local_list',[$data['now_dir'],'done']) }}">已整理</a></li>
                        @can('lxc_verify')
                            <li class="hide"><a target="_blank" href="{{ route('manage_new_oss_status') }}">整理状况查看</a></li>
                        @endcan
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            @forelse($data['now_books'] as $key => $cover_cip)
                                <div class="single_box_info col-md-12" data-id="{{ $key }}" @if($data['all_book_info'][$loop->index]) style="border: 4px solid green;" @endif>

                                    <div class="box-body">
                                        <div class="col-md-5 book_info_box">

                                            <div class="input-group">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" maxlength="4" class="form-control version_year" value="{{ $data['all_book_info'][$loop->index]?$data['all_book_info'][$loop->index]->version_year:'2018' }}">
                                            </div>
                                            <div class="input-group for_relate_sort hide">

                                            </div>


                                            <br>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade" class="grade_id form-control select2 pull-left " >
                                                    @forelse(config('workbook.grade') as $key => $grade)
                                                        @if($key>=1)
                                                        <option value="{{ $key }}" @if($data['all_book_info'][$loop->parent->index]) @if($data['all_book_info'][$loop->parent->index]->grade_id==$key) selected="selected" @endif @endif>{{ $grade }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2">
                                                    @forelse(config('workbook.subject_1010') as $key => $subject)
                                                        <option value="{{ $key }}" @if($data['all_book_info'][$loop->parent->index]) @if($data['all_book_info'][$loop->parent->index]->subject_id==$key) selected="selected" @endif @endif>{{ $subject }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    @forelse(config('workbook.volumes') as $key => $volume)
                                                        @if($key>=1)
                                                        <option value="{{ $key }}"
                                                                @if($data['all_book_info'][$loop->parent->index])       @if($data['all_book_info'][$loop->parent->index]->volumes_id==$key) selected="selected" @endif
                                                                @else
                                                                @if($key==2) selected="selected" @endif
                                                            @endif>{{ $volume }}</option>
                                                        @endif
                                                     @endforeach
                                                </select>
                                            </div>

                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">版本</label>
                                                <select data-name="version" class="version_id form-control select2">
                                                    @forelse(cache('all_version_now') as $key => $version)
                                                        @if($version->id>=0)
                                                        <option value="{{ $version->id }}" @if($data['all_book_info'][$loop->parent->index]) @if($data['all_book_info'][$loop->parent->index]->version_id==$version->id) selected="selected" @endif @endif>{{ $version->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <textarea class="hide refer_isbn form-control" cols="30" rows="3">参考isbn</textarea>
                                            <div class="input-group" style="width: 100%">

                                                <input maxlength="17" class="for_isbn_input form-control input-lg" style="font-size: 24px"  @if($data['all_book_info'][$loop->index]) @if($data['all_book_info'][$loop->index]->isbn) value="{{ convert_isbn($data['all_book_info'][$loop->index]->isbn) }}" @endif @else value="{{ $data['all_isbn'][$loop->index]?convert_isbn($data['all_isbn'][$loop->index]):'978-7-' }}" @endif />
                                                <a class="btn btn-danger input-group-addon add_isbn">保存isbn</a>
                                            </div>

                                            <div class="input-group">
                                                <textarea type="text" class="form-control now_name" rows="3">@if($data['all_book_info'][$loop->index]) {{ $data['all_book_info'][$loop->index]->bookname  }} @endif</textarea>
                                                <a class="btn btn-primary input-group-addon generate_name">生成</a>
                                            </div>
                                            <div class="all_other_info">

                                            </div>
                                        </div>
                                        <div class="clo-md-6">
                                            <div class="col-md-6">
                                                <div id="myCarousel_{{ $loop->index }}" class="clear carousel slide" data-interval="false">
                                                    <div class="carousel-inner" >
                                                        @forelse($cover_cip as $item)
                                                            <div class="item @if ($loop->first) active  @endif">
                                                            <a style="overflow-x: scroll" class="thumbnail show_big">
                                                                <img @if($loop->first) class="book_cover" @else class="book_cip" @endif data-pathname="{{ substr($item->getpathname(),26) }}" src="{{ 'http://192.168.0.117/bookcover/'.substr($item->getpathname(),26) }}" alt="{{ substr($item->getpathname(),26) }}">
                                                            </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <a class="carousel-control  left" href="#myCarousel_{{ $loop->index }}"
                                                       data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                    <a class="carousel-control right" href="#myCarousel_{{ $loop->index }}"
                                                       data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="btn btn-group">
                                            <a class="btn btn-danger change_position" data-type="1423">1423(顺序调整)</a>
                                        </div>
                                        <div class="answer_box" style="display: flex;overflow: auto">
                                            @if($data['all_book_answer'][$loop->index])
                                                @forelse($data['all_book_answer'][$loop->index] as $answer)
                                                    <a class="thumbnail"><img data-id="{{ $answer->id }}" class="answer_pic real_pic" data-original="{{ 'http://192.168.0.117/book/'.substr($answer->answer,21) }}" alt=""><i class="badge bg-blue delete_this">移除</i><i class="badge bg-red exchange" data-type="left">与左图交换</i><i class="badge bg-red exchange" data-type="right">与右图交换</i></a>
                                                @endforeach
                                            @endif
                                        </div>
                                        <a class="btn btn-danger confirm_book_done btn-lg btn-block">确认练习册和答案信息无误</a>
                                    </div>
                                </div>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    {{--<script src="{{ asset('js/magnify/js/jquery.magnify.js') }}"></script>--}}
    <script>
        $(function () {
            var lazy = new LazyLoad();
            var lazyLoadInstances = [];
            var lazyLazy = new LazyLoad({
                elements_selector: ".answer_box",
                callback_set: function(el) {
                    var oneLL = new LazyLoad({
                        container: el
                    });
                    lazyLoadInstances.push(oneLL);
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
                $('#show_big_photo').modal('show');
                $('#show_big_photo #modify_footer').attr('data-id',data_id);
                $('#show_big_photo #modify_footer input').val(now_value);
                $('#show_big_photo .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
            });

            //保存isbn
            $(document).on('click','.add_isbn',function () {
                let book_id = $(this).parent().attr('data-id');
                let isbn = $(this).prev().val();
                axios.post('{{ route('book_new_isbn_api','save_isbn') }}', {book_id, isbn}).then(response => {
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
                if(box.attr('not_relating')=='1'){
                    return false;
                }
                if($(this).hasClass('sort_name') && $('.box-header').find('.related_sort').val()!=="-999"){
                    box.find('.related_sort').val(-999).trigger('change');
                }else if($(this).hasClass('related_sort') && $('.box-header').find('.sort_name').val()!=="-999"){
                    $('.box-header').find('.sort_name').val(-999).trigger('change');
                }else{}
                let sort_id = $('.box-header').find('.related_sort').val();
                if(sort_id<=0){
                    sort_id = $('.box-header').find('.sort_name').val();
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
                    } else if (now_start === '8') {
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
                        $('.add_isbn').click();
                        let box = $(this).parents('.book_info_box');
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
                if($('.box-header').find('.sort_name').select2('data')[0].id>0){
                    let now_name = $('.box-header').find('.sort_name').select2('data')[0].name;
                    let now_text = $('.box-header').find('.sort_name').select2('data')[0].text;
                    if(now_name!==undefined){
                        sort_name = now_name
                    }else{
                        sort_name = now_text
                    }
                }else{
                    sort_name = $('.box-header').find('.related_sort option:selected').text();
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
            //1.更新a_workbook_1010_db
            //2.更新a_workbook_answer_1010bd
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
                if($('.box-header').find('.sort_name').val()>0){
                    sort_id = $('.box-header').find('.sort_name').val();
                }else if($('.box-header').find('.related_sort').val()>0){
                    sort_id = $('.box-header').find('.related_sort').val();
                }else{
                    alert('请选择系列');return false;
                }
                let grade_id = single_box_info.find('.grade_id').val();
                let subject_id = single_box_info.find('.subject_id').val();
                let volume_id = single_box_info.find('.volumes_id').val();
                let version_id = single_box_info.find('.version_id').val();
                let isbn = single_box_info.find('.for_isbn_input').val();

                let answer_choose_btn = single_box_info.find('.choose_book.active');
                let answer_grade_name = answer_choose_btn.attr('data-grade_name');
                let answer_subject_name = answer_choose_btn.attr('data-subject_name');
                let answer_sort = $('#answer_sort_dict option:selected').text();
                let answer_version =answer_choose_btn.attr('data-version');

                if(answer_choose_btn.length==0 || answer_grade_name.length==0 || answer_subject_name.length==0 || answer_sort.length==0 || answer_version.length==0){
                    return false;
                }


                let cover_photo = single_box_info.find('.book_cover').attr('data-pathname');
                let cip_photo = '';
                if(single_box_info.find('.book_cip').length>0){
                    cip_photo = single_box_info.find('.book_cip').attr('data-pathname')
                }else{
                    cip_photo = cover_photo
                }

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
                axios.post('{{ route('manage_new_local_api','confirm_done') }}',{now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all,answer_grade_name,answer_subject_name,answer_sort,answer_version,cover_photo,cip_photo}).then(response=>{
                    if(response.data.status===1){
                        single_box_info.remove();
                    }else{
                        alert(response.data.msg);
                    }
                }).catch(function () {});

            });

            //审核通过
            {{--$(document).on('click','.verify_confirm',function () {--}}
                {{--let now_id = $(this).attr('data-book-id');--}}
                {{--axios.post('{{ route('manage_new_api','verify_done') }}',{now_id}).then(response=>{--}}
                    {{--if(response.data.status===1){--}}
                        {{--$(this).parent().html(`--}}
                           {{--<i class="badge bg-blue">已审核：{{ date('Y-m-d H',time()) }}</i>--}}
                           {{--`)--}}
                    {{--}--}}
                {{--}).catch()--}}
            {{--})--}}
            //标记答案不全
            {{--$(document).on('click','.mark_answer',function () {--}}
                {{--if(!confirm('确认操作')){--}}
                    {{--return false;--}}
                {{--}--}}
                {{--let now_id = $(this).parents('.single_box_info').attr('data-id');--}}
                {{--axios.post('{{ route('manage_new_api','mark_answer') }}',{now_id}).then(response=>{--}}
                    {{--if(response.data.status===1){--}}
                        {{--if($(this).hasClass('btn-primary')){--}}
                            {{--$(this).removeClass('btn-primary').addClass('btn-danger').html('已标记此练习册答案不全');--}}
                        {{--}else{--}}
                            {{--$(this).removeClass('btn-danger').addClass('btn-primary').html('标记此练习册答案不全');--}}
                        {{--}--}}

                    {{--}--}}
                {{--}).catch(function () {})--}}
            {{--});--}}

            //跳转
            {{--$('#to_book_id').click(function () {--}}
                {{--let book_id = $(this).prev().val();--}}
                {{--window.open('{{ route('manage_new_oss','done') }}'+'/'+book_id);--}}
            {{--});--}}




            //练习册封面目录
            $('#answer_sort_dict').change(function () {
                let sort_name = $(this).val();
                axios.post('{{ route('manage_new_local_api','get_sort') }}',{sort_name}).then(response=>{
                    if(response.data.status===1){
                        $('.all_other_info').each(function () {
                            $(this).html(response.data.sort_info);
                        })
                    }
                }).catch(function () {});
            });

            //按钮切换
            $(document).on('click','.choose_book',function () {
                let now_box = $(this).parents('.book_info_box');
                now_box.attr('not_relating',1);
                now_box.find('.choose_book').each(function () {
                    $(this).removeClass('active');
                });
                $(this).addClass('active');
                let sort_id=0;
                let sort_name = '';
                if($('.box-header').find('.sort_name').val()>0){
                    sort_id = $('.box-header').find('.sort_name').val();
                    sort_name = $('.box-header').find('.sort_name option:selected').text();
                }else if($('.box-header').find('.related_sort').val()>0){
                    sort_id = $('.box-header').find('.related_sort').val();
                    sort_name = $('.box-header').find('.related_sort option:selected').text();
                }else{
                    alert('请选择系列');return false;
                }
               let subject_id = $(this).attr('data-subject');
                let grade_id = $(this).attr('data-grade');
                let answer_grade_name = $(this).attr('data-grade_name');
                let answer_subject_name = $(this).attr('data-subject_name');
                let version_id = $(this).attr('data-version_id');
                let version_name = $(this).attr('data-version');
                let version_year = $(this).parents('.book_info_box').find('.version_year').val();
                now_box.find('.grade_id').val(grade_id).trigger('change');
                now_box.find('.subject_id').val(subject_id).trigger('change');
                now_box.attr('not_relating',0);
                now_box.find('.version_id').val(version_id).trigger('change');

                let grade_name = now_box.find('.grade_id option:selected').text();
                let subject_name = now_box.find('.subject_id option:selected').text();
                let volume_name = now_box.find('.volumes_id option:selected').text();
                let book_name = version_year+'年'+sort_name+grade_name+subject_name+volume_name+version_name;

                book_name = book_name.replace('上册','下册');
                book_name = book_name.replace('全一册上','全一册下');
                book_name = book_name.replace('全一册','全一册下');
                book_name = book_name.replace('思想品德','道德与法治');
                now_box.find('.now_name').val(book_name);

                let answer_sort = $('#answer_sort_dict option:selected').text();
                let answer_version =version_name;

                axios.post('{{ route('manage_new_local_api','get_answer') }}',{answer_sort,answer_version,answer_grade_name,answer_subject_name}).then(response=>{
                    if(response.data.status===1){
                        $(this).parents('.single_box_info').find('.answer_box').html(response.data.answer_info);
                    }
                }).catch();
                event.preventDefault();
            });

        //调整答案顺序
            $(document).on('click','.change_position',function () {
                if(!confirm('确认调整顺序')){
                    return false;
                }
                let box = $(this).parents('.single_box_info');
               let answer_box = box.find('.answer_box');
               let pos_type = $(this).attr('data-type');
               if(answer_box.length>0){
                   if(pos_type=='1423'){
                       answer_box.find('img').each(function (i) {
                           if(i%4==2){
                               $(this).parent().insertBefore($(this).parent().prev());
                           }
                           if(i%4==3){
                               $(this).parent().insertBefore($(this).parent().prev());
                           }
                       })
                   }
               }
            });
        });

    </script>

@endpush