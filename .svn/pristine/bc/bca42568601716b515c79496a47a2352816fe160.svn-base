@extends('layouts.backend')

@section('new_book_buy','active')

@push('need_css')
<style>
    .book_box{
        margin: 5px;
        border: 1px dashed grey;
    }
    .new_border{
        border: 4px solid red;
    }
    .for_isbn_input{
        padding:1px;
    }
    .update_this_book{
        display: none;
    }
</style>
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册购买管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-body">
                <div class="btn-group">
                    <a class="btn btn-default now_user">
                        @if(Auth::id()===2)
                            {{ \App\User::find($data['jj_sort']->update_uid)->name }}
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu" data-sort="{{ $data['jj_sort']->sort }}">
                        <li><a class="change_owner" data-uid="5">黄少敏</a></li>
                        <li><a class="change_owner" data-uid="8">苏蕾</a></li>
                        <li><a class="change_owner" data-uid="11">张连荣</a></li>
                        <li><a class="change_owner" data-uid="14">陈卓</a></li>
                        <li><a class="change_owner" data-uid="17">肖高萍</a></li>
                        <li><a class="change_owner" data-uid="18">宋晗</a></li>
                        <li><a class="change_owner" data-uid="19">印娜</a></li>
                        <li><a class="change_owner" data-uid="20">张玲莉</a></li>
                    </ul>
                </div>
                <h3>答案存放目录:<strong>\\QINGXIA23\book4_new\{{ $data['jj_sort']->sort }}_{{ $data['jj_sort']->sort_name }}\练习册名称_练习册id</strong></h3>
                <h3>封面存放目录:<strong>\QINGXIA23\book4_new\{{ $data['jj_sort']->sort }}_{{ $data['jj_sort']->sort_name }}\练习册名称_练习册id\cover</strong></h3>
                <h3>注意事项：<strong><b class="bg-red">新增购买一定要注意书名版本名称规范
                    年代 +系列名+年级+科目+上下册+版本
                            例：2018年 53天天练 三年级 英语  下册  人教PEP版 </b></strong></h3>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#jj_books" data-toggle="tab">{{ $data['jj_sort']->sort_name }}<em class="badge bg-red">{{ $data['jj_sort']->collect_count }}</em>
                            {{--<em class="badge bg-black hide">{{ $data['sort_num'] }}本需重新编辑或添加</em></a></li>--}}
                        <span>
                            <a class="badge bg-blue">已购买<i>{{ $data['all_book_bought'] }}</i></a>
                            <a class="badge bg-yellow">已录入<i>{{ $data['all_book_answers'] }}</i></a>
                            <a class="badge bg-red">已上传<i>{{ $data['all_book_uploaded'] }}</i></a>
                        </span>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="jj_books">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    @forelse($data['nav_version_now'] as $key=>$version_value)
                                        <li class="@if($version_value->version_id==$data['now_version_select']) active @endif">
                                            <a href="{{ route('new_book_buy_detail',[$data['sort_now'],$version_value->version_id]) }}">
                                                {{ cache('all_version_now')->where('id',$version_value->version_id)->first()->name }}
                                                <i class="badge bg-blue">{{ $data['version_book_bought'][$data['sort_now']][$version_value->version_id] }}</i>
                                                <i class="badge bg-yellow">{{ $data['version_book_answers'][$data['sort_now']][$version_value->version_id] }}</i>
                                                <i class="badge bg-red">{{ $data['version_book_uploaded'][$data['sort_now']][$version_value->version_id] }}</i>
                                            </a>

                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    @forelse($data['jj_sort_detail'] as $key=>$version_value)
                                    <div class="tab-pane @if($loop->first) active @endif" id="version_{{ $key }}">
                                        <div style="overflow: scroll">

                                            <table class="table table-bordered table-responsive" data-sort="{{ $data['sort_now'] }}" data-jj="1">
                                                <tr>
                                                    <th>年级</th>
                                                    @forelse([1,2,3,8,9,10] as $subject)
                                                        <th>{{ config('workbook.subject_1010')[$subject] }}</th>
                                                    @endforeach
                                                </tr>
                                                @forelse([3,4,5,6,7] as $grade)
                                                    <tr>
                                                        <td>{{ config('workbook.grade')[$grade] }}</td>
                                                        @forelse([1,2,3,8,9,10] as $subject)
                                                            @if(isset($version_value[$subject]) && isset($version_value[$subject][$grade]))
                                                                <td class="col-md-4">
                                                                    @php $has_book =\App\AWorkbook1010::where(['sort'=>$data['jj_sort']->sort,'version_id'=>$key,'subject_id'=>$subject,'grade_id'=>$grade,'volumes_id'=>2,'version_year'=>2018])->select(['id','bookname'])->orderBy('version_year','desc')->get()  @endphp

                                                                    <div class="input-group" style="width:100%">
                                                                        @if(count($has_book)>=1)
                                                                            <select class="form-control">
                                                                                @forelse($has_book as $now_book)
                                                                                    <option value="{{ $now_book->id }}">{{ $now_book->bookname }}</option>
                                                                                    @endforeach
                                                                            </select>
                                                                            <a class="btn input-group-addon btn-primary to_book_detail">查看</a>

                                                                        @else
                                                                            <a class="btn btn-block btn-danger">暂无</a>
                                                                        @endif
                                                                    </div>
                                                                    @forelse($version_value[$subject][$grade] as $version_book)

                                                                        @forelse($version_book as $book)
                                                                            @if($loop->parent->first && $loop->first)
                                                                                <div style="margin-top: 5px" class="add_book_box">
                                                                                    <input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="" placeholder="isbn" />
                                                                                    <div class="input-group" style="width: 100%">
                                                                                        <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                                                                                            <option value="-2">全部版本</option>
                                                                                            @if($key>=0)
                                                                                                <option value="{{ $key }}" selected>{{ cache('all_version_now')->where('id',$key)->first()->name }}</option>
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>
                                                                                    <textarea rows="4" class="form-control" placeholder="练习册名称" >2018年{{ $data['jj_sort']->sort_name.config('workbook.grade')[$grade].config('workbook.subject_1010')[$subject].'下册'.cache('all_version_now')->where('id',$key)->first()->name }}</textarea>
                                                                                    <div class="btn-group pull-right">
                                                                                        <a data-id="999999999|{{ $book->id }}" class="btn btn-success btn-xs buy_status" data-grade="{{ $grade }}" data-subject="{{ $subject }}">新增购买</a>
                                                                                    </div>
                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="book_box @if($book->done===2) new_border @endif" data-id="{{ $book->id }}">
                                                                                <p>
                                                                                    @if($book->has_update==1)
                                                                                        <a class="update_this_book label label-success hide">已升级</a>
                                                                                    @else
                                                                                        @if($book->done==1)
                                                                                            @if(\App\AWorkbook1010Zjb::where('id',$book->id)->count()>0)
                                                                                                <a class="label label-warning">@if($book->version_year>=2018) 已上传@endif</a>

                                                                                            @else
                                                                                                <a class="label label-success">@if($book->version_year>=2018) 已录入@endif</a>
                                                                                            @endif

                                                                                       @else
                                                                                            <a class="label label-info">@if($book->version_year>=2018) 已购买@endif</a>
                                                                                        @endif
                                                                                    @endif
                                                                                    <a class="btn btn-info btn-xs">{{ $book->version_year }}<i class="badge bg-black">{{ $book->has_main_book?$book->has_main_book->collect_count:0 }}</i><i class="badge bg-red">{{ $book->has_main_book?$book->has_main_book->concern_num:0 }}</i></a>
                                                                                </p>
                                                                                <p>
                                                                                    @if($book->isbn && $book->version_year<2018)
                                                                                        <a target="_blank" class=" isbn_search_btn" href="https://s.taobao.com/search?q={{ $book->isbn }}">{{ $book->isbn }}</a>
                                                                                    @endif
                                                                                </p>

                                                                                <div class="has_buy_btn book_info_box">
                                                                                    <div>
                                                                                    @if($book->isbn && $book->version_year==2018)
                                                                                            <div data-id="{{ $book->id }}" style="width: 100%">
                                                                                                <input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="{{ $book->isbn?convert_isbn($book->isbn):'978-7-' }}" />
                                                                                                <a class="btn btn-danger btn-block add_isbn btn-xs hide ">保存isbn</a>
                                                                                            </div>
                                                                                    @endif
                                                                                    </div>
                                                                                    <div class="input-group" style="width: 100%">
                                                                                        <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                                                                                            <option value="-2">全部版本</option>
                                                                                            @if($key>=0)
                                                                                                <option value="{{ $key }}" selected>{{ cache('all_version_now')->where('id',$key)->first()->name }}</option>
                                                                                            @endif
                                                                                        </select>
                                                                                        <a class="btn btn-primary input-group-addon change_version hide">更改</a>
                                                                                    </div>
                                                                                    <div class="book_name_box">
                                                                                    @if($book->id<1000000)
                                                                                    <p>{{  $book->sort_name }}</p>
                                                                                    @else
                                                                                    <textarea rows="4" style="width: 100%" class="form-control">{{  $book->sort_name }}</textarea>
                                                                                    @endif
                                                                                    <p class="btn-group" style="width:100%">
                                                                                        @if($book->id<1000000)
                                                                                            <a style="width:100%" class="btn btn-primary btn-xs" href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank">查看练习册</a>
                                                                                        @else
                                                                                            <a style="width:50%" class="btn btn-primary btn-xs get_related_book" data-grade="{{ $grade }}" data-subject="{{ $subject }}" data-volume="2" data-version="{{ $key }}" data-sort="{{ $data['jj_sort']->sort }}">查看往年</a>
                                                                                            <a style="width:50%" class="btn btn-danger save_new_bookname btn-xs">保存</a>
                                                                                        @endif
                                                                                    </p>
                                                                                    </div>
                                                                                </div>
                                                                                @if($book->version_year=='2018' && $book->id>1000000)
                                                                                    <p class="btn-group" style="width: 100%;">
                                                                                        @if(\App\AWorkbook1010Zjb::where('id',$book->id)->count()>0)
                                                                                    <a style="width:50%"  data-id="{{ $book->id }}" class="btn btn-primary btn-xs check_upload_answer">查看答案</a>
                                                                                            @else
                                                                                            <a style="width:50%" data-id="{{ $book->id }}" class="btn btn-warning btn-xs check_upload_answer">答案未完善</a>
                                                                                            @endif<a style="width:50%" data-id="{{ $book->id }}" class="btn btn-danger btn-xs delete_this_book">删除</a>
                                                                                    @if($book->arrived_at)
                                                                                            <a class="badge bg-red confirm_receive hide">已收货</a>
                                                                                    @else
                                                                                            <a class="badge bg-blue confirm_receive hide">确认收货</a>
                                                                                    @endif
                                                                                    </p>
                                                                                @endif

                                                                            </div>
                                                                            @endforeach

                                                                            @endforeach
                                                                </td>
                                                            @else
                                                                @if($grade==8 && $subject==5)
                                                                    <td class="col-md-4"></td>
                                                                @elseif($grade<=6 && $subject>=4)
                                                                    <td class="col-md-4"></td>
                                                                @else
                                                                    <td class="col-md-4">
                                                                    @php $has_book =\App\AWorkbook1010::where(['sort'=>$data['jj_sort']->sort,'version_id'=>$key,'subject_id'=>$subject,'grade_id'=>$grade,'volumes_id'=>2,'version_year'=>2018])->select(['id','bookname'])->orderBy('version_year','desc')->get()  @endphp
                                                                    <div class="input-group" style="width:100%">
                                                                        @if(count($has_book)>1)
                                                                            <select class="form-control">
                                                                                @forelse($has_book as $now_book)
                                                                                    <option value="{{ $now_book->id }}">{{ $now_book->bookname }}</option>
                                                                                    @endforeach
                                                                            </select>
                                                                            <a class="btn input-group-addon btn-primary to_book_detail">查看</a>

                                                                        @else
                                                                            <a class="btn btn-block btn-danger">暂无</a>
                                                                        @endif
                                                                    </div>
                                                                    <div class="add_book_box">
                                                                        <input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="" placeholder="isbn" />
                                                                        <div class="input-group" style="width: 100%">
                                                                            <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                                                                                <option value="-2">全部版本</option>
                                                                                @if($key>=0)
                                                                                    <option value="{{ $key }}" selected>{{ cache('all_version_now')->where('id',$key)->first()->name }}</option>
                                                                                @endif
                                                                            </select>
                                                                            <a class="btn btn-primary input-group-addon change_version hide">更改</a>
                                                                        </div>
                                                                        <textarea rows="4" class="form-control" placeholder="练习册名称" >2018年{{ $data['jj_sort']->sort_name.config('workbook.grade')[$grade].config('workbook.subject_1010')[$subject].'下册'.cache('all_version_now')->where('id',$key)->first()->name }}</textarea>
                                                                        <div class="btn-group pull-right">
                                                                            <a data-id="999999999|{{ $key.'_'.$grade.'_'.$subject }}" class="btn btn-success btn-xs buy_status" data-grade="{{ $grade }}" data-subject="{{ $subject }}">新增购买</a>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                            <table class="table table-bordered table-responsive" data-sort="{{ $data['sort_now'] }}" data-jj="1">
                                                <tr>
                                                    <th>年级</th>
                                                    @forelse([1,2,3,4,5,7,8,9,10] as $subject)
                                                        <th>{{ config('workbook.subject_1010')[$subject] }}</th>
                                                    @endforeach
                                                </tr>
                                                @forelse([8,9] as $grade)
                                                    <tr>
                                                        <td>{{ config('workbook.grade')[$grade] }}</td>
                                                        @forelse([1,2,3,4,5,7,8,9,10] as $subject)
                                                            @php if($subject==10 and $grade>=9){ continue; }@endphp
                                                            @if(isset($version_value[$subject]) && isset($version_value[$subject][$grade]))
                                                                <td class="col-md-2">
                                                                    @php $has_book =\App\AWorkbook1010::where(['sort'=>$data['jj_sort']->sort,'version_id'=>$key,'subject_id'=>$subject,'grade_id'=>$grade,'volumes_id'=>2,'version_year'=>2018])->select(['id','bookname'])->orderBy('version_year','desc')->get()  @endphp

                                                                    <div class="input-group" style="width: 100%">
                                                                        @if(count($has_book)>1)
                                                                            <select class="form-control">
                                                                                @forelse($has_book as $now_book)
                                                                                    <option value="{{ $now_book->id }}">{{ $now_book->bookname }}</option>
                                                                                    @endforeach
                                                                            </select>
                                                                            <a class="btn input-group-addon btn-primary to_book_detail">查看</a>

                                                                        @else
                                                                            <a class="btn btn-block btn-danger">暂无</a>
                                                                        @endif
                                                                    </div>
                                                                    @forelse($version_value[$subject][$grade] as $version_book)

                                                                        @forelse($version_book as $book)
                                                                            @if($loop->parent->first && $loop->first)

                                                                                <div style="margin-top: 5px" class="add_book_box">
                                                                                    <input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="" placeholder="isbn" />
                                                                                    <div class="input-group" style="width: 100%">
                                                                                        <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                                                                                            <option value="-2">全部版本</option>
                                                                                            @if($key>=0)
                                                                                                <option value="{{ $key }}" selected>{{ cache('all_version_now')->where('id',$key)->first()->name }}</option>
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>
                                                                                    <textarea rows="4" class="form-control" placeholder="练习册名称" >2018年{{ $data['jj_sort']->sort_name.config('workbook.grade')[$grade].config('workbook.subject_1010')[$subject].'下册'.cache('all_version_now')->where('id',$key)->first()->name }}</textarea>
                                                                                    <div class="btn-group pull-right">
                                                                                        <a data-id="999999999|{{ $book->id }}" class="btn btn-success btn-xs buy_status" data-grade="{{ $grade }}" data-subject="{{ $subject }}">新增购买</a>
                                                                                    </div>
                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="book_box @if($book->done===2) new_border @endif" data-id="{{ $book->id }}">
                                                                                <p>
                                                                                    @if($book->has_update==1)
                                                                                        <a class="update_this_book label label-success hide">已升级</a>
                                                                                    @else
                                                                                        @if($book->done==1)
                                                                                            @if(\App\AWorkbook1010Zjb::where('id',$book->id)->count()>0)
                                                                                                <a class="label label-warning">@if($book->version_year>=2018) 已上传@endif</a>

                                                                                            @else
                                                                                                <a class="label label-success">@if($book->version_year>=2018) 已录入@endif</a>
                                                                                            @endif

                                                                                        @else
                                                                                            <a class="label label-info">@if($book->version_year>=2018) 已购买@endif</a>
                                                                                        @endif
                                                                                    @endif
                                                                                    <a class="btn btn-info btn-xs">{{ $book->version_year }}<i class="badge bg-black">{{ $book->has_main_book?$book->has_main_book->collect_count:0 }}</i><i class="badge bg-red">{{ $book->has_main_book?$book->has_main_book->concern_num:0 }}</i></a>
                                                                                </p>
                                                                                <p>
                                                                                    @if($book->isbn && $book->version_year<2018)
                                                                                        <a target="_blank" class=" isbn_search_btn" href="https://s.taobao.com/search?q={{ $book->isbn }}">{{ $book->isbn }}</a>
                                                                                    @endif
                                                                                </p>

                                                                                <div class="has_buy_btn book_info_box">
                                                                                    <div>
                                                                                        @if($book->isbn && $book->version_year==2018)
                                                                                            <div data-id="{{ $book->id }}" style="width: 100%">
                                                                                                <input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="{{ $book->isbn?convert_isbn($book->isbn):'978-7-' }}" />
                                                                                                <a class="btn btn-danger btn-block add_isbn btn-xs hide ">保存isbn</a>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="input-group" style="width: 100%">
                                                                                        <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                                                                                            <option value="-2">全部版本</option>
                                                                                            @if($key>=0)
                                                                                                <option value="{{ $key }}" selected>{{ cache('all_version_now')->where('id',$key)->first()->name }}</option>
                                                                                            @endif
                                                                                        </select>
                                                                                        <a class="btn btn-primary input-group-addon change_version hide">更改</a>
                                                                                    </div>
                                                                                    <div class="book_name_box">
                                                                                        <textarea rows="4" style="width: 100%" class="form-control">{{  $book->sort_name }}</textarea>
                                                                                        <p class="btn-group" style="width:100%">
                                                                                            @if($book->id<1000000)
                                                                                                <a style="width:100%" class="btn btn-primary btn-xs" href="http://www.1010jiajiao.com/daan/bookid_{{ $book->id }}.html" target="_blank">查看练习册</a>
                                                                                            @else
                                                                                                <a style="width:50%" class="btn btn-primary btn-xs get_related_book" data-grade="{{ $grade }}" data-subject="{{ $subject }}" data-volume="2" data-version="{{ $key }}" data-sort="{{ $data['jj_sort']->sort }}">查看往年</a>
                                                                                                <a style="width:50%" class="btn btn-danger save_new_bookname btn-xs">保存</a>
                                                                                            @endif
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                @if($book->version_year=='2018' && $book->id>1000000)
                                                                                    <p class="btn-group" style="width: 100%;">
                                                                                        @if(\App\AWorkbook1010Zjb::where('id',$book->id)->count()>0)
                                                                                            <a style="width:50%"  data-id="{{ $book->id }}" class="btn btn-primary btn-xs check_upload_answer">查看答案</a>
                                                                                        @else
                                                                                            <a style="width:50%" data-id="{{ $book->id }}" class="btn btn-warning btn-xs check_upload_answer">答案未完善</a>
                                                                                        @endif<a style="width:50%" data-id="{{ $book->id }}" class="btn btn-danger btn-xs delete_this_book">删除</a>
                                                                                        @if($book->arrived_at)
                                                                                            <a class="badge bg-red confirm_receive hide">已收货</a>
                                                                                        @else
                                                                                            <a class="badge bg-blue confirm_receive hide">确认收货</a>
                                                                                        @endif
                                                                                    </p>
                                                                                @endif

                                                                            </div>
                                                                            @endforeach

                                                                            @endforeach
                                                                </td>
                                                            @else
                                                                @if($grade==8 && $subject==5)
                                                                    <td class="col-md-2"></td>
                                                                @else
                                                                    <td class="col-md-2">
                                                                        @php $has_book =\App\AWorkbook1010::where(['sort'=>$data['jj_sort']->sort,'version_id'=>$key,'subject_id'=>$subject,'grade_id'=>$grade,'volumes_id'=>2,'version_year'=>2018])->select(['id','bookname'])->orderBy('version_year','desc')->get()  @endphp

                                                                        <div class="input-group" style="width:100%">
                                                                            @if(count($has_book)>1)
                                                                                <select class="form-control">
                                                                                    @forelse($has_book as $now_book)
                                                                                        <option value="{{ $now_book->id }}">{{ $now_book->bookname }}</option>
                                                                                        @endforeach
                                                                                </select>
                                                                                <a class="btn input-group-addon btn-primary to_book_detail">查看</a>

                                                                            @else
                                                                                <a class="btn btn-block btn-danger">暂无</a>
                                                                            @endif
                                                                        </div>
                                                                        <div class="add_book_box">
                                                                            <input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="" placeholder="isbn" />
                                                                            <div class="input-group" style="width: 100%">
                                                                                <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                                                                                    <option value="-2">全部版本</option>
                                                                                    @if($key>=0)
                                                                                        <option value="{{ $key }}" selected>{{ cache('all_version_now')->where('id',$key)->first()->name }}</option>
                                                                                    @endif
                                                                                </select>
                                                                                <a class="btn btn-primary input-group-addon change_version hide">更改</a>
                                                                            </div>
                                                                            <textarea rows="4" class="form-control" placeholder="练习册名称" >2018年{{ $data['jj_sort']->sort_name.config('workbook.grade')[$grade].config('workbook.subject_1010')[$subject].'下册'.cache('all_version_now')->where('id',$key)->first()->name }}</textarea>
                                                                            <div class="btn-group pull-right">
                                                                                <a data-id="999999999|{{ $key.'_'.$grade.'_'.$subject }}" class="btn btn-success btn-xs buy_status" data-grade="{{ $grade }}" data-subject="{{ $subject }}">新增购买</a>
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                            @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
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
        $(function () {
            //保存isbn
            $(document).on('click','.add_isbn',function () {
                let book_id = $(this).parent().attr('data-id');
                let isbn = $(this).prev().val();
                axios.post('{{ route('book_new_isbn_api','save_new_isbn') }}', {book_id, isbn}).then(response => {
                }).catch()
            });
            //isbn加横杠
            $('.for_isbn_input').bind('input propertychange', function() {

                if($(this).val().length===3){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length===5){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length>6) {
                    let now_start = $(this).val()[6];
                    if (now_start <= 3) {
                        if ($(this).val().length === 9) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start > 3 && now_start <= 5) {
                        if ($(this).val().length === 10) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '8') {
                        console.log($(this).val().length);
                        if ($(this).val().length === 11) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '9') {
                        if ($(this).val().length === 12) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    }
                    if ($(this).val().length === 15) {
                        $(this).val($(this).val() + '-');
                        this.selectionStart = this.selectionEnd = this.value.length + 1
                    }
//                    if($(this).val().length===17){
//                        $('.add_isbn').click();
//                    }
                }
            });

            //保存新书名
            $(document).on('click','.save_new_bookname',function () {
                let book_id = $(this).parents('.book_box').attr('data-id');
                let book_name_now = $(this).parents('.book_name_box').find('textarea').val();
                if(book_id>1000000){
                    axios.post('{{ route('new_book_buy_api','change_new_name') }}',{book_id,book_name_now}).catch(response=>{

                    }).catch();
                }
            });

            $(document).on('click','.buy_status',function () {
               let id = $(this).attr('data-id');
               let jj = $(this).parents('table').attr('data-jj');
               let grade_id = $(this).attr('data-grade');
               let subject_id = $(this).attr('data-subject');
               let sort = $(this).parents('table').attr('data-sort');
               let add_book_box = $(this).parents('.add_book_box');
               let bookname = add_book_box.find('textarea').val();
               let isbn = add_book_box.find('.for_isbn_input').val();
               let version_id = add_book_box.find('select').val();
               let version_name = add_book_box.find('select option:selected').text();
               let status_badge = $(this);

               if(bookname.length<10){
                   alert('请检查书名');
                   return false;
               }
               if($(this).html()==='已购买'){
                   if(!confirm('确认取消已购买状态')){
                       return false;
                   }
               }
               axios.post('{{ route('new_book_buy_api','mark_status') }}',{id,jj,sort,isbn,bookname,version_id}).then(response=>{
                   if(response.data.status===1){
                       if(response.data.type==='cancel'){
                           $(this).parent().parent().html(``)
                       }else{
                           $(this).parent().parent().after(`
<div class="book_box" data-id="${response.data.new_id}">
<p><a class="label label-info">已购买</a>
<a class="btn btn-info btn-xs">2018<i class="badge bg-black">0</i><i class="badge bg-red">0</i></a></p>
<div data-id="${response.data.new_id}" style="width: 100%"><input maxlength="17" class="for_isbn_input form-control" style="font-size: 17px" value="${isbn}"><a class="btn btn-danger btn-block add_isbn btn-xs hide">保存isbn</a></div>
<div class="input-group" style="width: 100%">
              <select data-name="version" class="version_id form-control select2" tabindex="-1" aria-hidden="true">
                      <option value="-2">全部版本</option>
                               <option value="${version_id}" selected>${version_name}</option>
                               </select>
                 </div>
                 <textarea rows="4" style="width: 100%" class="form-control">${bookname}</textarea>
                         <p class="btn-group" style="width:100%">
                              <a style="width:50%" class="btn btn-primary btn-xs get_related_book" data-grade="${grade_id}" data-subject="${subject_id}" data-volume="2" data-version="${version_id}" data-sort="${sort}" target="_blank">查看往年</a><a style="width:50%" class="btn btn-danger save_new_bookname btn-xs">保存</a></p>
                              <p class="btn-group" style="width: 100%;">
                              <a style="width:50%" data-id="${response.data.new_id}" class="btn btn-primary btn-xs check_upload_answer">查看答案</a><a style="width:50%" data-id="${response.data.new_id}" class="btn btn-danger btn-xs delete_this_book">删除</a>
</div>`)
                           $('select[data-name="version"]').select2();
                       }

                   }
               }).catch(function (error) {
                   console.log(error);
               })
            });

            //显示版本
            $('select[data-name="version"]').select2({data: $.parseJSON('{!! $data['version_select'] !!} '),});
            //更换版本
            $(document).on('click','.change_version',function () {
                let book_id = $(this).parents('.book_box').attr('data-id');
                let version_id = $(this).parents('.book_box').find('.version_id').val();
                axios.post('{{ route('new_book_buy_api','change_version') }}',{book_id,version_id}).then(response=>{
                    if(response.data.status===1){
                        alert('更换成功');
                    }
                }).catch(function () {})
            });
            //更换所属人
            $('.change_owner').click(function () {
                let user_id = $(this).attr('data-uid');
                let now_username = $(this).html();
                let sort = $(this).parent().parent().attr('data-sort');
                axios.post('{{ route('new_book_buy_api','change_owner') }}',{user_id,sort}).then(response=>{
                    if(response.data.status===1){
                        $(this).parents('.btn-group').find('.now_user').html(now_username);
                    }
                }).catch(function () {

                });
            });

            //练习册升级
            $('.update_this_book').click(function () {
               let book_id = $(this).parent().parent().attr('data-id');
               let now_html = $(this).html();
               if(now_html==='已升级'){
                   if(!confirm('取消升级并删除升级练习册')){
                       return false;
                   }
               }
               axios.post('{{ route('new_book_buy_api','update_this_book') }}',{book_id}).then(response=>{
                   if(response.data.status===1){
                        if(now_html==='已升级'){
                            $(this).html('升级').removeClass('label-success').addClass('label-info');
                        }else{
                            $(this).parent().parent().before(`<div class="book_box" data-id="${response.data.new_id}">
<p><a class="label label-info">已购买</a>
<a class="btn btn-info btn-xs">2018<i class="badge bg-black">0</i><i class="badge bg-red">0</i></a></p>
<span class="has_buy_btn"><a target="_blank">${response.data.new_name}</a></span> <p><a data-id="${response.data.new_id}" class="badge bg-red delete_this_book">删除</a><a target="_blank" href="" class="badge bg-red">查看答案</a><a class="badge bg-blue confirm_receive">确认收货</a></p></div>`);
                            $(this).html('已升级').removeClass('label-info').addClass('label-success');
                        }
                   }
               }).catch()
            });

            //新练习册删除
            $(document).on('click','.delete_this_book',function () {
                let book_id = $(this).attr('data-id');
                axios.post('{{ route('new_book_buy_api','delete_book') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        $(this).parents('.book_box').remove();
                    }
                }).catch()
            });

            //练习册查看往年
            $(document).on('click','.get_related_book',function () {
                let grade = $(this).attr('data-grade');
                let subject = $(this).attr('data-subject');
                let volume = $(this).attr('data-volume');
                let version = $(this).attr('data-version');
                let sort = $(this).attr('data-sort');
               window.open('{{ route('new_book_history') }}'+'/0/'+grade+'/'+subject+'/'+volume+'/'+version+'/'+sort);
            });

            //新练习册查看答案
            $(document).on('click','.check_upload_answer',function () {
               let book_id = $(this).attr('data-id');
               //http://192.168.0.130/jiajiaot/ding/lianxice/answeredit/1217104
                window.open('http://192.168.0.130/jiajiaot/ding/lianxice/answeredit/'+book_id);
            });

            //确认收货
            $(document).on('click','.confirm_receive',function () {
                let book_id = $(this).parents('.book_box').attr('data-id');
                axios.post('{{ route('new_book_buy_api','confirm_receive') }}',{book_id}).then(response=>{
                    if(response.data.status===1){
                        $(this).html('已收货').removeClass('bg-blue').addClass('bg-red');
                    }
                }).catch();
            })
            //查看已有练习册
            $(document).on('click','.to_book_detail',function () {
               let book_id = $(this).prev().val();
               window.open('{{ substr(route('audit_answer_detail',0),0,-2) }}'+'/'+book_id);
            });

        })
    </script>
@endpush