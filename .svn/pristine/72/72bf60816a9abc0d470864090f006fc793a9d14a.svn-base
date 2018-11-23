@extends('layouts.backend')

@section('audit_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush



@section('content')
    <div class="modal fade" id="show_img">
        <div class="modal-dialog" style="width: 80%">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
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

        <div class="box box-primary">
            <div class="box-header with-border">isbn详情->
                <span><a class="btn btn-primary">{{ $data['isbn'] }}<em class="badge bg-red">{{ count($data['offical_book']) }}</em></a></span>
            </div>

            <div class="row" id="main_lxc_select">

                <div style="position: fixed;z-index: 9999;bottom: 50px" id="main_book_choose_btn">
                    <a class="btn btn-danger select_all_box" data-now="1">全选</a>
                </div>
            </div>


            {{--已有练习册--}}
            <div style="position: fixed;top: 50px;right:10px;z-index: 999;">
                <div class="box box-danger  collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $data['isbn'] }}已有练习册</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="height: 400px;overflow: auto">
                        @forelse($data['offical_book'] as $book)
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="cover_info">
                                        <div class="box box-primary">
                                        <div class="box-body">
                                        <a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html">{{ $book->bookname }}</a>
                                        <a class="thumbnail cover_imf" style="max-width: 300px"><img src="{{ config('workbook.thumb_image_url').$book->cover_photo_thumbnail }}" data-original="{{ config('workbook.thumb_image_url').$book->cover_photo_thumbnail }}"/></a>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>{{ config('workbook.grade')[$book->grade_id] }}</div>
                                    <div>{{ config('workbook.subject_1010')[$book->subject_id] }}</div>
                                    <div>{{ config('workbook.volumes')[$book->volumes_id] }}</div>
                                    <div>{{ $data['all_version']->where('id',$book->version_id)->first()->name }}</div>
                                    <div>{{ $book->has_sort?$book->has_sort->name:'' }}</div>
                                    <div>{{ $book->version_year }}</div>
                                </div>
                                <a data-id="{{ $book->id }}" data-type="old_book" data-isbn="{{ $data['isbn'] }}" class="btn btn-danger main_lxc">设为主书</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="box-body" id="old_box">
                @forelse($data['isbn_detail'] as $isbn)
                    @if($loop->index%3==0)
                        <div class="row row_select" data-id="{{ $loop->index }}">
                            @endif
                            <div class="col-md-4">
                                <div class="box box-primary">
                                    <div class="box-header box-for-choose with-border">
                                        <div>
                                        <input type="hidden" class="hdid" value="{{ $isbn->hdid }}" />
                                            <span>
                                                <label><input type="checkbox" class="take_this" name="check_for_change" value="{{ $isbn->id }}"></label>
                                            </span>
                                        </div>
                                        <div class="box-tools">
                                            <span>
                                                <a class="take_this_row btn btn-xs btn-danger">选择此行</a>
                                            <a class="btn btn-primary btn-xs main_lxc" data-id="{{ $isbn->id }}" data-type="new_book">将练习册设为主书</a>
                                                </span>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <a class="thumbnail col-md-6 show_big_img cover_img"><img
                                                data-original="{{ config('workbook.user_image_url').$isbn->cover_img }}"/></a>
                                        <a class="thumbnail col-md-6 show_big_img isbn_img"><img
                                                data-original="{{ config('workbook.user_image_url').$isbn->cip_img }}"/></a>
                                    </div>
                                    {{ $isbn->addtime }}
                                </div>

                            </div>
                            @if($loop->index%3==2)
                        </div>
                    @endif
                @endforeach
            </div>
            <div id="for_box_info">
            @if(count($data['isbn_detail'])>0)
            <div class="need_info hide">
                <div class="input-group" style="width: 100%">
                    <label class="input-group-addon">书名</label>
                    <input type="text" class="form-control book_name"
                           value="{{ $data['isbn_detail'][0]->sort_name }}">
                </div>
                <div class="input-group" style="width: 100%">
                    <label class="input-group-addon">年份</label>
                    <input type="text" class="form-control version_year"
                           value="{{ $data['isbn_detail'][0]->version_year }}">
                </div>

                <div class="input-group pull-left" style="width:50%">
                    <label class="input-group-addon">年级</label>
                    <select data-name="grade"
                            class="grade_id form-control select2 pull-left" tabindex="-1"
                            aria-hidden="true">
                        <option selected="selected"
                                value="{{ $data['isbn_detail'][0]->grade_id }}">{{ config('workbook.grade')[intval($data['isbn_detail'][0]->grade_id)] }}</option>
                    </select>
                </div>

                <div class="input-group pull-left" style="width:50%">
                    <label class="input-group-addon">科目</label>
                    <select data-name="subject" class="subject_id form-control select2"
                            tabindex="-1" aria-hidden="true">
                        <option selected="selected"
                                value="{{ $data['isbn_detail'][0]->subject_id }}">{{ config('workbook.subject_1010')[intval($data['isbn_detail'][0]->subject_id)] }}</option>
                    </select>

                </div>

                <div class="input-group pull-left" style="width:50%">
                    <label class="input-group-addon">卷册</label>
                    <select data-name="volumes" class="volumes_id form-control select2">
                        <option selected="selected"
                                value="{{ $data['isbn_detail'][0]->volumes_id }}">{{ $data['all_volumes']->where('id',$data['isbn_detail'][0]->volumes_id)->count()>0?$data['all_volumes']->where('id',$data['isbn_detail'][0]->volumes_id)->first()->volumes:0 }}</option>
                    </select>

                </div>


                    <div class="input-group pull-left" style="width: 50%">
                        <label class="input-group-addon">版本</label>
                        <select data-name="version" class="version_id form-control select2"
                                tabindex="-1" aria-hidden="true">
                            <option selected="selected"
                                    value="{{ $data['isbn_detail'][0]->version_id }}">{{ $data['all_version']->where('id',$data['isbn_detail'][0]->version_id)->first()->name }}</option>
                        </select>
                    </div>


            <div style="width: 100%">
                <div class="input-group">
                    <label class="input-group-addon">系列</label>
                    <select data-name="sort" class="form-control sort_name" style="width: 100%">
                        <option value="{{ $data['isbn_detail'][0]->sort }}">{{ $data['isbn_detail'][0]->has_sort?$data['isbn_detail'][0]->has_sort->name.'_'.$data['isbn_detail'][0]->sort:'待定' }}</option>
                    </select>
                </div>
            </div>
                @if(!$data['isbn_detail'][0]->has_sort && $data['isbn_detail'][0]->sort>0)
                    <div class="input-group">
                        <input type="text" data-sort="{{ $data['isbn_detail'][0]->sort }}" value="" class="form-control" placeholder="输入系列名"/>
                        <lable class="input-group-addon btn btn-danger save_sort_name">保存</lable>
                    </div>
                @endif
                <div class="input-group">
                    <label class="input-group-addon">子系列</label>
                    <select style="width: 50%" data-name="sub_sort"
                            class="form-control subsort_name select2">
                        @if($data['isbn_detail'][0]->has_sort)
                            <option value="0">未选择</option>
                            @forelse($data['isbn_detail'][0]->has_sort->sub_sorts as $sub_sort)
                                <option @if($data['isbn_detail'][0]->ssort_id===$sub_sort->id) selected
                                        @endif value="{{ $sub_sort->id }}">{{ $sub_sort->name.'_'.$sub_sort->id }}</option>
                                @endforeach
                                @endif
                    </select>
                    {{--<div style="width: 50%;float: right">--}}
                        {{--<label class="btn btn-primary add_option"--}}
                               {{--data-type="sub_sort">新增</label>--}}
                        {{--<a class="btn btn-success" target="_blank" href="{{ route('book_new_subsort_arrange',[$data['isbn_detail'][0]->sort,$data['isbn_detail'][0]->ssort_id?$data['isbn_detail'][0]->ssort_id:$data['isbn_detail'][0]->sort]) }}">编辑</a>--}}
                    {{--</div>--}}
                </div>

                <div class="btn btn-group">
                    <a data-isbn="{{ $data['isbn'] }}" data-type="new_book" data-id="{{ $data['isbn_detail'][0]->id }}" class="to_this_book btn btn-danger">保存该练习册</a>
                </div>
            </div>
            @endif
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {

            const isbn_now = '{{ $data['isbn'] }}';
            function init_select2() {
                $('.select2').select2();
                //Initialize Select2 Elements
                $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['grade_select'] !!} '),});
                $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['subject_select'] !!} '),});
                $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['volume_select'] !!} '),});
                $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});

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
                    //let book_id = $(this).parents('.need_info').attr('data-id');
                    //let sub_sort_sel = $(`.need_info[data-id=${book_id}] select[data-name="sub_sort"]`);
                    let book_info_box = $(this).parents('.need_info');
                    let sub_sort_sel = $(book_info_box).find('select[data-name="sub_sort"]');
                    axios.post('{{ route('book_new_workbook_api','refresh_sub_sort') }}', {sort}).then(response => {
                        if (response.data.status === 1) {
                            sub_sort_sel.html('').select2({data: response.data.data,});
                        } else {
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                });
            }

            init_select2();


            var lazy = new LazyLoad();
            //显示大图
            $(document).on('click','.show_big_img',function () {
                let img = $(this).find('img').attr('data-original');
                $('#show_img').modal('show');
                $('#show_img .modal-body').html(`<img width="100%" src="${img}" />`);
            });

            //选中练习册
            $(document).on('click','.box-for-choose',function () {
                let info_box = $(this).parents('.col-md-4');
                if($(this).find('input').attr('checked')=='checked'){
                    $(this).find('input').removeAttr('checked');
                    if($(info_box).find('.need_info').length){
                        $('.need_info').addClass('hide');
                    }
                }else{
                    $(this).find('input').attr('checked','checked');
//                    if($(info_box).find('.need_info').length){
//                        if($('.need_info').hasClass('hide')){
//                            $('.need_info').removeClass('hide');
//                        }else{
//                            $('.need_info').addClass('hide');
//                        }
//                    }else{
//                        $('.need_info').removeClass('hide');
//                    }
//                    $('.need_info').appendTo(info_box);
                }
            });

            //选中此行
            $(document).on('click','.take_this_row',function () {
                if($(this).html()==='移除'){
                    if($(this).parents('.col-md-3').hasClass('bg-red')){
                        return false;
                    }
                    $(this).html('选择此行');
                    $('#old_box').prepend(`<div class="row row_select"><div class="col-md-4">${$(this).parents('.col-md-3').html()}</div></div>`);
                    $(this).parents('.col-md-3').remove();
                }else{
                    let single_row = $(this).parents('.row_select');
                    if($(this).parents('.col-md-4').find('.take_this').attr('checked')=='checked'){
                        $(single_row).find('.take_this').removeAttr('checked');
                    }else{
                        $(single_row).find('.take_this').attr('checked','checked');
                    }
                }


               event.stopPropagation();
            });

            //选择主书
            $(document).on('click','.main_lxc',function () {
                let now_book_id = $(this).attr('data-id');
                let old_book_id = $(this).parents('.new_main_box').attr('data-id');
                let now_book_type = $(this).attr('data-type');
                    axios.post('{{ route('audit_api','get_book_info') }}',{now_book_id,now_book_type}).then(response=> {
                        if (response.data.status===1){
                            let book_info = response.data.book_info;
                            $('.need_info .book_name').val(book_info.sort_name);
                            $('.need_info .version_year').val(book_info.version_year);
                            $('.need_info .grade_id').val(book_info.grade_id).trigger('change');
                            $('.need_info .subject_id').val(book_info.subject_id).trigger('change');
                            $('.need_info .volumes_id').val(book_info.volumes_id).trigger('change');
                            $('.need_info .version_id').val(book_info.version_id).trigger('change');
                            $('.need_info .sort_name ').val(book_info.sort).trigger('change');
                        }
                }).catch();
                    let now_box,now_body;
                    if(now_book_type==='old_book'){
                        now_box = $(this).parent().find('.cover_info');
                        now_body = now_box;
                    }else{
                        now_box = $(this).parents('.col-md-4');
                        now_body = now_box.find('.box-body');
                        now_box.find('.take_this_row').html('移除');
                    }

                let new_main_box_len = $('.new_main_box').length+1;

                //在已归类中选择
                if($(this).parents('.all_children_box').length>0){
                    let all_children_box = $(this).parents('.all_children_box');
                    $(all_children_box).find('.choose_main_book').removeClass('choose_main_book bg-red');
                    $(this).parents('.single_lxc').addClass('choose_main_book bg-red');
                    all_children_box.prev().html($(this).parents('.single_lxc').find('.box-body').html());
                    $(this).parents('.new_main_box').attr('data-id',now_book_id);
                    $(`#main_book_choose_btn span[data-id="${old_book_id}"]`).attr('data-id',now_book_id);

                }else{
                    $('#main_lxc_select').append(`
                <div class="box-body new_main_box" data-id="${now_book_id}" data-type="${now_book_type}">
                    <div class="col-md-7">
                        <div class="show_main">${now_body.html()}</div>
                        <div class="all_children_box">
                            <div class="col-md-3 single_lxc choose_main_book bg-red" style="height: 150px" data-id="${now_book_id}">${now_box.html()}</div>
                        </div>
                    </div>
                    <div class="col-md-5 edit_box">
                        <a class="btn btn-primary btn-lg edit_box_btn">编辑此书信息</a>
                        <a class="btn btn-danger btn-lg cancel_box_btn">取消主书设置</a>
                    </div>
                   </div>
                   <hr>
                    `);
                    $('#main_book_choose_btn').append(
                        `<span data-id="${now_book_id}">
                        <a class="btn btn-success move_to_main_box">将选中练习册归属至(主书${new_main_box_len})</a>
                    </span>`
                    );
                    if(now_book_type==='new_book'){
                        now_box.remove();
                    }
                }
                $('.need_info').appendTo($(`.new_main_box[data-id="${now_book_id}"] .edit_box`)).removeClass('hide');
                $(`.new_main_box[data-id="${now_book_id}"] .edit_box .to_this_book`).attr({'data-type':'new_book','data-id':now_book_id,'data-isbn':isbn_now});
                event.stopPropagation();
            });

            //选择书籍归属至主书
            $(document).on('click','.move_to_main_box',function () {
               let book_id = $(this).parent().attr('data-id');
                console.log(book_id);
               $('#old_box .take_this:checked').each(function () {
                   let now_book_id = $(this).val();
                   let now_box = $(this).parents('.col-md-4');
                   now_box.find('.take_this_row').html('移除');
                   let now_body = now_box.find('.box-body');
                   $(`.new_main_box[data-id="${book_id}"] .all_children_box`).append(
                       `<div class="col-md-3 single_lxc" style="height: 150px" data-id="${now_book_id}">${now_box.html()}</div>`
                   );

                   now_box.remove();
               });

            });

            //全选
            $(document).on('click','.select_all_box',function () {
                if($(this).attr('data-now')==1){
                    $(this).attr('data-now',0);
                    $('#old_box .take_this').attr('checked','checked');
                }else{
                    $(this).attr('data-now',1);
                    $('#old_box .take_this').removeAttr('checked');
                }
            });

            //调出编辑练习册信息
            $(document).on('click','.edit_box_btn',function () {
                $(this).parent().prev().find('.choose_main_book .main_lxc').click();
            });

            //归属练习册
            $(document).on('click','.to_this_book',function () {
                if(!confirm('确认保存?')){
                    return false;
                }
                let now_box = $(this).parents('.new_main_box');
                let book_id = $(this).attr('data-id');
                let isbn = $(this).attr('data-isbn');
                let type = $(this).attr('data-type');
                let now_book_ids = [];

                $(now_box).find('.single_lxc').each(function () {
                    now_book_ids.push($(this).attr('data-id'));
                });
//                $(`input[name="check_for_change"]:checked`).each(function (i) {
//                    now_book_ids.push((this).value);
//                });
                if(now_book_ids.length<1){
                    alert('请选择求助');
                    return false;
                }
               if(type==='new_book'){
                    let info_box = $('.need_info').parent();
                    let bookname  = info_box.find('.book_name').val();
                    let cover  = $(now_box).find('.show_main .cover_img img').attr('data-original');
                    let grade_id  = info_box.find('.grade_id').val();
                    let subject_id  = info_box.find('.subject_id').val();
                    let volumes_id  = info_box.find('.volumes_id').val();
                    let version_id  = info_box.find('.version_id').val();
                    let version_year  = info_box.find('.version_year').val();
                    let sort  = info_box.find('.sort_name').val();
                    let ssort_id  = info_box.find('.subsort_name').val();
                    let hdid = $(now_box).find('.hdid').val();
                    axios.post('{{ route('audit_api','save_offical_book')  }}',{book_id,bookname,isbn,cover,grade_id,subject_id,volumes_id,version_id,version_year,sort,ssort_id,now_book_ids,hdid}).then(response=>{
                        if(response.data.status===1){
                            alert('保存成功');
                            $(`input[name="check_for_change"]:checked`).parents('.col-md-4').remove();
                        }
                    }).catch(function (error) {
                        alert('保存失败');
                        console.log(error);
                    })
               }else{
                   axios.post('{{ route('audit_api','to_offical_book')  }}',{type,book_id,isbn,now_book_ids}).then(response=>{
                       if(response.data.status===1){
                           $(`input[name="check_for_change"]:checked`).parents('.col-md-4').remove();
                       }
                   }).catch(function (error) {
                       console.log(error);
                   })
               }
            });


            //取消主书设置
            $(document).on('click','.cancel_box_btn',function () {
                let old_book_id = $(this).parents('.new_main_box').attr('data-id');
                console.log(old_book_id);
                $(`#main_book_choose_btn span[data-id="${old_book_id}"]`).remove();
                let restore_box = '';
                let now_box = $(this).parent().prev().find('.single_lxc').each(function (i) {
                    $(this).find('.take_this_row').each(function () {
                       $(this).html('选择此行');
                    });
                   if(i%3==0){
                       restore_box += `<div class="row row_select">`
                   }
                   restore_box += `<div class="col-md-4">${$(this).html()}</div>`
                   if(i%3==2){
                       restore_box += `</div>`
                   }
               });

                $('#main_book_choose_btn span').each(function (i) {
                   $(this).html(`<a class="btn btn-success move_to_main_box">将选中练习册归属至(主书${i+1})</a>`);
                });
               $('#old_box').prepend(restore_box);
               $('.need_info').appendTo($('#for_box_info'));
               $(this).parent().parent().remove();
            });
        });

    </script>
@endpush