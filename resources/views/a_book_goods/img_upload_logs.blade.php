@extends('layouts.backend')

@section('new_buy_analyze','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush


@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">全书总览</li>
        </ol>
    </section>
    <section class="content">
    <div class="box box-default color-palette-box">
        <button type="button" class="btn btn-success" id="updatefile">更新目录</button>
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
           <div class="col-md-12">
                <div class="input-group col-md-6">
                    {{--<select id="sort_id" class="form-control sort_name click_to">--}}
                        {{--<option value="{{ cache('all_sort_now')->where("id",$data['sort'])->first()->id }}">--}}
                            {{--@php--}}
                                {{--$name=cache('all_sort_now')->where("id",$data['sort'])->first()->name--}}
                            {{--@endphp--}}
                            {{--@if($name!='nosort')--}}
                                {{--{{ $name }}--}}
                            {{--@else--}}
                                {{--全部系列--}}
                            {{--@endif--}}
                            {{--</option>--}}
                        {{--<option value="-999">全部系列</option>--}}
                    {{--</select>--}}

                </div>
               {{--<div class="input-group pull-left col-md-3">--}}
                   {{--<input class="form-control" id="search_word" placeholder="练习册名称" type="text" value="" />--}}
                   {{--<a class="input-group-addon btn btn-primary" id="search_book_btn">搜索</a>--}}
               {{--</div>--}}

               {{--<button type="button" class="btn btn-primary" style="margin-left: 20px;" id="AddMark">加入待购买</button>--}}
               {{--<button type="button" class="btn btn-danger" style="margin-left: 20px;" id="DelMark">作废</button>--}}
        </div>
    <form action="{{route('img_upload_logs')}}" method="post" id="choose_id">
                {{ csrf_field() }}
        <div class="box-body">
            <div class="col-md-12">
                <table class="table table-striped" style="text-align: center"  style="border:1px solid #ccc">
                    <tbody>
                    <tr>
                        {{--<th style="width:3%"><input type="checkbox" onclick="swapCheck()"/></td></th>--}}
                        <th style="width:18%">目录</th>
                        <th style="width:20%">系列</th>
                        <th style="width:7%">科目</th>
                        <th style="width:10%">年级</th>
                        <th style="width:10%">卷册</th>
                        <th style="width:5%">版本</th>
                        <th style="width:6%">操作</th>
                        <th style="width:10%">来源</th>
                    </tr>
                    <tr style="background-color:#ccc; ">
                        <td></td>
                        <td style="width:100px;">
                            <div class="input-group pull-left " style="width:100%">
                                <select id="sort_sel" style="width:100%" name="sort" class="sortall saixuan">
                                    @if(isset($r_sort))
                                        <option value="{{$r_sort}}">{{$sort_value}}</option>
                                    @else
                                        <option value="">筛选</option>
                                    @endif
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="input-group pull-left col-md-2" style="width:100%">
                                <select id="subject_sel" style="width:100%" name="subject" class="saixuan">
                                    <option value="">筛选</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="input-group pull-left col-md-2" style="width:100%">
                                <select id="grade_sel" style="width:100%" name="grade" class="saixuan">
                                    <option value="">筛选</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="input-group pull-left col-md-2" style="width:100%">
                                <select id="volumes_sel" style="width:100%" name="volume" class="saixuan">
                                    <option value="">筛选</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="input-group pull-left col-md-3" style="width:100%">
                                <select id="version_sel" style="width:100%" name="version" class="saixuan">
                                    <option value="">筛选</option>
                                </select>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach($data as $k=>$v)
                        <tr data-oid="{{$v->id}}"  style="border:1px solid #ccc">
                            {{--<td><input type="checkbox" class="check" style="width:20px;"></td>--}}
                            <td style="border:1px solid #ccc" mulu="{{$v->path_name}}">
                                网络\QINGXIA23\book{{$v->path_name}}
                            </td>
                            <td style="width:100px;" style="border:1px solid #ccc">
                                <select indextype="preg_sort" class="sortall update_index select_sort"  style=" width:200px;">
                                    <option value="{{$v->preg_sort}}">{{$v->name}}</option>
                                </select>
                            </td>
                            <td style="border:1px solid #ccc">
                                <select indextype="preg_subject" class="update_index select_subject"  >
                                    <option value="{{$v->preg_subject}}">
                                        {{config('workbook.subject_1010')[$v->preg_subject]}}
                                    </option>
                                </select>
                            </td>
                            <td style="border:1px solid #ccc">
                                <select indextype="preg_grade" class="update_index select_grade"  style="width:100px;">
                                    <option value="{{$v->preg_grade}}">{{config('workbook.grade')[$v->preg_grade]}}</option>
                                </select>
                            </td>
                            <td style="border:1px solid #ccc">
                                <select indextype="preg_volume" class="update_index select_volume"  style="width:100px;">
                                    <option value="{{$v->preg_volume}}">{{config('workbook.volumes')[$v->preg_volume]}}</option>
                                </select>
                            </td>
                            <td style="border:1px solid #ccc">
                                <select indextype="preg_version" class="update_index select_version"  >
                                    <option value="{{$v->preg_version}}">{{$v->press_name}}</option>
                                </select>
                            </td>
                            <td style="width:40px;" style="border:1px solid #ccc">
                                <button type="button" class="btn btn-success move" >匹配</button>
                            </td>
                            <td class="input_box" style="width:50px;" style="border:1px solid #ccc" >
                                @foreach($v->from_id as $ke=>$id)
                                    @if($id!=null)
                                    <input type="radio" value="{{$id}}" bookname="{{$v->box[$ke]}}" name="onlyone" />{{$v->box[$ke]}}_{{$id}}
                                    <img class="answer_pic" style="width:90px;height:180px;"  src="{{$v->path[$ke]}}" alt="" />

                                    <br/>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        @component('components.modal',['id'=>'show_img','title'=>'查看图片'])
                            @slot('body','')
                            @slot('footer','')
                        @endcomponent

                    @endforeach
                    </tbody>
                </table>

            </div>
            <div>
                {{ $data->links() }}
            </div>

        </div>
    </form>
    </div>
    </div>
    </section>
@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    {{--<script>--}}
        {{--$(function () {--}}
            {{--//选择系列--}}


            {{--$('#search_book_btn').click(function () {--}}
                {{--let sort_id=$('#sort_id').val();--}}
                {{--let volumes_id = $('#volumes_sel').val();--}}
                {{--let word = $.trim($('#search_word').val());--}}
                {{--window.location.href='{{ route('book_list') }}'+'/'+sort_id+'/'+volumes_id+'/'+word;--}}
            {{--});--}}



            {{--$('#volumes_sel').val('{{ $data['volumes_id'] }}');--}}
            {{--$('#search_word').val('{{ $data['word'] }}');--}}
        {{--})--}}
    {{--</script>--}}

<script type="text/javascript">
    //checkbox 全选/取消全选
    var isCheckAll = false;
    function swapCheck() {
        if (isCheckAll) {
            $("input[type='checkbox']").each(function() {
                this.checked = false;
            });
            isCheckAll = false;
        } else {
            $("input[type='checkbox']").each(function() {
                this.checked = true;
            });
            isCheckAll = true;
        }
    }

    $(function(){
       $("#AddMark").click(function(){
           if(confirm('确定要加入待购买吗?')){
               let checks = $(".check:checked");
               let checkData = new Array();
               checks.each(function(){
                   checkData.push($(this).parents("tr").attr('data-oid'));
               });
               axios.post('{{ route('ajax_book_list') }}',{checkData}).then(response=>{
                   if(response.data.status===1){
                        window.location.reload();
                    }
                });

           }
       });

        $("#DelMark").click(function(){
            if(confirm('确定要作废吗?')){
                let checks = $(".check:checked");
                let checkData = new Array();
                checks.each(function(){
                    checkData.push($(this).parents("tr").attr('data-oid'));
                });
                axios.post('{{ route('ajax_book_list') }}',{checkData}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                });
            }
        });
        //初始化下拉列表
        $("#volumes_sel").select2({
            data:$.parseJSON('{!!$volumes!!}')
        });
        $("#version_sel").select2({
            data:$.parseJSON('{!!$version!!}')
        });
        $("#subject_sel").select2({
            data:$.parseJSON('{!!$subject!!}')
        });
        $("#grade_sel").select2({
            data:$.parseJSON('{!!$grade!!}')
        });

        //设置默认值
        $("#volumes_sel").val(['{!!$r_volume!!}']).trigger('change');
        $("#version_sel").val(['{!!$r_version!!}']).trigger('change');
        $("#subject_sel").val(['{!!$r_subject!!}']).trigger('change');
        $("#grade_sel").val(['{!!$r_grade!!}']).trigger('change');
        $("#sort_sel").val(['{!!$r_sort!!}']).trigger('change');

        //筛选select2
        $("#sort_sel").select2({
            language: "zh-CN",
            ajax: {
                type:'GET',
                url: "{{ route('get_sort') }}",
                dataType: 'json',
                data: function (params){
                    return {
                        word: params.term
                    };
                },
                delay: 400,
                processResults: function (text) {
                    return {
                        results: text
                    };
                },
                cache: true

            },
            escapeMarkup: function (markup) {
                return markup;
            }, // 自定义格式化防止xss注入
            minimumInputLength: 1,//最少输入多少个字符后开始查询
            templateResult: function formatRepo(repo) {
//                if (repo.loading) return repo.text;
                return '<option value="' + repo.id + '">' + repo.text + '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo) {
                //alert(repo.name || repo.text);
                return repo.name || repo.text;
            }

        });

        //属性修改select2
        $(".select_sort").select2({
            language: "zh-CN",
            ajax: {
                type:'GET',
                url: "{{ route('get_sort') }}",
                dataType: 'json',
                data: function (params){
                    return {
                        word: params.term
                    };
                },
                delay: 400,
                processResults: function (text) {
                    return {
                        results: text
                    };
                },
                cache: true

            },
            escapeMarkup: function (markup) {
                return markup;
            }, // 自定义格式化防止xss注入
            minimumInputLength: 1,//最少输入多少个字符后开始查询
            templateResult: function formatRepo(repo) {
//                if (repo.loading) return repo.text;
                return '<option value="' + repo.id + '">' + repo.text + '</option>';
            }, // 函数用来渲染结果
            templateSelection: function formatRepoSelection(repo) {
                //alert(repo.name || repo.text);
                return repo.name || repo.text;
            }

        });


        $('.select_subject').select2({
            data:$.parseJSON('{!!$subject!!}')
        });
        $('.select_version').select2({
            data:$.parseJSON('{!!$version!!}')
        });
        $('.select_volume').select2({
            data:$.parseJSON('{!!$volumes!!}')
        });
        $('.select_grade').select2({
            data:$.parseJSON('{!!$grade!!}')
        });

        //筛选select绑定change事件
        $('.saixuan').change(function(){
            $('#choose_id').submit();
        });

        //更新select绑定change事件
        $('.update_index').change(function(){
            var obj=$(this).closest('tr').find('select');
            var json=[];
            $.each(obj,function(i,j){
                if(i==0){
                    json['preg_sort']=$(j).val();
                }else if(i==1){
                    json['preg_subject']=$(j).val();
                }else if(i==2){
                    json['preg_grade']=$(j).val();
                }else if(i==3){
                    json['preg_volume']=$(j).val();
                }else if(i==4){
                    json['preg_version']=$(j).val();
                }
            });
            var index=$(this).val();
            var keys=$(this).attr('indextype');
            var id=$(this).closest('tr').attr('data-oid');
            var str={'id':id,'preg_grade':json['preg_grade'],'preg_sort':json['preg_sort'],'preg_subject':json['preg_subject'],'preg_version':json['preg_version'],'preg_volume':json['preg_volume']};
           //更新数据到数据库
            var this_=$(this);
            $.ajax({
                'headers': {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url':'{{route('change_path_name')}}',
                'datatype':'json',
                'data':str,
                'type':'post',
                'success':function(msg){
                    var data=JSON.parse(msg);
                    var html='';
                    if(data['status']=='success'){
                        $.each(data['data'],function(i,j){
                            html+='<input type="radio" bookname="'+j['bookname']+'" value="'+j['from_id']+'" name="onlyone">'+j['bookname']+'_'+j['from_id']+'<img class="answer_pic" style="width:90px;height:180px;"  src="'+j['path']+'" alt="" /><br/>';

                        });
                        var oldhtml=$(this_.closest('tr').find('td')[7]).html(html);
                        //刷新当前页面
//                        window.location.reload();
                    }else{
                        alert('没有找到匹配数据');
                        var oldhtml=$(this_.closest('tr').find('td')[7]).html(html);
                    }
                }
            });
        });


        //move绑定点击事件
        $('.move').click(function(){
            //准备参数
            var id=$(this).closest('tr').attr('data-oid');
            var mulu=$($(this).closest('tr').find('td')[0]).attr('mulu');
            var sort=$($(this).closest('tr').find('select')[0]).val();
            var subject=$($(this).closest('tr').find('select')[1]).val();
            var grade=$($(this).closest('tr').find('select')[2]).val();
            var volume=$($(this).closest('tr').find('select')[3]).val();
            var version=$($(this).closest('tr').find('select')[4]).val();
            var from_id=$($(this).closest('tr').find('td')[7]).find('input:checked').val();
            var bookname=$($(this).closest('tr').find('td')[7]).find('input:checked').attr('bookname');
            var data={'id':id,'sort':sort,'subject':subject,'grade':grade,'volume':volume,'version':version,'from_id':from_id,'bookname':bookname,'mulu':mulu};
            var this_=this;
            $.ajax({
                'headers': {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url':'{{route('move_files')}}',
                'datatype':'json',
                'data':data,
                'type':'post',
                'success':function(msg){
                    var data=JSON.parse(msg);
                    if(data['status']=='success'){
                        alert('匹配完成');
                        $(this_).closest('tr').remove();
                    }else if(data['status']=='path_problem'){
                        alert('目录不存在');
                        $(this_).closest('tr').remove();
                    }else if(data['status']=='olddata'){
                        $(this_).closest('tr').remove();
                    }else if(data['status']=='nodata'){
                        alert("请选择来源");
                    }else{
                        alert('网络繁忙，请重试');
                    }

                }
            });
        });

        //更新目录
        $('#updatefile').click(function(){
            $.ajax({
                'headers': {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url':'{{route('updatefile')}}',
                'datatype':'json',
                'type':'get',
                'success':function(msg){
                    var data=JSON.parse(msg);
                    alert('更新完成');

                }
            });
        });


    });


</script>
@endpush