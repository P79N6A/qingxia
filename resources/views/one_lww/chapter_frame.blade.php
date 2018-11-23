@push('need_css')
    <link rel="stylesheet" href="{{ asset('css/jstree.style.min.css') }}"/>
    <style>
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
                   href="{{ route('one_lww_chapter',[$data['onlyid'],$v]) }}">{{ $v->version_year }}</a>
            @endforeach

            <div class="nav-tabs-custom" style="margin-top: 50px;">
                <ul class="nav nav-tabs">
                    <li @if ($data['volume_id'] == 0) class="active"@endif>
                        <a href="#tab_0" data-toggle="tab" data-volumes="0">未处理</a>
                    </li>
                    <li @if($data['volume_id'] == 1) class="active" @endif>
                        <a href="#tab_1" data-toggle="tab" data-volumes="1">上册</a>
                    </li>
                    <li @if ($data['volume_id'] == 2) class="active" @endif>
                        <a href="#tab_2" data-toggle="tab" data-volumes="2">下册</a>
                    </li>
                    <li @if ($data['volume_id'] == 3) class="active" @endif>
                        <a href="#tab_3" data-toggle="tab" data-volumes="3">全一册</a>
                    </li>
                </ul>

                <div class="tab-content">
                    @foreach(range(0,4) as $i)
                    <div class="tab-pane @if($i == $data['volume_id']) active @endif"
                         id="tab_{{ $i }}">
                        <div class="row" style="margin-left: 0px;">
                            <div class="col-md-4">
                                <div>
                                    <button type="button" class="btn btn-success btn-block cover_manage"
                                            data-toggle="modal" data-target="#myModal"
                                            data-onlyid="{{ $data['onlyid'] }}" data-volumes="{{ $i }}"
                                            data-year="{{ $data['year'] }}">封面管理
                                    </button>
                                    <button class="btn btn-info" onclick="node_create({{ $i }})"> 新增</button>
                                    <button class="btn btn-info" onclick="node_rename({{ $i }})"> 编辑</button>
                                    <button class="btn btn-info" onclick="node_delete({{ $i }})"> 删除</button>
                                    <button class="btn btn-info" onclick="save_chapter({{ $i }})"> 保存</button>
                                    <div class="form-group" style="float:left">
                                        <select class="form-control volumes_sel" data-volumes="{{ $i }}">
                                            <option value="0">修改卷册</option>
                                            <option value="1">上册</option>
                                            <option value="2">下册</option>
                                            <option value="3">全一册</option>
                                        </select>
                                    </div>
                                </div>
                                <!--描述：jstree 树形菜单容器-->
                                <div id="jstree_demo_div_{{ $i }}">
                                </div>
                            </div>
                            <div class="box-body col-md-7 right_box" style="margin-left: 20px;">
                                <a target="_blank" href="{{ route('lww_chapter',[$data['onlyid'],$data['year']]) }}" class="btn btn-primary">去做解析</a>
                                <a class="btn btn-success generate_img">生成解析图</a>
                                <div type="text/plain" id="E_add_{{ $i }}" name='question'
                                     style="width:100%;"></div>
                                <button class="btn btn-primary save_message" style="float: right;"
                                        data-volume="{{ $i }}">保存内容
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
