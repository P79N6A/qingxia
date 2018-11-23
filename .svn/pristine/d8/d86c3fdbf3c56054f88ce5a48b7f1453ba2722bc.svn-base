@extends('layouts.backend')

@section('lww_index','active')

@push('need_css')
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
            <div class="box-body row">
                    <div class="col-md-4">
                        <div class="detail_box">
                            <div class="col-md-6">
                            <p>onlyid:<a target="_blank" href="http://www.1010jiajiao.com/daan/book/{{ $data['only_detail']->onlyid }}.html">{{ $data['only_detail']->onlyid }}</a></p>
                            <p>onlyname:<a>{{ $data['only_detail']->bookname }}</a></p>
                            <div>
                                <a target="_blank" href="{{ 'http://www.1010jiajiao.com/daan/bookid_'.$data['book_detail']->id.'.html' }}">{{ $data['book_detail']->bookname }}</a>
                            </div>
                            @inject('barcodeGenerator', 'Picqer\Barcode\BarcodeGeneratorPNG')
                            <div>{{ $data['book_detail']->isbn }}</div>
                            @php
                                try{
                                    echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$data['book_detail']->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                }catch (Exception $e){
                                    echo '无法生成此isbn的条形码';
                                }
                            @endphp
                                <h3>
                                    <p>1.右侧图片无法正常显示则为处理解析没有预览生成图片<a class="btn btn-default" href="{{ route('no_chapter_analysis_index',[$data['onlyid'],$data['year'],$data['volume_id'],$data['bookid']]) }}">前往处理</a></p>
                                    <p>2.左侧无章节则为未处理章节<a   class="btn btn-default" href="{{ route('one_lww_chapter',[$data['onlyid'],$data['year'],$data['volume_id']]) }}">前往处理</a></p>
                                    <p>3.点击章节不翻页则为章节与解析页码未关联<a class="btn btn-default" href="{{ route('lww_chapter',[$data['onlyid'],$data['year'],$data['volume_id'],$data['bookid']]) }}">前往处理</a></p>
                                    <p>4.上述流程处理完毕,即可在该页检查,确认通过解析</p>
                                </h3>
                            </div>
                            <div class="col-md-6">
                            <a class="thumbnail"><img src="{{ $data['book_detail']->cover }}" /></a>
                            </div>

                            <div class="col-md-12">
                                @if(count($data['all_page'])>0 && $data['has_chapter_num']>0)
                            <a class="btn btn-block btn-danger" id="confirm_analysis" data_book_id="{{ $data['bookid'] }}">确认无误,通过该解析</a>
                                @endif
                            </div>
                        </div>
                        <div class="chapter_box">
                            <div id="jstree_demo_div_{{ $data['volume_id'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 analysis_box text-center ">
                        <div>
                        @forelse($data['all_page'] as $key=>$page)
                            <a data-pid="{{ $page->pid }}" data-id="{{ $key }}" class="btn @if ($loop->first) btn-danger @else btn-default @endif now_page_index">{{ $page->page }}</a>
                        @endforeach
                        </div>
                            <hr>
                        <div id="myCarousel" style="margin-top: 20px" class="clear carousel slide" data-interval="false">
                            <div class="carousel-inner">
                                @forelse($data['all_page'] as $page)
                                    <div class="item @if ($loop->first) active @endif">
                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo"
                                           data-toggle="modal" data-target="#cover_photo">
                                            <img class="answer-img img-responsive" src="{{ 'http://thumb.1010pic.com/jiexi/chapterimg/'.$page->pid.'.png' }}" alt="First slide">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control  left" href="#myCarousel"
                               data-slide="prev"><i style="left:0;top:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                            <a class="carousel-control right" href="#myCarousel"
                               data-slide="next"><i style="right:0;top:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('need_js')
    <script src="{{ asset('js/jstree.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/ueditor.config.js?t=111", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/ueditor1.all.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/lang/zh-cn/zh-cn.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/addKityFormulaDialog.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/getKfContent.js", 0) }}"></script>
    <script type="text/javascript" src="{{ asset("ueditor/kityformula-plugin/defaultFilterFix.js", 0) }}"></script>
    <script>
        const now_volume_id = '{{ $data['volume_id'] }}';

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
            let tree = $('#jstree_demo_div_' + volumes);
            $(tree.jstree().get_json(tree, {
                flat: true
            })).each(function (index, value) {
                var node = tree.jstree().get_node(this.id);
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
                let tree = $('#jstree_demo_div_' + volumes);
                tree.jstree(true).refresh();

            });
        }


        // 初始化操作
        $('.nav-tabs  li').click(function () {
            var volumes = $(this).find('a').attr('data-volumes');
            init(volumes);
        });

        function init(volumes) {
            var tree = $("#jstree_demo_div_" + volumes).jstree({
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
                        "dataType": "json",

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

            tree.on('select_node.jstree', function (e, data) {
                var node = data.node;
                var tree = data.instance;
                let chapterid = node.id;
                axios.post('{{ route('preview_analysis_ajax',['get_pid']) }}',{chapterid}).then(response=>{
                    if(response.data.status===1){
                        let pid = response.data.data.now_pid;
                        $(`.now_page_index[data-pid="${pid}"]`).click();
                    }else{
                        alert('当前章节未分配页码');
                    }
                })
            });
        }

        $(function () {
            init({{ $data['volume_id'] }});
            $(document).on('click','.now_page_index',function () {
                $('.now_page_index.btn-danger').removeClass('btn-danger').addClass('btn-default');
                $(this).addClass('btn-danger').removeClass('btn-default');
                let page_index = $(this).attr('data-id');
                let now_pid = $(this).attr('data-pid');
                $("#myCarousel").carousel(parseInt(page_index));
            })

        })

        //通过该解析
        $('#confirm_analysis').click(function () {
            if(!confirm('确认所有页码均可显示，通过该解析')){
                return false;
            }
            let now_book_id = $(this).attr('data_book_id');
            axios.post('{{ route('preview_analysis_ajax','confirm_done') }}',{now_book_id}).then(response=>{
                if(response.data.status===1){
                    alert('已通过审核,请及时在手机端检查确认');
                }
            })
        })



    </script>
@endpush