@extends('layouts.backend')

@section('audit_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .panel-body img{
            height: 400px;
        }
    </style>
@endpush



@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">答案审核</li>
        </ol>
    </section>
    <section class="content">
        @component('components.modal',['id'=>'show_img'])
            @slot('title','查看')
            @slot('body','')
            @slot('footer','')
        @endcomponent

        @component('components.modal',['id'=>'show_cover'])
            @slot('title','')
            @slot('body')
               <div class="col-md-12"></div>
            @endslot
            @slot('footer','')
        @endcomponent
        <div class="box box-primary">
            <div class="box-header">求助列表</div>
            <div class="box-body">
                <div class="col-md-12">
                    @foreach($data['all_isbn'] as $isbn)
                        @php if($isbn->hasIsbnDetail){
                                $preg_grade_id = $isbn->hasIsbnDetail->preg_grade_id;
                                $preg_subject_id = $isbn->hasIsbnDetail->preg_subject_id;
                                $preg_volumes_id = $isbn->hasIsbnDetail->preg_volumes_id;
                                $preg_version_id = $isbn->hasIsbnDetail->preg_version_id;
                                $preg_sort_id = $isbn->hasIsbnDetail->preg_sort_id;
                                $real_bookname = $isbn->hasIsbnDetail->real_bookname;
                            } else{
                                $preg_grade_id = -1;
                                $preg_subject_id = -1;
                                $preg_volumes_id = -1;
                                $preg_version_id = -1;
                                $preg_sort_id = -1;
                                $real_bookname = '';
                            }
                        @endphp
                        <div class="col-md-6 info_box" data-isbn="{{ $isbn->isbn }}">
                            <div class="panel panel-primary">
                                <div class="panel panel-heading">
                                    {{ $isbn->isbn }}
                                    求助数<em class="badge bg-red">{{ $isbn->num }}</em>
                                    搜索数<em class="badge bg-red">{{ $isbn->hasSearchTemp?$isbn->hasSearchTemp->searchnum:0 }}</em>
                                </div>
                                <div class="panel panel-body">
                                    <a class="btn btn-block btn-default show_offical_book">已有练习册<em class="badge bg-red has_book_num">{{ $isbn->has_offical_book_count }}</em></a>
                                    <p class="print_description" style="height: 100px;overflow: scroll">{{ $isbn->hasIsbnDetail?$isbn->hasIsbnDetail->print_description:'' }}</p>
                                    <p class="col-md-12">
                                        <div class="col-md-6">
                                        <a class="thumbnail">
                                            <img class="answer_pic" src="{{ config('workbook.user_image_url').$isbn->hasWorkBookUserFirst->cover_img }}" alt="" />
                                        </a>
                                        <a class="btn btn-primary btn-block show_cover" data-isbn="{{ $isbn->isbn }}">选择封面</a>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('audit_isbn_detail',$isbn->isbn) }}">查看该isbn所有用户上传求助</a>
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">书名</label>
                                                <input type="text" class="form-control book_name" data-real_name="{{ $real_bookname }}" value="{{ $real_bookname?$real_bookname:$isbn->hasWorkBookUserFirst->sort_name }}">
                                            </div>
                                            <div class="input-group hide">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" maxlength="4" class="form-control version_year" value="">
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade" class="grade_id form-control select2 pull-left " >


                                                    @forelse(config('workbook.grade') as $key => $grade) @if($key>=0)<option value="{{ $key }}" @if($isbn->hasWorkBookUserFirst->grade_id==$key or $preg_grade_id==$key) selected="selected" @endif>{{ $grade }}</option>@endif @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2">

                                                    @forelse(config('workbook.subject_1010') as $key => $subject) @if($key>=0)<option value="{{ $key }}" @if($isbn->hasWorkBookUserFirst->subject_id==$key or $preg_subject_id==$key) selected="selected" @endif>{{ $subject }}</option>@endif @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    @forelse(config('workbook.volumes') as $key => $volume) @if($key>=0)<option value="{{ $key }}" @if($isbn->hasWorkBookUserFirst->volumes_id==$key or $preg_volumes_id==$key) selected="selected" @endif>{{ $volume }}</option>@endif @endforeach
                                                </select>
                                            </div>

                                            <div style="width: 100%">
                                                <div class="input-group pull-left">
                                                    <label class="input-group-addon">版本</label>
                                                    <select data-name="version" class="version_id form-control select2">
                                                        <option value="0">人教版</option>
                                                        @forelse(cache('all_version_now') as $key => $version) @if($version->id>-1)<option value="{{ $version->id }}" @if($isbn->hasWorkBookUserFirst->version_id==$version->id or $preg_version_id==$version->id) selected="selected" @endif>{{ $version->name }}</option>@endif @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <label class="input-group-addon">系列</label>
                                                @php
                                                    $now_sort = $isbn->hasWorkBookUserFirst->sort_id>0?$isbn->hasWorkBookUserFirst->sort_id:$preg_sort_id;
                                                    $now_sort_sql = cache('all_sort_now')->where('id',$now_sort)->first()
                                                @endphp
                                                <select data-name="sort" class="form-control sort_name select2">
                                                    <option value="{{ $now_sort }}" selected>{{ $now_sort_sql?$now_sort_sql->name:'待定' }}</option>
                                                </select>
                                            </div>

                                            <div class="btn btn-group">
                                                <a data-id="{{ $isbn->isbn }}" class="save_book btn btn-danger">保存</a>
                                                <a class="btn btn-primary hide generate_name">生成</a>
                                            </div>
                                            <div>
                                                <p>处理人：</p>
                                                <p>处理方法：</p>
                                                <a class="btn btn-primary btn-block" target="_blank" href="{{ route('audit_answer_detail',10264679) }}">查看该isbn所有用户上传答案</a>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="box-footer">{{ $data['all_isbn']->links() }}</div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
            $('.select2').select2();

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

            //显示所有封面
            $('.show_cover').click(function () {
                let isbn = $(this).attr('data-isbn');
                axios.get('{{ route('audit_isbn_cover') }}'+'/'+isbn).then(response=>{
                   if(response.data.status===1){
                       let all_imgs = response.data.data;
                       let now_html = '';
                       console.log(all_imgs);
                       for(let item of all_imgs){
                           now_html += `<a class="col-md-4 thumbnail"><img class="set_cover" src="${item['cover_img']}"></a>`;
                       }
                       $('#show_cover .modal-body').html(`<div class="col-md-12" data-isbn="${isbn}">${now_html}</div>`);
                       $('#show_cover').modal('show');

                   }
                });
            });

            //选择封面
            $(document).on('click','.set_cover',function () {
                if(!confirm('确认选择封面')){
                    return false;
                }
                let isbn = $(this).parents('.col-md-12').attr('data-isbn');
                $(`.info_box[data-isbn=${isbn}] .answer_pic`).attr('src',$(this).attr('src'));
                $('#show_cover').modal('hide');
            });

            //查看已有练习册
            $(document).on('click','.show_offical_book',function () {
                let now_isbn = $(this).parents('.info_box').attr('data-isbn');
                let has_book_num = parseInt($(this).find('.has_book_num').html());
                if(has_book_num===0){
                    return false;
                }
                axios.post('{{ route('ajax_new_audit_list','show_offical_book') }}',{now_isbn}).then(response=>{
                    if(response.data.status===1){
                        let all_books = response.data.data;
                        let now_html = '';
                        for(let item of all_books){
                            now_html += `
                            <div class="col-md-6 info_box">
                            <div class="panel panel-primary">
                                <div class="panel panel-heading">
                                收藏数<em class="badge bg-red">${item['collect_count']}</em>
                                </div>
                                <div class="panel panel-body">
                                    <p class="col-md-12">
                                        <div class="col-md-6">
                                        <a class="thumbnail">
                                            <img class="answer_pic" src="${item['cover']}" alt="" />
                                        </a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">书名</label>
                                                <input type="text" disabled class="form-control book_name" value="${item['bookname']}">
                                            </div>
                                            <div class="input-group">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" disabled maxlength="4" class="form-control version_year" value="${item['version_year']}">
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">年级</label>
                                                <input type="text" disabled class="form-control grade_id" value="${item['grade_id']}">
                                            </div>

                            <div class="input-group pull-left">
                                <label class="input-group-addon">科目</label>
                                <input type="text" disabled class="form-control subject_id" value="${item['subject_id']}">
                            </div>
                            <div class="input-group pull-left">
                                <label class="input-group-addon">卷册</label>
                                <input type="text" disabled class="form-control volumes" value="${item['volumes_id']}">
                            </div>
                            <div style="width: 100%">
                                <div class="input-group pull-left">
                                    <label class="input-group-addon">版本</label>
                                    <input type="text" disabled class="form-control volumes" value="${item['version_id']}">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="input-group-addon">系列</label>
                            <input type="text" disabled class="form-control sort_id" value="${item['sort']}">
                        </div>

                                            <div class="btn btn-group">
                                                <a data-id="${item['id']}" class="save_book btn btn-danger">选定此练习册</a>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>`
                        }
                        $('#show_cover .modal-body').html(`<div class="col-md-12" data-isbn="${now_isbn}">${now_html}</div>`);
                        $('#show_cover').modal('show');
                    }
                }).catch();

                //$('#show_cover .modal-body').html(`<div class="col-md-12" data-isbn="${isbn}">${now_html}</div>`);
                //$('#show_cover').modal('show');
            });

            let grade_info = new Map([['一年级',1],['二年级',2],['三年级',3],['四年级',4],['五年级',5],['六年级',6],['六年 级',6],['七年级',7],['初一',7],['八年级',8],['初二',8],['九年级',9],['初三',9]]);

            let subject_info = new Map([['语文',1],['数学',2],['英语',3],['物理',4],['化学',5],['地理',6],['历史',7],['政治',8],['思想品德',8],['道德与法制',8],['生物',9],['科学',10]]);

            let volumes_info = new Map([['上册',1],['上 /',1],['下册',2],['全一册',3],['中考用书',4],['高考用书',5],['暑假作业',6],['寒假作业',7],['小升初用书',8],['必修1',9],['必修2',10],['必修3',11],['必修4',12],['必修5',13],['选修1',15],['选修2',16],['选修3',17],['选修4',18]]);

