@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
<link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}" />
@endpush
<style>
    #show_cover_photo img{
        width:100%
    }
    .left_box .active{
        border-color: red;
    }
    .float {
        z-index:999;
        position:fixed;
        top:0px;
    }
    .big_img{
        z-index: 999;
    }
    img{
        max-width: 100%;
    }

    .panel-default .panel-body{
        max-height: 600px;
        overflow: scroll;
    }


</style>

@section('content')
    <div class="modal fade" id="show_cover_photo">
        <div class="modal-dialog" style="width:60%">
            <div class="modal-content">
                <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">查看图片</h4></div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>


  {{--  <div class="modal fade" id="show_voice_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">关联章节音频 <span class="close" data-dismiss="modal">&times;</span></div>
                <div class="modal-body">

                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>--}}
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">05网练习册管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 05网练习册管理-></h3>
                {{--<a class="FontBig">当前练习册共享路径地址:&nbsp;&nbsp;&nbsp;&nbsp;file://desktop-r455bre/public/all_book_pages/{{ $data['book_id'] }}/pages/</a>--}}
                <span class="pull-right">
                    <a href="{{ route('lww_index') }}" class="btn btn-default">返回</a>
                </span>
            </div>
            <div class="box-body">
                <ul class="nav nav-tabs">
                    @foreach($data['chapter_list'] as $volume=>$info)
                    <li><a href="#tab_{{ $volume }}" data-toggle="tab" data-volumes="{{ $volume }}">{{ config('workbook.volumes')[$volume] }}</a></li>
                    @endforeach
                </ul>

                <div class="tab-content">
                @foreach($data['chapter_list'] as $volume=>$info)
                        <div class="tab-pane" id="tab_<?php echo $volume;?>"  data-volume="{{ $volume }}">
                     <div class="row">
                    {{--<div class="col-md-4">
                        <div style="min-width: 250px;">
                            <button type="button" class="btn btn-success btn-sm" onclick="demo_create({{ $data['book_id'] }});"><i class="glyphicon glyphicon-asterisk"></i> 新增</button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="demo_rename({{ $data['book_id'] }});"><i class="glyphicon glyphicon-pencil"></i> 重命名</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="demo_delete({{ $data['book_id'] }});"><i class="glyphicon glyphicon-remove"></i> 删除</button>
                            --}}{{--<button type="button" class="btn btn-danger btn-sm" onclick="wrong_mark({{ $data['book_id'] }});"><i class="glyphicon glyphicon-remove"></i> 错误标记</button>--}}{{--
                            <button type="button" class="btn btn-primary btn-sm" onclick="demo_save({{ $data['book_id'] }});"><i class="glyphicon glyphicon-ok"></i> 保存</button>
                            <button data-id="{{ $data['book_id'] }}" class="btn btn-default btn-sm make_book_chapter">生成练习册章节</button>
                        </div>
                        <div id="jstree_demo_{{ $data['book_id'] }}" class="jstree_show demo pull-left" style="margin-top:1em; min-height:200px;"></div>
                    </div>--}}
                    <div class="col-md-3">
                        <div class="box">
                            <div class="box-body">
                                <span>
                                    <strong class="pull-left">已上传页码</strong>
                                        <a target="_blank" href="{{ route('lww_upload_page',[$data['book_id'].substr($data['year'],-2,2).$data['volume_id']]) }}" class="pull-right btn btn-xs btn-danger">上传页整理</a>
                                </span>
                                <br>
                                <div style="height: 100px;overflow-y: auto">
                                  @forelse($data['all_pages'][$volume] as $value)
                                    <a class="btn btn-xs btn-default">{{ $value }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                       {{-- @if(Session::has('mp3_error'))
                            <h4 class="alert alert-danger">
                                {{ Session::get('mp3_error') }}
                                <span class="close" data-dismiss="alert">&times;</span>
                            </h4>
                        @endif--}}
                        <table class="table">
                            <tr>
                                <th>章节名</th>
                                {{--<th>章节音频</th>--}}
                                {{--<th>包含页码</th>
                                <th>页管理</th>
                                <th>操作</th>--}}
                            </tr>
                            @foreach($info as $v)
                                {{--@if($v['child']==0)--}}
                                    <tr data-chapter-id="{{ $v['id'] }}" class="add_page_box">
                                        <td>{{$v['name']}}
                                        {{--<td>--}}
                                            {{--@if($value->mp3id)--}}
                                            {{--<a  class="btn btn-xs btn-primary">已关联</a>--}}
                                                {{--@else--}}
                                                {{--<a data-chapterid="{{ $value->id }}" data-toggle="modal" data-target="#show_voice_modal" class="btn btn-xs btn-default">关联</a>--}}
                                            {{--@endif--}}
                                        {{--</td>--}}
                                            <div class="input-group">
                                        <input style="width: 50%" placeholder="内容页" class="form-control pages_range hide" value="{{ $v['pages'] }}">
                                        <input style="width: 100%" placeholder="解析页" class="form-control answer_pages_range" value="{{ $v['pid_pages'] }}">
                                            </div>
                                        <div class="operate_box" data-book-id="{{ $data['book_id'].substr($data['year'],-2,2).$data['volume_id'] }}">
                                            <div class="hide">
                                            <a target="_blank" href="{{ route('lww_show_page',[$data['book_id'].substr($data['year'],-2,2).$data['volume_id'],$v['id']]) }}" class="btn btn-xs btn-primary">单题解析管理</a>
                                           {{-- @if($data['has_audio']==1)
                                            <a target="_blank" href="{{ route('lww_diandu_page',[$data['book_id'],$value['id']]) }}" class="btn btn-xs btn-success">点读管理</a>
                                            @endif--}}

                                            <a class="btn btn-xs btn-success add_page" >添加内容页</a>
                                            @if($v['has_jiexi']==0)
                                                <a class="btn btn-xs btn-default done_jiexi">确认该章节解析添加完毕</a>
                                            @else
                                                <a class="btn btn-xs btn-danger done_jiexi">解析完毕</a>
                                            @endif
                                            </div>
                                            <a target="_blank" href="{{ route('no_chapter_analysis_index',[$data['book_id'],$data['year'],$data['volume_id'],$data['single_book_id']]) }}" class="btn btn-xs btn-primary">章节解析管理</a>
                                            <a class="btn btn-xs btn-success add_answer_page" >关联解析页章节</a>
                                        </div>
                                           {{-- <div class="input-group" style="width:80%" data-chapter-id="{{ $v['id'] }}">
                                               --}}{{-- <input class="form-control pull-right start_page" placeholder="起始页">
                                                <span class="input-group-addon">到</span>
                                                <input class="form-control end_page" placeholder="结束页">
                                                <span class="input-group-addon">页</span>--}}{{--
                                                <a class="btn btn-success add_page" >添加</a>
                                            </div>--}}
                                        </td>
                                    </tr>
                                 {{--@else--}}
                                    {{--<tr><td>{{ $v['name'] }}</td></tr>--}}
                                {{--@endif--}}
                            @endforeach
                        </table>
                    </div>

                     <div class="col-md-9">
                         @if(isset($data['booklist'][$volume]))
                         <div class="input-group pull-left">
                             <input class="form-control a_bookid"  placeholder="练习册id" type="text" value="">
                             <a class="input-group-addon btn btn-primary update_bookid">确定</a>
                         </div>
                             <div style="height: 100px;overflow-y: auto;width:100%;">
                                 <a target="_blank" href="{{ route('lww_upload_page',$data['book_id'].substr($data['year'],-2,2).$data['volume_id']) }}" class="pull-right btn btn-xs btn-danger">上传页整理</a>
                             </div>
                         @endif

                             <ul class="nav nav-tabs">
                                 <li class="hide"><a href="#bookimg_content" data-toggle="tab" >内容页匹配</a></li>
                                 <li class="active"><a id="get_answer_pages" href="#bookimg_answer" data-toggle="tab">解析页匹配</a></li>
                             </ul>
                             <div class="tab-content" data-volume="{{ $volume }}">
                                 <div class="tab-pane" id="bookimg_content">
                                     <div class="bookimg_box boxlist_{{ $volume }} row">
                                         <button class="btn btn-primary get_imgs">获取书本图片</button>
                                         <div class="left_box col-md-12">
                                             <div class="imgs_box_{{ $volume }}" style="max-height:600px;overflow-y: scroll;"></div>
                                         </div>
                                    </div>
                                 </div>
                                 <div class="tab-pane active" id="bookimg_answer">
                                     <div class="bookimg_box row">
                                     <button class="btn btn-primary get_answer_imgs">获取解析</button>
                                     <div class="left_box col-md-12">
                                         <div class="answer_imgs_box" style="max-height:600px;overflow-y: scroll;"></div>
                                     </div>
                                    </div>
                                 </div>
                             </div>
                     </div>

                    </div>
                    </div>

                @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="{{ asset('js/jstree.min.js') }}"></script>
<script>
    window.onscroll=function(){
        if ($(document).scrollTop() >300)
        {
            $(".bookimg_box").addClass('float');
        }else{
            $(".bookimg_box").removeClass('float');
        }
    }


    $(function(){
        var mydate=new Date();
        var month=mydate.getMonth()+1;
        if(month>=8 || month<2){
            if($("#tab_1").length>0){
                $("[href='#tab_1']").parent('li').addClass("active");
                $("#tab_1").addClass("active");
            }else if($("#tab_3").length>0){
                $("[href='#tab_3']").parent('li').addClass("active");
                $("#tab_3").addClass("active");
            }else {
                $("[href='#tab_2']").parent('li').addClass("active");
                $("#tab_2").addClass("active");
            }

        }else{
            if($("#tab_2").length>0){
                $("[href='#tab_2']").parent('li').addClass("active");
                $("#tab_2").addClass("active");
            }else if($("#tab_3").length>0){
                $("[href='#tab_3']").parent('li').addClass("active");
                $("#tab_3").addClass("active");
            }else{
                $("[href='#tab_1']").parent('li').addClass("active");
                $("#tab_1").addClass("active");
            }

        }




    $('.book_id').click(function(){

        var box=$(this).parents('.tab-pane');
        var bookid=$(this).attr('data-bookid');
        box.find('.a_bookid').val(bookid);
    })

    //对应书本id
    $(".update_bookid").click(function(){
        var box=$(this).parents('.tab-pane');
        var volume=box.attr('data-volume');
        var a_bookid=box.find('.a_bookid').val();
        if(a_bookid==''){alert('请先选择一本书！');return;}
        axios.post('{{ route('lww_update_bookid') }}',{'volume':volume,'bookid':'{{ $data['book_id'] }}','a_bookid':a_bookid}).then(response=>{
            window.location.reload();
        }).catch(function (error) {
            alert('操作失败');
            console.log(error)
        })
    })


    });
</script>

<script>
    $(function () {

        $(document).on('click','.big_img',function (e) {
            var now_img_src = $(this).attr('data-img');
            console.log(now_img_src);
            $('#show_cover_photo').find('.modal-body').html(
                    '<img src="'+now_img_src+'">'
            )
            e.stopPropagation();
        });


        $('.get_imgs').click(function(){ //获取书本图片
            var volume=$(this).parents('.tab-content').attr('data-volume');
            var bookid='{{ $data['book_id'] }}';
            var year='{{ $data['year'] }}'
            axios.post('{{ route('lww_get_bookimgs') }}',{year,volume,bookid}).then(response=>{
                if(response.data.status==0){alert(response.data.msg);return;}
                var str='';
                $.each(response.data,function(i,e){
                    str+='<p class="thumbnail col-md-6 page_box" data-page="'+i+'"><a class="btn btn-primary big_img" data-target="#show_cover_photo" data-toggle="modal" data-img="http://daan.1010pic.com/'+e+'?t='+Date.parse(new Date())+'">放大</a><img  alt="'+e+'"  class="initial loaded" src="http://daan.1010pic.com/'+e+'?t='+Date.parse(new Date())+'"></p>';
                });
                $('.imgs_box_'+volume).html(str);
            });
        });



        $('#get_answer_pages').click(function () {
            if($('.answer_imgs_box').html()==''){
                $('.get_answer_imgs').click();
            }
        })


        $('.get_answer_imgs').click(function () {
            let now_book_id = '{{ $data['single_book_id'] }}'
            axios.post('{{ route('lww_get_answer_bookimgs') }}',{now_book_id}).then(response=>{
                if(response.data.status==0){alert(response.data.msg);return;}else{
                    let str='';
                    let now_all_pages = response.data.data.all_pages
                    for(let i in now_all_pages){
                        str+=`<div class="panel panel-default col-md-6 page_box" data-page="${now_all_pages[i].pid}">
                                <div class="panel-heading">page:${now_all_pages[i].page}  pid:${now_all_pages[i].pid}<a class="btn btn-primary" target="_blank" href="http://handler.05wang.com/htm2pic/thread_preview/${now_all_pages[i].pid}">预览</a></div>
                                <div class="panel-body">${now_all_pages[i].message_html}</div>

</div>`;
                    }

                    $('.answer_imgs_box').html(str);
                }

            });
        });


        //$('.get_imgs').click();
        $('.get_answer_imgs').click();


        var start = null;
        $(document).on('click',".page_box",function(e){
            var volume=$(this).parents('.tab-content').attr('data-volume');
            if (e.shiftKey) {
                var si = $(start).index();
               // console.log(si);
                var ti = $(this).index();
                var sel = $('.imgs_box_'+volume).find(".page_box").slice(Math.min(si, ti), Math.max(si, ti) + 1);
                sel.addClass('active');
                $('.imgs_box_'+volume).find(".page_box").not(sel).removeClass("active");
            } else {
                start = this;
                if ($(this).hasClass('active')) {
                    $(this).removeClass("active");
                } else {
                    $(this).addClass("active");
                }
            }
        });


    //添加章节页码
     $(document).on('click','.add_page',function () {
         var flag=0;
         var tr=$(this).parents('tr');
         var box = $(this).parents('.tab-pane');
         var page_box=tr;
         var chapter_id = tr.attr('data-chapter-id');
         var volume=box.attr('data-volume');
         var pages=[];
         var str='';
             if(page_box.prev().find('.pages_range').length==1){
                 str=page_box.prev().find('.pages_range').val();
             }
         $('.imgs_box_'+volume).find('.active').each(function(){
             if(str.indexOf($(this).attr('data-page')) != -1) flag=1;
               pages.push($(this).attr('data-page'));
         });
             tr.css("background-color",'#eee');
         axios.post('{{ route('lww_save_chapter_page') }}',{'chapter_id':chapter_id,'pages':pages}).then(response=>{
             if(response.data.status===1){
                 page_box.find('.pages_range').val(response.data.page_str);
                 $('.page_box').removeClass('active');
                 if(flag)alert('你所选的页码中有与上个章节页码！');
             }
         }).catch(function (error) {
            console.log(error)
         })
     });


     //添加答案页码
        $(document).on('click','.add_answer_page',function () {
            let page_box = $(this).parents('.add_page_box');
            let choose_imgs = $('#bookimg_answer').find('.page_box.active');
            let pages = [];
            let chapter_id = $(this).parents('tr').attr('data-chapter-id');;
            choose_imgs.each(function () {
                pages.push($(this).attr('data-page'));
            });
            if(pages.length==0) return false;
            axios.post('{{ route('lww_save_answer_chapter_page') }}',{chapter_id,pages}).then(response=>{
                if(response.data.status===1){
                    page_box.find('.answer_pages_range').val(response.data.data.page_str);
                }
                $('.page_box').removeClass('active');
            }).catch(function (error) {
                alert('操作失败');
                console.log(error)
            })
        });

     //设置当前展示学年
     $('.set_show_year_now').click(function(){
         let book_id = '{{ $data['book_id'] }}';
         let now_show_year = $(this).attr('value');
         if(confirm('更改当前展示学年')){
             axios.post('{{ route('lww_set_year') }}',{book_id,now_show_year}).then(response=>{
                 if(response.data.status===1){
                     window.location.reload();
                 }

             })
         }
     });

     $(document).on('click','.done_jiexi',function(){
            let book_id=$(this).parent('.operate_box').attr('data-book-id');
            let page_box = $(this).parents('.add_page_box');
            let chapter_id = page_box.attr('data-chapter-id');
            axios.post('{{ route('lww_set_jiexi_done') }}',{book_id,chapter_id}).then(response=>{
                if($(this).hasClass('btn-default')){
                    $(this).addClass('btn-danger').removeClass('btn-default').html('解析完毕');
                }else{
                    $(this).addClass('btn-default').removeClass('btn-danger').html('确认该章节解析添加完毕');
                }

            }).catch(function (error) {
                alert('操作失败');
            })
        });

    })




</script>

{{--<script>

    function demo_create(book_id) {
        let ref = $('#jstree_demo_'+book_id).jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        //sel = ref.create_node(sel, {"type":"file"});
        sel = ref.create_node(sel, {"type":"default"});
        if(sel) {
            ref.edit(sel);
        }
    }
    function demo_rename(book_id) {
        let ref = $('#jstree_demo_'+book_id).jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        ref.edit(sel);
    };
    function demo_delete(book_id) {
        let ref = $('#jstree_demo_'+book_id).jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        ref.delete_node(sel);
    };
    function demo_save(book_id) {
        let a = confirm('确定更改章节?请确认未产生框题解答工作,否则可能丢失数据');
        if(a!==true){
            return false;
        }
        let chapter_data = [];
        let $tree = $('#jstree_demo_'+book_id);
        $($tree.jstree().get_json($tree, {
            flat: true
        })).each(function(index, value) {
                //console.log(value.text);
                let node = $tree.jstree().get_node(this.id);
                let lvl = node.parents.length;
                let idx = index;
                chapter_data[idx] = {
                    index: idx,
                    level: lvl,
                    text: value.text,
                };
            });

        axios.post('{{ route('lww_set_chapter') }}',{id:book_id,chapters:JSON.stringify(chapter_data)}).then(response=>{
            if(response.data.status===1){
                window.location.reload();
            }else{
                alert('更新失败');
            }
        }).catch(function (error) {
            console.log(error);
        });
        --}}{{--$.ajax({--}}{{--
            --}}{{--type:'post',--}}{{--
            --}}{{--url:'{{ route('lww_set_chapter') }}',--}}{{--
            --}}{{--data:{_token:token,id:book_id,chapters:JSON.stringify(chapter_data)},--}}{{--
            --}}{{--dataType:'json',--}}{{--
            --}}{{--success: function (s) {--}}{{--
                --}}{{--if(s.status==1){--}}{{--
                    --}}{{--alert('更新成功');--}}{{--
                    --}}{{--window.location.reload();--}}{{--
                --}}{{--}else{--}}{{--
                    --}}{{--alert('更新失败');--}}{{--
                --}}{{--}--}}{{--
            --}}{{--}--}}{{--
        --}}{{--})--}}{{--
    }
    $(function () {
        //初始化章节列表
        $('.jstree_show').jstree({
            "core" : {
                "animation" : 0,
                "check_callback" : true,
                "themes" : { "stripes" : true },
                'data' : {
                    'url' : function (node) {
                        //return node.id === '#' ? '/ajax_demo_roots.json' : '/ajax_demo_children.json';
                        return '/05wang/api/get_book_chapter/'+this.element.attr('id').substring(12);
                    },
                    'data' : function (node) {
                        //return { 'id' : node.id };
                        return node;
                    },
                    "state" : "open",
                }
            },
            "types" : {
                "#" : {
                    "max_children" : 1,
                    "max_depth" : 7,
                    "valid_children" : ["root"],
                    "state":["open"]
                },
                "root" : {
                    "icon" : "fa fa-share",
                    "valid_children" : ["default"],
                    "state":"open"
                },
                "default" : {
                    "icon" : "fa fa-circle-o text-aqua",
                    "valid_children" : ["default"],
                    "state":"open"
                },
            },
            "plugins" : [
                "contextmenu", "dnd", "search",
                "state", "types", "wholerow"
            ]
        });

        //通过课本生成章节
        $('.make_book_chapter').click(function () {
            let o = {
                book_id :$(this).attr('data-id')
            };
            axios.post('{{ route('set_lwwbook_chapter') }}',o).then(response=>{
                if(response.data.status==1){
                    window.location.reload();
                }
            }).catch(function (error) {
                console.log(error);
            });
        });
        //添加章节页码
        $('.add_page').click(function () {
            let page_box = $(this).parents('.add_page_box');
            let chapter_id = page_box.attr('data-chapter-id');
            let start_page = page_box.find('.start_page').val();
            let end_page = page_box.find('.end_page').val();

            if(isNaN(start_page) || isNaN(end_page) || end_page<start_page){
                alert('输入有误');
            }
            let o = {
                book_id: '{{ $data['book_id'] }}',
                chapter_id:chapter_id,
                start_page:start_page,
                end_page:end_page
            };
            axios.post('{{ route('lww_set_chapter_page') }}',o).then(response=>{
                page_box.find('.pages_range').val(response.data.page_str);
            }).catch(function (error) {
                alert('操作失败');
                console.log(error)
            })
        });


    });
</script>--}}
@endpush