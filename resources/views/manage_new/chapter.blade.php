@extends('layouts.backend')

@section('book_new_chapter','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <div class="modal fade" id="show_big_pic">
        <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">唯一表整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">

            <div class="" style="position: fixed;top: 50px;right:10px;z-index: 999;">
                <div class="box box-primary box-solid collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">章节查看</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div style="height:500px;width: 500px;overflow-y: auto">
                            @if(isset($data['chapter_info']) && $data['chapter_info'])
                                @foreach($data['chapter_info'] as $chapter)
                                    <a class="list-group-item">{{ $chapter->chaptername }}</a>
                                @endforeach
                            @else
                                <p>暂无对应章节</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 唯一表整理</h3>
                <a class="btn btn-danger pull-right" href="{{ route('book_new_status') }}">整理状况查看</a>
            </div>
            <div class="box-body">
                <div style="clear: both"></div>
                <div class="form-group">
                    <div class="input-group pull-left" style="width: 25%;">
                        <label class="input-group-addon">年级</label>
                        <select id="new_grade_select" data-name="grade"
                                class="grade_id form-control select2 pull-left" tabindex="-1"
                                aria-hidden="true">
                            <option selected="selected" value="{{ $data['grade_search'] }}">{{ config('workbook.grade')[$data['grade_search']] }}</option>
                        </select>
                    </div>
                    <div class="input-group pull-left" style="width: 25%">
                        <label class="input-group-addon">科目</label>
                        <select id="new_subject_select" data-name="subject" class="subject_id form-control select2"
                                tabindex="-1" aria-hidden="true">
                            <option selected="selected" value="{{ $data['subject_search'] }}">{{ config('workbook.subject_1010')[$data['subject_search']] }}</option>
                        </select>
                        <label class="input-group-addon btn btn-primary" id="to_volume_version">查看</label>
                    </div>

                </div>
                <div class="clearfix"></div>
                <br>

                <div class="">
                    <ul class="nav nav-pills">
                        @forelse($data['all_volume_version'] as $value)
                            <li>
                                <button data-toggle="dropdown" class="btn-get-menu btn @if($value[0]->volumes_id==$data['volume_search']) btn-danger @else btn-primary @endif">{{ $data['all_volumes']->where('id',$value[0]->volumes_id)->first()->volumes }}</button>
                                <ul class="dropdown-menu">
                                    @foreach($value as $num)
                                        <li><a href="{{ route('book_new_chapter',[$data['grade_search'],$data['subject_search'],$num->volumes_id,$num->version_id,$data['type']]) }}">
                                            <i class="fa fa-circle-o">{{ $data['all_version']->where('id',$num->version_id)->first()->name }}</i>
                                            <small class="label pull-right  @if($num->version_id==$data['version_search']) bg-red @else bg-blue @endif">{{ $num->num }}</small>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="input-group hide" style="width: 50%">
                    <label class="input-group-addon">系列查询</label>
                    <select class="form-control sort_name click_to">

                    </select>
                    <a class="input-group-addon btn btn-primary click_to_btn">查看</a>
                </div>

                <hr>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li @if($data['type']==='unfinished') class="active" @endif><a
                                    href="{{ route('book_new_chapter',[$data['grade_search'],$data['subject_search'],$data['volume_search'],$data['version_search'],'unfinished']) }}">未整理</a></li>
                        <li @if($data['type']==='finished') class="active" @endif><a
                                    href="{{ route('book_new_chapter',[$data['grade_search'],$data['subject_search'],-1,-1,'finished']) }}">已整理</a></li>
                    </ul>
                    @if($data['type']==='finished')
                        <div class="input-group">
                            <label class="input-group-addon btn btn-primary" id="redict_to">跳转至该id</label>
                            <input class="form-control"/>
                        </div>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane @if($data['type']==='unfinished') active @endif" id="unfinished">
                            @if($data['type']==='unfinished')
                                @foreach($data['all_book'] as $book)
                                    <div class="box-body well single_book_info" data-id="{{ $book->id }}">
                                        <div class="col-md-6">
                                            <div>
                                                <strong data-side="left"
                                                        class="page_rotate_single label label-info">向左转</strong>
                                                <strong data-side="right" class="page_rotate_single label label-info">向右转</strong>
                                                <strong class="save_pic label label-danger">保存</strong>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="thumbnail" data-target="#show_big_pic" data-hd-cover="{{ isset($book->has_hd_book->cover_photo)?$book->has_hd_book->cover_photo:'none' }}" data-toggle="modal">
                                                    <img data-src="{{ $book->cover }}"
                                                         src="{{ $book->cover.'?t='.time() }}" alt="">
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>练习册id:{{ $book->id }}</strong></p>
                                                <a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html">{{ $book->bookname }}</a>
                                            </div>
                                            <div class="col-md-12">
                                            @if(count($book->has_answers)>0)
                                                <div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false">
                                                    <div class="carousel-inner" >
                                                        @foreach($book->has_answers as $key => $answer)
                                                            @php  $answers = explode('|',$answer->answer); @endphp
                                                            @if(is_array($answers))
                                                                @foreach($answers as $answer_img)
                                                                    <div class="item @if ($loop->first && $key==0) active  @endif">
                                                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                            <img class="answer-img img-responsive" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}"
                                                                                 alt="First slide">
                                                                        </a>
                                                                        <div class="carousel-caption text-orange">{{ $answer->textname }}</div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="item @if ($loop->first && $key==0) active @endif">
                                                                    <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                        <img class="answer-img img-responsive" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer->answer) }}" alt="First slide">
                                                                    </a>
                                                                    <div class="carousel-caption text-orange FontBig">{{ $answer->textname }}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <a class="carousel-control  left" href="#myCarousel_{{ $book->id }}"
                                                       data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                    <a class="carousel-control right" href="#myCarousel_{{ $book->id }}"
                                                       data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                </div>
                                            @else
                                                <p>暂无对应答案</p>
                                            @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 book_info_box" data-id="{{ $book->id }}">
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">书名</label>
                                                <input type="text" class="form-control book_name"
                                                       value="{{ $book->bookname }}">
                                            </div>
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" class="form-control version_year"
                                                       value="{{ $book->version_year }}">
                                            </div>

                                            <div class="input-group pull-left" style="width:40%">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade"
                                                        class="grade_id form-control select2 pull-left" tabindex="-1"
                                                        aria-hidden="true">
                                                    <option selected="selected"
                                                            value="{{ $book->grade_id }}">{{ config('workbook.grade')[intval($book->grade_id)] }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group" style="width:60%">
                                                <select data-name="grade_name" style="width: 100%"
                                                        class="grade_name select2">
                                                    <option selected="selected"
                                                            value="-1">{{ $book->grade_name?$book->grade_name:config('workbook.grade')[intval($book->grade_id)] }}</option>
                                                </select>
                                                <label class="input-group-addon btn btn-primary add_option"
                                                       data-type="grade_name">新增</label>
                                            </div>

                                            <div class="input-group pull-left" style="width:40%">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2"
                                                        tabindex="-1" aria-hidden="true">
                                                    <option selected="selected"
                                                            value="{{ $book->subject_id }}">{{ config('workbook.subject_1010')[intval($book->subject_id)] }}</option>
                                                </select>

                                            </div>
                                            <div class="input-group" style="width:60%">
                                                <select data-name="subject_name" style="width: 100%"
                                                        class="subject_name select2">
                                                    <option selected="selected"
                                                            value="-1">{{ $book->subject_name?$book->subject_name:config('workbook.subject_1010')[intval($book->subject_id)] }}</option>
                                                </select>
                                                <label class="input-group-addon btn btn-primary add_option"
                                                       data-type="subject_name">新增</label>
                                            </div>

                                            <div class="input-group pull-left" style="width:40%">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    <option selected="selected"
                                                            value="{{ $book->volumes_id }}">{{ $data['all_volumes']->where('id',$book->volumes_id)->count()>0?$data['all_volumes']->where('id',$book->volumes_id)->first()->volumes:0 }}</option>
                                                </select>

                                            </div>
                                            <div class="input-group" style="width:60%">
                                                <select data-name="volumes_name" style="width: 100%"
                                                        class="volumes_name select2">
                                                    <option selected="selected"
                                                            value="-1">{{ $book->volume_name?$book->volume_name:($data['all_volumes']->where('id',$book->volumes_id)->count()>0?$data['all_volumes']->where('id',$book->volumes_id)->first()->volumes:0) }}</option>
                                                </select>

                                                <label class="input-group-addon btn btn-primary add_option"
                                                       data-type="volumes_name">新增</label>
                                            </div>

                                            <div style="width: 100%">
                                                <div class="input-group pull-left" style="width: 40%">
                                                    <label class="input-group-addon">版本</label>
                                                    <select data-name="version" class="version_id form-control select2"
                                                            tabindex="-1" aria-hidden="true">
                                                        <option selected="selected"
                                                                value="{{ $book->version_id }}">{{ $data['all_version']->where('id',$book->version_id)->first()->name }}</option>
                                                    </select>
                                                </div>
                                                <div class="input-group" style="width: 60%">
                                                    <select data-name="version_name" style="width: 100%"
                                                            class="version_name select2">
                                                        <option selected="selected"
                                                                value="-1">{{ $book->version_name?$book->version_name:$data['all_version']->where('id',$book->version_id)->first()->name }}</option>
                                                    </select>
                                                    <label class="input-group-addon btn btn-primary add_option"
                                                           data-type="version_name">新增</label>
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <label class="input-group-addon">系列</label>
                                                <select data-name="sort" class="form-control sort_name">
                                                    <option value="{{ $book->sort }}">{{ $book->has_sort?$book->has_sort->name.'_'.$book->sort:'待定' }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group">
                                                <label class="input-group-addon">子系列</label>
                                                <select style="width: 50%" data-name="sub_sort"
                                                        class="form-control subsort_name select2">
                                                    @if($book->has_sort)
                                                        <option value="0">未选择</option>
                                                        @forelse($book->has_sort->sub_sorts as $sub_sort)
                                                            <option @if($book->ssort_id===$sub_sort->id) selected
                                                                    @endif value="{{ $sub_sort->id }}">{{ $sub_sort->name.'_'.$sub_sort->id }}</option>
                                                            @endforeach
                                                            @endif
                                                </select>
                                                <div style="width: 50%;float: right">
                                                <label class="btn btn-primary add_option"
                                                       data-type="sub_sort">新增子系列</label>
                                                <a class="btn btn-success" target="_blank" href="{{ route('book_new_subsort_arrange',[$book->sort,$book->ssort_id?$book->ssort_id:$book->sort]) }}">编辑子系列</a>
                                                </div>
                                            </div>

                                            <div class="input-group hide" style="width: 100%">
                                                <select class="form-control">
                                                    <option>出版社/多选</option>
                                                </select>
                                            </div>

                                            <div class="btn btn-group">
                                                <a data-id="{{ $book->id }}" class="save_book btn btn-danger">保存</a>
                                                <a target="_blank" class="btn btn-info" href="{{ route('book_new_only_detail',[$book->sort,$book->ssort_id,$book->grade_id,$book->subject_id,$book->volumes_id,$book->version_id,$book->version_year]) }}">唯一化查看</a>
                                                <a class="btn btn-danger del_this">删除</a>
                                            </div>
                                            <div class="btn btn-group">
                                                <a class="btn btn-primary confirm_chapter">确认章节无误</a>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                                {{ $data['all_book']->links() }}
                            @endif
                        </div>
                        <div class="tab-pane @if($data['type']==='finished') active @endif" id="finished">
                            @if($data['type']==='finished')
                                @foreach($data['all_book'] as $book)
                                    <div class="box-body well single_book_info" data-id="{{ $book->id }}">
                                        <div class="col-md-5">
                                            <div>
                                                {{--<a class="btn btn-default">删除</a>--}}
                                                {{--<a class="btn btn-default">更改封面</a>--}}
                                                {{--<a class="btn btn-default">搜索</a>--}}
                                                {{--<a class="btn btn-default">完成</a>--}}
                                                <strong data-side="left"
                                                        class="page_rotate_single label label-info">向左转</strong>
                                                <strong data-side="right" class="page_rotate_single label label-info">向右转</strong>
                                                <strong class="save_pic label label-danger">保存</strong>
                                            </div>

                                            <div class="col-md-6">
                                                <a class="thumbnail" data-target="#show_big_pic" data-hd-cover="{{ isset($book->has_hd_book->cover_photo)?$book->has_hd_book->cover_photo:'none' }}" data-toggle="modal">
                                                    <img data-src="{{ $book->cover }}"
                                                         src="{{ $book->cover.'?t='.time() }}" alt="">
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>练习册id:{{ $book->id }}</strong></p>
                                                <a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html">{{ $book->bookname }}</a>
                                                <p><strong>编辑人id:{{ $book->has_editor->id }}</strong></p>
                                                <p><strong>编辑人名称:{{ $book->has_editor->name }}</strong></p>
                                                <p><strong>编辑时间:{{ $book->updated_at }}</strong></p>
                                            </div>
                                            @if(count($book->has_answers)>0)
                                                <div id="myCarousel_{{ $book->id }}" class="clear carousel slide" data-interval="false">
                                                    <div class="carousel-inner" >
                                                        @foreach($book->has_answers as $key => $answer)
                                                            @php  $answers = explode('|',$answer->answer); @endphp
                                                            @if(is_array($answers))
                                                                @foreach($answers as $answer_img)
                                                                    <div class="item @if ($loop->first && $key==0) active  @endif">
                                                                        <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                            <img class="answer-img img-responsive" data-original="{{ url(config('workbook.workbook_url').$answer_img) }}"
                                                                                 alt="First slide">
                                                                        </a>
                                                                        <div class="carousel-caption text-orange">{{ $answer->textname }}</div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="item @if ($loop->first && $key==0) active @endif">
                                                                    <a style="overflow-x: scroll" class="thumbnail show_cover_photo" data-hd-cover="none" data-toggle="modal" data-target="#cover_photo">
                                                                        <img class="answer-img img-responsive" data-original="{{ url('http://121.199.15.82/standard_answer/'.$answer->answer) }}" alt="First slide">
                                                                    </a>
                                                                    <div class="carousel-caption text-orange FontBig">{{ $answer->textname }}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <a class="carousel-control  left" href="#myCarousel_{{ $book->id }}"
                                                       data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
                                                    <a class="carousel-control right" href="#myCarousel_{{ $book->id }}"
                                                       data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
                                                </div>
                                            @else
                                                <p>暂无对应答案</p>
                                            @endif
                                        </div>
                                        <div class="col-md-7 book_info_box" data-id="{{ $book->id }}">
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">书名</label>
                                                <input type="text" class="form-control book_name"
                                                       value="{{ $book->bookname }}">
                                            </div>
                                            <div class="input-group" style="width: 100%">
                                                <label class="input-group-addon">年份</label>
                                                <input type="text" class="form-control version_year"
                                                       value="{{ $book->version_year }}">
                                            </div>
                                            <div class="input-group pull-left" style="width:40%">
                                                <label class="input-group-addon">年级</label>
                                                <select data-name="grade"
                                                        class="grade_id form-control select2 pull-left" tabindex="-1"
                                                        aria-hidden="true">
                                                    <option selected="selected"
                                                            value="{{ $book->grade_id }}">{{ config('workbook.grade')[intval($book->grade_id)] }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group" style="width:60%">
                                                <select data-name="grade_name" class="grade_name select2" style="width: 100%">
                                                    <option selected="selected"
                                                            value="-1">{{ $book->grade_name }}</option>
                                                </select>
                                                <label class="input-group-addon btn btn-primary add_option"
                                                       data-type="grade_name">新增</label>
                                            </div>

                                            <div class="input-group pull-left" style="width:40%">
                                                <label class="input-group-addon">科目</label>
                                                <select data-name="subject" class="subject_id form-control select2"
                                                        tabindex="-1" aria-hidden="true">
                                                    <option selected="selected"
                                                            value="{{ $book->subject_id }}">{{ config('workbook.subject_1010')[intval($book->subject_id)] }}</option>
                                                </select>

                                            </div>
                                            <div class="input-group" style="width:60%">
                                                <select data-name="subject_name" class="subject_name select2" style="width: 100%;">
                                                    <option selected="selected"
                                                            value="-1">{{ $book->subject_name }}</option>
                                                </select>
                                                <label class="input-group-addon btn btn-primary add_option "
                                                       data-type="subject_name">新增</label>
                                            </div>
                                            <div class="input-group pull-left" style="width:40%">
                                                <label class="input-group-addon">卷册</label>
                                                <select data-name="volumes" class="volumes_id form-control select2">
                                                    <option selected="selected"
                                                            value="{{ $book->volumes_id }}">{{ $data['all_volumes']->where('id',$book->volumes_id)->first()->volumes }}</option>
                                                </select>

                                            </div>
                                            <div class="input-group" style="width:60%">
                                                <select data-name="volumes_name" class="volumes_name select2" style="width:100%">
                                                    <option selected="selected"
                                                            value="-1">{{ $book->volume_name }}</option>
                                                </select>
                                                <label class="input-group-addon btn btn-primary add_option "
                                                       data-type="volumes_name">新增</label>
                                            </div>
                                            <div style="width: 100%">
                                                <div class="input-group pull-left" style="width: 40%">
                                                    <label class="input-group-addon">版本</label>
                                                    <select data-name="version" class="version_id form-control select2"
                                                            tabindex="-1" aria-hidden="true">
                                                        <option selected="selected"
                                                                value="{{ $book->version_id }}">{{ $data['all_version']->where('id',$book->version_id)->first()->name }}</option>
                                                    </select>

                                                </div>
                                                <div class="input-group" style="width: 60%">
                                                    <select data-name="version_name" class="version_name select2" style="width: 100%;">
                                                        <option selected="selected"
                                                                value="-1">{{ $book->version_name }}</option>
                                                    </select>
                                                    <label class="input-group-addon add_option btn btn-primary"
                                                           data-type="version_name">新增</label>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label class="input-group-addon">系列</label>
                                                <select data-name="sort" class="form-control sort_name">
                                                    <option value="{{ $book->sort }}">{{ $book->has_sort?$book->has_sort->name.'_'.$book->sort:'待定' }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group">
                                                <label class="input-group-addon">子系列</label>
                                                <select style="width:50%;" data-name="sub_sort"
                                                        class="form-control subsort_name select2">
                                                    @if($book->has_sort)
                                                        <option value="0">未选择</option>
                                                        @forelse($book->has_sort->sub_sorts as $sub_sort)
                                                            <option @if($book->ssort_id===$sub_sort->id) selected
                                                                    @endif value="{{ $sub_sort->id }}">{{ $sub_sort->name.'_'.$sub_sort->id }}</option>
                                                            @endforeach
                                                            @endif
                                                </select>
                                                <div style="width: 50%;float: right">
                                                <label class="btn btn-primary add_option"
                                                       data-type="sub_sort">新增子系列</label>
                                                <a class="btn btn-success" target="_blank" href="{{ route('book_new_subsort_arrange',[$book->sort,$book->ssort_id?$book->ssort_id:$book->sort]) }}">编辑子系列</a>
                                                </div>
                                            </div>
                                            <div class="input-group hide" style="width: 100%">
                                                <select class="form-control">
                                                    <option>出版社/多选</option>
                                                </select>
                                            </div>
                                            <div class="btn btn-group">
                                                <a data-id="{{ $book->id }}" class="save_book btn btn-danger">保存</a>
                                                <a target="_blank" class="btn btn-info" href="{{ route('book_new_only_detail',[$book->sort,$book->ssort_id,$book->grade_id,$book->subject_id,$book->volumes_id,$book->version_id,$book->version_year]) }}">唯一化查看</a>
                                                <a class="btn btn-danger del_this">删除</a>
                                            </div>
                                            <div class="btn btn-group">
                                                <a class="btn btn-primary confirm_chapter">确认章节无误</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $data['all_book']->links() }}
                            @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2();
            //Initialize Select2 Elements
            $('select[data-name="grade"]').select2({data: $.parseJSON('{!! $data['grade_select'] !!} '),});
            $('select[data-name="subject"]').select2({data: $.parseJSON('{!! $data['subject_select'] !!} '),});
            $('select[data-name="volumes"]').select2({data: $.parseJSON('{!! $data['volume_select'] !!} '),});
            $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});
            $('select[data-name="grade_name"]').select2({data: $.parseJSON('{!! $data['grade_name_select'] !!} '),});
            $('select[data-name="subject_name"]').select2({data: $.parseJSON('{!! $data['subject_name_select'] !!} '),});
            $('select[data-name="volumes_name"]').select2({data: $.parseJSON('{!! $data['volume_name_select'] !!} '),});
            $('select[data-name="version_name"]').select2({data: $.parseJSON('{!! $data['version_name_select'] !!} '),});
            //获取系列
            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('book_new_workbook_api','sort') }}",
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
                    return '<option value="' + repo.id + '">' + repo.name+'_'+repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });
            //系列更改更新子系列
            $('.sort_name').change(function () {
                let sort = $(this).val();
                let book_id = $(this).parents('.single_book_info').attr('data-id');
                let sub_sort_sel = $(`.single_book_info[data-id=${book_id}] select[data-name="sub_sort"]`);
                axios.post('{{ route('book_new_workbook_api','refresh_sub_sort') }}', {sort}).then(response => {
                    if (response.data.status === 1) {
                        sub_sort_sel.html('').select2({data: response.data.data,});
                    } else {
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            });

            //保存
            $('.save_book').click(function () {
                $('#error_box[data-ignore="false"]').remove();
                let book_id = $(this).attr('data-id');
                let now_box = $(`.book_info_box[data-id=${book_id}]`);
                let book_name = now_box.find('.book_name').val();
                let grade_id = now_box.find('.grade_id').val();
                let subject_id = now_box.find('.subject_id').val();
                let volumes_id = now_box.find('.volumes_id').val();
                let version_id = now_box.find('.version_id').val();
                let version_year = now_box.find('.version_year').val();
                let grade_name = now_box.find('.grade_name option:selected').text();
                let subject_name = now_box.find('.subject_name option:selected').text();
                let volumes_name = now_box.find('.volumes_name option:selected').text();
                let version_name = now_box.find('.version_name option:selected').text();
                let sort_id = now_box.find('.sort_name').val();
                let subsort_id = now_box.find('.subsort_name').val();
                let subsort_name = now_box.find('.subsort_name option:selected').text();
                let o = {
                    book_id, book_name, grade_id, subject_id, volumes_id, version_id,
                    grade_name, subject_name, volumes_name, version_name,
                    sort_id, subsort_id, version_year
                };
                let unmatch = '';
                if($.trim(subsort_name)==''){
                    alert('子系列为空');return false;
                }
                if($.trim(grade_name)==''){
                    alert('年级为空');return false;
                }
                if($.trim(subject_name)==''){
                    alert('科目为空');return false;
                }
                if($.trim(volumes_name)==''){
                    alert('卷册为空');return false;
                }
                if($.trim(version_name)==''){
                    alert('版本为空');return false;
                }

                if(book_name.search(subsort_name)===-1){
                    unmatch += '<label class="label label-danger">子系列名称</label> ';
                }
                if(book_name.search(grade_name)===-1){
                    unmatch += '<label class="label label-danger">年级</label> ';
                }
                if(book_name.search(subject_name)===-1){
                    unmatch += '<label class="label label-danger">科目</label> ';
                }
                if(book_name.search(volumes_name)===-1){
                    unmatch += '<label class="label label-danger">卷册</label> ';
                }
                if(book_name.search(version_name)===-1){
                    unmatch += '<label class="label label-danger">版本</label> ';
                }
                if(unmatch){
                    unmatch = '当前未匹配字段: '+unmatch;
                    if($('#error_box[data-ignore="true"]').length===0){
                        $(`.book_info_box[data-id=${book_id}]`).append(`
                            <div id="error_box" data-ignore="false">
                                <div><a>当前书本名称: ${book_name}</a></div>
                                <div>当前整理名称: <label class="label label-info">${subsort_name}</label>
                                <label class="label label-info">${grade_name}</label>
                                <label class="label label-info">${subject_name}</label>
                                <label class="label label-info">${volumes_name}</label>
                                <label class="label label-info">${version_name}</label></div>
                                <div>${unmatch}</div>
                                <a id="confirm_again" data-id="${book_id}" class="btn btn-primary">确认无误,继续提交</a>
                            </div>

                            `);
                        return false;
                    }
                }
                axios.post('{{ route('book_new_workbook_api','update_sub_sort') }}', o).then(response => {
                    if (response.data.status === 1) {
                        $(`.single_book_info[data-id=${book_id}]`).remove();
                    } else {
                        alert(response.data.msg);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
                //var option = new Option(sort_name, sort_id);
                //option.selected = true;
                //$('.edit_box[data-id="' + now_book_id + '"]').find('select[data-name="sort"]').append(option).trigger("change");
            });
            //新增
            $('.add_option').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                let type = $(this).attr('data-type');
//                let add_name = '1';
//                let add_id = 0;
                $('#new_box').remove();
                $(this).parent().after(`<div class="input-group" id="new_box">
                                    <input type="text" class="form-control" value="" />
                                    <label class="btn btn-danger input-group-addon" id="add_input_box" data-id="${book_id}" data-type="${type}">确认</label>
                                    <label class="btn btn-default input-group-addon" id="del_input_box">取消</label>
                                </div>`);
//                if(add_name===''){
//                    return false;
//                }
//                let option = new Option(add_name, add_id);
//                option.selected = true;
//                $(`.book_info_box[data-id="${book_id}"]`).find(`select[data-name="${type}"]`).append(option).trigger("change");
            });

            //忽略错误,继续提交
            $(document).on('click','#confirm_again',function () {
                let book_id = $(this).attr('data-id');
                $(this).parent().attr('data-ignore',"true");
                $(`a.save_book[data-id=${book_id}]`).click();
            });


            //跳转id
            $('#redict_to').click(function () {
                let book_id = $(this).next().val();
                if (book_id > 0) {
                    window.location.href = '{{ route('book_new_index','finished') }}?edit_id=' + book_id;
                }
            });

            //图片放大
            $('.thumbnail').click(function () {
                let hd_cover = $(this).attr('data-hd-cover');
                if(hd_cover==='none'){
                    $('#show_big_pic .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
                }else{
                    let img = `<img src="http://image.hdzuoye.com/book_photo_path/${hd_cover}"/>`;
                    $('#show_big_pic .modal-body').html(`<a class="thumbnail">${img}</a>`);
                }

            });
            //图片旋转保存
            let now_rotate = 0;
            $('.page_rotate_single').click(function () {
                let side = $(this).attr('data-side');
                let now_book_box = $(this).parents('.single_book_info');
                let now_img = now_book_box.find('.thumbnail img');
                let book_id = now_book_box.attr('data-id');
                if (side === 'left') {
                    now_rotate -= 1;
                } else {
                    now_rotate += 1;
                }
                if (now_rotate < 0) {
                    now_rotate = 4 - parseInt(Math.abs(now_rotate) % 4)
                } else {
                    now_rotate = now_rotate % 4
                }
                now_img.attr('src', now_img.attr('data-src') + '?x-oss-process=image/rotate,' + now_rotate * 90 + '&time=' + Date.parse(new Date()));
            });

            $('.save_pic').click(function () {
                let now_book_box = $(this).parents('.single_book_info');
                let now_img_sel = now_book_box.find('.thumbnail img');
                let old_img = now_img_sel.attr('data-src').replace('http://thumb.1010pic.com/', '');
                let now_img = now_img_sel.attr('src');
                axios.post('{{ route('save_pic_to_oss') }}', {old_img, now_img}).then(function (s) {
                    alert('保存成功');
                }).catch(function (error) {
                    console.log(error);
                });
            });

            //确认新增
            $(document).on('click', '#add_input_box', function () {
                let book_id = $(this).attr('data-id');
                let data_type = $(this).attr('data-type');
                let sort = $(`.book_info_box[data-id="${book_id}"] select[data-name="sort"]`).val();
                let add_name = $(this).prev().val();
                let add_id = 0;
                if (confirm('确认新增')) {
                    let o;
                    if (data_type === 'sub_sort') {
                        o = {
                            data_type,
                            sort,
                            sub_sort_name: add_name,
                        };
                    } else {
                        o = {
                            book_id,
                            data_type,
                            add_name,
                        };
                    }
                    axios.post('{{ route('book_new_workbook_api','add_name') }}', o).then(response => {
                        if (response.data.status === 1) {
                            add_id = response.data.data.new_id;
                            if ($.trim(add_name) === '') {
                                return false;
                            }
                            let option = new Option(add_name, add_id);
                            option.selected = true;
                            @if($data['sort']!=-999) $(`select[data-name="${data_type}"]`).append(option);  @endif
                            $(`.book_info_box[data-id="${book_id}"]`).find(`select[data-name="${data_type}"]`).append(option).trigger("change");
                            if(response.data.data.new_sort){
                                let option1 = new Option(add_name, response.data.data.new_sort);
                                option1.selected = true;
                                $(`.book_info_box[data-id="${book_id}"]`).find("select[data-name='sort']").append(option1).trigger("change");
                            }
                            $('#new_box').remove();
                        } else {
                            alert('新增失败,请重试');
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            });

            //取消
            $(document).on('click','#del_input_box',function () {
               $('#new_box') .remove();
            });

            //查看指定系列
            $('.click_to_btn').click(function () {
               window.open('{{ route('book_new_subsort_arrange') }}'+'/'+$('.click_to').val());
            });

            //查看指定年级科目
            $('#to_volume_version').click(function () {
                window.location.href = '{{ route('book_new_chapter') }}'+'/'+$('#new_grade_select').val()+'/'+$('#new_subject_select').val();
            });

            //删除
            $('.del_this').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                axios.post('{{ route('book_new_workbook_api','del_this') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        $(`.single_book_info[data-id=${book_id}]`).remove();
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            });

            //图片加载
            var cHeight = 0;

            $('.carousel').on('slide.bs.carousel', function(e) {
                var $nextImage = $(e.relatedTarget).find('img');
                $activeItem = $('.active.item', this);
                // prevents the slide decrease in height
                if (cHeight == 0) {
                    cHeight = $(this).height();
                    $activeItem.next('.item').height(cHeight);
                }
                // prevents the loaded image if it is already loaded
                var src = $nextImage.attr('data-original');
                if (typeof src !== "undefined" && src != "") {
                    $nextImage.attr('src', src);
                    $nextImage.attr('data-original', '');
                }
            });
            var lazy = new LazyLoad();

            //确认章节无误
            $('.confirm_chapter').click(function () {
                let book_id = $(this).parents('.book_info_box').attr('data-id');
                axios.post('{{ route('book_new_workbook_api','confirm_chapter') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        $(`.single_book_info[data-id=${book_id}]`).remove();
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            });
        });
    </script>
@endpush