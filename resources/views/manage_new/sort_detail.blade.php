@extends('layouts.backend')

@section('book_new_sort','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <div class="modal fade" id="show_big_pic">
        <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tool_box">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" id="confirm_operate">确认</a>
                    <a class="btn btn-danger" data-dismiss="modal">取消</a>
                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">子系列整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 唯一表整理->子系列整理</h3>
                <a class="btn btn-danger pull-right" href="{{ route('book_new_index') }}">返回</a>
            </div>
            <div class="box-body">
                <h2><a href="{{ route('book_new_subsort_arrange',[$data['all_sort_books'][0]->id,0]) }}">{{ $data['all_sort_books'][0]->name }}</a></h2>
                <ul class="nav nav-pills">
                @forelse($data['all_sub_sort'] as $value)
                    <li>
                        <button data-id="{{ $value->id }}" class="btn @if($data['sub_sort_now']==$value->id) btn-danger @else btn-primary @endif" data-toggle="dropdown" ><strong class="now_sort_name">{{ $value->name }}</strong>
                        <i>({{ $value->num }})</i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="btn btn-xs btn-default" href="{{ route('book_new_subsort_arrange',[$data['all_sort_books'][0]->id,$value->id]) }}" >查看</a></li>
                        <li><a data-type="rename" data-id="{{ $value->id }}" data-target="#tool_box" data-toggle="modal" class="btn btn-xs btn-default subsort_operate">更改名称</a></li>
                        <li><a data-type="delete" data-id="{{ $value->id }}" data-target="#tool_box" data-toggle="modal" class="btn btn-xs btn-default subsort_operate">删除</a></li>
                        <li><a data-type="move" data-id="{{ $value->id }}" data-target="#tool_box" data-toggle="modal" class="btn btn-xs btn-default subsort_operate">移动</a></li>
                    </ul>
                    </li>
                @endforeach
                </ul>
                    <hr>

                @forelse($data['now_sort_books'] as $book)
                    <div class="box-body well single_book_info" data-id="{{ $book->id }}">
                            <div class="col-md-6">
                                <div>
                                    <strong data-side="left"
                                            class="page_rotate_single label label-info">向左转</strong>
                                    <strong data-side="right" class="page_rotate_single label label-info">向右转</strong>
                                    <strong class="save_pic label label-danger">保存</strong>
                                </div>
                                <div class="col-md-6">
                                    <a class="thumbnail" data-hd-cover="{{ isset($book->has_hd_book->cover_photo)?$book->has_hd_book->cover_photo:'none' }}" data-target="#show_big_pic" data-toggle="modal">
                                        <img data-src="{{ $book->cover }}"
                                             src="{{ $book->cover.'?t='.time() }}" alt="">
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>练习册id:{{ $book->id }}</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 book_info_box" data-id="{{ $book->id }}">
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
                                    <select data-name="grade_name" style="width: 100%"
                                            class="grade_name select2">
                                        <option selected="selected"
                                                value="-1">{{ $book->grade_name?$book->grade_name:config('workbook.grade')[intval($book->grade_id)] }}</option>
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
                                    <select data-name="subject_name" style="width: 100%"
                                            class="subject_name select2">
                                        <option selected="selected"
                                                value="-1">{{ $book->subject_name?$book->subject_name:config('workbook.subject_1010')[intval($book->subject_id)] }}</option>
                                    </select>
                                    <label class="input-group-addon btn btn-primary add_option"
                                           data-type="subject_name">新增</label>
                                </div>

                                <div class="input-group pull-left" style="width:40%">
                                    <label class="input-group-addon">卷册</label>
                                    <select data-name="volumes" class="volumes_id form-control select2">
                                        <option selected="selected"
                                                value="{{ $book->volumes_id }}">{{ $data['all_volumes']->where('id',$book->volumes_id)->first()?$data['all_volumes']->where('id',$book->volumes_id)->first()->volumes:0 }}</option>
                                    </select>

                                </div>
                                <div class="input-group" style="width:60%">
                                    <select data-name="volumes_name" style="width: 100%"
                                            class="volumes_name select2">
                                        <option selected="selected"
                                                value="-1">{{ $book->volume_name?$book->volume_name:($data['all_volumes']->where('id',$book->volumes_id)->first()?$data['all_volumes']->where('id',$book->volumes_id)->first()->volumes:'未选择') }}</option>
                                    </select>

                                    <label class="input-group-addon btn btn-primary add_option"
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
                                        <select data-name="version_name" style="width: 100%"
                                                class="version_name select2">
                                            <option selected="selected"
                                                    value="-1">{{ $book->version_name?$book->version_name:$data['all_version']->where('id',$book->version_id)->first()->name }}</option>
                                        </select>
                                        <label class="input-group-addon btn btn-primary add_option"
                                               data-type="version_name">新增</label>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <label class="input-group-addon">系列</label>
                                    <select data-name="sort" class="form-control sort_name">
                                        <option value="{{ $book->sort }}">{{ $data['all_sort_books'][0]->name.'_'.$book->sort }}</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label class="input-group-addon">子系列</label>
                                    <select style="width:75%" data-name="sub_sort"
                                            class="form-control subsort_name select2">
                                            <option value="0">未选择</option>
                                            @forelse($data['all_sub_sort'] as $sub_sort)
                                                <option @if($book->ssort_id===$sub_sort->id) selected
                                                        @endif value="{{ $sub_sort->id }}">{{ $sub_sort->name.'_'.$sub_sort->id }}</option>
                                                @endforeach
                                    </select>
                                    <label class="btn btn-primary add_option"
                                           data-type="sub_sort">新增子系列</label>
                                </div>

                                <div class="input-group hide" style="width: 100%">
                                    <select class="form-control">
                                        <option>出版社/多选</option>
                                    </select>
                                </div>

                                <div class="btn btn-group">
                                    <a data-id="{{ $book->id }}" class="save_book btn btn-danger">保存</a>
                                    <a target="_blank" class="btn btn-info" href="{{ route('book_new_only_detail',[$book->sort,$book->ssort_id,$book->grade_id,$book->subject_id,$book->volumes_id,$book->version_id,$book->version_year]) }}">唯一化查看</a>
                                    <a class="btn btn-danger del_this">删除</a>
                                </div>
                            </div>
                    </div>
                @endforeach


                {{ $data['now_sort_books']->links() }}
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            //Initialize Select2 Elements
            $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['grade_select'] !!} '),});
            $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['subject_select'] !!} '),});
            $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['volume_select'] !!} '),});
            $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});
            $('select[data-name="grade_name"]').select2({data: $.parseJSON('{!! $data['grade_name_select'] !!} '),});
            $('select[data-name="subject_name"]').select2({data: $.parseJSON('{!! $data['subject_name_select'] !!} '),});
            $('select[data-name="volumes_name"]').select2({data: $.parseJSON('{!! $data['volume_name_select'] !!} '),});
            $('select[data-name="version_name"]').select2({data: $.parseJSON('{!! $data['version_name_select'] !!} '),});
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
                    return '<option value="' + repo.id + '">' + repo.name +'_'+repo.id + '</option>';
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
                        sub_sort_sel.html('')
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


            //跳转id
            $('#redict_to').click(function () {
                let book_id = $(this).next().val();
                if (book_id > 0) {
                    window.location.href = '{{ route('book_new_index','finished') }}?edit_id=' + book_id;
                }
            });

            //图片放大
            $('.thumbnail').click(function () {
                let hd_cover = $(this).attr('data-hd-cover');
                if(hd_cover==='none'){
                    $('#show_big_pic .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
                }else{
                    let img = `<img src="http://image.hdzuoye.com/book_photo_path/${hd_cover}"/>`;
                    $('#show_big_pic .modal-body').html(`<a class="thumbnail">${img}</a>`);
                }



                //$('#show_big_pic .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
            });
            //图片旋转保存
            let now_rotate = 0;
            $('.page_rotate_single').click(function () {
                let side = $(this).attr('data-side');
                let now_book_box = $(this).parents('.single_book_info');
                let now_img = now_book_box.find('.thumbnail img');
                let book_id = now_book_box.attr('data-id');
                if (side === 'left') {
                    now_rotate -= 1;
                } else {
                    now_rotate += 1;
                }
                if (now_rotate < 0) {
                    now_rotate = 4 - parseInt(Math.abs(now_rotate) % 4)
                } else {
                    now_rotate = now_rotate % 4
                }
                now_img.attr('src', now_img.attr('data-src') + '?x-oss-process=image/rotate,' + now_rotate * 90 + '&time=' + Date.parse(new Date()));
            });

            $('.save_pic').click(function () {
                let now_book_box = $(this).parents('.single_book_info');
                let now_img_sel = now_book_box.find('.thumbnail img');
                let old_img = now_img_sel.attr('data-src').replace('http://thumb.1010pic.com/', '');
                let now_img = now_img_sel.attr('src');
                axios.post('{{ route('save_pic_to_oss') }}', {old_img, now_img}).then(function (s) {
                    alert('保存成功');
                }).catch(function (error) {
                    console.log(error);
                });
            });

            //确认新增
            $(document).on('click', '#add_input_box', function () {
                let book_id = $(this).attr('data-id');
                let data_type = $(this).attr('data-type');
                let sort = $(`.book_info_box[data-id="${book_id}"] select[data-name="sort"]`).val();
                let add_name = $(this).prev().val();
                let add_id = 0;
                if (confirm('确认新增')) {
                    let o;
                    if (data_type === 'sub_sort') {
                        o = {
                            data_type,
                            sort,
                            sub_sort_name: add_name,
                        };
                    } else {
                        o = {
                            book_id,
                            data_type,
                            add_name,
                        };
                    }
                    axios.post('{{ route('book_new_workbook_api','add_name') }}', o).then(response => {
                        if (response.data.status === 1) {
                            add_id = response.data.data.new_id;
                            if ($.trim(add_name) === '') {
                                return false;
                            }
                            let option = new Option(add_name, add_id);
                            option.selected = true;
                            $(`.book_info_box[data-id="${book_id}"]`).find(`select[data-name="${data_type}"]`).append(option).trigger("change");
                            $('#new_box').remove();
                        } else {
                            alert('新增失败,请重试');
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            });

            //取消
            $(document).on('click','#del_input_box',function () {
                $('#new_box') .remove();
            });

            //操作框
            $('.subsort_operate').click(function () {
               let data_type = $(this).attr('data-type');
               let data_id = $(this).attr('data-id');
               let now_name = $(this).parents('li').find('.now_sort_name').html();
               let now_html = `<div id="now_operate" data-type="${data_type}" data-id="${data_id}">`
               if(data_type==='rename'){
                   now_html += `<div class="input-group">
                    <label class="input-group-addon">更改子系列名称</label>
                    <input type="text" class="form-control" value="${now_name}"/>
                    </div>`;
               }else if(data_type==='delete'){
                   now_html += `确认删除子系列--<strong class="bg-red">${now_name}</strong>?删除后该系列所有练习册归置于主系列`;
               }else if(data_type==='move'){
                   let all_sub_sort = JSON.parse('{!! json_encode($data['all_sub_sort']) !!}');
                   let now_radio = '';
                   for(let i in all_sub_sort){
                       let checked = '';
                       if(data_id==all_sub_sort[i].id){
                           checked = 'checked';
                       }else{
                           checked = false;
                       }
                       now_radio += `<div class="radio">
                            <label>
                              <input type="radio" name="optionsRadios" value="${all_sub_sort[i].id}" ${checked}>
                              ${all_sub_sort[i].name}
                            </label>
                          </div>`
                   }
                   now_html += `
                    <p>移动练习册至</p>
                    <div class="form-group">
                        ${now_radio}
                       </div>`;
               }else{
                   return false;
               }
               now_html += '</div>';
                $('#tool_box .modal-body').html(now_html);
            });

            //确认操作
            $(document).on('click','#confirm_operate',function () {
                let data_type = $('#now_operate').attr('data-type');
                let subsort_id = $('#now_operate').attr('data-id');
                let o;
                if(data_type==='rename'){
                    let new_name = $('#now_operate input').val();
                    o = {subsort_id,data_type,new_name};
                }else if(data_type==='delete'){
                    o = {subsort_id,data_type};
                }else if(data_type==='move'){
                    let new_subsort_id = $("input[name='optionsRadios']:checked").val();
                    o = {subsort_id,data_type,new_subsort_id};
                }else{
                    return false;
                }
                axios.post('{{ route('book_new_workbook_api','subsort_operate') }}',o).then(response=>{
                    if(response.data.status===1){
                        $('#tool_box').modal('hide');
                        console.log(response);
                        //window.location.reload();
                    }else{
                        alert('修改失败');
                    }
                }).catch(function (error) { console.log(error); });
            });

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
        });
    </script>
@endpush