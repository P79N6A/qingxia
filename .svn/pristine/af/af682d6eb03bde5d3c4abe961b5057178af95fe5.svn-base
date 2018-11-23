@extends('layouts.backend')

@section('audit_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .panel-body img{
            height: 400px;
        }

         .answer_pic {
             min-width: 150px;
             max-height: 350px;
             min-height: 200px;
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
            @slot('title')
            <strong>查看图片</strong>
                <span class="pull-right">
                    <a class="page_now btn btn-primary" data-type="prev">上一页</a>
                    <a class="page_now btn btn-primary" data-type="next">下一页</a>
                </span>
            @endslot
            @slot('body','')
            @slot('footer')
            <span class="pull-right">
                    <a class="page_now btn btn-primary" data-type="prev">上一页</a>
                    <a class="page_now btn btn-primary" data-type="next">下一页</a>
                </span>
            @endslot
        @endcomponent

        @component('components.modal',['id'=>'show_cover'])
            @slot('title','')
            @slot('body')
               <div class="col-md-12"></div>
            @endslot
            @slot('footer','')
        @endcomponent
        <div class="box box-primary">
            <div class="box-header">
                求助列表
            </div>
            <div class="box-body">
                <div class="col-md-12">
                        <div class="col-md-6 info_box" data-isbn="{{ $data['book']->isbn }}">
                            <div class="panel panel-primary">
                                <div class="panel panel-body">
                                    <a class="btn btn-block btn-default show_offical_book">已有练习册<em class="badge bg-red has_book_num"></em></a>
                                    <p class="print_description" style="height: 100px;overflow: scroll">{{ $data['book']->description }}</p>
                                    <p class="col-md-12">
                                        <div class="col-md-6">
                                        <a class="thumbnail">
                                            <img class="answer_pic" src="{{ config('workbook.user_image_url').($data['book']->user_book->count()?$data['book']->user_book[0]->cover_img:'' )}}" alt="" />
                                        </a>
                                        <a class="btn btn-primary btn-block show_cover" data-isbn="{{ $data['book']->isbn }}">选择封面</a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">书名</label>
                                                <input type="text" class="form-control book_name"  value="@if($data['existing_book']->count()>0){{ $data['existing_book'][0]->bookname }}@endif">
                                            </div>
                                            <div class="input-group pull-left" >
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" maxlength="4" class="form-control version_year" value="">
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade" class="grade_id form-control select2 pull-left " >
                                                    @forelse(config('workbook.grade') as $key => $grade) @if($key>=0)<option value="{{ $key }}" @if($data['book']->grade_id==$key ) selected="selected" @endif>{{ $grade }}</option>@endif @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2">
                                                    @forelse(config('workbook.subject_1010') as $key => $subject) @if($key>=0)<option value="{{ $key }}" @if($data['book']->subject_id==$key) selected="selected" @endif>{{ $subject }}</option>@endif @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    @forelse(config('workbook.volumes') as $key => $volume) @if($key>=0)<option value="{{ $key }}" @if($data['book']->volumes_id==$key) selected="selected" @endif>{{ $volume }}</option>@endif @endforeach
                                                </select>
                                            </div>

                                            <div style="width: 100%">
                                                <div class="input-group pull-left">
                                                    <label class="input-group-addon">版本</label>
                                                    <select data-name="version" class="version_id form-control select2">
                                                        <option value="0">人教版</option>
                                                        @forelse(cache('all_version_now') as $key => $version) @if($version->id>-1)<option value="{{ $version->id }}" @if($data['book']->version_id==$version->id ) selected="selected" @endif>{{ $version->name }}</option>@endif @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <label class="input-group-addon">系列</label>
                                                @php
                                                    $now_sort_sql = cache('all_sort_now')->where('id',$data['book']->sort)->first()
                                                @endphp
                                                <select data-name="sort" class="form-control sort_name select2">
                                                    <option value="{{ $data['book']->sort }}" selected>{{ $now_sort_sql?$now_sort_sql->name:'待定' }}</option>
                                                </select>
                                            </div>

                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">子系列</label>
                                                <select data-name="ssort" class="ssort_id form-control">
                                                   @if($data['book']->has_ssort)
                                                    @foreach($data['book']->has_ssort as $k=>$v)
                                                    <option value="{{ $v->ssort_id }}">{{ $v->ssort_name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="btn btn-group">
                                                <a data-id="{{ $data['book']->isbn }}" class="save_book btn btn-danger">保存</a>
                                                <a class="btn btn-primary hide generate_name">生成</a>
                                                <a class="btn btn-primary end_edit">处理完成</a>
                                            </div>
                                            <div>
                                                <a class="btn btn-primary btn-block" target="_blank" href="{{ route('new_audit_answer',$data['book']->isbn) }}">查看该isbn所有用户上传答案</a>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @if($data['existing_book']->count()>0)
                        <div class="col-md-6 existing_boox_box">
                            @foreach($data['existing_book'] as $k=>$v)
                                <a class="thumbnail col-md-6" href="{{ route('audit_answer_detail',$v->id).'?from=userAnswer' }}" target="_blank">
                                    <img src="{{ $v->cover }}" style="height: 200px;width: auto"/>
                                    <span>{{ $v->bookname }}</span>
                                </a>
                            @endforeach
                        </div>
                        @endif
                </div>
                @if($data['zyds_book']->count()>0)
                @foreach($data['zyds_book'] as $book)
                <div class="col-md-12 zyds_box">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <p>作业大师上传时间：{{ $book->cover_time}}</p>
                           {{-- <a class="btn btn-xs btn-danger img_all_choose">全选/反选</a>--}}
                            @if($book->status==0)
                                <a class="btn btn-success save_zydsbook">采用此答案和封面</a>
                            @else
                                <a class="btn btn-primary disabled">已采用</a>
                            @endif
                        </div>
                        <div class="panel-body">
                            <h3>作业大师答案</h3>
                            <div style="overflow-y: auto;display: flex" class="all_answer_now">
                                <div class="cover_box img_box">
                                    {{--<button class="btn btn-xs btn-primary img_choose">选中</button>--}}
                                    <a class="thumbnail">
                                        <img class="answer_pic img_box"  data-id="{{ $book->id }}" src="{{ $book->coverImageUrl }}">
                                    </a>
                                </div>
                               @foreach($book->has_answer as $k=>$v)
                                <div class="answer_box img_box">
                                  {{--  <button class="btn btn-xs btn-primary img_choose">选中</button>--}}
                                    <a class="thumbnail">
                                        <img class="answer_pic"  src="{{ $v->pageImageUrl }}"  data-id="{{ $v->id }}"/>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        {{--<div style="position: fixed;bottom: 20px;right:10px;z-index: 999;">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">已选择答案<a class="btn btn-xs btn-primary" id="download_all_pic">下载</a>&nbsp;<a class="btn btn-xs btn-danger" id="clear_all_pic">清空</a></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="img_download_box" style="display: flex;width: 800px;overflow-y: auto;">

                    </div>
                </div>
            </div>
        </div>--}}

    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function(){
           $('.save_zydsbook').click(function(){
               var box = $('.info_box');
               /* var bookinfo={};*/
               var zyds_box=$(this).parents('.zyds_box');
               var zyds_cover=zyds_box.find('.cover_box img').attr('src');

               box.find('.answer_pic').attr('src',zyds_cover);
              /* bookinfo.isbn = box.attr('data-isbn');
               bookinfo.bookname = box.find('.book_name').val();
               bookinfo.grade_id = box.find('.grade_id').val();
               bookinfo.subject_id = box.find('.subject_id').val();
               bookinfo.volumes_id = box.find('.volumes_id').val();
               bookinfo.version_id = box.find('.version_id').val();
               bookinfo.sort_id = box.find('.sort_name').val();
               bookinfo.ssort_id = box.find('.ssort_id').val();*/
               if($(this).hasClass('btn-success')){
                   zyds_box.attr('data-select','selected');
                   $(this).removeClass('btn-success').addClass('btn-primary').html('已选');
               }else{
                   zyds_box.attr('data-select','');
                   $(this).removeClass('btn-primary').addClass('btn-success').html('采用此答案和封面');
               }

           });
        });
    </script>


    <script>
        $(function () {
            $('.select2').select2();

            //选择子系列
            $('.sort_name').change(function(){
                var box = $(this).parents('.info_box');
                var sort_id = box.find('.sort_name').val();
                if(sort_id<0){alert('请先选择系列');return;}else{
                    axios.post('{{ route('IsbnArrange_ajax','get_ssort') }}',{sort_id}) .then(response=>{
                        var str='';
                        $.each(response.data.data,function(i,e){
                                str+='<option value="'+ e.ssort_id+'">'+e.ssort_name+'</option>';
                            });
                        $('.ssort_id').html(str);
                    });
                }
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
          /*  $(document).on('change','.grade_id,.subject_id,.volumes_id,.version_id,.sort_name',function () {
                let box = $(this).parents('.info_box');
                let sort_name = box.find('.sort_name option:selected').text();

                if(sort_name===undefined || sort_name.length<2){
                    sort_name = box.find('.sort_name').select2('data')[0].name
                }

                let bookname_now = '2018年'+sort_name + box.find('.grade_id option:selected').text() + box.find('.subject_id option:selected').text() + box.find('.volumes_id option:selected').text() + box.find('.version_id option:selected').text();
                box.find('.book_name').val(bookname_now);
            });*/

            //save_book
            $(document).on('click','.save_book',function () {
                var zyds_answer_all=[];
                var zyds_id=0;
                if($(".zyds_box[data-select='selected']").length==1){
                    if(!confirm('已选中了作业大师答案，确认保存?')){
                        return false;
                    }
                    $.each($(".zyds_box[data-select='selected']").find('.answer_box img'),function(i,e){
                        zyds_answer_all.push($(e).attr('src'));
                    });
                    zyds_id=$(".zyds_box[data-select='selected']").find('.cover_box img').attr('data-id');

                }else if($(".zyds_box[data-select='selected']").length>1){
                   alert('不能同时选中多本作业大师答案');return;
                }else{
                    if(!confirm('确认保存?')){
                        return false;
                    }
                }

                var box = $(this).parents('.info_box');
                var isbn = box.attr('data-isbn');
                var bookinfo={};
                bookinfo.bookname = box.find('.book_name').val();
                if(bookinfo.bookname.includes('未选择') || bookinfo.bookname.includes('待定')){
                    return false;
                }
                bookinfo.version_year=box.find('.version_year').val();
                bookinfo.grade_id = box.find('.grade_id').val();
                bookinfo.subject_id = box.find('.subject_id').val();
                bookinfo.volumes_id = box.find('.volumes_id').val();
                bookinfo.version_id = box.find('.version_id').val();
                bookinfo.sort_id = box.find('.sort_name').val();
                bookinfo.ssort_id = box.find('.ssort_id').val();
                bookinfo.cover = box.find('.answer_pic').attr('src');

                 axios.post('{{ route('IsbnArrange_ajax','save_book') }}',{isbn,bookinfo,zyds_answer_all,zyds_id})
                    .then(response=>{
                        alert(response.data.msg);
                        if(response.data.status==1){
                            window.location.reload();
                        }
                    });
            });

            $('.end_edit').click(function(){
                var isbn = $(this).parents('.info_box').attr('data-isbn');
                axios.post('{{ route('IsbnArrange_ajax','end_edit') }}',{isbn})
                        .then(response=>{
                                if(response.status){
                                    window.location.href=`{{ route('isbn_list') }}`;
                                }
                         });
            })


        //翻页
        $('.page_now').click(function () {
            var page_to = $(this).attr('data-type');
            var now_img = $(this).parents('.modal-dialog').find('img')

            if(page_to=='prev'){
                var prev_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').prev().find('img');
                if(prev_img.length>0){
                    now_img.attr({'src':prev_img.attr('src'),'data-id':prev_img.attr('data-id')});
                }
            }else{
                var next_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').next().find('img');
                if(next_img.length>0){
                    now_img.attr({'src':next_img.attr('src'),'data-id':next_img.attr('data-id')});
                }
            }
//                $(`img[data-status='now_modal_content']`).removeAttr('data-status');
//                now_img.attr('data-status','now_modal_content');
        })


        })




    </script>






@endpush