@extends('layouts.backend')

@section('isbn_manage')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    .cover-img{
        min-height:370px;
        max-height:370px;
    }
</style>

@endpush

@section('content')
    <div class="modal fade" id="cover_photo">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        查看图片
                        <span><a class="photo_left btn btn-default">向左旋转</a><a class="photo_right btn btn-default">向右旋转</a></span>
                    </h4>

                </div>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="change_cover">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        选择封面
                    </h4>
                </div>
                <div class="modal-body" style="display: flex;overflow: auto">

                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">isbn整理</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> isbn整理</h3>
            </div>
            <div class="box-body">
                @if(count($data['all_isbn'])>0)
                    @foreach($data['all_isbn'] as $edit)
                        @if($loop->first)<div class="row">@endif<div class="col-md-3 col-xs-6 pull-left edit_box" data-id="{{ $edit->id }}" data-press-id="{{ $edit->press_id }}" style="font-size: 12px;margin-bottom: 10px">
                                <a class="thumbnail show_cover_photo" data-toggle="modal" data-target="#cover_photo">
                                    @if(starts_with($edit->cover_photo_thumbnail,'//') or starts_with($edit->cover_photo_thumbnail,'http'))<img class="img-responsive cover-img lazy-load" data-original="{{ $edit->cover_photo_thumbnail }}" >@else<img class="img-responsive cover-img lazy-load" big_cover="{{ config('workbook.workbook_url').$edit->cover_photo }}" data-original="{{ config('workbook.workbook_url').$edit->cover_photo_thumbnail }}" >@endif
                                </a>
                                <span class="front-operation" data-id="{{ $edit->id }}"></span>
                                <input name="original_name" style="font-size: 1px;padding: 1px;" class="form-control " value="{{ $edit->bookname }}" />
                                <div class="input-group"><input name="isbn"  class="isbn form-control " placeholder="isbn" value="{{ $edit->isbn }}" /><span class="change-isbn-btn bg-blue input-group-addon">更新</span></div><div class="input-group"><input name="cover-url"  class="isbn form-control " placeholder="封面地址" value="{{ $edit->cover_photo_thumbnail }}" /><span class="change-cover-btn bg-red input-group-addon">更新</span></div>
                                <select style="width: 100%;" data-name="press_id" class="update_data form-control press_select"><option value="{{ $edit->press_id }}">{{ $edit->press_name.'_'.$edit->press_id }}</option></select>
                            </div>
                            @if(($loop->index+1)%4==0)
                        </div><div class="row">
                            @endif
                            @if($loop->last)
                        </div>
                        @endif
                    @endforeach
                    <div class="pull-right">
                        {{ $data['all_isbn']->links() }}
                    </div>
                @else
                    <p>暂无信息</p>
                @endif

            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>

