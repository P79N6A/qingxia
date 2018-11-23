@extends('layouts.backend')

@section('question_manage_index')
    active
@endsection

@push('need_css')
    <link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-hotspotter.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pageeditor/jquery-ui-1.9.2.custom.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/pageeditor/editor.css') }}">
<style>
.question_img{
    max-height: 500px;
}
.edit-area{
    margin-top: 30px;
}
#add_to_workplace{
    position: fixed;
    right: 200px;
    z-index: 999;
}
#del_to_recycle{
    position: fixed;
    right: 30px;
    z-index: 999;
}
.pointer{
    cursor: pointer;
}

.timu_sort {
    float: left;
    width: 25px;
    height: 20px;
}
.timu_page {
    float: right;
    width: 22px;
    margin-right: 18px;
    height: 20px;
}
#E_edit1{

}
.border-huge{
    border:none;
}

/*.border-huge{*/
    /*border: 5px solid black;*/
/*}*/
#img_operate_box_body.modal-body{
    padding: 0;
}
#edit-area.thumbnail{
    padding: 0;
}
#edit-area.thumbnail img{
    margin-left: 0;
}

</style>
@endpush

@section('content')
    <div class="modal fade" id="show_big_pic">
        <div class="modal-dialog" style="width: 70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="btn btn-primary" @click="page_rotata('left')">向左转</a>
                    <a class="btn btn-primary" @click="page_rotata('right')">向右转</a>
                    <a class="btn btn-primary hide" @click="page_zoom()">放大</a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <a :class="click_to_answer?'col-md-6 thumbnail pull-left':'thumbnail'"><img :src="this.now_big_pic" :style="'transform:rotate('+now_rotate*90+'deg)'" /></a>

                        <div v-if="click_to_answer===true" class="col-md-6 pull-right">

                            {{--<textarea class="form-control" v-model="answer_text" >--}}
                                {{--<p>回答区域</p>--}}
                            {{--</textarea>--}}
                            <a style="margin-top: 20px"  class="btn btn-primary pull-right">确认回答</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reply_feedback">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    回复反馈内容
                </div>
                <div class="modal-body">
                    <textarea class="form-control" v-model="reply_feedback">

                    </textarea>
                </div>
                <div class="modal-footer">
                    <button @click="feedback_reply" class="btn btn-primary">确认</button>
                    <button class="btn btn-danger" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="img_operate_box">
        <div class="modal-dialog" style="width: 70%;">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body" id="img_operate_box_body">
                    <div class="ed-top">
                                                <span class="spot-options">
                                                    <button id="clone-btn" title="复制"><span class="btn-icon"><img src="{{ asset('images/pageeditor/clone.png') }}"/></span></button>
                                                    <button id="del-btn" title="删除"><span class="btn-icon"><img
                                                                    src="{{ asset('images/pageeditor/del.png') }}"/></span></button>
                                                    {{--<button id="show_detail"><span class="btn-icon">预览</span></button>--}}
                                                </span>
                    </div>
                    <div>
                        <a class="thumbnail" id="edit-area" style="width: 100%;">
                            <img  id="ed-img" class="responsive " style="max-width: 100%;" data-src="http://thumb.1010pic.com/pic18/user_photo/20170902/08433d363f9cd5cfe64eed25b4c886e6.jpg?t=1509671440000" src="http://thumb.1010pic.com/pic18/user_photo/20170902/08433d363f9cd5cfe64eed25b4c886e6.jpg?t=1509671440000"/></a>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>

    </div>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">解题管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 解题管理</h3>
                <a id="add_to_workplace" @click="add_to_workplace()" class="btn btn-primary pull-right" v-if="checkboxModel.length>0"> 添加至工作台(@{{ checkboxModel.length }})</a>
                <a id="del_to_recycle" @click="del_to_recycle()" class="btn btn-danger pull-right" v-if="checkboxModel.length>0">移入回收站(@{{ checkboxModel.length }})</a>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">任务大厅<em class="badge bg-light-blue">@{{ questions_count }}</em></a></li>
                        <li><a href="#tab_2" data-toggle="tab" @click="show_feedback()">反馈站<em class="badge bg-light-blue">@{{ feedback_count }}</em></a></li>
                        <li><a href="#tab_3" data-toggle="tab" @click="show_workplace()">工作台<em class="badge bg-light-blue">@{{ workspace_count }}</em></a></li>
                        <li><a href="#tab_4" data-toggle="tab" @click="show_done()">已完成<em class="badge bg-light-blue">@{{ has_done_count }}</em></a></li>
                        <li><a href="#tab_5" data-toggle="tab" @click="show_recycle()">回收站<em class="badge bg-light-blue">@{{ recycle_count }}</em></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="col-md-4" v-for="(question ,index) in questions.data" >
                                <div class="box box-primary direct-chat direct-chat-primary">
                                    <div class="box-header">
                                        <p><strong>@{{ question.created_at }}</strong></p>
                                        <label style="width: -webkit-fill-available;"><strong style="display: -webkit-box;
    margin-bottom: 0;" class="well well-sm">@{{ question.content }}<input class="flat-red" type="checkbox" :id="question.id" name="check_it" :value="index" v-model="checkboxModel"/></strong></label>
                                    </div>
                                    <div class="box-body">
                                        <div style="min-height: 510px;max-height: 510px">
                                        <a class="thumbnail" @click="show_big_pic(question.img)" data-toggle="modal" data-target="#show_big_pic"><img class="responsive question_img " :src="'http://thumb.1010pic.com/'+question.img+'?t='+Date.parse(new Date())"/></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered hide">
                                <tr>
                                    <th><label><input type='checkbox' class='input-checkbox' v-model='checkedAll' @click='check_all()'>全选</label></th>
                                    <th>练习册信息</th>
                                    <th>提交图片</th>
                                </tr>
                                <tr v-for="(question ,index) in questions.data">
                                    <td><input class="flat-red" type="checkbox" :id="question.id" name="check_it" :value="index" v-model="checkboxModel"/></td>
                                    <td>
                                        <div class="well well-sm"><a class="label label-primary">@{{ question.bookname }}</a></div>
                                        <div class="well well-sm">@{{ question.content }}</div>
                                        <div class="well well-sm">@{{ question.created_at }}</div>
                                        <div class="well well-sm">用户id:  @{{ question.uid }}</div>
                                    </td>
                                    <td><a class="thumbnail" @click="show_big_pic(question.img)" data-toggle="modal" data-target="#show_big_pic"><img class="responsive question_img" :src="'http://thumb.1010pic.com/'+question.img+'?t='+Date.parse(new Date())"/></a></td>
                                    <td></td>
                                </tr>
                            </table>
                            <ul class="pagination pull-right">
                                <li v-if="questions.current_page==1" class="page-item disabled"><span class="page-link">上一页</span></li>
                                <li v-else class="page-item btn"><a class="page-link" @click="prev_page('questions')" rel="prev">上一页</a></li>
                                <ul v-if="questions_count%20>8">
                                    <li v-for="">
                                </ul>
                                <li v-if="questions.current_page<questions.last_page" class="page-item btn" @click="next_page('questions')"><span class="page-link">下一页</span></li>
                                <li v-else class="page-item disabled"><a class="page-link" rel="next">下一页</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <table class="table table-bordered" v-if="feedback.length>0">
                                <tr>
                                    <th>题目描述</th>
                                    <th>题目图片</th>
                                    <th>题目回答</th>
                                    <th>反馈内容</th>
                                    <th>反馈时间</th>
                                    <th>回复内容</th>
                                </tr>
                                <tr v-for="(question ,index) in feedback.data">
                                    <td><strong>@{{ question.content }}</strong></td>
                                    <td><a class="thumbnail" @click="show_big_pic(question.img)" data-toggle="modal" data-target="#show_big_pic"><img class="responsive question_img" :src="'http://thumb.1010pic.com/'+question.img"/></a></td>
                                    <td>@{{ question.answer }}</td>
                                    <td>@{{ question.feedback }}</td>
                                    <td>@{{ question.added_at }}</td>
                                    <td>
                                        <a v-if="question.status==0" class="btn btn-xs btn-primary" data-toggle="modal" @click="now_reply(question.id,index)" data-target="#reply_feedback">添加回复</a>
                                        <a v-else>@{{ question.solution }}</a>
                                    </td>
                                </tr>
                            </table>
                            <ul class="pagination pull-right" v-if="workspace.length>0">
                                <li v-if="feedback.current_page==1" class="page-item disabled"><span class="page-link">上一页</span></li>
                                <li v-else class="page-item btn"><a class="page-link" @click="prev_page('feedback')" rel="prev">上一页</a></li>
                                <li v-if="feedback.current_page<feedback.last_page" class="page-item btn" @click="next_page('feedback')"><span class="page-link">下一页</span></li>
                                <li v-else class="page-item disabled"><a class="page-link" rel="next">下一页</a></li>
                            </ul>

                        </div>
                        <div class="tab-pane" id="tab_3">

                            <table style="table-layout: fixed;" class="table table-bordered" v-if="workspace.length>0">
                                <tr>
                                    <th>提交问题</th>
                                    <th>相关操作</th>
                                </tr>
                                <div>
                                    <tr v-for="(question ,index) in workspace.data">
                                <td :data-id="question.id" style="text-align: center;width:50%">
                                    <div>
                                    <span style="position: relative;">
                                        <strong @click="page_rotate_single(question.id,'left')" class="label label-info">向左转</strong>
                                    <strong @click="page_rotate_single(question.id,'right')" class="label label-info">向右转</strong>
                                    <strong @click="save_pic(question.id,question.img)" class="label label-danger">保存</strong>
                                        <a class="pointer"  @click="show_detail(question.id)">@{{ question.content }}</a>
                                        <strong class="btn btn-xs btn-info" @click="move_to(question.id,'recycle')">移至回收站</strong>
                                        <strong class="btn btn-xs btn-info" @click="move_to(question.id,'workspace')">移至任务大厅</strong>
                                        <strong class="btn btn-xs btn-danger">老师id:@{{ question.teacher_uid }}</strong>
                                        <strong class="btn btn-xs btn-danger">老师:@{{ question.teacher_name }}</strong>
                                        <strong @click="show_img_big(question.id,question.img)" :data-src="'http://thumb.1010pic.com/'+question.img"  class="btn btn-xs btn-primary" data-toggle="modal" data-target="#img_operate_box" >放大</strong>
                                        </span>
                                        <div>
                                            <label class="label label-info">@{{ question['grade_name'] }}</label>
                                        </div>
                                    </div>


                                        <a :data-id="question.id" class="edit-area thumbnail">
                                                <img class="ed-img responsive question_img" :data-src="'http://thumb.1010pic.com/'+question.img" :src="'http://thumb.1010pic.com/'+question.img+'?t='+now_time"/></a>

                                </td>
                                <td class="answer_tool" :data-qid="question.id">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" :href="'#answer_tool_'+question.id+'_1'" class="btn btn-xs btn-danger">识别</a></li>
                                            <li><a data-toggle="tab" :href="'#answer_tool_'+question.id+'_2'" @click="show_answer_area(question.id,'',0)" class="btn btn-xs btn-primary">解答</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" :id="'answer_tool_'+question.id+'_1'">
                                                <div class="all_content_for">
                                                <div class="input-group">
                                                    <input type="text" class="now_search_word form-control" placeholder="搜索文字" />
                                                    <label :data-qid="question.id" class="now_search_btn btn btn-danger input-group-addon">搜索</label>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" :id="'answer_tool_'+question.id+'_2'">
                                                提交时间:<a class="label label-info">@{{ question.created_at }}</a>
                                                <br>
                                                加入工作台时间:<a class="label label-info">@{{ question.added_at }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{--<td><a class="btn btn-xs btn-default">图片解答</a><a class="btn btn-xs btn-default">文字解答</a></td>--}}
                            </tr>
                                </div>

                            </table>
                            <ul class="pagination pull-right" v-if="workspace.length>0">
                                <li v-if="workspace.current_page==1" class="page-item disabled"><span class="page-link">上一页</span></li>
                                <li v-else class="page-item btn"><a class="page-link" @click="prev_page('workspace')" rel="prev">上一页</a></li>
                                <li v-if="workspace.current_page<workspace.last_page" class="page-item btn" @click="next_page('workspace')"><span class="page-link">下一页</span></li>
                                <li v-else class="page-item disabled"><a class="page-link" rel="next">下一页</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <table class="table table-bordered" v-if="has_done.length>0">
                                <tr>
                                    <th>描述</th>
                                    <th>问题图片</th>
                                    <th>回答内容</th>
                                </tr>
                                <tr v-for="(question ,index) in has_done.data">
                                    <td>
                                        <div class="well well-sm">@{{ question.content }}</div>
                                        <div class="well well-sm">提交时间：<strong>@{{ question.created_at }}</strong></div>
                                        <div class="well well-sm">加入工作台时间：<strong>@{{ question.added_at }}</strong></div>
                                        <div class="well well-sm">回答时间：<strong>@{{ question.updated_at }}</strong></div>
                                    </td>
                                    <td  style="width:50%">
                                        <div>
                                        <span style="position: relative;">
                                        <strong @click="page_rotate_single(question.id,'left')" class="label label-info">向左转</strong>
                                    <strong @click="page_rotate_single(question.id,'right')" class="label label-info">向右转</strong>
                                    <strong @click="save_pic(question.id,question.img)" class="label label-danger">保存</strong>
                                        </span>
                                            <strong class="btn btn-xs btn-primary">老师id:@{{ question.teacher_uid }}</strong>
                                            <strong class="btn btn-xs btn-primary">老师:@{{ question.teacher_name }}</strong>
                                        </div>
                                        <a class="thumbnail edit-area" :data-id="question.id"  @click="show_big_pic(question.img)" data-toggle="modal" data-target="#show_big_pic"><img class="responsive question_img can_record " :data-src="'http://thumb.1010pic.com/'+question.img" :src="'http://thumb.1010pic.com/'+question.img+'?t='+now_time"/></a></td>
                                    {{--<td><div class="jumbotron" v-html="question.answer"></div></td>--}}
                                    <td>
                                        <a class="btn btn-primary btn-xs" @click="show_answer_area(question.qid,question.answer,question.id)">重新编辑</a>
                                        <img class="can_record" :src="'http://thumb.1010pic.com/'+question.answer_pic+'?t='+now_time" />
                                        <div class="row" v-if="question.t_img">
                                            <a class="col-md-4 thumbnail" v-for="teacher_img in question.t_img.split('|')">
                                                <img class="can_record" :src="'http://thumb.1010pic.com/'+teacher_img"/>
                                            </a>
                                            {{--v-for="(teacher_img) in question.t_img.split('|')"--}}
                                            {{--<img :src="'http://thumb.1010pic.com/'+teacher_img">--}}
                                        </div>
                                        <div class="row" v-if="question.has_audio">
                                            <div v-for="teacher_audio in question.audio_about">
                                                <audio controls class="can_record" :src="'http://thumb.1010pic.com/'+teacher_audio.voice_location"></audio>
                                            </div>
                                            {{--v-for="(teacher_img) in question.t_img.split('|')"--}}
                                            {{--<img :src="'http://thumb.1010pic.com/'+teacher_img">--}}
                                        </div>

                                    </td>

                                </tr>
                            </table>

                            <ul class="pagination pull-right" v-if="has_done.length>0">
                                <li v-if="has_done.current_page==1" class="page-item disabled"><span class="page-link">上一页</span></li>
                                <li v-else class="page-item btn"><a class="page-link" @click="prev_page('has_done')" rel="prev">上一页</a></li>
                                <li v-if="has_done.current_page<has_done.last_page" class="page-item btn" @click="next_page('has_done')"><span class="page-link">下一页</span></li>
                                <li v-else class="page-item disabled"><a class="page-link" rel="next">下一页</a></li>
                            </ul>

                        </div>
                        <div class="tab-pane" id="tab_5">
                            <table class="table table-bordered" v-if="recycle.length>0">
                                <tr>
                                    <th>描述</th>
                                    <th>提交图片</th>
                                    <th>提交时间</th>
                                    <th>删除时间</th>
                                </tr>
                                <tr v-for="(question ,index) in recycle.data">
                                    <td>
                                        <strong>@{{ question.content }}</strong>
                                        <strong class="btn btn-xs btn-primary">老师id:@{{ question.teacher_uid }}</strong>
                                        <strong class="btn btn-xs btn-primary">老师:@{{ question.teacher_name }}</strong>
                                        <strong class="btn btn-xs btn-info" @click="move_to(question.id,'workspace')">移回任务大厅</strong>
                                    </td>
                                    <td><a class="thumbnail" @click="show_big_pic(question.img)" data-toggle="modal" data-target="#show_big_pic"><img class="responsive question_img" :src="'http://thumb.1010pic.com/'+question.img"/></a></td>
                                    <td>@{{ question.created_at }}</td>
                                    <td>@{{ question.added_at }}</td>

                                </tr>
                            </table>

                            <ul class="pagination pull-right" v-if="recycle.length>0">
                                <li v-if="recycle.current_page==1" class="page-item disabled"><span class="page-link">上一页</span></li>

                                <li v-else class="page-item btn"><a class="page-link" @click="prev_page('recycle')" rel="prev">上一页</a></li>
                                <li v-if="recycle.current_page<recycle.last_page" class="page-item btn" @click="next_page('recycle')"><span class="page-link">下一页</span></li>
                                <li v-else class="page-item disabled"><a class="page-link" rel="next">下一页</a></li>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="panel panel-primary" id="answer_box" style="z-index:9;width:400px;display: none;position: fixed;top: 200px;right: 30px;">
                <div class="panel panel-body ui-draggable ui-draggable-handle">
                <script id="E_edit1" type="text/plain"></script>
                </div>
                    <div class="panel-footer">
                <a class="btn btn-primary" @click="confirm_answer()">保存</a>
                <a class="btn btn-default" @click="cancel_answer()">取消</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script>
