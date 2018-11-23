@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    .search_book_cover{
        height:150px;
    }
</style>
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
                    <div class="radio radio-status">
                        <label>
                            <input name="status" type="radio" value="1">有效
                        </label>
                        <label>
                            <input name="status" type="radio" checked value="0">无效
                        </label>
                    </div>
                    <div class="checkbox radio-type">
                        <label>
                            <input name="type" type="checkbox" value="1">解析
                        </label>
                        <label>
                            <input name="type" type="checkbox" value="2">点读
                        </label>
                        <label>
                            <input name="type" type="checkbox" value="3">跟读
                        </label>
                        <label>
                            <input name="type" type="checkbox" value="4">听写
                        </label>
                    </div>

                    @if(isset($data['now_book']))
                        <div class="row">
                        <div class=" col-md-3">
                    <a class="thumbnail"><img id="book_img" src=""></a>
                        </div>
                      </div>
                    @endif

                    {{--<input class="form-control" name="book_cover" placeholder="测试图片地址" value="//gw3.alicdn.com/bao/uploaded/i2/TB1oiATGVXXXXajXpXXXXXXXXXX_!!0-item_pic.jpg">--}}
                    <hr>
                    <div class="form-group" id="version_name_box">
                        <div class="input-group">
                        <label class="input-group-addon">已绑定book_id</label>
                        <input class="form-control" disabled id="real_id" />
                        <label class="input-group-addon btn btn-primary" id="clear_real_id">清除</label>
                        </div>
                        <select data-name="version_year" class="form-control select2 pull-left" style="width:20%">
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2013">2012</option>
                            <option value="2013">2011</option>
                        </select>
                        <div class="input-group pull-right" style="width:80%">
                        <input class="form-control pull-right" name="book_name" placeholder="练习册名称">
                            {{--<a class="btn btn-default input-group-addon" id="book_name_search">搜索</a>--}}
                        </div>
                    </div>
                    <div class="input-group" style="width:100%">

                        <select class="form-control grade_sel pull-left select2" style="width:50%">
                            @foreach(config('workbook.grade') as $key1=>$value1)
                                @if($key1>0)
                                <option value="{{ $key1 }}">{{ $value1 }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-control subject_sel pull-left select2" style="width:50%">
                            @foreach(config('workbook.subject_1010') as $key1=>$value1)
                                @if($key1>0)
                                <option value="{{ $key1 }}">{{ $value1 }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <br>
                    <div class="input-group" style="width:100%">
                        <select class="form-control volume_sel pull-left select2" style="width:50%">
                            @foreach(config('workbook.volumes') as $key1=>$value1)
                                <option value="{{ $key1 }}">{{ $value1 }}</option>
                            @endforeach
                        </select>
                        <select data-name="version_id" class="version_sel form-control select2" style="width:50%"
                                tabindex="-1" aria-hidden="true">
                            @foreach($data['version'] as $value1)
                                <option value="{{ intval($value1->id) }}">{{ $value1->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="input-group" id="isbn_version_box" style="width:100%">
                        <div class="input-group pull-left" style="width:45%">
                            <input class="form-control book_isbn" value="" placeholder="isbn"/>
                            {{--<label class="btn btn-default input-group-addon" id="book_isbn_search">搜索</label>--}}
                        </div>
                        <div class="input-group" style="width:50%">
                            <label class="input-group-addon">系列</label>
                            <select data-name="sort" class="form-control sort_select "></select>
                        </div>
                        {{--<input class="form-control book_page" value="" placeholder="总页数" style="width:50%"/>--}}
                    </div>
                    <br>
                    {{--<div class="input-group">--}}
                        {{--<label class="input-group-addon">系列</label>--}}
                        {{--<select data-name="sort" class="form-control sort_select "></select>--}}
                    {{--</div>--}}
                    <hr>
                    <div class="row"></div>
                    <a class="btn btn-danger" id="save_book">保存</a>
                    <a class="btn btn-default" href="{{ route('lww_index') }}">返回</a>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="search_box">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="{{ asset('/js/select2.full.min.js') }}"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script src="{{ asset('js/get_search_1010.js').'?t='.time() }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script>
    boSearch.open();
    var lazy = new LazyLoad();
    var token= "{{ csrf_token() }}";
    $('.select2').select2();

    $('#clear_real_id').click(function () {
       $('#real_id').val('');
    });

    function add_to(now) {
        let single_box = $(now).parents('.single_book_box');
        let book_id  = $(single_box).attr('data-id');
        axios.post('{{ route('add_check_it') }}',{book_id}).then(response=>{
            if(response.data.status===0){
                alert(response.data.msg);
            }else{
                $('input[name="book_name"]').val(single_box.find('.book_name').attr('data-name'));
                $('#real_id').val(single_box.find('.book_id').attr('data-id'));
                $('.grade_sel').val(single_box.find('.book_grade').attr('data-grade')).trigger('change');
                $('.subject_sel').val(single_box.find('.book_subject').attr('data-subject')).trigger('change');
                $('.volume_sel').val(single_box.find('.book_volume').attr('data-volume')).trigger('change');
                $('.version_sel').val(single_box.find('.book_version').attr('data-version')).trigger('change');
                $('.book_isbn').val(single_box.find('.book_isbn').attr('data-isbn')).trigger('change');
                $('.sort_select').html('<option selected value="'+single_box.find('.book_sort').attr('data-sort')+'">'+single_box.find('.book_sort').html()+'</option>');
            }
        }).catch(function (error) {
            console.log(error)
        });
    }



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
            cache: false
        },

        escapeMarkup: function (markup) {
            return markup;
        }, // 自定义格式化防止xss注入
        minimumInputLength: 1,//最少输入多少个字符后开始查询
        templateResult: function formatRepo(repo) {
            if (repo.loading) return repo.text;
            return '<option value=\'' + repo.id + '\'>' + repo.name + '</option>';
        }, // 函数用来渲染结果
        templateSelection: function formatRepoSelection(repo) {
            return repo.name || repo.text;
        },
    });

    //保存书籍
    $('#save_book').click(function () {
        var book_id = '{{ $data['book_id'] }}';
        var book_real_id = $('#real_id').val();
        var book_status = $('input[name="status"]:checked').val();
//        var book_img = $('input[name="book_cover"]').val();
        var book_version_year = $('select[data-name="version_year"]').val();
        var book_name = $('input[name="book_name"]').val();
        var book_grade = $('.grade_sel').val();
        var book_subject = $('.subject_sel').val();
        var book_volume = $('.volume_sel').val();
        var book_version = $('.version_sel').val();
        var book_isbn = $('.book_isbn').val();
        var book_page = $('.book_page').val();
        var book_sort = $('.sort_select').val();
        var book_type = '';
        $('input[name="type"]:checked').each(function () {
            book_type += $(this).val()+','
        });
        if(book_type==''){
            alert('请选择练习册类型');
            return false;
        }
        var o ={
            _token:token,
            book_id:book_id,
            book_real_id:book_real_id,
            book_status:book_status,
//            book_img:book_img,
            book_version_year:book_version_year,
            book_name:book_name,
            book_grade:book_grade,
            book_subject:book_subject,
            book_volume:book_volume,
            book_version:book_version,
            book_isbn:book_isbn,
            book_page:book_page,
            book_sort:book_sort,
            book_type:book_type
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
    var cover_src = '{{ Storage::url('storage/all_book_pages/'.$data['book_id']) }}';
    var s = {!! $data['now_book'] !!};
    if(s.status==1){
        $('input[name="status"]').eq(0).attr('checked','checked');
    }
    if(s.jiexi==1){
        $('input[name="type"]').eq(0).attr('checked','checked');
    }
    if(s.diandu==1){
        $('input[name="type"]').eq(1).attr('checked','checked');
    }
    if(s.gendu==1){
        $('input[name="type"]').eq(2).attr('checked','checked');
    }
    if(s.tingxie==1){
        $('input[name="type"]').eq(3).attr('checked','checked');
    }
    $('#book_img').attr('src',cover_src+'/cover.jpg');
    $('#real_id').val(s.real_id);
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
    
    {{--//书名搜索--}}
    {{--$('#book_name_search').click(function () {--}}
        {{--var word = $('input[name="book_name"]').val();--}}
        {{--var o = {--}}
            {{--_token:token,--}}
            {{--word:word,--}}
            {{--type:'book_name'--}}
        {{--};--}}
        {{--$.post('{{ route('lww_search_book') }}',o,function (s) {--}}
            {{--let result = JSON.parse(s);--}}
            {{--if(result.total>0){--}}
                {{--let results = result.matches;--}}
                {{--let results_len = results.length;--}}
                {{--if(results_len>0){--}}
                    {{--let now = '';--}}
                    {{--for(let i=0;i<results_len;i++){--}}
                        {{--let now_result = results[i].attrs;--}}
                        {{--now += '<a class="thumbnail col-md-3 text-center search_result" data-real_id="'+results[i].id+'" data-name="'+now_result.bookname+'" data-gid="'+now_result.grade_id+'"  data-sid="'+now_result.subject_id+'" data-vid="'+now_result.volumes_id+'" data-ver_id="'+now_result.version_id+'" data-isbn="'+now_result.isbn+'" data-sort="'+now_result.sort_id+'" data-sort_name="'+now_result.sort_name+'" title="'+now_result.isbn+'"><img class="search_book_cover" src="'+now_result.cover+'" alt=""><strong>'+now_result.bookname+'</strong></a>'--}}
                    {{--}--}}
                    {{--if($('.search_name_box').length==0){--}}
                        {{--$('#version_name_box').after('<div class="search_name_box col-md-12">'+now+'</div>');--}}
                    {{--}else{--}}
                        {{--$('.search_name_box').html(now)--}}
                    {{--}--}}
                {{--}--}}
            {{--}else{--}}
                {{--alert('暂无匹配结果');--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}
    {{----}}
    {{--//isbn搜索--}}
    {{--$('#book_isbn_search').click(function () {--}}
        {{--var word = $('.book_isbn').val();--}}
        {{--var o = {--}}
            {{--_token:token,--}}
            {{--word:word,--}}
            {{--type:'book_isbn'--}}
        {{--};--}}
        {{--$.post('{{ route('lww_search_book') }}',o,function (s) {--}}
            {{--let result = JSON.parse(s);--}}
            {{--if(result.total>0){--}}
                {{--let results = result.matches;--}}
                {{--let results_len = results.length;--}}
                {{--if(results_len>0){--}}
                    {{--let now = '';--}}
                    {{--for(let i=0;i<results_len;i++){--}}
                        {{--let now_result = results[i].attrs;--}}
                        {{--now += '<a class="thumbnail col-md-3 text-center search_result" data-real_id="'+results[i].id+'" data-name="'+now_result.bookname+'" data-gid="'+now_result.grade_id+'"  data-sid="'+now_result.subject_id+'" data-vid="'+now_result.volumes_id+'" data-ver_id="'+now_result.version_id+'" data-isbn="'+now_result.isbn+'" data-sort="'+now_result.sort_id+'" data-sort_name="'+now_result.sort_name+'" title="'+now_result.isbn+'"><img class="search_book_cover" src="'+now_result.cover+'" alt=""><strong>'+now_result.bookname+'</strong></a>'--}}
                    {{--}--}}
                    {{--if($('.search_isbn_box').length==0){--}}
                        {{--$('#isbn_version_box').after('<div class="search_isbn_box col-md-12">'+now+'</div>');--}}
                    {{--}else{--}}
                        {{--$('.search_isbn_box').html(now)--}}
                    {{--}--}}
                {{--}--}}
            {{--}else{--}}
                {{--alert('暂无匹配结果');--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}
    
//    //确认搜索结果
//    $(document).on('click','.search_result',function () {
//        $('input[name="book_name"]').val($(this).attr('data-name'));
//        $('#real_id').val($(this).attr('data-real_id'));
//        $('.grade_sel').val($(this).attr('data-gid')).trigger('change');
//        $('.subject_sel').val($(this).attr('data-sid')).trigger('change');
//        $('.volume_sel').val($(this).attr('data-vid')).trigger('change');
//        $('.version_sel').val($(this).attr('data-ver_id')).trigger('change');
//        $('.book_isbn').val($(this).attr('data-isbn')).trigger('change');
//        $('.sort_select').html('<option selected value="'+$(this).attr('data-sort')+'">'+$(this).attr('data-sort_name')+'</option>');
//    })
</script>
@endpush