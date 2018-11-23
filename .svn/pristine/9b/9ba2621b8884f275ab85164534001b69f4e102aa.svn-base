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
                <div class="row">
                    <div class="col-xs-3">
                        <input type="text" class="form-control" id="search_word" placeholder="名称">
                    </div>
                    <div class="col-xs-1">
                        <button type="submit" class="btn btn-primary" id="search">搜索</button>
                    </div>

                    <!-- <div class="col-xs-2" style="float: right;">
                         <button class="btn btn-success addbook_modal" data-toggle="modal" data-target="#myModal" >新增练习册</button>
                     </div>-->
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <select class="select2 form-control type_sel">
                            <option value="-1" @if($data['ssort_id']==-1) selected @endif>子系列</option>
                            @foreach($data['type_arr'] as $v)
                            <option value="{{ $v->ssort_id }}" @if($data['ssort_id']==$v->ssort_id) selected @endif>{{ $v->ssort_name?$v->ssort_name:'练习册' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-2 hide">
                        <select class="form-control xilie_sel">
                            <option value="-1" @if($data['sort_id']==-1) selected @endif>系列</option>
                            @foreach(cache('all_sort_now') as $k=>$v)
                            <option value="{{ $v->id }}" @if($data['sort_id']==$v->id) selected @endif >{{ $v->name }}</option>
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
                        <th class="col-xs-1">书名</th>
                        <th class="col-xs-1">系列-子系列</th>
                        <th class="col-xs-1" style="width: 20%;max-width: 400px; ">年级-科目-版本-isbn</th>
                        <th style="width: 60%">描述<a class="btn btn-xs btn-primary" href="{{ route('one_lww_hotbooklist',[$data['ssort_id'],$data['sort_id'],$data['grade_id'],$data['subject_id'],$data['version_id'],$data['order']==1?-1:1]) }}">按searchnum排序</a></th>
                        <th class="col-xs-2">操作</th>
                    </tr>
                    @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')
                    @foreach($data['list'] as $v3)
                        <tr data-id="{{ $v3->id }}">
                        <td>{{ $v3->bookname }}</td>
                        <td>
                            <div class="input-group">
                                <select class="select2 form-control select_ssort" style="width: 100%">
                                    <option value="-1" @if($data['ssort_id']==-1) selected @endif>选择子系列</option>
                                    @foreach($data['type_arr'] as $v)
                                        <option value="{{ $v->ssort_id }}" @if($v->ssort_id==$v3->ssort_id) selected @endif>{{ $v->ssort_name?$v->ssort_name:'练习册' }}</option>
                                    @endforeach
                                </select>
                                <a class="hide disabled btn btn-primary save_ssort" data-onlyid="{{ $v3->onlyid }}">保存</a>
                            </div>
                        </td>
                        <td data-id="{{ $v3->id }}" style="max-width: 400px;">
                            <div>
                                <select class="select2 form-control">
                                    <option value="-1">全部年级</option>
                                    @foreach(config('workbook.grade') as $key=>$grade)
                                        @if($key>0)
                                            <option @if($key==$v3->grade_id) selected @endif value="{{ $key }}">{{ $grade }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <a class="btn btn-primary save_hot_info" data-type="grade_id">保存</a>
                            </div>
                            <div>
                                <select class="select2 form-control">
                                    <option value="-1">全部科目</option>
                                    @foreach(config('workbook.subject_1010') as $key=>$subject)
                                        @if($key>0)
                                            <option @if($key==$v3->subject_id) selected @endif value="{{ $key }}">{{ $subject }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <a class="btn btn-primary save_hot_info" data-type="subject_id">保存</a>
                            </div>
                            <div>
                                <select class="select2 form-control">
                                    <option value="-1" @if($data['version_id']==-1) selected @endif>版本</option>
                                    @foreach(cache('all_version_now') as $k=>$v){
                                    <option value="{{ $v->id }}" @if($v3->version_id==$v->id) selected @endif>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                                <a class="btn btn-primary save_hot_info" data-type="version_id">保存</a>
                            </div>

                            <div><a class="btn btn-primary" href="http://www.1010jiajiao.com/daan/search.php?subject=alls&word={{ $v3->isbn }}" target="_blank">点击搜索isbn{{ $v3->isbn }}</a></div>
                        </td>
                       <td>
                           <a class="btn btn-primary">搜索量{{ $v3->searchnum }}</a>
                           @if($v3->has_buy==1)<a class="btn btn-danger">已买</a> @endif
                           <p>{{ $v3->description }}</p>
                           <p>
                               <div class="input-group">
                               <select class="select2 form-control">
                                   @foreach(['答案类型','答案','解析'] as $key=>$status)
                                   <option value="{{ $key }}" @if($v3->answer_url_type==$key) selected @endif >{{ $status }}</option>
                                   @endforeach
                               </select>
                               <input class="input-group form-control" type="text" value="{{ $v3->answer_url }}">
                               <a class="input-group-addon save_answer_url">保存学子斋网址</a>
                                </div>
                           </p>
                           <div class="col-md-12">
                           <div class="col-md-4">
                               <p style="height: 160px;">
                                   @php
                                       try{
                                           echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$v3->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                       }catch (Exception $e){
                                           echo '无法生成此isbn的条形码';
                                       }
                                   @endphp
                               </p>
                           </div>
                           <div class="col-md-8">
                               <div class="input-group">
                                   <a class="input-group-addon">现有答案情况</a>
                                   <select data-id="{{ $v3->id }}" data-type="answer" class="select2 form-control answer_status_select" style="width: 100%">
                                       @foreach(['未归类','完整答案有解析','完整答案','部分答案','(无答案)往年完整答案有解析','(无答案)往年完整答案','(无答案)往年部分答案','(无答案)无答案'] as $key=>$status)
                                           <option value="{{ $key }}" @if($key==$v3->answer_status) selected @endif>{{ $status }}</option>

                                       @endforeach
                                   </select>
                               </div>
                               <div class="input-group">
                                   <a class="input-group-addon">互动作业答案情况</a>
                               <select data-id="{{ $v3->id }}" data-type="hd" class="select2 form-control answer_status_select" style="width: 100%">
                                   @foreach(['未归类','完整答案有解析','完整答案','部分答案','(无答案)往年完整答案有解析','(无答案)往年完整答案','(无答案)往年部分答案','(无答案)无答案'] as $key=>$status)
                                       <option value="{{ $key }}" @if($key==$v3->hd_status) selected @endif>{{ $status }}</option>

                                   @endforeach
                               </select>
                               </div>
                               <div class="input-group">
                                   <a class="input-group-addon">快对作业答案情况</a>
                               <select data-id="{{ $v3->id }}" data-type="kd" class="select2 form-control answer_status_select" style="width: 100%">
                                   @foreach(['未归类','完整答案有解析','完整答案','部分答案','(无答案)往年完整答案有解析','(无答案)往年完整答案','(无答案)往年部分答案','(无答案)无答案'] as $key=>$status)
                                       <option value="{{ $key }}" @if($key==$v3->kd_status) selected @endif>{{ $status }}</option>

                                   @endforeach
                               </select>
                               </div>
                               <div class="input-group">
                                   <a class="input-group-addon">学子斋答案情况</a>
                               <select data-id="{{ $v3->id }}" data-type="xzz" class="select2 form-control answer_status_select" style="width: 100%">
                                   @foreach(['未归类','完整答案有解析','完整答案','部分答案','(无答案)往年完整答案有解析','(无答案)往年完整答案','(无答案)往年部分答案','(无答案)无答案'] as $key=>$status)
                                       <option value="{{ $key }}" @if($key==$v3->xzz_status) selected @endif>{{ $status }}</option>

                                   @endforeach
                               </select>
                               </div>
                               <p>
                               @if($v3->has_done)
                                   <a class="btn btn-success">处理完毕</a>
                                   <p>{{ \App\User::find($v3->updated_uid)->name}}.{{ $v3->updated_at }}</p>
                               @else
                                   <a data-id="{{ $v3->id }}" class="btn btn-primary confirm_done">确认完成</a>
                               @endif
                               </p>
                           </div>
                           </div>

                       </td>
                        <td>
                            {{--<a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_chapter',$v3->onlyid) }}">编辑</a>--}}
                            <p>
                            <a type="button" target="_blank" class="btn btn-primary" href="{{ route('one_lww_workbook_list',[$v3->ssort_id,$v3->sort,$v3->grade_id,$v3->subject_id,$v3->version_id]) }}">编辑</a>
                            </p>


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
                {{ $data['list']->links() }}

    <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">新增练习册</h4>
                    </div>
                    <div class="modal-body" style="height:250px;">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">书名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control bookname" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">书籍子系列</label>
                            <div class="col-sm-10">
                                <select  class="form-control book_type" >
                                    @foreach($data['type_arr'] as $v)
                                    <option value="{{ $v->ssort_id }}">{{ $v->ssort_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">所属系列</label>
                            <div class="col-sm-10">
                                <select  class="form-control book_xilie">
                                    @foreach(cache('all_sort_now') as $k=>$v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">年级</label>
                            <div class="col-sm-10">
                                <select  class="form-control book_grade">
                                    @foreach(config('workbook.grade') as $k=>$v)
                                        @if($k>0)
                                            <option value="{{ $k }}">{{ $v }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">科目</label>
                            <div class="col-sm-10">
                                <select  class="form-control book_subject">
                                    @foreach(config('workbook.subject_1010') as $k=>$v)
                                        @if($k>0)
                                            <option value="{{ $k }}">{{ $v }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本</label>
                            <div class="col-sm-10">
                                <select  class="form-control book_version">
                                    @foreach(cache('all_version_now') as $k=> $v)
                                       <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary btn_check" >确认添加</button>
                    </div>
                </div>
            </div>
        </div>




    </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function(){
            const order = '{{ $data['order'] }}';
            $('.select2').select2();
            $('#search').click(function(){
                var word=$('#search_word').val();
                axios.post('{{ route('one_lww_ajax','search_bookname') }}',{word}).then(response=>{
                    if(response.data.status===1){
                        window.location.href='{{ route('one_lww_hotbooklist') }}/?id_arr='+response.data.data.str;
                    }
                })
            });

            $(document).on('change','.type_sel,.xilie_sel,.grade_sel,.subject_sel,.version_sel',function(){
                var type_id=$('.type_sel').val()?$('.type_sel').val():-1;
                var xilie_id=$('.xilie_sel').val()?$('.xilie_sel').val():-1;
                var grade_id=$('.grade_sel').val()?$('.grade_sel').val():-1;
                var subject_id=$('.subject_sel').val()?$('.subject_sel').val():-1;
                var version_id=$('.version_sel').val()?$('.version_sel').val():-1;
                window.location.href = '{{ route('one_lww_hotbooklist') }}/'+type_id+'/'+xilie_id+'/'+grade_id+'/'+subject_id+'/'+version_id+'/'+order;
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
                let id = $(this).attr('data-id');
                let answer_type = $(this).attr('data-type');
                let answer_status = $(this).val();
                axios.post('{{ route('one_lww_ajax','hotbook_update_answer_status') }}',{id,answer_type,answer_status}).then(response=>{
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
            });

            //保存答案网址
            $('.save_answer_url').click(function () {
                let answer_url = $(this).prev().val();
                let answer_url_type = $(this).parent().find('select').val();
                let id = $(this).parents('tr').attr('data-id');
                axios.post('{{ route('one_lww_ajax','save_answer_url') }}',{id,answer_url,answer_url_type}).then(response=>{
                    if(response.data.status===0){
                        alert('更新失败');
                    }
                })
            });

            //确认处理完毕
            $('.confirm_done').click(function () {
                if(!confirm('确认处理完毕')){
                    return false;
                }
                let id = $(this).attr('data-id');
                axios.post('{{ route('one_lww_ajax','confirm_done_hotbooks') }}',{id}).then(response=>{
                    if(response.data.status===0){
                        alert('更新失败');
                    }else{
                        let now_data = response.data.data;
                        $(this).parent().html(`<a class="btn btn-success">处理完毕</a><p>${now_data.name}.${now_data.time}</p>`)
                    }
                })
            })

            //更新hot_book表
            $('.save_hot_info').click(function () {
                let book_id = $(this).parents('td').attr('data-id');
                let data_type = $(this).attr('data-type');
                let data_val = $(this).parent().find('select').val();
                axios.post('{{ route('one_lww_ajax','change_hotbook_info') }}',{book_id,data_type,data_val}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                })
            })
        })
    </script>
@endpush