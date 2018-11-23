@extends('layouts.backend')

@section('lww_index','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <style>
        #edui_fixedlayer{
            z-index: 2000 !important;
        }
        #myModal .book-box {
            width: 200px;
            height: 200px;
            background: #eee;
            padding: 5px 8px;
            border: 1px #ccc solid;
            float: left;
        }

        #myModal .book-box .img {
            width: 180px;
            height: 180px;
            float: left;
        }

        #myModal .book-box .img img {
            display: block;
            margin: auto;
            max-width: 180px;
        }

        #myModal .handle {
            float: right;
        }
    </style>
@endpush


@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
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
        <div class="box">

            <div class="onlybook_box hide">
            {{--@forelse($data['only_detail'] as $detail)--}}
                {{--<a>{{ $detail->bookname }}</a>--}}
                {{--@foreach($detail->hasWorkbooks as $workbook)--}}
                    {{--<div class="workbook_box col-md-12" style="max-width: 500px;overflow: auto">--}}
                    {{--<p>{{ $workbook->newname }}</p>--}}
                    {{--<a class="col-md-3 thumbnail flex"><img class="img-responsive" src="{{ $workbook->cover }}" alt=""></a>--}}
                    {{--</div>--}}
                {{--@endforeach--}}
            {{--@endforeach--}}
            </div>


            @foreach($data['year_arr'] as $k=>$v)
                <a class="btn btn-primary @if($v->version_year == $data['year']) active @endif"
                   href="{{ route('one_lww_chapter',[$data['onlyid'],$v->version_year,$data['volume_id']]) }}">{{ $v->version_year }}</a>
            @endforeach

            <h3 class="FontBig">当前图片存放地址&nbsp;&nbsp;&nbsp;&nbsp;\\QINGXIA23\www\analysis\{{ $data['onlyid'] }}\{{ substr($data['year'],-2) }}\{{ $data['volume_id'] }}\</h3>


            <div class="nav-tabs-custom" style="margin-top: 50px;">
                <ul class="nav nav-tabs">
                    <li @if ($data['volume_id'] == 0) class="active"@endif>
                        <a href="{{ route('one_lww_chapter',[$data['onlyid'],$data['year'],0]) }}"  data-volumes="0">未处理</a>
                    </li>
                    <li @if($data['volume_id'] == 1) class="active" @endif>
                        <a href="{{ route('one_lww_chapter',[$data['onlyid'],$data['year'],1]) }}"   data-volumes="1">上册</a>
                    </li>
                    <li @if ($data['volume_id'] == 2) class="active" @endif>
                        <a href="{{ route('one_lww_chapter',[$data['onlyid'],$data['year'],2]) }}"   data-volumes="2">下册</a>
                    </li>
                    <li @if ($data['volume_id'] == 3) class="active" @endif>
                        <a href="{{ route('one_lww_chapter',[$data['onlyid'],$data['year'],3]) }}" data-volumes="3">全一册</a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active"
                         id="tab_{{ $data['volume_id'] }}">
                        <div class="row" style="margin-left: 0px;">

                            <div class="col-md-4">
                                <div>
                                    <button type="button" class="hide btn btn-success btn-block cover_manage"
                                            data-toggle="modal" data-target="#myModal"
                                            data-onlyid="{{ $data['onlyid'] }}" data-volumes="{{ $data['volume_id'] }}"
                                            data-year="{{ $data['year'] }}">封面管理
                                    </button>


                                    <div class="input-group volume_change_box">
                                        <a class="btn btn-default disabled">将当前册次修改为</a>
                                        <select class="select2 form-control">
                                            <option value="1">上册</option>
                                            <option value="2">下册</option>
                                            <option value="3">全一册</option>
                                        </select>
                                        <a class="btn btn-primary update_volume">保存</a>
                                    </div>

                                    <br>
                                    <div class="input-group">
                                        <select class="select2 form-control select_chapter">
                                            <option value="0">复制章节</option>
                                            @forelse($data['year_arr'] as $k=>$v)
                                                @if($v->version_year<$data['year'])
                                                <option value="{{ $v->version_year }}">{{ $v->version_year }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <a class="input-group-addon btn btn-primary copy_chapter">复制往年章节到该年份</a>
                                    </div>

                                    <br>
                                    <button class="btn btn-info" onclick="node_create({{ $data['volume_id'] }})"> 新增</button>
                                    <button class="btn btn-info" onclick="node_rename({{ $data['volume_id'] }})"> 编辑</button>
                                    <button class="btn btn-info" onclick="node_delete({{ $data['volume_id'] }})"> 删除</button>
                                    <button class="btn btn-info" onclick="save_chapter({{ $data['volume_id'] }})"> 保存</button>
                                    <div class="form-group hide" style="float:left">
                                        <select class="form-control volumes_sel" data-volumes="{{ $data['volume_id'] }}">
                                            <option value="0">修改卷册</option>
                                            <option value="1">上册</option>
                                            <option value="2">下册</option>
                                            <option value="3">全一册</option>
                                        </select>
                                    </div>
                                    <a class="btn btn-default" href="{{ route('one_lww_chapter',[substr_replace(substr_replace($data['onlyid'],'00000',0,5), '00', -2),$data['year'],$data['volume_id']]) }}" target="_blank">查看课本</a>
                                    <a class="btn btn-primary copy_book_chapter">复制课本章节到该练习册</a>
                                </div>
                                <!--描述：jstree 树形菜单容器-->
                                <div id="jstree_demo_div_{{ $data['volume_id'] }}">
                                </div>
                            </div>
                            <div class="box-body col-md-6 right_box" style="margin-left: 20px;">
                                <div id="chapter_box" class="hide" data-id="0">
                                {{--@if(in_array(Auth::id(),[2,5,11,19,20]))--}}
                                    <a class="btn btn-danger btn-lg confirm_analysis_done">确认所有解析完毕，将解析转换为图片</a>
                                {{--@endif--}}
                                <a class="btn btn-default" id="show_chapter_pic">查看当前章节对应图片</a>
                                <a class="btn btn-default" id="renew_chapter_pic">重新生成当前章节图片</a>
                                <a class="btn btn-default" id="preview_chapter_pic">预览解析图片</a>
                                </div>
                                <div class="box collapsed-box upload_content_box">
                                    <div class="box-header">
                                        <h3 class="box-title">当前已上传内容页</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool show_upload_content" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body" style="height: 500px;overflow: scroll"></div>
                                </div>
                                <hr>


                                <div class="box show_analysis_box hide">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">答案解析</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <div class="box-body">
                                        <a target="_blank" href="{{ route('lww_chapter',[$data['onlyid'],$data['year'],$data['volume_id']]) }}" class="btn btn-primary">去做解析</a>
                                        <a target="_blank" class="hide btn btn-primary check_answer_pic">标准答案查看</a>
                                        <a class="btn btn-success generate_img hide">生成解析图</a>
                                        <div type="text/plain" id="E_add_{{ $data['volume_id'] }}" name='question'
                                             style="width:100%;"></div>
                                        <button class="btn btn-primary save_message" style="float: right;"
                                                data-volume="{{ $data['volume_id'] }}">保存内容
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="box show_answer_pic" style="z-index: 9999;">
                                    <div class="box-header">
                                        <h3 class="box-title">答案图片展示</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body" style="overflow: scroll">
                                        @forelse($data['only_detail'] as $only_book)
                                            @if($only_book->hasWorkbooks)
                                            @forelse($only_book->hasWorkbooks as $books)
                                                @if($books->volumes_id==$data['volume_id'])
                                                <div class="box">
                                                    <div class="box-header"><a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $books->id }}.html">{{ $books->bookname }}</a>
                                                        <div class="box-tools pull-right">
                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="box-body" style="overflow-y: auto;display: flex">
                                                @forelse($books->hasAnswers as $answer)
                                                    <a class="thumbnail offical_pic" style="min-width: 600px"><img src="{{ config('workbook.user_image_url').$answer->answer }}" alt=""></a>
                                                @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="height: 230px;   ">
                            <div class="book-box">
                                <div class="img">
                                    <img
                                        src="//thumb.1010pic.com/05wangapp/file/M00/00/65/CgKwP1QERvaAKjTpAABfyn4VLnw587.jpg_150x150">
                                </div>
                            </div>
                            <div class="handle">
                                <div class="form-group">
                                    <label>上传封面</label>
                                    <input type="file" name="myfile" multiple="multiple">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary save_cover">保存并更改封面</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


        </div>
    </div>
    </div>
