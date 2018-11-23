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
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 分享次数</h3>
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
                <form action="" method="post" id="choose_id">
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
                                        <td style="border:1px solid #ccc" mulu="">

                                        </td>
                                        <td style="width:100px;" style="border:1px solid #ccc">
                                            <select indextype="preg_sort" class="sortall update_index select_sort"  style=" width:200px;">
                                                <option value="">{{$v->id}}</option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_subject" class="update_index select_subject"  >
                                                <option value="">{{$v->cover}}</option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_grade" class="update_index select_grade"  style="width:100px;">
                                                <option value="">{{$v->newname}}</option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_volume" class="update_index select_volume"  style="width:100px;">
                                                <option value="">{{$v->onlyid}}</option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_version" class="update_index select_version"  >
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td style="width:40px;" style="border:1px solid #ccc">
                                            <button type="button" class="btn btn-success move" >匹配</button>
                                        </td>
                                        <td class="input_box" style="width:50px;" style="border:1px solid #ccc" >
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

                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>


<script type="text/javascript">

</script>
@endpush