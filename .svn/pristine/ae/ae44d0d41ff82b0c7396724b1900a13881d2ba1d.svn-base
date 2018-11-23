@extends('layouts.backend')

@section('lww_index','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')

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
                @php $sort_now =cache('all_sort_now')->where('id',$data['sort_id'])->first() @endphp
                <p>{{ $sort_now?$sort_now->name:'' }}</p>
            </div>
            <div class="box-body">
                {{--<div class="row">--}}
                    {{--<div class="col-xs-3">--}}
                        {{--<input type="text" class="form-control" id="search_word" placeholder="名称">--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1">--}}
                        {{--<button type="submit" class="btn btn-primary" id="search">搜索</button>--}}
                    {{--</div>--}}

                    <!-- <div class="col-xs-2" style="float: right;">
                         <button class="btn btn-success addbook_modal" data-toggle="modal" data-target="#myModal" >新增练习册</button>
                     </div>-->
                {{--</div>--}}
                <div class="row">

                    <div class="col-md-2">
                        <select class="select2 form-control onlyid_select">
                            <option value="-1">快速跳转onlyid</option>
                            @if($data['onlyid']!=-1)
                                <option selected value="{{ $data['onlyid'] }}">{{ $data['onlyid'] }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="select2 form-control sort_name">
                            <option value="-1">选择系列</option>
                            @if($data['sort_id']>-1)
                                <option selected value="{{ $data['sort_id'] }}">{{ cache('all_sort_now')->where('id',$data['sort_id'])->first()->name }}</option>
                            @endif
                        </select>
                    </div>


                    <div class="col-xs-2">
                        <select class="select2 form-control type_sel">
                            <option value="-1" @if($data['ssort_id']==-1) selected @endif>子系列</option>
                            @foreach($data['type_arr'] as $v)
                            <option value="{{ $v->ssort_id }}" @if($data['ssort_id']==$v->ssort_id) selected @endif>{{ $v->ssort_name?$v->ssort_name:'练习册' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xs-2">
                        <select class="select2 form-control version_sel">
                            <option value="-1" @if($data['version_id']==-1) selected @endif>版本</option>
                            @foreach(cache('all_version_now') as $k=>$v){
                            <option value="{{ $v->id }}" @if($data['version_id']==$v->id) selected @endif>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="select2 form-control subject_sel">
                            <option value="-1">全部科目</option>
                            @foreach(config('workbook.subject_1010') as $key=>$subject)
                                @if($key>0)
                                    <option @if($key==$data['subject_id']) selected @endif value="{{ $key }}">{{ $subject }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="select2 form-control grade_sel">
                            <option value="-1">全部年级</option>
                            @foreach(config('workbook.grade') as $key=>$grade)
                                @if($key>0)
                                    <option @if($key==$data['grade_id']) selected @endif value="{{ $key }}">{{ $grade }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <!-- /.box-body -->
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
                        <th class="col-xs-1">onlyid</th>
                        <th class="col-xs-1">thread_id</th>
                        <th class="col-xs-1">书名</th>
                        <th class="col-xs-1"><a href="{{ route('one_lww_booklist',[-1,$data['ssort_id'],$data['sort_id'],$data['grade_id'],$data['subject_id'],$data['version_id'],'rating']) }}" class="btn @if($data['order']=='rating') btn-danger @else btn-default @endif">停留时间倒序</a></th>
                        <th class="col-xs-1"><a href="{{ route('one_lww_booklist',[-1,$data['ssort_id'],$data['sort_id'],$data['grade_id'],$data['subject_id'],$data['version_id'],'searchnum']) }}" class="btn @if($data['order']=='searchnum') btn-danger @else btn-default @endif">搜索次数倒序</a></th>
                        <th class="col-xs-1">子系列</th>
                        <th class="col-xs-1">年级-科目-版本</th>
                        <th class="col-xs-2">答案情况</th>
                        <th class="col-xs-2">操作</th>
                    </tr>
                    @foreach($data['original_list'] as $v3)

                                <tr>

                        <td>
                            {{ $v3->onlyid }}
                            <p>
                                <a class="btn btn-primary" target="_blank" href="{{ route('lww_upload_page',[$v3->onlyid.config('workbook.school_year').config('workbook.now_add_book')['now_volumes']]) }}">查看已上传页(共{{ $v3->uploaded_imgs }}页)</a>
                                <a class="btn @if($v3->need_upload) btn-danger @else btn-default @endif mark_to_upload" data-onlyid="{{ $v3->onlyid }}">标记需要上传内容</a>
                            </p>
                        </td>
                        <td>
                            @if($v3->thread_id==0)
                                <a class="btn btn-danger add_to_lww" data-id="{{ $v3->onlyid }}">新增至零五网</a>
                            @else
                                {{ $v3->thread_id }}
                            @endif
                        </td>
                        <td>{{ $v3->bookname }}</td>
                        <td>{{ $v3->rating }}</td>
                        <td>{{ $v3->searchnum }}</td>
                        <td>

                            <div class="input-group">
                                <select class="select2 form-control select_ssort" style="width: 100%">
                                    <option value="-1" @if($data['ssort_id']==-1) selected @endif>选择子系列</option>
                                    @foreach($data['type_arr'] as $v)
                                        <option value="{{ $v->ssort_id }}" @if($v->ssort_id==$v3->ssort_id) selected @endif>{{ $v->ssort_name?$v->ssort_name:'练习册' }}</option>
                                    @endforeach
                                </select>
                                <a class="input-group-addon btn btn-primary save_ssort" data-onlyid="{{ $v3->onlyid }}">保存</a>
                            </div>
                        </td>
                        <td>
                            <p>{{ config('workbook.grade')[$v3->grade_id] }}</p>
                            <p>{{ config('workbook.subject_1010')[$v3->subject_id] }}</p>
                            <p>{{ cache('all_version_now')->where('id',$v3->version_id)->first()->name  }}</p>
                        </td>
                        <td>
                            <select data-id="{{ $v3->onlyid }}" class="select2 form-control answer_status_select" style="width: 100%">
                                @foreach(['未归类','完整答案有解析','完整答案','部分答案','(无答案)往年完整答案有解析','(无答案)往年完整答案','(无答案)往年部分答案','(无答案)无答案'] as $key=>$status)
                                    <option value="{{ $key }}" @if($key==$v3->answer_status) selected @endif>{{ $status }}</option>

                                @endforeach
                            </select>
                        </td>
                        <td>
                            {{--<a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_chapter',$v3->onlyid) }}">编辑</a>--}}
                            <p>
                            <a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_workbook_list',[$v3->ssort_id,$v3->sort_id,$v3->grade_id,$v3->subject_id,$v3->version_id]) }}">编辑</a>
                            </p>

                            <div class="input-group">
                                <a class="input-group-addon">选择兼职</a>
                                <select  data-id="{{ $v3->onlyid }}" class="select2 part_time_select" style="width: 100%">
                                    <option value="0">选择兼职</option>
                                @foreach($data['all_part_time'] as $user)
                                    <option @if($user->id==$v3->own_uid) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                                </select>
                            </div>

                            <a class="btn btn-danger hide del_book" data-only="{{ $v3->onlyid }}">删除</a>
                            {{--<a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_chapter',$v3->onlyid) }}">家教网管理</a>--}}
                            <!--<button type="button" class="btn btn-success update_modal" data-toggle="modal" data-target="#myModal">修改</button>
                            <button type="button" class="btn btn-danger del_book">删除</button>-->
                        </td>
                    </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
                {{ $data['original_list']->links() }}

    <!-- Modal -->
        {{--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">--}}
            {{--<div class="modal-dialog" role="document">--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                        {{--<h4 class="modal-title" id="myModalLabel">新增练习册</h4>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body" style="height:250px;">--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-2 control-label">书名</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control bookname" >--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-2 control-label">书籍子系列</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<select  class="form-control book_type" >--}}
                                    {{--@foreach($data['type_arr'] as $v)--}}
                                    {{--<option value="{{ $v->ssort_id }}">{{ $v->ssort_name }}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-2 control-label">所属系列</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<select  class="form-control book_xilie">--}}
                                    {{--@foreach($data['xilie_arr'] as $k=>$v)--}}
                                    {{--<option value="{{ $v[0]->id }}">{{ $v[0]->sort_name }}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-2 control-label">年级</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<select  class="form-control book_grade">--}}
                                    {{--@foreach(config('workbook.grade') as $k=>$v)--}}
                                        {{--@if($k>0)--}}
                                            {{--<option value="{{ $k }}">{{ $v }}</option>--}}
                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-2 control-label">科目</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<select  class="form-control book_subject">--}}
                                    {{--@foreach(config('workbook.subject_1010') as $k=>$v)--}}
                                        {{--@if($k>0)--}}
                                            {{--<option value="{{ $k }}">{{ $v }}</option>--}}
                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-2 control-label">版本</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<select  class="form-control book_version">--}}
                                    {{--@foreach(cache('all_version_now') as $k=> $v)--}}
                                       {{--<option value="{{ $v->id }}">{{ $v->name }}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>--}}
                        {{--<button type="button" class="btn btn-primary btn_check" >确认添加</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}




    </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function(){
            $('.select2').select2();
            {{--$('#search').click(function(){--}}
                {{--var word=$('#search_word').val();--}}
                {{--axios.post('{{ route('one_lww_ajax','search_bookname') }}',{word}).then(response=>{--}}
                    {{--if(response.data.status===1){--}}
                        {{--window.location.href='{{ route('one_lww_booklist') }}/?id_arr='+response.data.data.str;--}}
                    {{--}--}}
                {{--})--}}
            {{--});--}}

            $(document).on('change','.onlyid_select,.type_sel,.sort_name,.grade_sel,.subject_sel,.version_sel',function(){
                if($(this).hasClass('onlyid_select')){
                    let onlyid = $('.onlyid_select').val()!=-1?$('.onlyid_select').select2('data')[0].name:-1;
                    window.location.href = '{{ route('one_lww_booklist') }}/'+onlyid+'/-1/-1/-1/-1/-1';
                }else{
                    let sort_id  = $('.sort_name').val()?$('.sort_name').val():-1;
                    var type_id=$('.type_sel').val()?$('.type_sel').val():-1;
                    var grade_id=$('.grade_sel').val()?$('.grade_sel').val():-1;
                    var subject_id=$('.subject_sel').val()?$('.subject_sel').val():-1;
                    var version_id=$('.version_sel').val()?$('.version_sel').val():-1;
                    window.location.href = '{{ route('one_lww_booklist') }}/-1/'+type_id+'/'+sort_id+'/'+grade_id+'/'+subject_id+'/'+version_id;
                }
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
            //删除
            $('.del_book').click(function () {
                if(!confirm('确定要删除此书？')){
                    return false;
                }
                let onlyid = $(this).attr('data-only');
                axios.post('{{ route('one_lww_ajax','del_book') }}',{onlyid}).then(response=>{

                }).catch(function (e) {
                    console.log(e)
                })
            });

            //更新子系列
            $('.save_ssort').click(function () {
                let only_id = $(this).attr('data-onlyid');
                let ssort_id = $(this).parent().find('.select_ssort').val();
                let ssort_name = $(this).parent().find('.select_ssort option:selected').text();
                if(ssort_id>-1){
                    axios.post('{{ route('one_lww_ajax','update_ssort') }}',{ssort_id,only_id,ssort_name}).then(response=>{
                        if(response.data.status===1){
                            alert('更新成功');
                        }
                    })
                }
            })

            //新增至零五网
            $('.add_to_lww').click(function () {
                if(!confirm('确认新增至零五网')){
                    return false;
                }
                let only_id = $(this).attr('data-id');
                axios.post('{{ route('one_lww_ajax','add_to_lww') }}',{only_id}).then(response=>{
                    if(response.data.status===1){
                        $(this).after(response.data.data.new_id)
                        $(this).remove();
                    }
                })
            })

            //答案状态更新
            $('.answer_status_select').change(function () {
                let only_id = $(this).attr('data-id');
                let answer_status = $(this).val();
                axios.post('{{ route('one_lww_ajax','update_answer_status') }}',{only_id,answer_status}).then(response=>{
                    if(response.data.status===0){
                        alert('更新失败');
                    }
                })
            })

            //兼职人员选择
            $('.part_time_select').change(function () {
                let only_id = $(this).attr('data-id');
                let own_uid = $(this).val();
                axios.post('{{ route('one_lww_ajax','update_own_uid') }}',{only_id,own_uid}).then(response=>{
                    if(response.data.status===0){
                        alert('更新失败');
                    }
                })
            })

            //系列
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

            //onlyid
            $(".onlyid_select").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('one_lww_ajax','onlyid_get') }}",
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

            //标记需要上传内容页
            $('.mark_to_upload').click(function () {
                let onlyid = $(this).attr('data-onlyid');
                axios.post('{{ route('one_lww_ajax','need_upload_content') }}',{onlyid}).then(response=>{
                    if(response.data.status===1){
                        if($(this).hasClass('btn-default')){
                            $(this).removeClass('btn-default').addClass('btn-danger');
                        }else{
                            $(this).removeClass('btn-danger').addClass('btn-default');
                        }
                    }
                })
            });


        })
    </script>
@endpush