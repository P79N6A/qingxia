@extends('layouts.backend')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}" />
    <style>
        table td {
            min-width: 80px;
            padding: 3px;
        }
        .form-group{
            margin-bottom: 7px;
            padding-left: 0;
            padding-right: 0;
        }
        .answer-img{
            min-height:640px;
            max-height:640px;
            min-width:540px;
            max-width:540px;
        }
        .cover-img{
            min-height:370px;
            max-height:370px;
        }

        .col-md3, .col-xs-6{
            padding:5px;
        }
        .book-chapter-right{
            position: fixed;
            right: 20px;
            top: 50px;
            z-index: 99999;
            min-width: 160px;
        }
        .book-chapter-left{
            position: fixed;
            left: 50px;
            top: 50px;
            z-index: 99999;
            min-width: 160px;
        }
        input[name='sort_name']{
            width:75% !important;
            float:right !important;
            font-size: 1px;padding: 1px;
        }
    </style>
@endpush

@section('book_new_only')
    active
@endsection

@section('content')

    <div class="modal fade" id="cover_photo">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">查看图片</h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="answer_photo">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">查看图片</h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="form-errors">

    </div>


    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册编辑</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 练习册编辑</h3>
                -->
                {{--@if(!isset($no_onlyname))--}}
                    {{--<strong>{{ $onlycode }}</strong>--}}
                    {{--<p>当前系列专版信息：</p>--}}
                    {{--@if($edits->total()>0)--}}
                        {{--@foreach($edits as $book)--}}
                            {{--@if($loop->first)--}}
                                {{--{{ $book->note }}--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--@else--}}
                    {{--<strong>无onlycode</strong>--}}
                    {{--<p>当前科目：{{ config('workbook.subject_1010')[$subject] }}</p>--}}
                {{--@endif--}}
            </div>
            <div class="box-body">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @forelse($data['group_by_year'] as $key=>$value)
                            <li @if($loop->first) class="active" @endif><a data-toggle="tab" href="#year_{{ $key }}">{{ $key }}<em class="badge bg-light-blue">{{ count($value) }}</em></a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @forelse($data['group_by_year'] as $key=>$group_year)
                            <div class="tab-pane @if($loop->first) active @endif" id="year_{{ $key }}">
                                @forelse($group_year as $book)
                                    @if($loop->first)
                                        <div class="row">
                                            @endif
                                            <div class="col-md-6 col-xs-6 pull-left single_book_info" data-id="{{ $book->id }}" style="font-size: 12px;margin-bottom: 10px">
                                                <a class="thumbnail show_cover_photo" data-hd-cover="{{ isset($book->has_hd_book->cover_photo)?$book->has_hd_book->cover_photo:'none' }}" data-toggle="modal" data-target="#cover_photo">
                                                    <img class="img-responsive cover-img" data-big="{{ $book->cover }}" data-original="{{ $book->cover }}">
                                                </a>

                                                <div class="book_info_box" data-id="{{ $book->id }}">
                                                    <strong>bookid: {{ $book->id }} </strong>
                                                    <a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html">{{ $book->bookname }}</a>
                                                    <a class="btn btn-danger">收藏人数:{{ $book->collect_count }}</a>
                                                    <div class="input-group">
                                                        <label class="input-group-addon">将该书跳转至</label>
                                                        <input class="form-control" value="{{ $book->redirect_id }}" placeholder="该练习册可跳转至id" />
                                                        <label class="btn btn-primary input-group-addon redirect_btn">确认</label>
                                                    </div>
                                                    <div class="input-group">
                                                        <label class="input-group-addon">修改本书封面</label>
                                                        <input class="form-control" value="{{ $book->cover }}" placeholder="填入图片地址" />
                                                        <label class="btn btn-primary input-group-addon cover_change_btn">确认</label>
                                                    </div>
                                                    <div class="input-group">
                                                        <label class="input-group-addon">isbn</label>
                                                        <input class="form-control" value="{{ $book->isbn }}" />
                                                        <a target="_blank" href="https://s.taobao.com/search?q={{ $book->isbn  }}" class="input-group-addon btn btn-danger">淘宝搜索</a>
                                                    </div>
                                                    <div class="input-group" style="width: 100%">
                                                        <label class="input-group-addon">书名</label>
                                                        <input type="text" class="form-control book_name"
                                                               value="{{ $book->bookname }}">
                                                    </div>
                                                    <div class="input-group" style="width: 100%">
                                                        <label class="input-group-addon">年份</label>
                                                        <input type="text" class="form-control version_year"
                                                               value="{{ $book->version_year }}">
                                                    </div>
                                                    <div class="input-group pull-left" style="width:40%">
                                                        <label class="input-group-addon">年级</label>
                                                        <select data-name="grade"
                                                                class="grade_id form-control select2 pull-left" tabindex="-1"
                                                                aria-hidden="true">
                                                            <option selected="selected"
                                                                    value="{{ $book->grade_id }}">{{ config('workbook.grade')[intval($book->grade_id)] }}</option>
                                                        </select>
                                                    </div>

                                                    <div class="input-group" style="width:60%">
                                                        <select data-name="grade_name" class="grade_name select2" style="width: 100%">
                                                            <option selected="selected"
                                                                    value="-1">{{ $book->grade_name }}</option>
                                                        </select>
                                                        <label class="input-group-addon btn btn-primary add_option"
                                                               data-type="grade_name">新增</label>
                                                    </div>

                                                    <div class="input-group pull-left" style="width:40%">
                                                        <label class="input-group-addon">科目</label>
                                                        <select data-name="subject" class="subject_id form-control select2"
                                                                tabindex="-1" aria-hidden="true">
                                                            <option selected="selected"
                                                                    value="{{ $book->subject_id }}">{{ config('workbook.subject_1010')[intval($book->subject_id)] }}</option>
                                                        </select>

                                                    </div>
                                                    <div class="input-group" style="width:60%">
                                                        <select data-name="subject_name" class="subject_name select2" style="width: 100%;">
                                                            <option selected="selected"
                                                                    value="-1">{{ $book->subject_name }}</option>
                                                        </select>
                                                        <label class="input-group-addon btn btn-primary add_option "
                                                               data-type="subject_name">新增</label>
                                                    </div>
                                                    <div class="input-group pull-left" style="width:40%">
                                                        <label class="input-group-addon">卷册</label>
                                                        <select data-name="volumes" class="volumes_id form-control select2">
                                                            <option selected="selected"
                                                                    value="{{ $book->volumes_id }}">{{ $data['all_volumes']->where('id',$book->volumes_id)->first()->volumes }}</option>
                                                        </select>

                                                    </div>
                                                    <div class="input-group" style="width:60%">
                                                        <select data-name="volumes_name" class="volumes_name select2" style="width:100%">
                                                            <option selected="selected"
                                                                    value="-1">{{ $book->volume_name }}</option>
                                                        </select>
                                                        <label class="input-group-addon btn btn-primary add_option "
                                                               data-type="volumes_name">新增</label>
                                                    </div>
                                                    <div style="width: 100%">
                                                        <div class="input-group pull-left" style="width: 40%">
                                                            <label class="input-group-addon">版本</label>
                                                            <select data-name="version" class="version_id form-control select2"
                                                                    tabindex="-1" aria-hidden="true">
                                                                <option selected="selected"
                                                                        value="{{ $book->version_id }}">{{ $data['all_version']->where('id',$book->version_id)->first()->name }}</option>
                                                            </select>

                                                        </div>
                                                        <div class="input-group" style="width: 60%">
                                                            <select data-name="version_name" class="version_name select2" style="width: 100%;">
                                                                <option selected="selected"
                                                                        value="-1">{{ $book->version_name }}</option>
                                                            </select>
                                                            <label class="input-group-addon add_option btn btn-primary"
                                                                   data-type="version_name">新增</label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group">
                                                        <label class="input-group-addon">系列</label>
                                                        <select data-name="sort" class="form-control sort_name">
                                                            <option value="{{ $book->sort }}">{{ $book->sort_name.'_'.$book->sort }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-group">
                                                        <label class="input-group-addon">子系列</label>
                                                        <select data-name="sub_sort" class="form-control subsort_name select2">
                                                            <option value="0">未选择</option>
                                                            @forelse($data['all_sub_sort'] as $sub_sort)
                                                                <option @if($book->ssort_id===$sub_sort->id) selected
                                                                        @endif value="{{ $sub_sort->id }}">{{ $sub_sort->name.'_'.$sub_sort->id }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                    <div style="width: 50%;float: right">
                                                        <label class="btn btn-primary add_option"
                                                               data-type="sub_sort">新增子系列</label>
                                                        <a class="btn btn-success" target="_blank" href="{{ route('book_new_subsort_arrange',[$book->sort,$book->ssort_id?$book->ssort_id:$book->sort]) }}">编辑子系列</a>

                                                    </div>

                                                    <div class="btn btn-group">
                                                        <a data-id="{{ $book->id }}" class="save_book btn btn-primary">保存</a>
                                                        <a data-id="{{ $book->id }}" class="del_this btn btn-danger">删除</a>
                                                    </div>
                                                </div>


                                                {{--<select style="width: 100%;" data-name="press_id" class="update_data form-control press_select">--}}
                                                {{--<option value="{{ $book->press_id }}">{{ $book->press_name }}</option>--}}
                                                {{--</select>--}}
                                                {{--<input disabled="disabled" class="form-control" name="press" value="{{ $book->press_name }}" />--}}
                                                <div class="input-group hide">
                                                    <a class="btn btn-success btn-xs all_done pull-left">完成编辑</a>
                                                    {{--<a class="btn btn-primary btn-xs page_all_done pull-left">全部完成编辑</a>--}}
                                                    <a class="btn btn-danger btn-xs sort_delete pull-left">删除</a>
                                                </div>
                                                @if(count($book->has_answers)>0)
                                                    <div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false">
                                                        <div class="carousel-inner" >
                                                            @foreach($book->has_answers as $key => $answer)
                                                                @php  $answers = explode('|',$answer->answer); @endphp
                                                                @if(is_array($answers))
                                                                    @foreach($answers as $answer_img)
                                                                        <div class="item @if ($loop->first && $key==0) active  @endif">
                                                                            <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                                <img class="answer-img img-responsive" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}"
                                                                                     alt="First slide">
                                                                            </a>
                                                                            <div class="carousel-caption text-orange">{{ $answer->textname }}</div>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="item @if ($loop->first && $key==0) active @endif">
                                                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                            <img class="answer-img img-responsive" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer->answer) }}" alt="First slide">
                                                                        </a>
                                                                        <div class="carousel-caption text-orange FontBig">{{ $answer->textname }}</div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <a class="carousel-control  left" href="#myCarousel_{{ $book->id }}"
                                                           data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                        <a class="carousel-control right" href="#myCarousel_{{ $book->id }}"
                                                           data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                    </div>
                                                @else
                                                    <p>暂无对应答案</p>
                                                @endif


                                            </div>
                                            @if(($loop->index+1)%2==0)
                                        </div><div class="row">
                                            @endif
                                            @if($loop->last)
                                        </div>
                                    @endif

                                    @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>

    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="/adminlte/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/adminlte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="{{ asset('js/jstree.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script>
        var token = '{{ csrf_token() }}';
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['grade_select'] !!} '),});
            $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['subject_select'] !!} '),});
            $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['volume_select'] !!} '),});
            $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});
            $('select[data-name="grade_name"]').select2({data: $.parseJSON('{!! $data['grade_name_select'] !!} '),});
            $('select[data-name="subject_name"]').select2({data: $.parseJSON('{!! $data['subject_name_select'] !!} '),});
            $('select[data-name="volumes_name"]').select2({data: $.parseJSON('{!! $data['volume_name_select'] !!} '),});
            $('select[data-name="version_name"]').select2({data: $.parseJSON('{!! $data['version_name_select'] !!} '),});
            //show big photo
            $('.show_cover_photo').click(function () {
                let src_now;
                let hd_cover = $(this).attr('data-hd-cover');
                let img;
                if(hd_cover!=='none') {
                    img = `<img src="http://image.hdzuoye.com/book_photo_path/${hd_cover}"/>`;
                }else{
                    if($(this).find('img').attr('data-big')){
                        src_now = $(this).find('img').attr('data-big');
                    }else{
                        src_now = $(this).find('img').attr('src');
                    }
                    img = `<img src=${src_now} />`;
                }
                $('.modal-body').html(`<a class="thumbnail">${img}</a>`);
            });
            //for_sorts

            //获取系列
            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('book_new_workbook_api','sort') }}",
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
                    return '<option value="' + repo.id + '">' + repo.name+'_'+repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });

            //系列更改更新子系列
            $('.sort_name').change(function () {
                let sort = $(this).val();
                let book_id = $(this).parents('.single_book_info').attr('data-id');
                let sub_sort_sel = $(`.single_book_info[data-id=${book_id}] select[data-name="sub_sort"]`);
                axios.post('{{ route('book_new_workbook_api','refresh_sub_sort') }}', {sort}).then(response => {
                    if (response.data.status === 1) {
                        sub_sort_sel.html('').select2({data: response.data.data,});
                    } else {
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            });

            //保存
            $('.save_book').click(function () {
                $('#error_box[data-ignore="false"]').remove();
                let book_id = $(this).attr('data-id');
                let now_box = $(`.book_info_box[data-id=${book_id}]`);
                let book_name = now_box.find('.book_name').val();
                let grade_id = now_box.find('.grade_id').val();
                let subject_id = now_box.find('.subject_id').val();
                let volumes_id = now_box.find('.volumes_id').val();
                let version_id = now_box.find('.version_id').val();
                let version_year = now_box.find('.version_year').val();
                let grade_name = now_box.find('.grade_name option:selected').text();
                let subject_name = now_box.find('.subject_name option:selected').text();
                let volumes_name = now_box.find('.volumes_name option:selected').text();
                let version_name = now_box.find('.version_name option:selected').text();
                let sort_id = now_box.find('.sort_name').val();
                let subsort_id = now_box.find('.subsort_name').val();
                let subsort_name = now_box.find('.subsort_name option:selected').text();
                let o = {
                    book_id, book_name, grade_id, subject_id, volumes_id, version_id,
                    grade_name, subject_name, volumes_name, version_name,
                    sort_id, subsort_id, version_year
                };
                let unmatch = '';
                if($.trim(subsort_name)==''){
                    alert('子系列为空');return false;
                }
                if($.trim(grade_name)==''){
                    alert('年级为空');return false;
                }
                if($.trim(subject_name)==''){
                    alert('科目为空');return false;
                }
                if($.trim(volumes_name)==''){
                    alert('卷册为空');return false;
                }
                if($.trim(version_name)==''){
                    alert('版本为空');return false;
                }

                if(book_name.search(subsort_name)===-1){
                    unmatch += '<label class="label label-danger">子系列名称</label> ';
                }
                if(book_name.search(grade_name)===-1){
                    unmatch += '<label class="label label-danger">年级</label> ';
                }
                if(book_name.search(subject_name)===-1){
                    unmatch += '<label class="label label-danger">科目</label> ';
                }
                if(book_name.search(volumes_name)===-1){
                    unmatch += '<label class="label label-danger">卷册</label> ';
                }
                if(book_name.search(version_name)===-1){
                    unmatch += '<label class="label label-danger">版本</label> ';
                }
                if(unmatch){
                    unmatch = '当前未匹配字段: '+unmatch;
                    if($('#error_box[data-ignore="true"]').length===0){
                        $(`.book_info_box[data-id=${book_id}]`).append(`
                            <div id="error_box" data-ignore="false">
                                <div><a>当前书本名称: ${book_name}</a></div>
                                <div>当前整理名称: <label class="label label-info">${subsort_name}</label>
                                <label class="label label-info">${grade_name}</label>
                                <label class="label label-info">${subject_name}</label>
                                <label class="label label-info">${volumes_name}</label>
                                <label class="label label-info">${version_name}</label></div>
                                <div>${unmatch}</div>
                                <a id="confirm_again" data-id="${book_id}" class="btn btn-primary">确认无误,继续提交</a>
                            </div>

                            `);
                        return false;
                    }
                }
                axios.post('{{ route('book_new_workbook_api','update_sub_sort') }}', o).then(response => {
                    if (response.data.status === 1) {
                        $(`.single_book_info[data-id=${book_id}]`).remove();
                    } else {
                        alert(response.data.msg);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
                //var option = new Option(sort_name, sort_id);
                //option.selected = true;
                //$('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="sort"]').append(option).trigger("change");
            });
            //新增
            $('.add_option').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                let type = $(this).attr('data-type');
//                let add_name = '1';
//                let add_id = 0;
                $('#new_box').remove();
                $(this).parent().after(`<div class="input-group" id="new_box">
                                    <input type="text" class="form-control" value="" />
                                    <label class="btn btn-danger input-group-addon" id="add_input_box" data-id="${book_id}" data-type="${type}">确认</label>
                                    <label class="btn btn-default input-group-addon" id="del_input_box">取消</label>
                                </div>`);
//                if(add_name===''){
//                    return false;
//                }
//                let option = new Option(add_name, add_id);
//                option.selected = true;
//                $(`.book_info_box[data-id="${book_id}"]`).find(`select[data-name="${type}"]`).append(option).trigger("change");
            });

            //忽略错误,继续提交
            $(document).on('click','#confirm_again',function () {
                let book_id = $(this).attr('data-id');
                $(this).parent().attr('data-ignore',"true");
                $(`a.save_book[data-id=${book_id}]`).click();
            });

            //取消
            $(document).on('click','#del_input_box',function () {
                $('#new_box') .remove();
            });


            {{--$('.update_data').change(function () {--}}
                {{--update_info($(this));--}}
            {{--});--}}

            {{--function update_info(info) {--}}
                {{--var tr_now = $(info).parents('.edit_box');--}}
                {{--var id = tr_now.data('id');--}}
                {{--var now_name = $(info).data('name');--}}
                {{--var now_data = $(info).val();--}}
                {{--console.log(info);--}}
                {{--var post_data = {--}}
                    {{--'id': id,--}}
                    {{--'_token': token,--}}
                    {{--'o_uid': '{{ Auth::user()->id }}'--}}
                {{--};--}}
                {{--post_data[now_name] = now_data;--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: "{{ route('workbook_update') }}",--}}
                    {{--data: post_data,--}}
                    {{--success: function (t) {--}}

                    {{--},--}}
                    {{--error: function (t) {--}}
                        {{--var errors = t.responseJSON;--}}
                        {{--var errorsHtml = '<div class="alert alert-danger">' +--}}
                            {{--'<span class="close" data-dismiss="alert">&times;</span>' +--}}
                            {{--'<ul>';--}}
                        {{--$.each(errors, function (key, value) {--}}
                            {{--errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.--}}
                        {{--});--}}
                        {{--errorsHtml += '</ul></div>';--}}

                        {{--$('#form-errors').html(errorsHtml);--}}
                    {{--},--}}
                    {{--dataType: "json"--}}
                {{--})--}}
            {{--}--}}

            {{--//完成编辑--}}
            {{--$('.all_done').click(function () {--}}
                {{--var data_not_alert = $(this).attr('data_not_alert');--}}
                {{--var status_tab = '{{ $status }}';--}}
                {{--var now_this = $(this);--}}
                {{--var id = now_this.parents('.edit_box').data('id');--}}

                {{--var special_info = $(this).parents('.edit_box').find('input[name="special_info"]').val();--}}
                {{--var special_info_2 = $(this).parents('.edit_box').find('input[name="special_info_2"]').val();--}}
                {{--var original_name = $(this).parents('.edit_box').find('input[name="original_name"]').val();--}}
                {{--var sort_name = $(this).parents('.edit_box').find('input[name="sort_name"]').val();--}}
                {{--var district = $(this).parents('.edit_box').find('input[name="district"]').val();--}}
                {{--var isbn = $(this).parents('.edit_box').find('input[name="isbn"]').val();--}}
                {{--var o = {--}}
                    {{--'id': id,--}}
                    {{--'original_name': original_name,--}}
                    {{--'district': district,--}}
                    {{--'sort_name':sort_name,--}}
                    {{--'isbn':isbn,--}}
                    {{--'_token': token,--}}
                    {{--'o_uid': '{{ Auth::user()->id }}'--}}
                {{--};--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: "{{ route('workbook_done_only') }}",--}}
                    {{--data: o,--}}
                    {{--success: function (t) {--}}
                        {{--if (t.status == 1) {--}}
                            {{--if (status_tab == 0) {--}}
                                {{--$('#now_num').html(parseInt($('#now_num').html()) - 1);--}}
                                {{--now_this.parents('.edit_box').remove();--}}
                            {{--} else {--}}
                                {{--if (data_not_alert != 0) {--}}
                                    {{--alert('更新成功');--}}
                                {{--}--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--error: function (t) {--}}
                        {{--var errors = t.responseJSON;--}}
                        {{--var errorsHtml = '<div class="alert alert-danger"><ul>';--}}

                        {{--$.each(errors, function (key, value) {--}}
                            {{--errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.--}}
                        {{--});--}}
                        {{--errorsHtml += '</ul></div>';--}}

                        {{--$('#form-errors').html(errorsHtml);--}}
                    {{--},--}}
                    {{--dataType: "json"--}}
                {{--})--}}
            {{--});--}}

            {{--//全部完成--}}
            {{--$('.page_all_done').click(function () {--}}
                {{--if (confirm('确认全部完成编辑')) {--}}
                    {{--$('.all_done').attr('data_not_alert', '0');--}}
                    {{--$('.all_done').click();--}}
                {{--}--}}
            {{--});--}}

            {{--//删除--}}
            {{--$(document).on('click','.sort_delete',function(){--}}
                {{--var book_id = $(this).parents('.edit_box').attr('data-id');--}}
                {{--var o = {_token:token,book_id:book_id};--}}
                {{--$.ajax({--}}
                    {{--type:'post',--}}
                    {{--dataType:'json',--}}
                    {{--url:'{{ route('delete_this_book') }}',--}}
                    {{--data:o,--}}
                    {{--success:function (s) {--}}
                        {{--if(s.status==1){--}}
                            {{--$('.edit_box[data-id="'+book_id+'"]').remove();--}}
                        {{--}--}}
                    {{--}--}}
                {{--})--}}
            {{--});--}}

            //clear the modal data
            $('#answer_photo').on('hidden.bs.modal', function () {
                $(this).removeData('bs.modal');
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
                    return '<option value="' + repo.id + '">' + repo.name + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo){
                    return repo.name || repo.text;
                },

            });

            //图片加载
            var cHeight = 0;

            $('.carousel').on('slide.bs.carousel', function(e) {
                var $nextImage = $(e.relatedTarget).find('img');
                $activeItem = $('.active.item', this);
                // prevents the slide decrease in height
                if (cHeight == 0) {
                    cHeight = $(this).height();
                    $activeItem.next('.item').height(cHeight);
                }
                // prevents the loaded image if it is already loaded
                var src = $nextImage.attr('data-original');
                if (typeof src !== "undefined" && src != "") {
                    $nextImage.attr('src', src);
                    $nextImage.attr('data-original', '');
                }
            });
            var lazy = new LazyLoad();

            //删除
            $('.del_this').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                axios.post('{{ route('book_new_workbook_api','del_this') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        $(`.single_book_info[data-id=${book_id}]`).remove();
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            });
            //跳转id
            $('.redirect_btn').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                let bind_id = parseInt($(this).prev().val());
                axios.post('{{ route('book_new_workbook_api','bind_redirect') }}',{book_id,bind_id}).then(response=>{
                    alert(response.data.msg);
                }).catch(function (error) {
                    console.log(error);
                })
            });

            //修改封面
            $('.cover_change_btn').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                let cover_photo = $(this).prev().val();
                axios.post('{{ route('book_new_workbook_api','change_cover') }}',{book_id,cover_photo}).then(response=>{
                    $(`.single_book_info[data-id=${book_id}] .cover-img`).attr('src',cover_photo);
                    alert(response.data.msg);
                }).catch(function (error) {
                    console.log(error);
                })
            });


            //年代选择
            @if($data['version_year_now']!=0)
            $('a[href="#year_{{ $data['version_year_now'] }}"]').click();
            @endif
        });

    </script>
@endpush
