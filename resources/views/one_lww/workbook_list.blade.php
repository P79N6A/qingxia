@extends('layouts.backend')

@section('lww_index','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link href="{{ asset('css/uploadfile.css') }}" rel="stylesheet">
@endpush

@section('content')

    @component('components.modal',['id'=>'replace_img','title'=>'替换'])
        @slot('body')
            <div id="fileuploader_single">Upload</div>
            <div id="done_img" class="row"></div>
        @endslot
        @slot('footer','')
    @endcomponent


    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent

    @component('components.modal',['id'=>'show_cover'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent

    @component('components.modal',['id'=>'add_workbook'])
        @slot('title','新增')
        @slot('body')
            <div class="col-md-12 book_info_box">
                <div class="col-md-6">
                    <div class="hot_books"></div>
                    <div>
                        <div>
                            <a class="btn btn-primary img_replace" data-toggle="modal" data-target="#replace_img">替换封面</a>
                            <a class="btn btn-danger show_cover">搜索封面</a>
                        </div>
                        <div class="col-md-6">
                            <a class="thumbnail"><img class="answer_pic new_book_img" src=""/></a>
                        </div>
                    </div>
                    <div class="isbn_imgs" style="height: 300px;overflow: scroll"></div>
                </div>
                <div class="col-md-6">

                    <div class="input-group" style="width: 100%">
                        <label class="input-group-addon">书名</label>
                        <input type="text" class="form-control book_name" data-real_name=""
                               value="">
                    </div>
                    <div class="isbn_now input-group">
                        <a class="input-group-addon">isbn:</a>
                        <input maxlength="17" class="now_val get_hot_book for_isbn_input form-control input-lg" style="font-size: 24px"  value="978-7-" />
                    </div>
                    <div class="input-group pull-left">
                        <label class="input-group-addon">年份</label>
                        <input type="text" maxlength="4" class="form-control version_year"
                               value="2018">
                    </div>

                    <div class="input-group pull-left">
                        <label class="input-group-addon">年级</label>
                        <select data-name="grade"
                                class="grade_id form-control select2 " style="width: 100%">
                            @forelse(config('workbook.grade') as $key => $grade)
                                <option value="{{ $key }}">{{ $grade }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="input-group pull-left">
                        <label class="input-group-addon">科目</label>
                        <select data-name="subject" class="subject_id form-control select2"  style="width: 100%">
                            @forelse(config('workbook.subject_1010') as $key => $subject)
                                <option value="{{ $key }}">{{ $subject }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="input-group pull-left" style="width: 100%">
                        <label class="input-group-addon">卷册</label>
                        <select data-name="volumes" class="volumes_id form-control select2" style="width: 100%">
                            @forelse(config('workbook.volumes') as $key => $volume)
                                @if($key>=0)
                                    <option value="{{ $key }}">{{ $volume }}</option>
                                @endif
                                @endforeach
                        </select>
                    </div>

                    <div style="width: 100%">
                        <div class="input-group pull-left">
                            <label class="input-group-addon">版本</label>
                            <select data-name="version" class="version_id form-control select2" style="width: 100%">
                                <option value="0">人教版</option>
                                @forelse(cache('all_version_now') as $key => $version)
                                    <option value="{{ $version->id }}">{{ $version->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-group pull-left">
                        <label class="input-group-addon">子版本</label>
                        <select class="select2 form-control ssort_select" style="width: 100%">
                            <option value="0"></option>

                            @forelse(\App\OneModel\ASubSort::where([['sort_id',$data['sort_id']],['ssort_name','!=','']])->select(['ssort_id','ssort_name'])->get() as $ssort)
                                <option @if($data['ssort_id']==$ssort->ssort_id) selected @endif value="{{ $ssort->ssort_id }}">{{ $ssort->ssort_name }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="input-group-addon">系列</label>
                        <select data-name="sort" class="form-control sort_name select2" style="width: 100%">
                            @if($data['sort_id']>=0)
                                <option value="{{ $data['sort_id'] }}"
                                        selected>{{ $data['sort_name']?$data['sort_name']:'未知' }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        @endslot
        @slot('footer')
            <a class="btn btn-danger" id="add_new_book">保存</a>
        @endslot
    @endcomponent


    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active"></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div id="rightContent">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">控制面板</h3>
                    </div>
                    <div class="box-body">
                        <a class="pull-right btn btn-primary" data-target="#add_workbook" data-toggle="modal" >新增</a>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">书本列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th class="col-xs-1">id</th>
                                <th>练习册信息</th>
                                <th class="col-xs-1">章节</th>
                                <th class="col-xs-1">参考答案/解析答案</th>
                            </tr>
                            @foreach($data['workbook_list'] as $k =>$v)
                                <tr>
                                    <td>{{ $v->id }}</td>
                                    <td style="width: 50%">
                                        <div class="col-md-12 info_box book_info_box" data-id="{{ $v->id }}" data-onlyid="{{ $v->has_only?$v->has_only->onlyid:0 }}">

                                            <div class="col-md-6">
                                                <p>1010jiajiao:<a href="http://www.1010jiajiao.com/daan/bookid_{{ $v->id }}.html" target="_blank">{{ $v->bookname }}</a></p>
                                                @if($v->has_only?$v->has_only->thread_id:0)
                                                <p>05wang:<a target="_blank" href="http://pc1.05wang.com/thread-{{ $v->has_only->thread_id }}-1-1.html">{{ $v->has_only->bookname05 }}</a></p>
                                                @endif

                                                <p>onlyid: {{ $v->has_only?$v->has_only->onlyid:'' }}</p>
                                                <div class="input-group">
                                                    <a class="input-group-addon">onlyname:</a>
                                                    <input type="text" value="{{ $v->has_only?$v->has_only->bookname:'' }}" class="form-control" />
                                                    <a class="input-group-addon update_onlyname">更新</a>
                                                </div>
                                                <div class="input-group">
                                                    <a class="input-group-addon">零五网name</a>
                                                    <input type="text" value="{{ $v->has_only?$v->has_only->bookname05:'' }}" class="form-control" />
                                                    <a class="input-group-addon update_bookname05">更新</a>
                                                </div>
                                                <div class="input-group">
                                                    <select class="select2 form-control select_onlyid" style="width: 100%">
                                                        <option value="-1">选择更改</option>
                                                        @forelse($data['other_likes'] as $other)
                                                            <option @if($v->onlyid==$other->onlyid) selected
                                                                    @endif value="{{ $other->onlyid }}">{{ $other->bookname }}</option>
                                                            @endforeach
                                                    </select>
                                                    <a data-id="{{ $v->id }}"
                                                       class="btn btn-primary input-group-addon confirm_change_onlyid">确认更改</a>
                                                </div>
                                                <br>
                                                <a class="btn btn-danger btn-block img_replace" data-toggle="modal" data-target="#replace_img">替换封面</a>
                                                <a data-id="{{ $v->onlyid }}" class="btn btn-danger btn-block onlyid_img_replace" >(零五网id{{ $v->has_only?$v->has_only->thread_id:0 }})更新为onlyid显示封面</a>
                                                <a class="thumbnail"><img class="answer_pic" src="{{ $v->cover }}"/></a>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="hot_books"></div>
                                                <div class="input-group" style="width: 100%">
                                                    <label class="input-group-addon">书名</label>
                                                    <input type="text" class="form-control book_name" data-real_name=""
                                                           value="{{ $v->bookname }}">
                                                </div>
                                                <div class="isbn_now input-group">
                                                    <a class="input-group-addon">isbn:</a>
                                                    <input maxlength="17" class="now_val for_isbn_input form-control input-lg" style="font-size: 24px"  value="{{ $v->isbn?convert_isbn($v->isbn):'978-7-' }}" />
                                                    <div class="input-group-btn">
                                                    <button type="button" class="input-lg btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作
                                                        <span class="fa fa-caret-down"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="btn btn-xs btn-block save_isbn">保存</a>
                                                        </li>
                                                        <li><a class="btn btn-xs btn-block search_isbn">搜索</a></li>

                                                    </ul>
                                                    </div>

                                                </div>
                                                <div class="input-group">
                                                    <label class="input-group-addon">年份</label>
                                                    <input type="text" maxlength="4" class="form-control version_year"
                                                           value="{{ $v->version_year }}">
                                                </div>

                                                <div class="input-group">
                                                    <label class="input-group-addon">年级</label>
                                                    <select data-name="grade"
                                                            class="grade_id form-control select2 pull-left ">
                                                        @forelse(config('workbook.grade') as $key => $grade)
                                                            <option value="{{ $key }}" @if($v->grade_id==$key)
                                                            selected="selected"
                                                                @endif>{{ $grade }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="input-group">
                                                    <label class="input-group-addon">科目</label>
                                                    <select data-name="subject" class="subject_id form-control select2">
                                                        @forelse(config('workbook.subject_1010') as $key => $subject)
                                                            <option value="{{ $key }}" @if($v->subject_id==$key)
                                                            selected="selected"
                                                                @endif>{{ $subject }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="input-group">
                                                    <label class="input-group-addon">卷册</label>
                                                    <select data-name="volumes" class="volumes_id form-control select2">
                                                        @forelse(config('workbook.volumes') as $key => $volume)
                                                            @if($key>=0)
                                                                <option value="{{ $key }}" @if($v->volumes_id==$key)
                                                                selected="selected"
                                                                    @endif>{{ $volume }}</option>
                                                            @endif
                                                            @endforeach
                                                    </select>
                                                </div>

                                                <div style="width: 100%">
                                                    <div class="input-group">
                                                        <label class="input-group-addon">版本</label>
                                                        <select data-name="version" class="version_id form-control select2">
                                                            <option value="0">人教版</option>
                                                            @forelse(cache('all_version_now') as $key => $version)
                                                                <option value="{{ $version->id }}"
                                                                        @if($v->version_id==$version->id ) selected="selected"
                                                                    @endif>{{ $version->name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                    <label class="input-group-addon">子版本</label>
                                                    <select class="select2 form-control ssort_select">
                                                        <option value="0"></option>
                                                        @forelse(\App\OneModel\ASubSort::where([['sort_id',$v->sort],['ssort_name','!=','']])->select(['ssort_id','ssort_name'])->get() as $ssort)
                                                            <option @if($v->ssort_id==$ssort->ssort_id) selected @endif value="{{ $ssort->ssort_id }}">{{ $ssort->ssort_name }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="input-group">
                                                    <label class="input-group-addon">系列</label>
                                                    <select data-name="sort" class="form-control sort_name select2">
                                                        @if($v->sort>=0)
                                                            <option value="{{ $v->sort }}"
                                                                    selected>{{ $v->hasSort?$v->hasSort->name:'未知' }}</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="btn btn-group">
                                                    <a data-all_id="{{ $v->id}}" class="save_book btn btn-danger">保存</a>
                                                    <a data-all_id="{{ $v->id}}" class="upgrade_book btn btn-success">升级</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p>
                                            <a target="_blank" class="btn btn-default"
                                               href="{{ route('no_chapter_analysis_index',[$v->onlyid,$v->version_year,$v->volumes_id,$v->id]) }}">处理解析</a>
                                        </p>
                                        <p>
                                        <a target="_blank" class="btn btn-primary"
                                           href="{{ route('one_lww_chapter',[$v->onlyid,$v->version_year,$v->volumes_id]) }}">创建/查看章节信息</a>
                                        </p>
                                        <p>
                                        <a target="_blank" class="btn btn-default"
                                           href="{{ route('lww_chapter',[$v->onlyid,$v->version_year,$v->volumes_id,$v->id]) }}">章节与页码匹配</a>
                                        </p>
                                        <p>
                                            <a target="_blank" class="btn btn-success"
                                               href="{{ route('preview_analysis_index',[$v->onlyid,$v->version_year,$v->volumes_id,$v->id]) }}">审核解析最终效果</a>
                                        </p>
                                        <p>
                                            <a target="_blank" class="btn btn-danger hide"
                                               href="{{ route('lww_show_page',[$v->onlyid.substr($v->version_year,-2).$v->volumes_id,0]) }}">处理单题解析</a>
                                        </p>
                                        <p>
                                            <a class="btn btn-info" target="_blank" href="{{ route('lww_upload_page',[$v->onlyid.substr($v->version_year,-2).$v->volumes_id]) }}">查看已上传页(共{{ $v->uploaded_imgs }}页)</a>
                                        </p>

                                    </td>
                                    <td>
                                        <p>
                                        <a class="btn btn-info" href="{{ route('audit_answer_detail',$v->id) }}"
                                           target="_blank">编辑参考答案(共{{ $v->has_answers_count }}页)</a>
                                        </p>
                                        <p>
                                            @if($data['analysis_list'][$k]['has_analysis'])
                                                {{ $data['analysis_list'][$k]['has_analysis'] }}
                                                /{{ $data['analysis_list'][$k]['has_analysis']+$data['analysis_list'][$k]['not_analysis'] }}
                                            @else
                                                @if($data['analysis_list'][$k]['has_analysis']==0 && $data['analysis_list'][$k]['not_analysis']==0)
                                                    <strong class="badge bg-red">请先创建章节</strong>
                                                @else
                                                    <strong class="badge bg-blue">暂未开始解析</strong>
                                                @endif
                                            @endif
                                        </p>
                                        <div>
                                            {{--<a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_chapter',$v->onlyid) }}">编辑</a>--}}
                                            {{--<a type="button" target="_blank" class="hide btn btn-primary" href="{{ route('lww_chapter',[$v->onlyid,$v->version_year]) }}">章节页码核对</a>--}}
                                            <a type="button" target="_blank" class="btn btn-primary hide"
                                               href="{{ route('one_lww_chapter',[$v->onlyid,$v->version_year,$v->volumes_id]) }}">解析答案编辑</a>
                                        {{--<a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_chapter',$v->onlyid) }}">家教网管理</a>--}}
                                        <!--<button type="button" class="btn btn-success update_modal" data-toggle="modal" data-target="#myModal">修改</button>
                                <button type="button" class="btn btn-danger del_book">删除</button>-->
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                {{ $data['workbook_list']->links() }}

            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquery.uploadfile.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2();
            {{--$('#search').click(function () {--}}
                {{--var word = $('#search_word').val();--}}
                {{--axios.post('{{ route('one_lww_ajax','search_bookname') }}', {word}).then(response => {--}}
                    {{--if (response.data.status === 1) {--}}
                        {{--window.location.href = '{{ route('one_lww_booklist') }}/?id_arr=' + response.data.data.str;--}}
                    {{--}--}}
                {{--})--}}
            {{--});--}}

            $(document).on('change', '.type_sel,.xilie_sel,.grade_sel,.subject_sel,.version_sel', function () {
                var type_id = $('.type_sel').val() ? $('.type_sel').val() : -1;
                var xilie_id = $('.xilie_sel').val() ? $('.xilie_sel').val() : -1;
                var grade_id = $('.grade_sel').val() ? $('.grade_sel').val() : -1;
                var subject_id = $('.subject_sel').val() ? $('.subject_sel').val() : -1;
                var version_id = $('.version_sel').val() ? $('.version_sel').val() : -1;
                window.location.href = '{{ route('one_lww_booklist') }}/-1/' + type_id + '/' + xilie_id + '/' + grade_id + '/' + subject_id + '/' + version_id;
            });

            /* $('.addbook_modal').click(function(){
                 $('#myModal').find('#myModalLabel').html('新增练习册');
                 $('#myModal').find('.btn_check').attr('data-id',0).html('确认添加');
                 $('#myModal').find('.bookname').val('');
                 $('#myModal').find('.book_type').val(0);
                 $('#myModal').find('.book_xilie').val(0);
                 $('#myModal').find('.book_grade').val(0);
                 $('#myModal').find('.book_subject').val(0);
                 $('#myModal').find('.book_version').val(0);
             });*/

            /*$('.update_modal').click(function(){
                var tr=$(this).parents('tr');
                var id=tr.attr('data-id');
                var bookname=tr.attr('data-name');
                var type_id=tr.attr('data-type');
                var xilie_id=tr.attr('data-xilie');
                var grade_id=tr.attr('data-grade');
                var subject_id=tr.attr('data-subject');
                var version_id=tr.attr('data-version');
                $('#myModal').find('#myModalLabel').html('修改练习册');
                $('#myModal').find('.btn_check').attr('data-id',id).html('确认修改');
                $('#myModal').find('.bookname').val(bookname);
                $('#myModal').find('.book_type').val(type_id);
                $('#myModal').find('.book_xilie').val(xilie_id);
                $('#myModal').find('.book_grade').val(grade_id);
                $('#myModal').find('.book_subject').val(subject_id);
                $('#myModal').find('.book_version').val(version_id);
            });*/

            /*$(document).on('click','.btn_check',function(){
                var book={};
                book.id=$(this).attr('data-id');
                book.bookname=$('.bookname').val();
                book.type_id=$('.book_type').val();
                book.xilie_id=$('.book_xilie').val();
                book.grade_id=$('.book_grade').val();
                book.subject_id=$('.book_subject').val();
                book.version_id=$('.book_version').val();
                api.data({'book':book}).post('admin/book/add_book').handle=function(s){
                    window.location.reload();
                }
            });*/

            /* $('.del_book').click(function(){
                 var a = confirm('确定要删除此书？');
                 if(a!==true){
                     return false;
                 }
                 var id=$(this).parents('tr').attr('data-id');
                 api.data({'id':id}).post('admin/book/del_book').handle=function(s){
                     window.location.reload();
                 }
             })*/


            //答案显示
            var cHeight = 0;

            $('.carousel').on('slide.bs.carousel', function (e) {
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


            //更改onlyid
            $('.confirm_change_onlyid').click(function () {
                let now_onlyid = $('.select_onlyid').val();
                let now_id = $(this).attr('data-id');

                axios.post('{{ route('one_lww_ajax','change_onlyid') }}', {now_id, now_onlyid}).then(response => {
                    if (response.data.status === 1) {
                        window.location.reload();
                    }
                })
            });


            $(".ssort_select").select2({
                tags: true,
                tokenSeparators: [',', ' ']
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

            //保存练习册
            $('.save_book').click(function () {
                if(!confirm('确认保存?')){
                    return false;
                }
                let box = $(this).parents('.info_box');

                let book_name = box.find('.book_name').val();
                if(book_name.includes('未选择') || book_name.includes('待定')){
                    return false;
                }
                let cover = box.find('.answer_pic').attr('src');
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();
                let sort_id = box.find('.sort_name').val();
                let version_year=box.find('.version_year').val();
                let ssort_id = box.find('.ssort_select').val();
                let ssort_name = box.find('.ssort_select option:selected').text();
                let isbn = box.find('.for_isbn_input').val();
                let book_id = box.attr('data-id');

                axios.post('{{ route('one_lww_ajax','save_bookinfo') }}',{isbn,book_id,cover,book_name,grade_id,subject_id,volumes_id,version_id,sort_id,version_year,ssort_id,ssort_name})
                    .then(response=>{
                        if(response.data.status===1){
                            $(this).parents('tr').remove();
                        }else{

                        }
                    });
            });

            //isbn
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

                        if($(this).hasClass('get_hot_book')){
                            axios.post('{{ route('one_lww_ajax','get_hot_books') }}',{ isbn }).then(response=>{
                                if(response.data.status===0){
                                    //$(this).addClass('bg-red').removeClass('bg-blue')
                                }else{
                                    let all_books = response.data.data;
                                    let book_str = '';
                                    for(let book in all_books){
                                        let single_book = all_books[book];
                                        book_str += `<a class="choose_hot_book btn btn-default" data-sort="${single_book['sort']}" data-isbn="${single_book['isbn']}" data-grade="${single_book['grade_id']}" data-subject="${single_book['subject_id']}" data-version="${single_book['version_id']}" title="${single_book['description']}">${single_book['bookname']}</a>`
                                    }
                                    console.log(book_str);
                                    box.find('.hot_books').html(book_str)
                                    //$(this).addClass('bg-blue').removeClass('bg-red')
                                }
                            });

                            isbn = isbn.replace(/-/g,'');
                            axios.get('{{ route('audit_isbn_cover') }}'+'/'+isbn).then(response=>{
                                if(response.data.status===1){
                                    let all_imgs = response.data.data;
                                    let now_html = '';
                                    for(let item of all_imgs){
                                        now_html += `<a class="col-md-4 thumbnail"><img class="set_cover" src="${item['cover_img']}"></a>`;
                                    }
                                    box.find('.isbn_imgs').html(now_html)

                                }
                            });
                        }
                        //get_related_sort
                    }

                }
            });


            //选择热门练习册
            $(document).on('click','.choose_hot_book',function () {
                let box = $(this).parents('.book_info_box');
                if($(this).hasClass('btn-default')){
                    $(this).removeClass('btn-default').addClass('btn-success')
                }else{
                    $(this).removeClass('btn-success').addClass('btn-default')
                }
                box.find('.grade_id').val($(this).attr('data-grade')).trigger('change');
                box.find('.subject_id').val($(this).attr('data-subject')).trigger('change');
                box.find('.verison_id').val($(this).attr('data-version')).trigger('change');
                box.find('.for_isbn_input').val($(this).attr('data-isbn'));
            });


            //搜索封面
            $('.show_cover').click(function () {
                let box = $(this).parents('.book_info_box');

                let isbn = box.find('.for_isbn_input').val().replace(/-/g,'');
                axios.get('{{ route('audit_isbn_cover') }}'+'/'+isbn).then(response=>{
                    if(response.data.status===1){
                        let all_imgs = response.data.data;
                        let now_html = '';
                        for(let item of all_imgs){
                            now_html += `<a class="col-md-4 thumbnail"><img class="set_cover" src="${item['cover_img']}"></a>`;
                        }
                        box.find('.isbn_imgs').html(now_html)

                    }
                });
            });

            //设置封面
            $(document).on('click','.set_cover',function () {
                let box = $(this).parents('.book_info_box');
                box.find('.new_book_img').attr('src',$(this).attr('src'));
            });


            $('.img_replace').click(function () {
                $('.img_replace').removeClass('replace_now');
                $(this).addClass('replace_now')
            })

            //上传封面

            $('#replace_img').on('show.bs.modal',function () {
                $(this).css('z-index','1111')
            });

            $('#show_img').on('show.bs.modal',function () {
                $(this).css('z-index','1111')
            });

            $("#fileuploader_single").uploadFile({
                url:"{{ route('upload_single') }}",
                fileName:"myfile",
                allowedTypes:"jpg,png,gif",
                multiple:false,
                showStatusAfterSuccess:false,
                onSuccess:function(files,data,xhr,pd)
                {
                    if(data.status===1){
                        let now_img = data.img;
                        $('.replace_now').parents('.book_info_box').find('.answer_pic').attr('src',now_img)
                        $('#replace_img').modal('hide');
                    }
                },
            });


            //新增练习册
            $(document).on('click','#add_new_book',function () {
                let box = $(this).parents('#add_workbook');
                let cover = box.find('.new_book_img').attr('src');
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();
                let sort_id = box.find('.sort_name').val();
                let version_year=box.find('.version_year').val();
                let ssort_id = box.find('.ssort_select').val();
                let ssort_name = box.find('.ssort_select option:selected').text();
                let isbn = box.find('.for_isbn_input').val();
                let book_name = box.find('.book_name').val();

                axios.post('{{ route('one_lww_ajax','add_new_book') }}',{cover,book_name,grade_id,subject_id,volumes_id,version_id,sort_id,version_year,ssort_id,ssort_name,isbn})
                    .then(response=>{
                        if(response.data.status===1){
                            window.location.reload();
                        }else{

                        }
                    });


            });


            //获取书名
            $('.book_name').focus(function(){
                let box = $(this).parents('.book_info_box');
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();
                let sort_id = box.find('.sort_name').val();
                //console.log(sort_id);

                if(sort_id>=0){
                    axios.post('{{ route('ajax_new_audit_list','show_only_bookname') }}',{sort_id,grade_id,subject_id,volumes_id,version_id}).then(response=>{
                        if(response.data.status===1){
                            if(response.data.data) {
                                if($('.newname_sel').length===0){
                                    box.find('.book_name').after('<select class="form-control newname_sel"><option value="">选择书名</option></select>');
                                    for (var i in response.data.data) {
                                        box.find('.newname_sel').append('<option value="' + response.data.data[i].newname + '">' + response.data.data[i].newname + '</option>');
                                    }
                                }
                            }
                        }
                    });
                }
            });

            $(document).on('change','.newname_sel',function (){
                let box = $(this).parents('.book_info_box');
                let new_name = $(this).val();
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();

                axios.post('{{ route('ajax_new_audit_list','get_final_name') }}',{new_name,grade_id,subject_id,volumes_id,version_id}).then(response=>{
                    if(response.data.status===1){
                        box.find('.book_name').val(response.data.data.final_name);
                    }
                });


            });

            //升级
            $('.upgrade_book').click(function () {
                if(!confirm('确认升级?')){
                    return false;
                }
                let book_id = $(this).attr('data-all_id');
                axios.post('{{ route('one_lww_ajax','upgrade_book') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }else{
                        alert('升级失败');
                    }
                })
            })

            //保存isbn
            $('.save_isbn').click(function () {
                let box = $(this).parents('.book_info_box');
                let book_id = box.attr('data-id');
                let isbn = box.find('.for_isbn_input').val();
                axios.post('{{ route('one_lww_ajax','save_isbn') }}',{book_id,isbn}).then(response=>{
                    if(response.data.status===1){
                        alert('保存成功');
                    }
                })
            });

            //搜索isbn
            $('.search_isbn').click(function () {
                let box = $(this).parents('.book_info_box');
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let sort_id = box.find('.sort_name').val();


                axios.post('{{ route('one_lww_ajax','get_hot_books') }}',{ grade_id,subject_id,sort_id }).then(response=>{
                    if(response.data.status===0){
                        box.find('.hot_books').html('所选年级，科目，系列暂无相关练习册')
                    }else{
                        let all_books = response.data.data;
                        let book_str = '';
                        for(let book in all_books){
                            let single_book = all_books[book];
                            book_str += `<div class="alert alert-info choose_hot_book" data-sort="${single_book['sort']}" data-isbn="${single_book['isbn']}" data-grade="${single_book['grade_id']}" data-subject="${single_book['subject_id']}" data-version="${single_book['version_id']}" ><h4>${single_book['bookname']}<i class="badge">搜索量：${single_book['searchnum']}</i></h4>${single_book['description']}</div>`
                        }
                        console.log(book_str);
                        box.find('.hot_books').html(book_str)
                        //$(this).addClass('bg-blue').removeClass('bg-red')
                    }
                });
            });


            //更新onlyid封面
            $('.onlyid_img_replace').click(function () {
                if(!confirm('确认更新onlyid展示图片')){
                    return false;
                }
                let onlyid = $(this).attr('data-id');
                let img = $(this).parent().find('.answer_pic').attr('src');
                axios.post('{{ route('one_lww_ajax','update_onlyid_img') }}',{onlyid,img}).then(response=>{
                   if(response.data.status===1){
                       alert('更新成功');
                   }
                });
            })

            //更新onlyanme
            $('.update_onlyname').click(function () {
                if(!confirm('确认更新onlyname')){
                    return false;
                }
                let onlyid = $(this).parents('.book_info_box').attr('data-onlyid');
                if(onlyid==0){
                    alert('无onlyid');
                    return false;
                }
                let update_type='bookname';
                let now_name = $(this).prev().val();
                axios.post('{{ route('one_lww_ajax','update_onlyinfo') }}',{onlyid,now_name,update_type}).then(response=>{
                    if(response.data.status===1){
                        alert('更新成功');
                    }
                });
            })

            //更新bookname05
            $('.update_bookname05').click(function () {
                if(!confirm('确认更新零五网name')){
                    return false;
                }
                let onlyid = $(this).parents('.book_info_box').attr('data-onlyid');
                if(onlyid==0){
                    alert('无onlyid');
                    return false;
                }
                let update_type='bookname05';
                let now_name = $(this).prev().val();
                axios.post('{{ route('one_lww_ajax','update_onlyinfo') }}',{onlyid,now_name,update_type}).then(response=>{
                    if(response.data.status===1){
                        alert('更新成功');
                    }
                });
            });

            //isbn获取热门练习册
            {{--$('.get_hot_book').bind('input propertychange',function () {--}}
                {{--if($(this).val().length===17){--}}
                    {{--let isbn = $(this).val();--}}

                    {{--axios.post('{{ route('one_lww_ajax','get_hot_books') }}',{ isbn }).then(response=>{--}}
                        {{--if(response.data.status===0){--}}
                            {{--//$(this).addClass('bg-red').removeClass('bg-blue')--}}
                        {{--}else{--}}
                            {{--//$(this).addClass('bg-blue').removeClass('bg-red')--}}
                        {{--}--}}
                    {{--})--}}
                {{--}--}}
            {{--})--}}

        })
    </script>
@endpush