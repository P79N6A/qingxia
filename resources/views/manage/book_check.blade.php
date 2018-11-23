@extends('layouts.backend')

@section('book_check')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker.min.css"/>
<style>
    .tool-box a, .tool-box select {
        margin-left: 10px;
    }

    .lazy-load-cover {
        min-width: 150px;
        max-height: 250px;
    }

    .cover-photo {
        max-height: 250px;
        min-height: 250px;
    }

    .cover-img {
        min-width: 150px;
        max-height: 250px;
        min-height: 250px;
    }

    .cover-box {
        overflow-y: auto;
        display: flex;
    }

    .at_top {
        position: fixed;
        z-index: 1;
    }
</style>
@endpush

@section('content')

    <div class="modal fade" id="photo_modal">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        查看图片
                        <span class="at_top"><a class="photo_left btn btn-default">向左旋转</a><a
                                    class="photo_right btn btn-default">向右旋转</a></span>
                    </h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="answer_modal">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        查看图片
                        <span class="at_top"><a class="photo_left btn btn-default">向左旋转</a><a
                                    class=" photo_right btn btn-default">向右旋转</a>
                        <a class=" photo_prev btn btn-default">上一张</a><a
                                    class=" photo_next btn btn-default">下一张</a></span>
                    </h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>


    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">答案审核</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 答案审核</h3>
            </div>
            <div class="box-body">
                <div class="input-group tool-box" style="width: 100%">
                    {{--<a href="{{ route('book_check') }}" class="btn btn-primary pull-left">最近一天</a>--}}
                    {{--<a class="btn btn-default pull-left">最近一周</a>--}}
                    <select id="grade_sel" class="form-control pull-left" style="width:10%">
                        <option value="0">全部年级</option>
                        @foreach(config('workbook.grade') as $key=>$value)
                            <option value="{{ $key }}"
                                    @if($key==$data['grade_id']) selected="selected"@endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <select id="subject_sel" class="form-control pull-left" style="width:10%">
                        <option value="0">全部科目</option>
                        @foreach(config('workbook.subject') as $key=>$value)
                            <option value="{{ $key }}"
                                    @if($key==$data['subject_id']) selected="selected"@endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <input name="isbn" type="text" class="form-control pull-left" value="{{ starts_with($data['isbn'],'9787')?$data['isbn']:'' }}" style="width:20%" placeholder="isbn"/>
                    <div class="input-group input-daterange" style="width:30%">
                        <input name="start_time" type="text" class="form-control"
                               value="{{ $data['start_time']?$data['start_time']:date('Y-m-d',time()) }}">
                        <div class="input-group-addon">to</div>
                        <input name="end_time" type="text" class="form-control"
                               value="{{ $data['end_time'] }}">
                        <a class="btn btn-primary input-group-addon" id="confirm_search">确认</a>
                    </div>

                </div>
                @if(isset($data['now_group']))
                    @foreach($data['now_group'] as $key=>$value)
                        @php
                        $group_arr = explode('|',$key);
                        $group_num = count($value);
                        @endphp
                        <a class="btn btn-xs btn-default" href="{{ route('book_check').'/'.$group_arr[0].'/'.$group_arr[1].'/'.$group_arr[2].'/'.$group_arr[3].'/'.$data['start_time'].'/'.$data['end_time'].'/'.$data['isbn'] }}">{{ config('workbook.grade')[$group_arr[0]].config('workbook.subject')[$group_arr[1]].config('workbook.volumes')[$group_arr[2]].collect($data['version'])->where('id',$group_arr[3])->first()->name }}<i class="badge">{{ $group_num }}</i></a>
                    @endforeach
                @endif
                <hr>
                <div class="row">
                    <div class="col-md-3" id="isbn_all_list">
                        @foreach($data['isbn_all'] as $key => $value)
                            <a target="_blank" href="{{ route('book_check').'/'.$data['grade_id'].'/'.$data['subject_id'].'/999/999/'.$data['start_time'].'/'.$data['end_time'].'/'.$value->bar_code }}" class="list-group-item list-group-item-primary @if($data['isbn']==$value->bar_code) active @endif"><span class="badge">{{ $key+1 }}</span>{{ $value->bar_code }}<span class="badge">共{{ $value->num }}页答案</span></a>
                        @endforeach
                        <a class="more_isbn btn btn-primary btn-block">更多</a>
                    </div>
                <div class="content-box col-md-9">
                    @if(count($data['book_now']->items())>0)
                        <a id="all_not_pass" class="btn btn-danger">该系列全部不通过</a>
                        <a id="isbn_not_pass" class="btn btn-danger">该isbn全部不通过</a>
                        @foreach($data['book_now'] as $key => $value)
                            <div class="row" data-id="{{ $value->id }}">
                                <div class="col-md-6">
                                <span class="pull-left col-md-5">
                            <a class="thumbnail show-cover" data-target="#photo_modal"
                               data-toggle="modal">
                                <img class="cover-photo lazy-load" data-img="{{ $value['cover_photo'] }}"
                                     data-original="{{ config('workbook.zone_img_head').auth_url('/zone/answer_cover/'.$value['cover_photo']) }}"/>
                            </a>
                                     <a data-target="#photo_modal" data-toggle="modal" class="btn btn-success show-cip"
                                        data-cip="{{ auth_url('/zone/answer_cip/'.$value['cip_photo']) }}">cip</a>
                                {{--<a class="btn btn-success" target="_blank"--}}
                                   {{--href="https://s.taobao.com/search?q={{ $value['bar_code'] }}">淘宝搜索</a>--}}
                                    <a class="btn btn-primary check_on">选中</a>
                                    <a class="btn btn-primary check_all_on">全/反选</a>
                                </span>
                                    <span class="pull-left text-center col-md-7 operate-box"
                                          data-id="{{ $value['id'] }}">
                                <div class="input-group">
                                    <input class="form-control bookname" value="{{ $value['book_name'] }}"/>
                                    <a class="input-group-addon btn btn-primary make_bookname">生成</a>
                                </div>
                                <div class="input-group" style="width: 100%">
                                    <select class="form-control subject_sel" style="width:33.3%">
                                        @foreach(config('workbook.subject') as $key1=>$value1)
                                            <option value="{{ $key1 }}"
                                                    @if($key1==$value['subject_id']) selected="selected"@endif>{{ $value1 }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-control grade_sel" style="width:33.3%">
                                        @foreach(config('workbook.grade') as $key1=>$value1)
                                            <option value="{{ $key1 }}"
                                                    @if($key1==$value['grade_id']) selected="selected"@endif>{{ $value1 }}</option>
                                        @endforeach
                                    </select>
                                    <select data-name="version_id" class="version_sel version_sel form-control select2" style="width:33%" tabindex="-1" aria-hidden="true">
                                        @foreach($data['version'] as $value1)
                                            @if($value->book_version_id==intval($value1->id))
                                                @php $select='selected=selected'; @endphp
                                            @else
                                                @php $select = ''; @endphp
                                            @endif<option {{$select}} value="{{ intval($value1->id) }}">{{ $value1->name }}</option>@endforeach
                        </select>
                                </div>
                                <div class="input-group">
                                <input class="form-control isbn"
                                       value="{{ $value['bar_code'] }}"/>
                                    <span class="input-group-addon">isbn</span>
                                </div>
                                <div class="input-group" style="width:100%">
                                    <input style="width:40%" class="form-control version_year" value="{{ $value['version'] }}">
                                    <select class="form-control volume_sel" style="width:60%">
                                        @foreach(config('workbook.volumes') as $key1=>$value1)
                                            <option value="{{ $key1 }}"
                                                    @if($key1==$value['volumes']) selected="selected"@endif>{{ $value1 }}</option>
                                        @endforeach
                                </select>
                                </div>
                                <div class="input-group">
                                    <select style="width: 100%;" data-name="press_id" class="form-control press_select">

                                    </select>
                                    <span class="input-group-addon">出版社</span>
                                </div>
                                <div class="input-group">
                                <select data-name="sort" class="form-control sort_select">

                                </select>
                                    <span class="input-group-addon">sort</span>
                                </div>
                                <input class="form-control last_time" disabled value="{{ $value['create_time'] }}"/>

                                <hr>
                                 <a class="btn btn-primary btn-block check_true">通过</a>
                                <a class="btn btn-danger btn-block check_false">不通过</a>
                            </span>
                                </div>
                                <div data-id="{{ $value['id'] }}" class="cover-box"
                                     style="overflow-y: auto;display: flex">
                                    @foreach($data['book_answer'][$key] as $key1=>$value1)
                                        <a class="thumbnail show-answer" data-id="{{ $key1 }}"
                                           data-target="#answer_modal" data-toggle="modal"><img
                                                    class="img-responsive cover-img lazy-load"
                                                    data-original="{{ config('workbook.zone_img_head').auth_url('/zone/answer/'.$value1->answer_img) }}"/></a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>暂无对应结果</p>
                    @endif
                </div>
                </div>
            </div>
            <br>
            <a class="btn btn-danger btn-lg" id="all_sel_fail">选中部分不通过</a>
            <hr>
            <div>
                {{ $data['book_now']->links() }}
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    $(function () {
        var token = '{{ csrf_token() }}';
        $('.select2').select2();
        //show_cip
        $(document).on('click','.show-cip',function () {
            var src_now = '{{ config('workbook.zone_img_head') }}'+ $(this).attr('data-cip');
            $('#photo_modal .modal-body').html('<img class="img-responsive" src=' + src_now + '>')
        });
        //show_cover
        $(document).on('click','.show-cover',function () {
            var src_now = $(this).find('img').attr('src');
            $('#photo_modal .modal-body').html('<img class="img-responsive" src=' + src_now + '>')
        });

        //show_answer
        $(document).on('click','.show-answer',function () {
            var now_book_id = $(this).parents('.cover-box').attr('data-id');
            var now_answer_key = $(this).attr('data-id');
            var src_now = $(this).find('img').attr('src');
            $('#answer_modal .modal-body').html('<img class="img-responsive" src=' + src_now + '>')
            $('#answer_modal .modal-content').attr({'data-id': now_book_id, 'data-key': now_answer_key});
        });
        //answer_control
        $(document).on('click','.photo_prev,.photo_next',function () {
            var now_content = $(this).parents('.modal-content');
            var now_book_id = now_content.attr('data-id');
            var now_key = now_content.attr('data-key');
            if ($(this).hasClass('photo_prev')) {
                now_key = parseInt(now_key) - 1;
                var prev_img = $('.cover-box[data-id="' + now_book_id + '"] a[data-id="' + now_key + '"]').find('img');
                if (prev_img.length == 0) {
                    alert('已到第一张');
                    return false;
                }
                var src_now = prev_img.attr('src');
                var now_answer_key = parseInt(now_key);
            } else {
                now_key = parseInt(now_key) + 1;
                var next_img = $('.cover-box[data-id="' + now_book_id + '"] a[data-id="' + now_key + '"]').find('img');
                if (next_img.length == 0) {
                    alert('已到最后一张');
                    return false;
                }

                var src_now = next_img.attr('src');
                var now_answer_key = parseInt(now_key)
            }
            $('#answer_modal .modal-body').html('<img class="img-responsive" src=' + src_now + '>')
            $('#answer_modal .modal-content').attr({'data-id': now_book_id, 'data-key': now_answer_key});

        });

        //选中
        $(document).on('click','.check_on',function () {
           if($(this).hasClass('btn-danger')){
               $(this).html('选中').removeClass('btn-danger').addClass('btn-primary')
           }else{
               $(this).html('取消选中').removeClass('btn-primary').addClass('btn-danger')
           }
        });

        //全选
        $(document).on('click','.check_all_on',function () {
            $('.check_on').each(function () {
                if($(this).hasClass('btn-danger')){
                    $(this).html('选中').removeClass('btn-danger').addClass('btn-primary')
                }else{
                    $(this).html('取消选中').removeClass('btn-primary').addClass('btn-danger')
                }
            });
        });

        //选中部分不通过
        $('#all_sel_fail').click(function () {
            $('.check_on.btn-danger').each(function () {
               var now_book_id = $(this).parents('.row').attr('data-id');
               $('.operate-box[data-id="'+now_book_id+'"] .check_false').attr('data-add',1);
               $('.operate-box[data-id="'+now_book_id+'"] .check_false').click();

            });
        });

        //answer_check
        $(document).on('click','.check_false,.check_true',function () {
            var book_not_add = $(this).attr('data-add');
            var book_id = $(this).parents('.operate-box').attr('data-id');

            if ($(this).hasClass('check_false')) {
                var o = {
                    _token: token,
                    type: 'check_false',
                    book_id: book_id
                };
            } else {
                var box = $('.operate-box[data-id="'+book_id+'"]');
                var cover = $('.row[data-id="'+book_id+'"] .cover-photo').attr('data-img');
                var bookname = box.find('.bookname').val();
                var grade_id = box.find('.grade_sel').val();
                var subject_id = box.find('.subject_sel').val();
                var version_id = box.find('.version_sel').val();
                var isbn = box.find('.isbn').val();
                var version_year = box.find('.version_year').val();
                var volume_id = box.find('.volume_sel').val();
                var press_id = box.find('.press_select').val();
                var sort_id = box.find('.sort_select').val();
                var o = {
                    _token: token,
                    type: 'check_true',
                    cover:cover,
                    bookname:bookname,
                    grade_id:grade_id,
                    subject_id:subject_id,
                    version_id:version_id,
                    isbn:isbn,
                    version_year:version_year,
                    volume_id:volume_id,
                    press_id:press_id,
                    sort_id:sort_id,
                    book_id: book_id
                };
            }

            $.ajax({
                type: 'post',
                url: '{{ route('book_check_api') }}',
                data: o,
                success: function (s) {
                    if (s.status == 1) {
                        $('.row[data-id="' + book_id + '"]').remove();
                        if(book_not_add!=1){
                            get_one_add();
                        }else{
                            window.location.reload();
                        }
                    }else{
                        alert(s.msg);
                    }
                },
                error: function () {

                },
                dataType: 'json'
            })
        });

        function get_one_add() {
            var grade_now = $('#grade_sel').val();
            var subject_now = $('#subject_sel').val();
            var start_time = $('input[name="start_time"]').val();
            var end_time = $('input[name="end_time"]').val();
            var isbn = $('input[name="isbn"]').val();
            var page_now = '{{ isset($_REQUEST['page'])?intval($_REQUEST['page']):1 }}';
            $.get('{{ route('book_check_add') }}'+'/' +page_now+'/'+ grade_now + '/' + subject_now + '/' + start_time + '/' + end_time+'/'+isbn,function (s) {
                console.log(x=s);
                var now_clone_box = $('.content-box .row:first').clone();
                now_clone_box.find("span.select2").remove();
                now_clone_box.find("select").select2();
                $('.content-box').append(now_clone_box);
                var last_box = $('.content-box .row:last');
                last_box.attr('data-id',s.book_now.id);
                last_box.find('.show-cover').html('<img class="cover-photo lazy-load" data-img="'+s.book_now.cover_photo+'"'
                                     +'data-original="'+s.book_cover_photo+'" src="'+s.book_cover_photo+'"/>');
                last_box.find('.show-cip').attr('data-cip',s.book_cip_photo);
                last_box.find('.operate-box').attr('data-id',s.book_now.id);
                var now_operate_box = $('.operate-box[data-id="'+s.book_now.id+'"]');
                now_operate_box.find('.bookname').val(s.book_now.book_name);
                now_operate_box.find('.subject_sel').val(s.book_now.subject_id).trigger("change");
                now_operate_box.find('.grade_sel').val(s.book_now.grade_id).trigger("change");
                now_operate_box.find('.version_sel').val(s.book_now.book_version_id).trigger("change");
                now_operate_box.find('.isbn').val(s.book_now.bar_code);
                now_operate_box.find('.version_year').val(s.book_now.version);
                now_operate_box.find('.volume_sel').val(s.book_now.volumes).trigger("change");
                now_operate_box.find('select[data-name="press_id"]').val(0);
                now_operate_box.find('select[data-name="sort"]').val(0);
                now_operate_box.find('.last_time').val(s.book_now.create_time);
                last_box.find('.cover-box').attr('data-id',s.book_now.id);
                var answer_len = s.book_answer.length;
                var imgs = ''
                for(var i=0;i<answer_len;i++){
                    imgs+='<a class="thumbnail show-answer" data-id="'+i+'" data-target="#answer_modal" data-toggle="modal"><img class="img-responsive cover-img lazy-load " data-original="'+s.book_answer[i].answer_img+'" src="'+s.book_answer[i].answer_img+'"></a>'
                }

                last_box.find('.cover-box').html(imgs);
                init_sel2();
            })
        }
        
        //生成书名
        $(document).on('click','.make_bookname',function () {
            var box = $(this).parents('.operate-box');
            var old_name = $(this).prev().val();
            if(box.find('.version_sel option:selected').val()==24){
                var book_version = ''
            }else{
                var book_version = box.find('.version_sel option:selected').text();
            }

            var new_book_name = box.find('.version_year').val()+'年'+old_name+box.find('.grade_sel option:selected').text()
            +box.find('.subject_sel option:selected').text()+box.find('.volume_sel option:selected').text()+book_version;
            $(this).prev().val(new_book_name);
        });


        function init_sel2() {
            //获取系列
            $(".sort_select").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('workbook_sort') }}",
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
                    return '<option value="' + repo.id + '">' + repo.name + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo){
                    return repo.name || repo.text;
                },

            });

            //获取出版社
            $(".press_select").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('workbook_press') }}",
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
                    return '<option value="' + repo.id + '">' + repo.name +'_'+repo.id+ '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo){
                    return repo.name || repo.text;
                },

            });
        }
        init_sel2();





        $('.input-daterange input').datepicker({
            'format': 'yyyy-mm-dd',
            'autoclose': true,
            'todayHighlight': true,

        });



