@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">05网练习册管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 05网练习册管理</h3></div>
            <div class="box-body">
                <div class="form col-md-6">
                    <div class="radio">
                        <label>
                            <input name="status" type="radio" value="1">有效
                        </label>
                        <label>
                            <input name="status" type="radio" value="0">无效
                        </label>
                    </div>
                    <br>
                    <div class="input-group" style="width:100%">
                        <label class="input-group-addon">所属练习册</label>
                        <input class="form-control book_name" value="" placeholder="isbn"/>
                    </div>
                    <br>
                    <div class="input-group" style="width:100%">
                        <label class="input-group-addon">章节名称</label>
                        <input class="form-control chapter_name" value="" placeholder="isbn"/>
                    </div>
                    <hr>
                    <a class="btn btn-danger" id="save_book">保存</a>
                    <a class="btn btn-default" href="{{ route('lww_index') }}">返回</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>

<script>
    var token= "{{ csrf_token() }}";
    $('.select2').select2();
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
        templateSelection: function formatRepoSelection(repo) {
            return repo.name || repo.text;
        },
    });

    //保存章节
    $('#save_book').click(function () {
        var book_id = '{{ $data['chapter_id'] }}';
        var book_status = $('input[name="status"]:checked').val();
        var book_img = $('input[name="book_cover"]').val();
        var book_version_year = $('select[data-name="version_year"]').val();
        var book_name = $('input[name="book_name"]').val();
        var book_grade = $('.grade_sel').val();
        var book_subject = $('.subject_sel').val();
        var book_volume = $('.volume_sel').val();
        var book_version = $('.version_sel').val();
        var book_isbn = $('.book_isbn').val();
        var book_page = $('.book_page').val();
        var book_sort = $('.sort_select').val();
        var o ={
            _token:token,
            book_id:book_id,
            book_status:book_status,
            book_img:book_img,
            book_version_year:book_version_year,
            book_name:book_name,
            book_grade:book_grade,
            book_subject:book_subject,
            book_volume:book_volume,
            book_version:book_version,
            book_isbn:book_isbn,
            book_page:book_page,
            book_sort:book_sort,
        };
        $.ajax({
            type:'post',
            url:'{{ route('lww_add_book') }}',
            data:o,
            success:function (s) {
                if(s.status==1){
                    window.location.href = '{{ route('lww_index') }}';
                }else{
                    alert('操作失败请重试');
                }
            },
            error:function () {

            },
            dataType:'json'
        });
    });

    @if(isset($data['now_book']))
    var s = {!! $data['now_book'] !!};
    $('#book_img').attr('src',s.cover);
    $('input[name="status"][value="'+s.status+'"]').attr('checked','checked');
    $('input[name="book_cover"]').val(s.cover);
    $('select[data-name="version_year"]').val(s.version_year).trigger('change');
    $('input[name="book_name"]').val(s.bookname);
    $('.grade_sel').val(s.grade_id).trigger('change');
    $('.subject_sel').val(s.subject_id).trigger('change');
    $('.volume_sel').val(s.volumes_id).trigger('change');
    $('.version_sel').val(s.version_id).trigger('change');
    $('.book_isbn').val(s.isbn).trigger('change');
    $('.sort_select').html('<option selected value="'+s.sort_id+'">'+s.sort_name+'</option>');
    @endif
</script>
@endpush