<script>
    var token = '{{ csrf_token() }}';
    $(function () {
        //show big photo
        $('.show_cover_photo').click(function () {
            var big_cover=$(this).find('img').attr('big_cover');
            var src_now = big_cover=='http://daan.1010pic.com/'?$(this).find('img').attr('src'):big_cover;
            $('.modal-body').html('<img class="img-responsive" src=' + src_now + '>');
        });

        $('.update_data').change(function () {
            update_info($(this));
        });

        $('.front-operation').each(function (i) {
            var edit_id = $(this).attr('data-id');
            $(this).prepend(
                '<a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_'+edit_id+'.html" class="btn btn-success pull-left" style="width:33.3%">查看练习册</a><a class="btn btn-info pull-left change_cover_btn" style="width:33.3%;" data-toggle="modal" data-target="#change_cover" data-id="'+edit_id+'">更换封面</a><a class="btn btn-danger sort_delete pull-left" style="width:33.3%">删除</a>'
            );
        });


        function update_info(info) {
            var tr_now = $(info).parents('.edit_box');
            var id = tr_now.data('id');
            var now_name = $(info).data('name');
            var now_data = $(info).val();
            var post_data = {
                'id': id,
                '_token': token,
                'o_uid': '{{ Auth::user()->id }}'
            };
            post_data[now_name] = now_data;
            $.ajax({
                type: "POST",
                url: "{{ route('workbook_update') }}",
                data: post_data,
                success: function (t) {

                },
                error: function (t) {
                    var errors = t.responseJSON;
                    var errorsHtml = '<div class="alert alert-danger">' +
                        '<span class="close" data-dismiss="alert">&times;</span>' +
                        '<ul>';
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(errorsHtml);
                },
                dataType: "json"
            })
        }
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

        var lazy = new LazyLoad();

        //更新isbn或封面
        $(document).on('click','.change-isbn-btn,.change-cover-btn',function () {
            var now_btn = $(this);
            var now_book_id = now_btn.parents('.edit_box').attr('data-id');
            var o ={ id:now_book_id, _token : token};
            if(now_btn.hasClass('change-isbn-btn')){
                o['isbn'] = now_btn.prev().val();
            }else{
                o['cover_photo_thumbnail'] = now_btn.prev().val();
            }
            $.ajax({
                type: 'post',
                url:'{{ route('workbook_update') }}',
                data:o,
                dataType:'json',
                success:function (s) {
                    if(s.status==1){
                        if(now_btn.hasClass('change-cover-btn')){
                            now_btn.parents('.edit_box').find('.cover-img').attr('src',now_btn.prev().val());
                        }
                    }else{alert('替换失败');}
                }
            })
        });

        //删除
        $(document).on('click','.sort_delete',function(){
            var book_id = $(this).parents('.edit_box').attr('data-id');
            var o = {_token:token,book_id:book_id};
            $.ajax({
                type:'post',
                dataType:'json',
                url:'{{ route('delete_this_book') }}',
                data:o,
                success:function (s) {
                    if(s.status==1){
                        $('.edit_box[data-id="'+book_id+'"]').remove();
                    }
                }
            })
        });

        //获取练习册封面
        $(document).on('click','.change_cover_btn',function () {
            var book_id = $(this).attr('data-id');
            var o = {
                _token:token,
                book_id:book_id,
            };
            $('#change_cover .modal-body').html('');
            $('#change_cover .modal-body').attr('data-book-id','');
            $.ajax({
                type:'post',
                dataType:'json',
                url:'{{ route('get_workbook_cover') }}',
                data:o,
                success:function (s) {
                    if(s.status==1){
                        var now_pic_box = '';
                        var data_len = s.data.length;
                        for(var i=0;i<data_len;i++){
                            now_pic_box += '<a class="thumbnail for-change-cover"><img src="'+s.data[i]['img']+'"></a>';
                        }
                        $('#change_cover .modal-body').attr('data-book-id',book_id);
                        $('#change_cover .modal-body').html(now_pic_box);

                    }else{
                        $('#change_cover .modal-body').html('<p>暂无可替换封面</p>');
                    }
                }
            })
        });

        //更新图片
        $(document).on('click','#change_cover img',function () {
            var img = $(this).attr('src');
            var book_id = $(this).parents('.modal-body').attr('data-book-id');
            var o = {
                id:book_id,
                cover_photo_thumbnail:img,
                _token:token
            };
            $.ajax({
                type: 'post',
                url:'{{ route('workbook_update') }}',
                data:o,
                dataType:'json',
                success:function (s) {
                    if(s.status==1){
                        $('.edit_box[data-id="'+book_id+'"] .show_cover_photo img').attr('src',img);
                        $('.edit_box[data-id="'+book_id+'"] input[name="cover-url"]').val(img);
                        $('#change_cover').modal('hide');
                    }else{
                        alert('替换失败');
                    }
                }
            })
        });

        //旋转图片
        var step = 0;
        $('.photo_left,.photo_right').click(function () {
            if($(this).hasClass('photo_left')){
                step -= 1;
            }else{
                step += 1;
            }
            $(this).parents('.modal-content').find('img').css({'transform': 'rotate('+step*90+'deg)'});
        });
    })
</script>
@endpush