//        $('.input-daterange input').each(function() {
//            //$(this).datepicker('clearDates');
//        });

        var lazyLoadInstances = [];
        var lazyLazy = new LazyLoad({
            elements_selector: ".cover-box",
            callback_set: function (el) {
                var oneLL = new LazyLoad({
                    container: el
                });
                lazyLoadInstances.push(oneLL);
            }
        });
        var lazy = new LazyLoad();

        //旋转图片
        var step = 0;
        $('.photo_left,.photo_right').click(function () {
            if ($(this).hasClass('photo_left')) {
                step -= 1;
            } else {
                step += 1;
            }
            $(this).parents('.modal-content').find('img').css({'transform': 'rotate(' + step * 90 + 'deg)'});
        });

        //条件搜索
        $('#confirm_search').click(function () {
            var grade_now = $('#grade_sel').val();
            var subject_now = $('#subject_sel').val();
            var start_time = $('input[name="start_time"]').val();
            var end_time = $('input[name="end_time"]').val();
            var isbn = $('input[name="isbn"]').val();
            console.log(grade_now, subject_now, start_time, end_time);
            var book_check_url = '{{ route('book_check') }}';
            if (end_time < start_time) {
                alert('结束日期小于开始日期');
                return false;
            }
            window.location.href = book_check_url + '/' + grade_now + '/' + subject_now + '/999/999/' + start_time + '/' + end_time+'/'+isbn;
        });

        //更多isbn
        $('.more_isbn').click(function () {
            var now_isbn_len = $('#isbn_all_list .list-group-item').length;
            $.get('{{ route('more_isbn') }}'+'/'+now_isbn_len,function (response) {
                console.log(response);
                let data_len = response.length;
                for(let i=0;i<data_len;i++){
                    $('.more_isbn').before('<a href="{{ route('book_check').'/'.$data['grade_id'].'/'.$data['subject_id'].'/999/999/'.$data['start_time'].'/'.$data['end_time'].'/' }}'+response[i].bar_code+'" class="list-group-item list-group-item-primary">'+response[i].bar_code+'<span class="badge">共'+response[i].num+'页答案</span></a>');
                }
            });
        })

        $('#isbn_all_list a').click(function () {
            $(this).addClass('active');
        })
        
        //系列全部不通过
        $('#all_not_pass').click(function () {
            let grade_id = '{{ $data['grade_id'] }}';
            let subject_id = '{{ $data['subject_id'] }}';
            let volumes = '{{ $data['volumes'] }}';
            let book_version_id = '{{ $data['book_version_id'] }}';
            let isbn = '{{ $data['isbn'] }}';
            let o = {
                grade_id:grade_id,
                subject_id:subject_id,
                volumes:volumes,
                book_version_id:book_version_id,
                isbn:isbn,
                _token:token
            };
            $.post('{{ route('all_not_pass') }}',o,function(s){
                if(s.status==1){
                    window.location.reload();
                }
            });
        })

        //isbn全部不通过
        $('#isbn_not_pass').click(function () {
            let isbn = '{{ $data['isbn'] }}';
            let o = {
                isbn:isbn,
                _token:token
            };
            $.post('{{ route('isbn_not_pass') }}',o,function(s){
                if(s.status==1){
                    window.location.reload();
                }
            });
        })
    })
</script>
@endpush