</section>

@endsection

@push('need_js')
    <script src="{{ asset('js/jstree.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script type="text/javascript" src="{{ asset("ueditor/ueditor.config.js?t=111", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/ueditor1.all.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/lang/zh-cn/zh-cn.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/addKityFormulaDialog.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/getKfContent.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/defaultFilterFix.js", 0) }}"></script>
    <script>
        const now_volume_id = '{{ $data['volume_id'] }}';
        var toolbar = {
            toolbars: [[
                'source', '|', 'undo', 'redo',
                'bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'forecolor', 'fontfamily', 'fontsize', 'insertimage', '|', 'inserttable', 'preview', 'spechars', 'snapscreen', 'insertorderedlist', 'insertunorderedlist'
            ]], 'scaleEnabled': true, 'initialFrameHeight': 400
        };
        var ue0 = UE.getEditor(`E_add_${now_volume_id}`, toolbar);
        // var ue1 = UE.getEditor('E_add_1', toolbar);
        // var ue2 = UE.getEditor('E_add_2', toolbar);
        // var ue3 = UE.getEditor('E_add_3', toolbar);

        ue0.ready(function () {
            $(`#E_add_${now_volume_id}`).find(".edui-toolbar").prepend('<div style="float:left;">此处填写答案</div>');
        });
        // ue1.ready(function () {
        //     $("#E_add_1").find(".edui-toolbar").prepend('<div style="float:left;">此处填写答案</div>');
        // });
        // ue2.ready(function () {
        //     $("#E_add_2").find(".edui-toolbar").prepend('<div style="float:left;">此处填写答案</div>');
        // });
        // ue3.ready(function () {
        //     $("#E_add_3").find(".edui-toolbar").prepend('<div style="float:left;">此处填写答案</div>');
        // });
    </script>

    <script>
        function node_create(volumes) {
            var ref = $("#jstree_demo_div_" + volumes).jstree(true);
            var sel = ref.get_selected();
            if (!sel.length) {
                alert("请先选择一个节点");
                return;
            }
            sel = sel[0];
            sel = ref.create_node(sel);
            if (sel) {
                ref.edit(sel);
            }
        }

        function node_rename(volumes) {
            var ref = $("#jstree_demo_div_" + volumes).jstree(true);
            var sel = ref.get_selected();
            if (!sel.length) {
                alert("请先选择一个节点");
                return;
            }
            sel = sel[0];
            ref.edit(sel);
        }

        function node_delete(volumes) {
            if (!confirm('确定要删除此章节')) {
                return false;
            }
            var ref = $("#jstree_demo_div_" + volumes).jstree(true);
            var sel = ref.get_selected();
            //ref.open_all();
            /* var result=[];
             var childrenNodes=ref.get_children_dom(sel);
             if (childrenNodes) {
                 for (var i = 0; i < childrenNodes.length; i++) {
                     var row = childrenNodes[i];
                         result.push(row.id);
                 }
             }
             console.log(result);return;*/
            // var result = [];
            // getChildNodes(sel, result, ref);


            if (!sel.length) {
                alert("请先选择一个节点");
                return;
            }
            let sel_first = sel[0];
            if (ref.get_node(sel_first).parent == '#') {
                alert("根节点不允许删除");
                return;
            }
            //result.push(sel);
            ref.delete_node(sel);

            axios.post('{{ route('one_lww_ajax','del_chapter') }}',{'chapter_arr':sel});
        }


        function getChildNodes(treeNode, result, ref) {  //获取所有选中项目及子项目
            var childrenNodes = ref.get_children_dom(treeNode);
            if (childrenNodes) {
                for (var i = 0; i < childrenNodes.length; i++) {
                    var row = childrenNodes[i];
                    if ($.inArray(row.id, result) == -1) {
                        result.push(row.id);
                    }
                    result = getChildNodes(row.id, result, ref);
                }
            }
            return result;
        }

        function save_chapter(volumes) {
            var a = confirm('确定更改章节?请确认未产生框题解答工作,否则可能丢失数据');
            if (a !== true) {
                return false;
            }
            var chapter_data = [];
            var $tree = $('#jstree_demo_div_' + volumes);
            //console.log($tree.jstree().get_json($tree, {flat: true}));
            $($tree.jstree().get_json($tree, {
                flat: true
            })).each(function (index, value) {
                var node = $tree.jstree().get_node(this.id);
                var lvl = node.parents.length;
                if (lvl > 1) {
                    chapter_data[index] = {
                        level: lvl,
                        text: value.text,
                        id: value.id
                    };
                }
            });
            //console.log(chapter_data);
            axios.post('{{ route('one_lww_ajax','save_chapter') }}',{
                'onlyid': '{{ $data['onlyid'] }}',
                'year': '{{ $data['year'] }}',
                'volumes_id': volumes,
                'chapter_data': chapter_data
            }).then(response=>{
                var $tree = $('#jstree_demo_div_' + volumes);
                $tree.jstree(true).refresh();

            });
        }


        // 初始化操作
        $('.nav-tabs  li').click(function () {
            var volumes = $(this).find('a').attr('data-volumes');
            init(volumes);
        });

        function init(volumes) {
            var $tree = $("#jstree_demo_div_" + volumes).jstree({
                "core": {
                    //'multiple': false,  // 是否可以选择多个节点
                    //"check_callback": true, //    允许拖动菜单  唯一 右键菜单
                    "check_callback": true,//设置为true,当用户修改数时,允许所有的交互和更好的控制(例如增删改)
                    "themes": {"stripes": true},//主题配置对象,表示树背景是否有条带
                    "data": {
                        //'url' : url,
                        //'data' : function(node){
                        //return { 'id' : node.id };
                        //}
                        "url": "{{ route('one_lww_ajax','getchapter') }}?onlyid={{ $data['onlyid'] }}&year={{ $data['year'] }}&volumes=" + volumes,
                        "dataType": "json"
                    },
                    "check_callback": function (operation, node, node_parent, node_position, more) {
                        if (operation === "move_node") {
                            var node = this.get_node(node_parent);
                            if (node.id === "#") {
                                alert("根结点不可以删除");
                                return false;
                            }
                            if (node.state.disabled) {
                                alert("禁用的不可以删除");
                                return false;
                            }
                        } else if (operation === "delete_node") {
                            var node = this.get_node(node_parent);
                            if (node.id === "#") {
                                alert("根结点不可以删除");
                                return false;
                            }
                        }
                        return true;
                    }
                },
                "plugins": [ //插件
                    "search", //允许插件搜索
                    // "sort", //排序插件
                    "state", //状态插件
                    "types", //类型插件
                    "unique", //唯一插件
                    "wholerow", //整行插件
                    //"contextmenu",
                    "dnd"
                ],
                types: {
                    "#": {
                        "max_children": 1,
                        "max_depth": 5,
                        "valid_children": ["root"],
                        "state": ["open"]
                    },
                    "default": { //设置默认的icon 图
                        "icon": "fa fa-circle-o text-aqua"
                    }
                }
            });
            //return $tree;

            {{--$tree.on('select_node.jstree', function (e, data) {--}}
                {{--var node = data.node;--}}
                {{--var tree = data.instance;--}}
                {{--if (tree.is_parent(node)) {--}}
                    {{--$('#tab_' + volumes).find('.save_message').attr('data-tid', 0);--}}
                    {{--console.log('此节点有下级节点');--}}
                {{--} else {--}}
                    {{--var id = node.id;--}}
                    {{--$('#tab_' + volumes).find('.save_message').attr('data-tid', id);--}}

                    {{--$('#chapter_box').attr('data-id',id);--}}
                    {{--axios.post('{{ route('one_lww_ajax','get_message') }}',{id}).then(response=>{--}}
                        {{--if(response.data.status===1){--}}
                            {{--$('.right_box:visible').attr('now_chapter_id',id);--}}
                            {{--$('.right_box:visible').attr('now_volume_id',volumes);--}}
                            {{--ue0.setContent(response.data.data.message_html);--}}
                            {{--//if (volumes == 0) ue0.setContent(response.data.data.message_html);--}}
                            {{--// if (volumes == 1) ue1.setContent(response.data.data.message_html);--}}
                            {{--// if (volumes == 2) ue2.setContent(response.data.data.message_html);--}}
                            {{--// if (volumes == 3) ue3.setContent(response.data.data.message_html);--}}
                        {{--}--}}
                    {{--})--}}

                {{--}--}}

            {{--});--}}


        }
    </script>

    <script>
        $(function () {
            init({{ $data['volume_id'] }});
            /*$(document).on('click','.jstree-children .jstree-leaf',function(){
                console.log(is_parent($(this)));
                var id=$(this).attr('id');
                var jstree_id=$(this).parents('.jstree-default').attr('id');
                var volume_id=jstree_id.substr(jstree_id.length-1,1);
                $('#tab_'+volume_id).find('.save_message').attr('data-tid',id);
                api.data({'id':id}).post('admin/book/get_message').handle=function(s){
                    if(volume_id==0) ue0.setContent(s);
                    if(volume_id==1) ue1.setContent(s);
                    if(volume_id==2) ue2.setContent(s);
                    if(volume_id==3) ue3.setContent(s);
                }
            });*/


            $(".save_message").click(function () {
                var message = '';
                var volume_id = $(this).attr('data-volume');
                var id = $(this).attr('data-tid');
                if (id == 0) {
                    alert('父目录不能填写内容!');
                    return;
                }
                if(isNaN(id)){
                    alert('该章节未保存,请先保存后再操作!');
                    return;
                }
                message = ue0.getContent();
                // if (volume_id == 1) message = ue1.getContent();
                // if (volume_id == 2) message = ue2.getContent();
                // if (volume_id == 3) message = ue3.getContent();
                axios.post('{{ route('one_lww_ajax','save_message') }}',{id,volume_id,message}).then(response=>{
                    if(response.data.status===1){
                        alert('已保存');
                    }
                })
            });

            $('.volumes_sel').change(function () {
                var type = 'id';
                var old_volumes = $(this).attr('data-volumes');
                var ref = $("#jstree_demo_div_" + old_volumes).jstree(true);
                var sel = ref.get_selected();
                ref.open_all();
                var result = [];
                getChildNodes(sel, result, ref);
                sel = sel[0];
                result.push(sel);
                if (ref.get_node(sel).parent == '#') {
                    type = 'pid';
                }
                var change_volumes = $(this).val();
                //console.log(result);return;

                axios.post('{{ route('one_lww_ajax','change_volumes') }}',{
                    'id_arr': result,
                    'year': '{{ $data['year'] }}',
                    'type': type,
                    'old_volumes': old_volumes,
                    'change_volumes': change_volumes,
                    'onlyid': sel
                }).then(response=>{
                    if(response.data.status===1){
                        window.location.href = '{{ route('one_lww_chapter',[$data['onlyid'],$data['year']]) }}/' + change_volumes;
                    }
                })

            });

            $('.start_edit').click(function () {
                var id = $(this).attr('data-id');
                axios.post('{{ route('one_lww_ajax','start_edit') }}',{id}).then(response=>{
                    if(response.data.status===1){
                        $('.start_edit').hide();
                        $('.end_edit').removeClass('hide');
                        $('.tab-content').removeClass('hide');
                    }
                })
            })

            $('.end_edit').click(function () {
                var id = $(this).attr('data-id');
                axios.post('{{ route('one_lww_ajax','end_edit') }}',{id}).then(response=>{
                    if(response.data.status===1){
                        $('.end_edit').hide();
                        alert('已完成！');
                    }
                })
            })

            $(".cover_manage").click(function () {
                $('#myModal .book-box').find('img').attr('src', '');
                $('#myModal').attr({'data-bookid': 0});
                var onlyid = $(this).attr('data-onlyid');
                var volume_id = $(this).attr('data-volumes');
                var year = $(this).attr('data-year');
                axios.post('{{ route('one_lww_ajax','get_cover') }}',{onlyid,volume_id,year}).then(response=>{
                    if(response.data.status===1){
                        $('#myModal .book-box').find('img').attr('src', s.cover).css({
                            "max-width": "180px",
                            "max-height": "180px"
                        });
                        $('#myModal').attr({'data-bookid': s.id});
                    }
                })
            })

            $(".save_cover").click(function () {
                var formData = new FormData();
                var bookid = $('#myModal').attr('data-bookid');
                formData.append('bookid', bookid);
                formData.append('myfile', $('input[name=myfile]')[0].files[0]);
                axios.post('{{ route('one_lww_ajax','save_cover') }}',{formData}).then(response=>{
                    if(response.data.status===1){
                        window.location.href = '{{ route('one_lww_chapter',[$data['onlyid'],$data['year']]) }}/'+volume;
                    }
                })
            });

            //将html生成图片
            $('.generate_img').click(function () {
                $(this).parents('.right_box').find('.save_message').click();
                let html = '';
                let chapter_id = $(this).parents('.right_box').attr('now_chapter_id');
                let book_id = '{{ $data['onlyid'] }}';
                let volume_id = $(this).parents('.right_box').attr('now_volume_id');
                html = ue0.getContent();
                //if (volume_id == 0) html = ue0.getContent();
                // if (volume_id == 1) html = ue1.getContent();
                // if (volume_id == 2) html = ue2.getContent();
                // if (volume_id == 3) html = ue3.getContent();
                if(html=='' || chapter_id<0){
                    alert('请选择章节和填充内容');
                    return false;
                }
                axios.post('{{ route('one_lww_ajax','html_to_pic') }}',{html,book_id,chapter_id}).then(response=>{
                    if(response.data.status===1){
                        window.open(response.data.data.now_img);
                    }
                });
            });

            //内容页移动
            $('.upload_content_box').draggable();
            $('.upload_content_box').resizable();

            //移动答案图片
            $('.show_answer_pic').draggable();
            $('.show_answer_pic').resizable();

            //移动解析
            $('.show_analysis_box').draggable();
            $('.show_analysis_box').resizable();


            $('.select2').select2();
            //复制章节
            $('.copy_chapter').click(function () {
                if(!confirm('确认操作会覆盖当前年份现有章节')){
                    return false;
                }
                let version_year = $('.select_chapter').val();
                let onlyid = '{{ $data['onlyid'] }}';
                let volume = '{{ $data['volume_id'] }}';
                let to_year = '{{ $data['year'] }}';
                axios.post('{{ route('one_lww_ajax','upgrade_chapter') }}',{onlyid,version_year,volume,to_year}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                })
            });

            //复制课本章节
            $('.copy_book_chapter').click(function () {
                if(!confirm('确认操作会覆盖当前年份现有章节')){
                    return false;
                }

                let onlyid = '{{ $data['onlyid'] }}';
                let volume = '{{ $data['volume_id'] }}';
                let version_year = '{{ $data['year'] }}';
                axios.post('{{ route('one_lww_ajax','copy_book_chapter') }}',{onlyid,version_year,volume}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }else{
                        alert('生成失败');
                    }
                })
            });


            //展示已上传内容页
            $('.show_upload_content').click(function () {
                if($('.upload_content_box').hasClass('collapsed-box') && $('.upload_content_box .box-body').html().length<1){
                    let year = '{{ $data['year'] }}';
                    let volume = '{{ $data['volume_id'] }}';
                    let bookid = '{{ $data['onlyid'] }}';
                    axios.post('{{ route('lww_get_bookimgs') }}',{year,volume,bookid}).then(response=>{
                        if(response.data.status==0){alert(response.data.msg);return;}
                        var str='';
                        $.each(response.data,function(i,e){
                            str+='<p class="thumbnail col-md-6 page_box" data-page="'+i+'"><img  alt="'+e+'"  class="answer_pic thumbnail" src="http://daan.1010pic.com/'+e+'?t='+Date.parse(new Date())+'"></p>';
                        });
                        $('.upload_content_box .box-body').html(str);
                    });
                }
            });

            //确认当前解析完毕生成图片
            $('.confirm_analysis_done').click(function () {
                let year = '{{ $data['year'] }}';
                let volume = '{{ $data['volume_id'] }}';
                let onlyid = '{{ $data['onlyid'] }}';
                axios.post('{{ route('one_lww_ajax','confirm_analysis_done') }}',{onlyid,volume,year}).then(response=>{
                    if(response.data.status===1){
                        alert('当前解析图片已上线');
                    }
                })
            })

            //查看章节图片
            $('#show_chapter_pic').click(function () {
                let chapter_id = $(this).parents('#chapter_box').attr('data-id');
                if(chapter_id!=0){
                    window.open(`http://thumb.1010pic.com/jiexi/chapterimg/${chapter_id}.png?t={{ time() }}`)
                }
            })

            //生成当前章节图片
            $('#renew_chapter_pic').click(function () {
                let chapter_id = $(this).parents('#chapter_box').attr('data-id');
                if(chapter_id!=0){
                    let res = axios.post('{{ route('one_lww_ajax','renew_chapter_pic') }}',{chapter_id}).then(response=>{
                        if(response.data.code===0){
                            window.open(`http://thumb.1010pic.com/jiexi/chapterimg/${chapter_id}.png?t={{ time() }}111`)
                        }
                    })
                }
            });

            //预览解析图片
            $('#preview_chapter_pic').click(function () {
                let chapter_id = $(this).parents('#chapter_box').attr('data-id');
                window.open(`http://handler.05wang.com/htm2pic/thread_preview/${chapter_id}?t={{ time().str_random(3) }}111`)
            });

            //放大图片
            $('.offical_pic').click(function () {
                let answer_pic = $(this).find('img').attr('src');
                window.open(answer_pic);
            })

            //更新上下册
            $('.update_volume').click(function () {
                if(!confirm('确认修改章节')){
                    return false;
                }
               let to_volume_id = $(this).parent().find('select').val();
                let year = '{{ $data['year'] }}';
                let volume = '{{ $data['volume_id'] }}';
                let onlyid = '{{ $data['onlyid'] }}';
               axios.post('{{ route('one_lww_ajax','update_volume') }}',{year,volume,onlyid,to_volume_id}).then(response=>{
                   if(response.data.status===1){
                       alert('保存成功');
                       var $tree = $('#jstree_demo_div_' + volume);
                       $tree.jstree(true).refresh();
                   }else{
                       alert(response.data.msg);
                   }
               })
            });
        })
    </script>
@endpush