//    export default{
////    })
    let app = new Vue({
        el: '#app-vue',
        data: {
            questions:[],
            checkboxModel : [],
            checkedAll:'',
            now_big_pic:'',
            now_rotate:0,
            now_rotate_id:0,
            zoom:1,
            feedback:[],
            workspace:[],
            has_done:[],
            recycle:[],
            questions_count:'',
            feedback_count:'',
            workspace_count:'',
            has_done_count:'',
            recycle_count:'',
            current_url :'qweqwe',
            reply_feedback:'',
            reply_feedback_id:'',
            reply_index:'',
            click_to_answer:false,
            answer_id:'',
            edit_id:0,
            answer_text:'',
            old_workspace_data:[],
            now_time:0
        },
        created() {
            this.get_square();
            this.get_all_num();
            this.now_time = Date.parse(new Date());
            console.log('created');
        },
        updated(){
          let bookpage = {};
          if(this.workspace.length>0){
              Editor.init();
              Editor.initNewImage($('#ed-img').attr('src'));

              $(document).on('mousedown','.ed-img',function () {
                  $('.edit-area').show();
                  $('#edit-area').attr('data-id',$(this).parent().attr('data-id'));
                  console.log($('#ed-img').attr('src'));
                  console.log($(this).attr('src'));
                  console.log(Editor.spotPool);
                  Editor.spotPool = [];
                  $('#ed-img').attr('src',$(this).attr('src'));
                  $('#img_operate_box_body').appendTo($(this).parent().parent());
                  $(this).parent().hide();
                  console.log(($(this)).attr('src'));
//                  let now_src = $(this).attr('src');
//                  $('#edit-area').attr('data-id',$(this).parent().attr('data-id'));
//                  $('#edit-area img').attr('src',now_src);
                  $('.red-spot').remove();
              });

              $('#img_operate_box').on('hide.bs.modal',function () {
                  $('.red-spot').remove();
              });
          }
        },
        methods:{
            //获取任务大厅数据
            get_square() {
                axios.get('{{ route('api_que_manage_index','get_square') }}').then(response=>{
                    this.questions = response.data.question;
                    this.questions_count = this.questions.total;
                }).catch(function (error) {
                    console.log(error)
                })
            },
            //获取统计数据
            get_all_num(){
                axios.get('{{ route('api_que_manage_index','get_all_num') }}').then(response=>{
                    this.feedback_count = response.data.feedback_count;
                    this.workspace_count = response.data.workspace_count;
                    this.has_done_count = response.data.has_done_count;
                    this.recycle_count = response.data.recycle_count;
                }).catch(function () {

                });
            },
            //上一页
            prev_page(type) {
                if(type==='questions'){
                    axios.get(this.questions.prev_page_url).then(response=>{
                        this.questions = response.data.question;
                    }).catch(function (error) { console.log(error) })
                }else if(type==='feedback'){
                    axios.get(this.feedback.prev_page_url).then(response=>{
                        this.feedback = response.data.feedback;
                        this.feedback.length = response.data.feedback.data.length;
                    }).catch(function (error) { console.log(error) })
                }else if(type==='workspace'){
                    $('.red-spot').remove();
                    this.workspace.data = [];
                    $('#img_operate_box_body').appendTo($('#img_operate_box .modal-content'));
                    axios.get(this.workspace.prev_page_url).then(response=>{
                        this.workspace = response.data.work_space;
                        this.workspace.length = response.data.work_space.data.length;
//                        $('.ed-img:eq(0)').mousedown();
                    }).catch(function (error) { console.log(error) })
                }else if(type==='has_done'){
                    axios.get(this.has_done.prev_page_url).then(response=>{
                        this.has_done = response.data.has_done;
                        this.has_done.length = response.data.has_done.data.length
                    }).catch(function (error) { console.log(error) })
                }else if(type==='recycle'){
                    axios.get(this.recycle.prev_page_url).then(response=>{
                        this.recycle = response.data.show_recycle;
                        this.recycle.length = response.data.show_recycle.data.length
                    }).catch(function (error) { console.log(error) })
                }else{
                    return false;
                }
                window.scrollTo(0,0);
            },
            next_page(type) {
                if(type==='questions'){
                    axios.get(this.questions.next_page_url).then(response=>{
                        this.questions = response.data.question;
                    }).catch(function (error) { console.log(error)  })
                }else if(type==='feedback'){
                    axios.get(this.feedback.next_page_url).then(response=>{
                        this.feedback = response.data.feedback;
                        this.feedback.length = response.data.feedback.data.length;
                    }).catch(function (error) { console.log(error)  })
                }else if(type==='workspace'){
                    $('.red-spot').remove();
                    this.workspace.data = [];
                    $('#img_operate_box_body').appendTo($('#img_operate_box .modal-content'));
                    axios.get(this.workspace.next_page_url).then(response=>{
                        this.workspace = response.data.work_space;
                        this.workspace.length = response.data.work_space.data.length;
                        //$('.ed-img:eq(0)').mousedown();
                    }).catch(function (error) { console.log(error)  })
                }else if(type==='has_done'){
                    axios.get(this.has_done.next_page_url).then(response=>{
                        this.has_done = response.data.has_done;
                        this.has_done.length = response.data.has_done.data.length
                    }).catch(function (error) { console.log(error)  })
                }else if(type==='recycle'){
                    axios.get(this.recycle.next_page_url).then(response=>{
                        this.recycle = response.data.show_recycle;
                        this.recycle.length = response.data.show_recycle.data.length
                    }).catch(function (error) { console.log(error)  })
                }else{
                    return false;
                }
                window.scrollTo(0,0);
            },
            //全选
            check_all: function() {
                if(this.checkboxModel.length==this.questions.data.length){
                    this.checkboxModel = []
                }else{
                    let now_checked = this.checkboxModel = [];
                    this.questions.data.forEach(function (item,i) {
                        now_checked.push(i)
                    });
                    this.checkboxModel = now_checked;
                    this.checkedAll = 1;
                }
            },

            //显示可画图大图
            show_img_big(id,img){
                //非编辑状态
                if($('#img_operate_box #img_operate_box_body').length==0) {
                    $('#img_operate_box_body').appendTo($('#img_operate_box .modal-content'));
                    $('.edit-area').show();
                }
                    let now_src = img;
                    $('#edit-area').attr('data-id',id);
                    $('#edit-area img').attr('src','http://thumb.1010pic.com/'+now_src);
                    Editor.spotPool = [];
                    $('.red-spot').remove();
                //this.now_big_pic = 'http://thumb.1010pic.com/'+img;
            },
            //显示大图
            show_big_pic(now) {
                this.click_to_answer = false;
                this.now_big_pic = 'http://thumb.1010pic.com/'+now;
                this.now_rotate = 0;
                this.zoom = 1;
            },
            page_rotata(side) {
                if(side==='left'){
                    this.now_rotate -= 1;
                }else{
                    this.now_rotate += 1;
                }
            },
            page_rotate_single(id,side){
                //处于编辑状态
                if($('#img_operate_box #img_operate_box_body').length==0){
                    $('#img_operate_box_body').appendTo($('#img_operate_box .modal-content'));
                    $('.edit-area').show();
                }
                console.log(id);
                console.log(side);
                if(id!=this.now_rotate_id){
                    this.now_rotate = 0;
                }
                if(side==='left'){
                    this.now_rotate -= 1;
                }else{
                    this.now_rotate += 1;
                }
                if(this.now_rotate<0){
                    this.now_rotate = 4-parseInt(Math.abs(this.now_rotate)%4)
                }else{
                    this.now_rotate = this.now_rotate%4
                }
                console.log($(`a.edit-area[data-id="${id}"]:visible img`).attr('data-src'));

                if($(`a.edit-area[data-id="${id}"] img`).attr('data-src').match(/rotate/)!=null){
                    $(`a.edit-area[data-id="${id}"] img`).attr('data-src',$(`a.edit-area[data-id="${id}"] img`).attr('data-src').split('?')[0]);
                }
               $(`a.edit-area[data-id="${id}"] img`).attr('src',$(`a.edit-area[data-id="${id}"] img`).attr('data-src')+'?x-oss-process=image/rotate,'+this.now_rotate*90+'&time='+Date.parse(new Date()));
                $('.red-spot').remove();
                this.now_rotate_id = id;
                return false;
                //$(`a[data-id="${id}"]`).css({'transform':'rotate('+this.now_rotate*90+'deg)'});
            },
            page_zoom(){
                this.zoom = 1;
            },

            //添加至工作台
            add_to_workplace(id) {
                let now_checked = [];
                let now_questions = this.questions.data;
                this.checkboxModel.forEach(function (item,i) {
                    now_checked.push(now_questions[item].id);
                });
                let o = {
                    check:now_checked
                };
                axios.post('{{ route('api_que_manage_index','add_to_workplace') }}',o).then(response=>{
                    let now_question_len = now_questions.length;
                    let no_checked = [];
                    if(response.data.status===1){
                        this.questions_count -= this.checkboxModel.length;
                        this.workspace_count += this.checkboxModel.length;
                        for(let i=0;i<now_question_len;i++){
                            if(this.checkboxModel.indexOf(i)>=0){
                                console.log(i);
                            }else{
                                no_checked.push(this.questions.data[i]);
                            }
                        }
                        this.questions.data = no_checked;
                        this.checkboxModel = [];
                    }else{
                        let has_check_len = response.data.checked.length;
                        this.questions_count -= has_check_len;
                        this.workspace_count += this.checkboxModel.length;
                        let has_check = [];
                        for(let i=0;i<now_question_len;i++){
                            for(let j=0;j<has_check_len;j++){
                                if(this.questions.data[i].id===response.data.checked[j]){
                                    has_check.push(i);
                                }
                            }
                        }
                        for(let i=0;i<now_question_len;i++){
                            if(has_check.indexOf(i)>=0){
                                console.log(i);
                            }else{
                                no_checked.push(this.questions.data[i]);
                            }
                        }
                        this.questions.data = no_checked;
                        this.checkboxModel = [];
                        alert(response.data.msg);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //旋转后保存
            save_pic(id,img){
                let old_img = img;
                let now_img = $(`a[data-id=${id}] img`).attr('src');
                axios.post('{{ route('save_pic_to_oss') }}',{old_img,now_img}).then(function (s) {
                    alert('保存成功');
                }).catch(function (error) {
                    console.log(error);
                });
                console.log(img);
                console.log($(`a[data-id=${id}] img`).attr('src'));
            },
            move_to(id,type){
              if(type==='recycle'){
                axios.post('{{ route('question_move_to','recycle') }}',{id}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                }).catch(function (error) { console.log(error) });
              }else if(type==='workspace'){
                  axios.post('{{ route('question_move_to','workspace') }}',{id}).then(response=>{
                      if(response.data.status===1){
                          window.location.reload();
                      }
                  }).catch(function (error) { console.log(error) });
              }
            },

            //答题
            show_answer_area(id,content,edit_id){
                $('#answer_box').show();
                this.edit_id = edit_id;
                if(content){
                    ue.setContent(content);
                }
                this.click_to_answer = true;
                this.answer_id = id;
            },
            confirm_answer(){
                let answer_text = ue.getContent();
                let o ={
                    id:this.answer_id,
                    edit_id:this.edit_id,
                    answer:answer_text,
                };
                axios.post('{{ route('api_que_manage_index','make_answer') }}',o).then(reponse=>{
                    if(reponse.data.status===1){
                        axios.post('{{ route('api_que_manage_index','html_to_pic') }}',{qid:this.answer_id});
                        $('#answer_tool_'+this.answer_id+'_2 .jumbotron').remove();
                        $('#answer_tool_'+this.answer_id+'_2').append('<div class="jumbotron">'+ue.getContent()+'</div>');
                        $('#answer_box').hide();
                        ue.execCommand('cleardoc');
                        $('td[data-qid='+this.answer_id+']').parent().remove();
                        //$('#show_big_pic').modal('hide');
                        //$('a[data-id="'+this.answer_id+'"]').next().remove();
//                        $('td[data-qid='+this.answer_id+']').append('<a class="pull-right btn btn-lg btn-danger">已解决</a>');
//                        $('td[data-qid='+this.answer_id+']').parent().css({'border':'5px solid grey'})
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            },
            cancel_answer(){
                $('#answer_box').hide();
            },
            //移入回收站
            del_to_recycle(id){
                let now_checked = [];
                let now_questions = this.questions.data;
                this.checkboxModel.forEach(function (item,i) {
                    now_checked.push(now_questions[item].id);
                });
                let o = {
                    check:now_checked
                };
                axios.post('{{ route('api_que_manage_index','del_to_recycle') }}',o).then(response=>{
                    let now_question_len = now_questions.length;
                    let no_checked = [];
                    if(response.data.status===1){
                        this.questions_count -= this.checkboxModel.length;
                        this.recycle_count += this.checkboxModel.length;
                        for(let i=0;i<now_question_len;i++){
                            if(this.checkboxModel.indexOf(i)>=0){
                                console.log(i);
                            }else{
                                no_checked.push(this.questions.data[i]);
                            }
                        }
                        this.questions.data = no_checked;
                        this.checkboxModel = [];
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            //显示反馈站
            show_feedback(){
                axios.get('{{ route('api_que_manage_index','show_feedback') }}').then(response=>{
                    console.log(response.data.feedback);
                    this.feedback = response.data.feedback;
                    this.feedback.length = response.data.feedback.data.length
                }).catch(function (error) {
                    console.log(error);
                });
                this.get_all_num();
            },

            //显示工作台
            show_workplace(){
                axios.get('{{ route('api_que_manage_index','show_workplace') }}').then(response=>{
                    console.log(response.data.work_space);
                    this.workspace = response.data.work_space;
                    this.workspace.length = response.data.work_space.data.length
                }).catch(function (error) {
                    console.log(error);
                });
                this.get_all_num();
            },

            //显示已完成
            show_done(){
                axios.get('{{ route('api_que_manage_index','has_done') }}').then(response=>{

                    this.has_done = response.data.has_done;
                    this.has_done.length = response.data.has_done.data.length
                }).catch(function (error) {
                    console.log(error);
                });
                this.get_all_num();
            },

            show_recycle(){
                axios.get('{{ route('api_que_manage_index','show_recycle') }}').then(response=>{
                    console.log(response.data.show_recycle);
                    this.recycle = response.data.show_recycle;
                    this.recycle.length = response.data.show_recycle.data.length
                }).catch(function (error) {
                    console.log(error);
                });
                this.get_all_num();
            },
            //题目详情
            show_detail(id){
                window.open('{{ route('que_manage_index') }}/detail/'+id);
            },
            now_reply(id,index){
                this.reply_feedback = '';
                this.reply_feedback_id = id;
                this.reply_index = index;
            },
            //发送回复
            feedback_reply(){
                let o ={
                    id:this.reply_feedback_id,

                    solution:this.reply_feedback
                };
                axios.post('{{ route('api_que_manage_index','feedback_reply') }}',o).then(response=>{
                    if(response.data.status===1){
                        console.log(this.feedback);
                        this.feedback.data[this.reply_index].status=1;
                        this.feedback.data[this.reply_index].solution=this.reply_feedback;
                        $('#reply_feedback').modal('hide');
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            }
        },
    });

    window.UEDITOR_HOME_URL = '{{ asset('ueditor') }}/';
</script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/pageeditor/jquery-hotspotter.min.js') }}"></script>
<script src="{{ asset('js/pageeditor/editor_many.js').'?t='.time() }}"></script>

<script src="{{ asset('ueditor/ueditor.config.js') }}"></script>
<script src="{{ asset('ueditor/ueditor1.all.js') }}"></script>
<script src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>
<script src="{{ asset('ueditor/kityformula-plugin/addKityFormulaDialog.js') }}"></script>
<script src="{{ asset('ueditor/kityformula-plugin/getKfContent.js') }}"></script>
<script src="{{ asset('ueditor/kityformula-plugin/defaultFilterFix.js') }}"></script>
<script>
    let toolbar = {
        toolbars: [[
            'source', '|', 'undo', 'redo',
            'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist'
        ]],
    };
    //    var ue1=UE.getEditor('E_add1', toolbar);
    //    var ue2=UE.getEditor('E_add2', toolbar);

    let ue = UE.getEditor('E_edit1', toolbar);
    ue.ready(function () {
        ue.setHeight(200);
    });
    $('#save_answer').on('click',function () {
        let answer_text = ue.getContent();
        let o ={
            id:this.answer_id,
            answer:answer_text
        };
        axios.post('{{ route('api_que_manage_index','make_answer') }}',o).then(reponse=>{
            if(reponse.data.status==1){
                $('#answer_box').hide();
                ue.execCommand('cleardoc');
                //$('#show_big_pic').modal('hide');
                $('td[data-qid="'+this.answer_id+'"]').html('<a class="btn btn-default">已解答</a>')
            }
        }).catch(function (error) {
            console.log(error);
        })
    });

    //放大图片



    $(document).on('click','.ocr_btn',function () {
        let now_img_width = Editor.$edImg.width();
        let now_img_height = Editor.$edImg.height();
        let now_src = Editor.$edImg.attr('src');
        let qid = $(this).parents('#edit-area').attr('data-id');
        let sort_id = $(this).attr('data-id');
        if(Editor.spotPool[sort_id-1]!=undefined){
            Editor.spotPool[sort_id-1].flushOptions();
        }
        let p = Editor.spotPool[sort_id-1];
        let cuts = [Math.round(p.coord[0]),Math.round(p.coord[1]),Math.round(p.dim[0]),Math.round(p.dim[1])];
        cuts = cuts.join(',');
        let o = {now_img_width,now_img_height,now_src,qid,sort_id,cuts};
//        console.log(o);return false;
        axios.post('{{ route('api_que_manage_ocr') }}',o).then(response=>{
            if(response.data.status===1){
                $(`td[data-qid="${qid}"] .nav li:nth-child(1) a`).click();
                let timu_data = $.parseJSON(response.data.now_timu);
                let timu_list = `<div data-qid="${qid}" class="search_answer_box panel panel-primary">
                        <div class="panel-heading">
                            ${response.data.ocr_word}
                        </div>
                        <div class="panel-body" style="overflow-y: auto;height:500px">`;
                for(let i in timu_data){
                    timu_list += `<div class="well well-sm" data-id="${timu_data[i].id}" data-md5-id="${timu_data[i].md5id}">
<div class="now_qustion">${timu_data[i].question}</div><br><div class="now_answer">${timu_data[i].answer}</div><a class="add_to_ue btn btn-xs btn-danger" data-qid="${qid}">加入编辑框</a></div>`
                }
                $(`#answer_tool_${qid}_1 div[data-qid="${qid}"]`).remove();
                $(`#answer_tool_${qid}_1 .all_content_for`).append(timu_list);
                $(`#answer_tool_${qid}_1 .now_search_word`).val(response.data.ocr_word);
            }else{
                alert('暂无识别结果');
            }
        }).catch(function (error) { console.log(error) });

    });

    //直接文字搜索
    $(document).on('click','.now_search_btn',function () {
        let word = $(this).prev().val();
        let qid = $(this).attr('data-qid');
        axios.post('{{ route('api_que_search') }}',{word}).then(response=>{
            if(response.data.status===1){
                $(`td[data-qid="${qid}"] .nav li:nth-child(1) a`).click();
                let timu_data = $.parseJSON(response.data.now_timu);
                let timu_list = `<div data-qid="${qid}" class="search_answer_box panel panel-primary">
                        <div class="panel-heading">
                            ${word}
                        </div>
                        <div class="panel-body" style="overflow-y: auto;height:500px">`;
                for(let i in timu_data){
                    timu_list += `<div class="well well-sm" data-id="${timu_data[i].id}" data-md5-id="${timu_data[i].md5id}">
<div class="now_qustion">${timu_data[i].question}</div><br><div class="now_answer">${timu_data[i].answer}</div><a class="add_to_ue btn btn-xs btn-danger" data-qid="${qid}">加入编辑框</a></div>`
                }
                $(`#answer_tool_${qid}_1 div[data-qid="${qid}"]`).remove();
                $(`#answer_tool_${qid}_1 .all_content_for`).append(timu_list);
            }else{
                alert('暂无识别结果');
            }
        }).catch(function (error) { console.log(error) });
    });

    $(document).on('click','.add_to_ue',function () {
        let qid = $(this).attr('data-qid');
        let now_answer = $(this).prev().html();

        $(`td[data-qid="${qid}"] .nav li:nth-child(2) a`).click();
        app.show_answer_area(qid,now_answer);
        ue.ready(function () {
            ue.setContent(now_answer);
        });
    });

    $('#answer_box').draggable({});

</script>
@endpush