//            //1=>'语文','数学','英语','物理','化学','地理','历史','政治','生物','科学','综合'
//            let subject_info = {1:'语文',2:'数学',3:'英语',4:'物理',5:'化学',6:'地理',7:'历史',8:'政治',9:'生物',10:'科学',11:'思想品德',12:'道德与法制'};
//            //0=>'未选择','上册','下册','全一册','中考用书','高考用书','暑假作业','寒假作业','小升初用书','必修1','必修2','必修3','必修4','必修5','必修其它','选修1','选修2','选修3','选修4','选修其它'
//            let volumes_info = {1:'上册',2:'下册',3:'全一册',4:'中考用书',5:'高考用书',6:'暑假作业',7:'寒假作业',8:'小升初用书',9:'必修1',10:'必修2',11:'必修3',12:'必修4',13:'必修5',15:'选修1',16:'选修2',17:'选修3',18:'选修4'}


            $('.info_box').each(function (i) {
                let now_text = $(this).find('.print_description').html();
                if($(this).find('.grade_id').val()<=0){
                    for(let [grade_name,grade_id] of grade_info){
                        if(now_text.includes(grade_name))    {
                            $(this).find('.grade_id').val(grade_id).trigger('change');
                        }
                    }
                }
                if($(this).find('.subject_id').val()<=0) {
                    for (let [subject_name, subject_id] of subject_info) {
                        if (now_text.includes(subject_name)) {
                            $(this).find('.subject_id').val(subject_id).trigger('change');
                        }
                    }
                }
                if($(this).find('.volumes_id').val()<=0) {
                    for (let [volumes_name, volumes_id] of volumes_info) {
                        if (now_text.includes(volumes_name)) {
                            $(this).find('.volumes_id').val(volumes_id).trigger('change');
                        }
                    }
                }
                if($(this).find('.grade_id').val()>0 && $(this).find('.subject_id').val()>0 && $(this).find('.volumes_id').val()>0 && $(this).find('.version_id').val()>=0 && $(this).find('.sort_name').val()>=0 && $(this).find('book_name').attr('data-real_name')==='') {
                    let sort_name = $(this).find('.sort_name option:selected').text();
                    if(sort_name===undefined){
                        sort_name = $(this).find('.sort_name').select2('data')[0].name
                    }
                    let bookname_now = sort_name + $(this).find('.grade_id option:selected').text() + $(this).find('.subject_id option:selected').text() + $(this).find('.volumes_id option:selected').text() + $(this).find('.version_id option:selected').text();
                    $(this).find('.book_name').val(bookname_now)
                }
                //console.log($(this).find('.print_description').html());
            });


            //change book name
            $(document).on('change','.grade_id,.subject_id,.volumes_id,.version_id,.sort_name',function () {
                let box = $(this).parents('.info_box');
                let sort_name = box.find('.sort_name option:selected').text();

                if(sort_name===undefined || sort_name.length<2){
                    sort_name = box.find('.sort_name').select2('data')[0].name
                }

                let bookname_now = sort_name + box.find('.grade_id option:selected').text() + box.find('.subject_id option:selected').text() + box.find('.volumes_id option:selected').text() + box.find('.version_id option:selected').text();
                box.find('.book_name').val(bookname_now);
            });

            //save_book
            $(document).on('click','.save_book',function () {
                if(!confirm('确认保存?')){
                    return false;
                }
                let box = $(this).parents('.info_box');
                let isbn = box.attr('data-isbn');
                let book_name = box.find('.book_name').val();
                if(book_name.includes('未选择') || book_name.includes('待定')){
                    return false;
                }
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();
                let sort_id = box.find('.sort_name').val();
                axios.post('{{ route('ajax_new_audit_list','save_bookinfo') }}',{isbn,book_name,grade_id,subject_id,volumes_id,version_id,sort_id})
                    .then(response=>{
                        if(response.data.status===1){
                            box.remove();
                        }
                    });

                {{--$(now_box).find('.single_lxc').each(function () {--}}
                    {{--now_book_ids.push($(this).attr('data-id'));--}}
                {{--});--}}
{{--//                $(`input[name="check_for_change"]:checked`).each(function (i) {--}}
{{--//                    now_book_ids.push((this).value);--}}
{{--//                });--}}
                {{--if(now_book_ids.length<1){--}}
                    {{--alert('请选择求助');--}}
                    {{--return false;--}}
                {{--}--}}
                {{--if(type==='new_book'){--}}
                    {{--let info_box = $('.need_info').parent();--}}
                    {{--let bookname  = info_box.find('.book_name').val();--}}
                    {{--let cover  = $(now_box).find('.show_main .cover_img img').attr('data-original');--}}
                    {{--let grade_id  = info_box.find('.grade_id').val();--}}
                    {{--let subject_id  = info_box.find('.subject_id').val();--}}
                    {{--let volumes_id  = info_box.find('.volumes_id').val();--}}
                    {{--let version_id  = info_box.find('.version_id').val();--}}
                    {{--let version_year  = info_box.find('.version_year').val();--}}
                    {{--let sort  = info_box.find('.sort_name').val();--}}
                    {{--let ssort_id  = info_box.find('.subsort_name').val();--}}
                    {{--let hdid = $(now_box).find('.hdid').val();--}}
                    {{--axios.post('{{ route('audit_api','save_offical_book')  }}',{book_id,bookname,isbn,cover,grade_id,subject_id,volumes_id,version_id,version_year,sort,ssort_id,now_book_ids,hdid}).then(response=>{--}}
                        {{--if(response.data.status===1){--}}
                            {{--alert('保存成功');--}}
                            {{--$(`input[name="check_for_change"]:checked`).parents('.col-md-4').remove();--}}
                        {{--}--}}
                    {{--}).catch(function (error) {--}}
                        {{--alert('保存失败');--}}
                        {{--console.log(error);--}}
                    {{--})--}}
                {{--}else{--}}
                    {{--axios.post('{{ route('audit_api','to_offical_book')  }}',{type,book_id,isbn,now_book_ids}).then(response=>{--}}
                        {{--if(response.data.status===1){--}}
                            {{--$(`input[name="check_for_change"]:checked`).parents('.col-md-4').remove();--}}
                        {{--}--}}
                    {{--}).catch(function (error) {--}}
                        {{--console.log(error);--}}
                    {{--})--}}
                {{--}--}}
            });

        })
    </script>
@endpush