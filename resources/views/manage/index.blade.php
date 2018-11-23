@extends('layouts.backend')

@section('content')
    <section class="content-header">
        <h1>
            控制面板
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">控制面板</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            {{--<div class="col-lg-3 col-xs-6">--}}
                {{--<!-- small box -->--}}
                {{--<div class="small-box bg-aqua">--}}
                    {{--<div class="inner">--}}
                        {{--<h3><sup>1.</sup></h3>--}}

                        {{--<p>1010练习册整理</p>--}}
                    {{--</div>--}}
                    {{--<div class="icon">--}}
                        {{--<i class="fa fa-file-text-o"></i>--}}
                    {{--</div>--}}
                    {{--<a href="{{ route('workbook') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>--}}
                {{--</div>--}}
            {{--</div>--}}
            @can('workbook_only')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>练习册唯一化整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <a href="{{ route('workbook_only') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('sort_name')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>sort_name整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-files-o"></i>
                    </div>
                    <a href="{{ route('sort_name') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            <div class="col-lg-3 col-xs-6 hide">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>2.</sup></h3>
                        <p>vzy练习册整理</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-files-o"></i>
                    </div>
                    <a href="{{ route('lxc') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6 hide">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>3.</sup></h3>
                        <p>vzy练习册整理(重新整理)</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-files-o"></i>
                    </div>
                    <a href="{{ route('lxc_v2') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @can('book_now')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>vzy子系列整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-th"></i>
                    </div>
                    <a href="{{ route('sub_sort') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('book_now_v2')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>课本整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <a href="{{ route('book_arrange') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('workbook_cover')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>练习册封面整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file-o"></i>
                    </div>
                    <a href="{{ route('workbook_cover') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>

            </div>
            @endcan
            @can('video_manage')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>视频整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file-video-o"></i>
                    </div>
                    <a href="{{ route('video_manage') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('isbn_manage')
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>isbn整理</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-th-large"></i>
                    </div>
                    <a href="{{ route('isbn_manage') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('book_recycle')
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>回收站</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-trash-o"></i>
                    </div>
                    <a href="{{ route('book_recycle') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('book_check')
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><sup>答案审核</sup></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check-square-o"></i>
                    </div>
                    <a href="{{ route('book_check') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endcan
            @can('manage')
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><sup>权限管理</sup></h3>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a href="{{ route('system_manage') }}" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endcan
        </div>
    </section>
@endsection