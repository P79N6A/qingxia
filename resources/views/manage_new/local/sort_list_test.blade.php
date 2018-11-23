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
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
            </div>

            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li @if($data['type']==='local_dir') class="active" @endif><a href="{{ route('manage_new_local_test_list',[$data['sort'],'local_dir']) }}">已买</a></li>
                        <li @if($data['type']==='pending') class="active" @endif><a href="{{ route('manage_new_local_test_list',[$data['sort'],'pending']) }}">已录</a></li>
                        <li @if($data['type']==='done') class="active" @endif><a href="{{ route('manage_new_local_test_list',[$data['sort'],'done']) }}">已上</a></li>
                        @can('lxc_verify')
                            <li class="hide"><a target="_blank" href="{{ route('manage_new_oss_status') }}">整理状况查看</a></li>
                        @endcan
                    </ul>
                    <div class="tab-content">
                        <div class="btn btn-group">
                            @forelse($data['now_books'] as $now_book)
                                <a class="btn btn-default" href="#book_{{ $now_book->id }}">{{ $now_book->bookname }}</a>
                            @endforeach
                        </div>
                        <div class="tab-pane active">
                            @forelse($data['now_books'] as $now_book)
                                <div class="single_box_info col-md-12" data-id="{{ $now_book->id }}" id="book_{{ $now_book->id }}">

                                    <div class="box-body">
                                        <div class="col-md-5 book_info_box">
                                            <a>{{ $now_book->bookname }}  id:{{ $now_book->id }}</a>
                                            @if($data['type']==='pending')
                                                <a class="btn btn-danger move_to_local" data-id="{{ $now_book->id }}">重新整理</a>
                                            @endif
                                            @if($data['type']==='local_dir')
                                            <h4>答案地址：{{ $now_book->answer_dir }}</h4>
                                            <h4>封面地址：{{ $now_book->cip_dir }}</h4>
                                            @endif
                                            <div class="input-group">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" maxlength="4" class="form-control version_year" value="{{ $now_book->version_year?$now_book->version_year:'2018' }}" />
                                            </div>
                                            <div class="input-group for_relate_sort hide">

                                            </div>
                                            <div class="input-group">
                                                <label class="input-group-addon">系列</label>
                                                <select data-name="sort" class="form-control sort_name select2">
                                                    <option value="{{ $now_book->sort }}">{{ $data['sort_name'] }}</option>
                                                </select>
                                            </div>

                                            <br>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade" class="grade_id form-control select2 pull-left " >
                                                    @forelse(config('workbook.grade') as $key => $grade)
                                                        @if($key>=1)
                                                        <option value="{{ $key }}" @if($now_book->grade_id==$key) selected="selected" @endif>{{ $grade }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2">
                                                    @forelse(config('workbook.subject_1010') as $key => $subject)
                                                        <option value="{{ $key }}" @if($now_book->subject_id==$key) selected="selected" @endif>{{ $subject }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    @forelse(config('workbook.volumes') as $key => $volume)
                                                        @if($key>=1)
                                                        <option value="{{ $key }}"
                                                                @if($now_book->volumes_id==$key) selected="selected" @endif >{{ $volume }}</option>
                                                        @endif
                                                     @endforeach
                                                </select>
                                            </div>

                                            <div class="input-group pull-left" style="width: 50%">
                                                <label class="input-group-addon">版本</label>
                                                <select data-name="version" class="version_id form-control select2">
                                                    @forelse(cache('all_version_now') as $key => $version)
                                                        @if($version->id>=0)
                                                        <option value="{{ $version->id }}" @if($now_book->version_id==$version->id) selected="selected" @endif>{{ $version->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group" style="width: 100%">

                                                <input maxlength="17" class="for_isbn_input form-control input-lg" style="font-size: 24px"  value="{{ $now_book->isbn?convert_isbn($now_book->isbn):'978-7-' }}" />
                                                <a class="btn btn-danger input-group-addon add_isbn">保存isbn</a>
                                            </div>

                                            <div class="input-group">
                                                <textarea type="text" class="form-control now_name" rows="3">{{ $now_book->bookname  }}</textarea>
                                                <a class="btn btn-primary input-group-addon generate_name">生成</a>
                                            </div>
                                            <div class="all_other_info">

                                            </div>
                                        </div>
                                        <div class="clo-md-6">
                                            <div class="col-md-6">
                                                <div id="myCarousel_{{ $now_book->id }}" class="clear carousel slide" data-interval="false">
                                                    <div class="carousel-inner" >

                                                            <div class="item active">
                                                            <a style="overflow-x: scroll" class="thumbnail show_big">
                                                                <img  class="book_cover" data-pathname="{{ $now_book->cover_photo }}" src="{{ $now_book->cover }}" >
                                                            </a>
                                                            </div>
                                                        <div class="item">
                                                            <a style="overflow-x: scroll" class="thumbnail show_big">
                                                                <img class="book_cip" data-pathname="{{ $now_book->cip_photo }}" src="{{ $now_book->cip_photo }}">
                                                            </a>
                                                        </div>

                                                    </div>
                                                    <a class="carousel-control  left" href="#myCarousel_{{ $now_book->id }}"
                                                       data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                    <a class="carousel-control right" href="#myCarousel_{{ $now_book->id }}"
                                                       data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="input-group" style="width:30%">
                                            <input type="text" value="1423" class="form-control">
                                            <a class="input-group-addon change_position">调整顺序</a>
                                            {{--<a class="btn btn-danger change_position" data-type="1423">1423(顺序调整)</a>--}}
                                        </div>
                                        <div class="answer_box" style="display: flex;overflow: auto">
                                            @if($data['type']=='local_dir')
                                                @forelse($now_book->has_answers as $key => $answer)
                                                    <a class="thumbnail"><img data-id="{{ $key }}" class="answer_pic real_pic" data-original="{{ 'http://192.168.0.117/book4_new/'.substr($answer,22) }}" alt=""><i class="badge bg-blue delete_this">移除</i><i class="badge bg-red exchange" data-type="left">与左图交换</i><i class="badge bg-red exchange" data-type="right">与右图交换</i></a>
                                                @endforeach
                                            @else
                                                @if($now_book->has_answers)
                                                    @forelse($now_book->has_answers as $answer)
                                                        <a class="thumbnail"><img data-id="{{ $answer->id }}" class="answer_pic real_pic" data-original="{{ 'http://192.168.0.117/'.$answer->answer }}" alt=""><i class="badge bg-blue delete_this">移除</i><i class="badge bg-red exchange" data-type="left">与左图交换</i><i class="badge bg-red exchange" data-type="right">与右图交换</i></a>
                                                    @endforeach
                                                @endif
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
            const now_type='{{ $data['type'] }}';
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

            $('.show_big').click(function () {
                let data_id = $(this).parents('.single_box_info').attr('data-id');
                let now_value = $(this).parents('.single_box_info').find('input').val();
                $('#show_big_photo').modal('show');
                $('#show_big_photo #modify_footer').attr('data-id',data_id);
                $('#show_big_photo #modify_footer input').val(now_value);
                $('#show_big_photo .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
            });

            {{--//保存isbn--}}
            {{--$(document).on('click','.add_isbn',function () {--}}
                {{--let book_id = $(this).parent().attr('data-id');--}}
                {{--let isbn = $(this).prev().val();--}}
                {{--axios.post('{{ route('book_new_isbn_api','save_isbn') }}', {book_id, isbn}).then(response => {--}}
                {{--}).catch();--}}
            {{--});--}}

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
            $(document).on('change', '.sort_name,.grade_id,.subject_id,.volumes_id,.version_id', function () {
                let box = $(this).parents('.book_info_box');
                if(box.attr('not_relating')=='1'){
                    return false;
                }
                sort_id = box.find('.sort_name').val();

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
                    }else{

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
                        //$('.add_isbn').click();
                        let box = $(this).parents('.book_info_box');
                        let isbn = $(this).val();

                        axios.post('{{ route('manage_new_api','isbn_check') }}',{ isbn }).then(response=>{
                            if(response.data.status===0){
                                $(this).addClass('bg-red').removeClass('bg-blue')
                            }else{
                                $(this).addClass('bg-blue').removeClass('bg-red')
                            }
                        })

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
                let box = $(this).parents('.book_info_box');
                let version_year = $(this).parents('.book_info_box').find('.version_year').val();

                let now_name = box.find('.sort_name').select2('data')[0].name;
                let now_text = box.find('.sort_name').select2('data')[0].text;
                if(now_name!==undefined){
                    sort_name = now_name
                }else{
                    sort_name = now_text
                }

                let grade_name = $(this).parents('.book_info_box').find('.grade_id option:selected').text();
                let subject_name = $(this).parents('.book_info_box').find('.subject_id option:selected').text();
                let volume_name = $(this).parents('.book_info_box').find('.volumes_id option:selected').text();
                let version_name = $(this).parents('.book_info_box').find('.version_id option:selected').text();
                let book_name = version_year+'年'+sort_name+grade_name+subject_name+volume_name+version_name;

//                book_name = book_name.replace('上册','下册');
//                book_name = book_name.replace('全一册上','全一册下');
//                book_name = book_name.replace('全一册','全一册下');
                book_name = book_name.replace('思想品德','道德与法治');
                $(this).prev().val(book_name);
            });

            //完成所有信息
            //1.更新a_workbook_1010_db
            //2.更新a_workbook_answer_1010bd
            $(document).on('click','.confirm_book_done',function () {
                if(!confirm('确认完成编辑')){
                    return false;
                }
                let single_box_info = $(this).parents('.single_box_info');
                let now_id = single_box_info.attr('data-id');
                let bookname = single_box_info.find('.now_name').val();
//                bookname = bookname.replace('上册','下册');
//                bookname = bookname.replace('全一册上','全一册下');
//                bookname = bookname.replace('全一册','全一册下');
                bookname = bookname.replace('思想品德','道德与法治');
                let version_year = single_box_info.find('.version_year').val();
                let sort_id = single_box_info.find('.sort_name').val();
                let grade_id = single_box_info.find('.grade_id').val();
                let subject_id = single_box_info.find('.subject_id').val();
                let volume_id = single_box_info.find('.volumes_id').val();
                let version_id = single_box_info.find('.version_id').val();
                let isbn = single_box_info.find('.for_isbn_input').val();

                let cover_photo = single_box_info.find('.book_cover').attr('data-pathname');
                let cip_photo = '';
                if(single_box_info.find('.book_cip').length>0){
                    cip_photo = single_box_info.find('.book_cip').attr('data-pathname')
                }else{
                    cip_photo = cover_photo
                }

                let answer_all = [];
                if(now_type==='local_dir'){
                    let not_continue = 0;
                    single_box_info.find('.real_pic').each(function (i) {
                        if($(this).attr('src')==undefined){
                            not_continue = 1;
                        }
                        answer_all[i] = $(this).attr('src');
                    });
                    if(not_continue===1){
                        alert('请确认答案检查完毕');
                        return false;
                    }
                }else{
                    single_box_info.find('.real_pic').each(function (i) {
                        answer_all[i] = $(this).attr('data-id');
                    });
                }

                if(sort_id<0){
                    alert('系列未选择');
                    return false;
                }
                if(isbn.length!==17){
                    alert('isbn未填写完整');
                    if(!confirm('直接提交??')){
                        return false;
                    }
                }
                single_box_info.hide();
                //single_box_info.find('.add_isbn').click();
                axios.post('{{ route('manage_new_local_test_api','confirm_done') }}',{now_type,now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all,cover_photo,cip_photo}).then(response=>{
                    if(response.data.status===1){
                        if(now_type==='local_dir'){

                        }else{
                            alert('更新成功');
                        }
                    }else{
                        alert(response.data.msg);
                        if(now_type==='local_dir'){
                            single_box_info.show();
                        }
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
                axios.post('{{ route('manage_new_local_test_api','get_sort') }}',{sort_name}).then(response=>{
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

//                book_name = book_name.replace('上册','下册');
//                book_name = book_name.replace('全一册上','全一册下');
//                book_name = book_name.replace('全一册','全一册下');
                book_name = book_name.replace('思想品德','道德与法治');
                now_box.find('.now_name').val(book_name);

                let answer_sort = $('#answer_sort_dict option:selected').text();
                let answer_version =version_name;

                axios.post('{{ route('manage_new_local_test_api','get_answer') }}',{answer_sort,answer_version,answer_grade_name,answer_subject_name}).then(response=>{
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
                if($(this).prev().val().length<3){
                    return false;
                }
                let box = $(this).parents('.single_box_info');
                let answer_box = box.find('.answer_box');
                let arr = $(this).prev().val().split("");
                let arr_len = arr.length;
                console.log('qweqe');
                console.log(arr);
                console.log(arr_len);

                for(let i=0;i<arr_len-1;i++){
                    for(let j=0;j<arr_len-1-i;j++){
                        if(arr[j]>arr[j+1]){
                            answer_box.find('img').each(function (x) {
                                if(x%arr_len===j){
                                    console.log(j);
                                    $(this).parent().insertAfter($(this).parent().next());
                                }
                            });
                            let temp=arr[j];
                            arr[j]=arr[j+1];
                            arr[j+1]=temp;
                        }
                    }
                }
            });

            //重新整理
            $('.move_to_local').click(function () {
                let book_id = $(this).attr('data-id');
                axios.post('{{ route('manage_new_local_test_api','move_to_local') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        $(this).parents('.single_box_info').hide();
                    }
                }).catch();
            })

        });

    </script>

@endpush