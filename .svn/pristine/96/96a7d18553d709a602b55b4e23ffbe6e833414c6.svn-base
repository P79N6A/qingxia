@extends('layouts.backend')

@section('audit_index','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
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

        @component('components.modal',['id'=>'replace_img','title'=>'替换'])
        @slot('body')
        <div id="fileuploader">Upload</div>
        <div id="done_img" class="row"></div>
        @endslot
        @slot('footer','')
        @endcomponent

        <div class="box box-primary">
            <div class="box-header">求助列表</div>
            <div class="box-body">
                <a type="button" class="btn btn-primary @if($data['status']==0)active @endif" href="{{ route('new_audit_index',[0,$data['start'],$data['end']]) }}">未处理</a>
                <a type="button" class="btn btn-primary @if($data['status']==1)active @endif" href="{{ route('new_audit_index',[1,$data['start'],$data['end']]) }}">已处理</a>

                <a style="float: right;" type="button" class="btn btn-primary @if($data['start']!='')active @endif"  href="{{ route('new_audit_index',[$data['status'],date("Y-m-d", strtotime("-1 day")),date("Y-m-d")]) }}">按时间筛选</a>
                <a style="float: right;" type="button" class="btn btn-primary @if($data['start']=='')active @endif"  href="{{ route('new_audit_index',$data['status']) }}">不按时间筛选</a>
                <div class="form-group" style="display:@if($data['start']=='') none @else block @endif ;">
                    <label>时间筛选</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i>
                    </span>
                            <i class="fa fa-caret-down"></i>{{substr($data['start'],0,10)}}~{{substr($data['end'],0,10)}}
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')
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
                            if($isbn->hasWorkBookUserFirst){
                                $bookuser_img=$isbn->hasWorkBookUserFirst->cover_img;
                                $bookuser_subject_id=$isbn->hasWorkBookUserFirst->subject_id;
                                $bookuser_grade_id=$isbn->hasWorkBookUserFirst->grade_id;
                                $bookuser_volumes_id=$isbn->hasWorkBookUserFirst->volumes_id;
                                $bookuser_version_id=$isbn->hasWorkBookUserFirst->version_id;
                                $bookuser_sort_id=$isbn->hasWorkBookUserFirst->sort_id;
                                $bookuser_sort_name=$isbn->hasWorkBookUserFirst->sort_name;
                            }else{
                                $bookuser_img='';
                                $bookuser_subject_id='';
                                $bookuser_grade_id='';
                                $bookuser_volumes_id='';
                                $bookuser_version_id='';
                                $bookuser_sort_id='';
                                $bookuser_sort_name='';
                            }
                        @endphp
                        <div class="col-md-6 info_box" data-isbn="{{ $isbn->isbn }}" data-all_id="{{ $isbn->hasIsbnDetail? $isbn->hasIsbnDetail->id:-1 }}">
                            <div class="panel panel-primary">
                                <div class="panel panel-heading">
                                    {{ $isbn->isbn }}
                                    求助数<em class="badge bg-red">{{ $isbn->has_work_book_user_count }}</em>
                                    搜索数<em class="badge bg-red">
                                        @if($data['start']=='')
                                        {{ $isbn->hasSearchTemp?$isbn->hasSearchTemp->searchnum:0 }}
                                            @else
                                        {{ $isbn->searchnum }}
                                            @endif
                                    </em>
                                </div>
                                <div class="panel panel-body">
                                    <div class="text-center" style="padding: 10px">
                                        @php
                                            try{
                                                echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$isbn->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                            }catch (Exception $e){
                                                echo '无法生成此isbn的条形码';
                                            }
                                        @endphp
                                    </div>
                                    <a class="btn btn-block btn-default show_offical_book">已有练习册<em class="badge bg-red has_book_num">{{ $isbn->has_offical_book_count }}</em></a>
                                    <p class="print_description" style="height: 100px;overflow: scroll">{{ $isbn->hasIsbnDetail?$isbn->hasIsbnDetail->print_description:'' }}</p>
                                    <p class="col-md-12">
                                        <div class="col-md-6">
                                        <a class="thumbnail">
                                            <img class="answer_pic" src="@if($data['status']==1 && $data['start']==''){{ $isbn->cover_photo }}@elseif($data['status']==1 && $data['start']!=''){{config('workbook.user_image_url').$isbn->hasWorkBookUserFirst->cover_img}}@else{{ config('workbook.user_image_url').$bookuser_img }}@endif" alt="" />
                                        </a>
                                        @if($data['status']==0)<div>求助时间：{{ $isbn->hasWorkBookUserFirst?$isbn->hasWorkBookUserFirst->addtime:'' }}</div>@endif
                                        <a class="btn btn-primary btn-block show_cover" data-isbn="{{ $isbn->isbn }}">选择封面</a>
                                        {{--<a class="btn btn-danger btn-block img_replace" data-toggle="modal" data-target="#replace_img">替换封面</a>--}}

                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('audit_isbn_detail',$isbn->isbn) }}">查看该isbn所有用户上传求助</a>
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">书名</label>
                                                <input type="text" class="form-control book_name" data-real_name="
                                                @if($data['status']==1 && $data['start']==''){{$isbn->bookname}}@elseif($data['status']==1 && $data['start']!=''){{ $isbn->hasIsbnTemp->bookname }}@else{{ $real_bookname }}@endif" value="@if($data['status']==1 && $data['start']==''){{$isbn->bookname}}@elseif($data['status']==1 && $data['start']!=''){{ $isbn->hasIsbnTemp->bookname }}@else{{ $real_bookname }}@endif">
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" maxlength="4" class="form-control version_year" value="@if($data['status']==1 && $data['start']==''){{$isbn->version_year}}@elseif($data['status']==1 && $data['start']!=''){{ $isbn->hasIsbnTemp->version_year }}@else{{2018}}@endif">
                                            </div>
                                            {{--@if($data['status']==0 && $data['start']!='')
                                                <div class="input-group pull-left">
                                                    <label class="input-group-addon">出版社</label>
                                                    <input type="text" maxlength="4" class="form-control press" value="{{ $isbn->press }}" disabled>
                                                </div>
                                            @endif--}}



                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade" class="grade_id form-control select2 pull-left " >
                                                    @forelse(config('workbook.grade') as $key => $grade)
                                                        @if($key>=0)<option value="{{ $key }}"
                                                         @if($data['status']==1 && $data['start']=='')
                                                             @if($isbn->grade_id==$key)
                                                                            selected="selected"
                                                                     @endif>{{ $grade }}</option>
                                                        @elseif($data['status']==1 && $data['start']!='')
                                                            @if($isbn->hasIsbnTemp->grade_id==$key)
                                                                selected="selected"
                                                            @endif>{{ $grade }}</option>
                                                        @else
                                                            @if($bookuser_grade_id==$key or $preg_grade_id==$key)
                                                                            selected="selected"
                                                            @endif>{{ $grade }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2">

                                                    @forelse(config('workbook.subject_1010') as $key => $subject)
                                                        @if($key>=0)<option value="{{ $key }}"
                                                        @if($data['status']==1 && $data['start']=='')
                                                            @if($isbn->subject_id==$key)
                                                                            selected="selected"
                                                                    @endif>{{ $subject }}</option>
                                                        @elseif($data['status']==1 && $data['start']!='')
                                                            @if($isbn->hasIsbnTemp->subject_id==$key)
                                                                selected="selected"
                                                            @endif>{{ $subject }}</option>
                                                        @else
                                                            @if($bookuser_subject_id==$key or $preg_subject_id==$key)
                                                                selected="selected"
                                                            @endif>{{ $subject }}</option>
                                                        @endif
                                                        @endif
                                                            @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group pull-left">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    @forelse(config('workbook.volumes') as $key => $volume)
                                                        @if($key>=0)<option value="{{ $key }}"
                                                        @if($data['status']==1 && $data['start']=='')
                                                            @if($isbn->volumes_id==$key)
                                                                            selected="selected"
                                                                    @endif>{{ $volume }}</option>
                                                        @elseif($data['status']==1 && $data['start']!='')
                                                            <option value="{{ $key }}"
                                                            @if($isbn->hasIsbnTemp->volumes_id==$key ) selected="selected"
                                                                    @endif>{{ $volume }}</option>
                                                        @else
                                                            @if($bookuser_volumes_id==$key or $preg_volumes_id==$key)
                                                                selected="selected"
                                                            @endif>{{ $volume }}</option>
                                                        @endif
                                                        @endif
                                                            @endforeach
                                                </select>
                                            </div>




                                            <div style="width: 100%">
                                                <div class="input-group pull-left">
                                                    <label class="input-group-addon">版本</label>
                                                    <select data-name="version" class="version_id form-control select2">
                                                        <option value="0">人教版</option>
                                                        @forelse(cache('all_version_now') as $key => $version)
                                                            @if($data['status']==1 && $data['start']=='')
                                                                <option value="{{ $version->id }}"
                                                                @if($isbn->version_id==$version->id ) selected="selected"
                                                                        @endif>{{ $version->name }}</option>
                                                            @elseif($data['status']==1 && $data['start']!='')
                                                                <option value="{{ $version->id }}"
                                                                @if($isbn->hasIsbnTemp->version_id==$version->id ) selected="selected"
                                                                        @endif>{{ $version->name }}</option>
                                                            @else
                                                                @if($version->id>-1)
                                                                    <option value="{{ $version->id }}"
                                                                    @if($bookuser_version_id==$version->id or $preg_version_id==$version->id) selected="selected"
                                                                            @endif>{{ $version->name }}</option>
                                                                @endif
                                                            @endif
                                                                @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <label class="input-group-addon">系列</label>

                                                <select data-name="sort" class="form-control sort_name select2">
                                                    @if($data['status']==1 && $data['start']=='')
                                                        <option value="{{ $isbn->sort }}" selected>{{ $isbn->has_sort->name }}</option>
                                                    @elseif($data['status']==1 && $data['start']!='')
                                                        <option value="{{ $isbn->sort }}" selected>{{ $isbn->hasIsbnTemp->has_sort->name }}</option>
                                                    @else
                                                    <option value="{{ $isbn->hasIsbnDetail?$isbn->hasIsbnDetail->has_sort?$isbn->hasIsbnDetail->has_sort->id:-1:-1 }}" selected>{{ $isbn->hasIsbnDetail?$isbn->hasIsbnDetail->has_sort?$isbn->hasIsbnDetail->has_sort->name:'未知':'未知'}}</option>
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="btn btn-group">
                                               @if($data['status']==1 && $data['start']!='')
                                                    <a data-id="{{ $isbn->hasIsbnTemp->id }}" class="change_book btn btn-danger">修改</a>
                                               @elseif($data['status']==1 && $data['start']=='')

                                                @else
                                                    <a data-all_id="{{ $isbn->isbn}}" class="save_book btn btn-danger">保存</a>
                                                    <a class="btn btn-primary hasbook" target="_Blank">已有</a>
                                                @endif
                                            </div>
                                            <div>
                                                <p>处理人：@if($data['start']==''){{ $isbn->has_user?$isbn->has_user->name:'' }}@else{{ $isbn->hasIsbnTemp->has_user?$isbn->hasIsbnTemp->has_user->name:'' }}@endif</p>
                                                <a class="btn btn-primary btn-block" target="_blank" href="{{ route('new_audit_answer',$isbn->isbn) }}">查看该isbn所有用户上传答案</a>
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
<script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {
           /* var lazyLoadInstances = [];
            var lazyLazy = new LazyLoad({
                elements_selector: ".cover-box",
                callback_set: function(el) {
                    var oneLL = new LazyLoad({
                        container: el
                    });
                    lazyLoadInstances.push(oneLL);
                }
            });
            var lazy = new LazyLoad();
            console.log(lazy);

            $("#fileuploader").uploadFile({
                console.log(files);
            });*/


            $('.select2').select2();

            $('.change_book').click(function(){
                if(!confirm('确认修改?')){
                    return false;
                }
                let box = $(this).parents('.info_box');
                let isbn = box.attr('data-isbn');
                let bookid=$(this).attr('data-id');
                let book_name = box.find('.book_name').val();
                if(book_name.includes('未选择') || book_name.includes('待定')){
                    return false;
                }
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();
                let sort_id = box.find('.sort_name').val();
                let version_year=box.find('.version_year').val();
                let cover=box.find('.answer_pic').attr('src');

                axios.post('{{ route('ajax_new_audit_list','update_bookinfo') }}',{bookid,isbn,book_name,grade_id,subject_id,volumes_id,version_id,sort_id,version_year,cover})
                        .then(response=>{
                    if(response.data.status===1){
                    alert('修改成功');
                    window.location.reload();
                }
            });
            });

            $('.book_name').focus(function(){
                let box = $(this).parents('.info_box');
                /* let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();*/
                let sort_id = box.find('.sort_name').val();
                //console.log(sort_id);
                if(box.find(".newname_sel").length<=0){
                    axios.post('{{ route('ajax_new_audit_list','show_only_bookname') }}',{sort_id})
                            .then(response=>{
                        if(response.data.status===1){
                        if(response.data.data.length>0) {
                            box.find('.book_name').after('<select class="form-control newname_sel"><option value="">选择书名</option></select>');
                            for (var i in response.data.data) {
                                box.find('.newname_sel').append('<option value="' + response.data.data[i].newname + '">' + response.data.data[i].newname + '</option>');
                            }
                        }
                           /* if(response.data.data.length<=0){
                                let sort_name = box.find('.sort_name option:selected').text();
                                if(sort_name===undefined || sort_name.length<2){
                                    sort_name = box.find('.sort_name').select2('data')[0].name
                                }
                                let bookname_now = sort_name + box.find('.grade_id option:selected').text() + box.find('.subject_id option:selected').text() + box.find('.volumes_id option:selected').text() + box.find('.version_id option:selected').text();
                                box.find('.book_name').val(bookname_now);
                            }else{

                            }*/
                    }
                });
                }
            });

            $(document).on('change','.newname_sel',function (){
                let box = $(this).parents('.info_box');
                box.find('.book_name').val($(this).val());
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
                           now_html += `<a class="col-md-4 thumbnail"><img class="set_cover" src="{{ config('workbook.user_image_url') }}${item['cover_img']}"></a>`;
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
                                            <img /*class="answer_pic"*/ src="${item['cover']}" alt="" />
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
                                                <a data-id="${item['id']}" href="http://www.1010jiajiao.com/daan/bookid_${item['id']}.html">查看线上练习册</a>
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
            /*$(document).on('change','.grade_id,.subject_id,.volumes_id,.version_id,.sort_name',function () {
                let box = $(this).parents('.info_box');
                let sort_name = box.find('.sort_name option:selected').text();

                if(sort_name===undefined || sort_name.length<2){
                    sort_name = box.find('.sort_name').select2('data')[0].name
                }

                let bookname_now = sort_name + box.find('.grade_id option:selected').text() + box.find('.subject_id option:selected').text() + box.find('.volumes_id option:selected').text() + box.find('.version_id option:selected').text();
                box.find('.book_name').val(bookname_now);
            });*/

        $(document).on('change','.sort_name',function () {
            $('.newname_sel').remove();
            $(this).parents('.info_box').find('.book_name').focus();
        });

            //save_book
            $(document).on('click','.save_book',function () {
                if(!confirm('确认保存?')){
                    return false;
                }
                let box = $(this).parents('.info_box');
                let isbn = box.attr('data-isbn');
                let all_id=box.attr('data-all_id');
                let book_name = box.find('.book_name').val();
                if(book_name.includes('未选择') || book_name.includes('待定')){
                    return false;
                }
                let grade_id = box.find('.grade_id').val();
                let subject_id = box.find('.subject_id').val();
                let volumes_id = box.find('.volumes_id').val();
                let version_id = box.find('.version_id').val();
                let sort_id = box.find('.sort_name').val();
                let version_year=box.find('.version_year').val();
                let cover=box.find('.answer_pic').attr('src');
                axios.post('{{ route('ajax_new_audit_list','save_bookinfo') }}',{all_id,isbn,book_name,grade_id,subject_id,volumes_id,version_id,sort_id,version_year,cover})
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

        //已有
            $(document).on('click','.hasbook',function () {
                if(!confirm('确认已有?')){
                    return false;
                }
                let box = $(this).parents('.info_box');
                let isbn = box.attr('data-isbn');
                axios.post('{{ route('ajax_new_audit_list','has_book') }}',{isbn})
                        .then(response=>{
                    if(response.data.status===1){
                    box.remove();
                    }
                });
            });
       /* //替换封面
        $('.img_replace').click(function () {
            let answer_id = $(this).parents('.img_box').attr('data-id');
            $('#replace_img').attr('data-id',answer_id);
        });*/

        //上传



        })
    </script>
@endpush

@push('need_js')
<script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
<script  src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
<script>
    $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                    '今天'       : [moment(), moment()],
                    '昨天'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '最近7天' : [moment().subtract(6, 'days'), moment()],
                    '最近30天': [moment().subtract(29, 'days'), moment()],
                    '本月'  : [moment().startOf('month'), moment().endOf('month')],
                    '上个月'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens : 'right', //日期选择框的弹出位置
                startDate: moment("{{$data['start']}}").utc().subtract(-1,'days'),
                endDate  : moment("{{$data['end']}}").utc().subtract(-1,'days'),
                format : 'YYYY/MM/DD', //控件中from和to 显示的日期格式
                locale : {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '起始时间',
                    toLabel : '结束时间',
                    customRangeLabel : '自定义',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                        '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                    firstDay : 1
                }
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('YYYY/MM/DD') + ' ~ ' + end.format('YYYY/MM/DD'));
                window.location.href = `{{ route('new_audit_index') }}/{{$data['status']}}/${start.format("YYYY-MM-DD")}/${end.format("YYYY-MM-DD")}`;
                //window.location.href = '{{ route('user_feedback_status')}}/'+start.format("YYYY-MM-DD")+"/"+end.format("YYYY-MM-DD");
            }
    )
</script>
@endpush