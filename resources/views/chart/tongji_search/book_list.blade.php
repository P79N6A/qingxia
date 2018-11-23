@extends('layouts.backend')
@push('need_css')

@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">搜索统计</li>
        </ol>


    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">ISBN无结果书本信息</h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="isbn_data" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ISBN</th>
                                            <th>书本名</th>
                                            <th>年级</th>
                                            <th>学科</th>
                                            <th>卷册</th>
                                            <th>版本</th>
                                            <th>系列</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($datas as $data)
                                            <tr>
                                                <td>{{$data->isbn}}</td>
                                                <td>{{$data->bookname}} </td>
                                                <td>{{$data->grade_name}}}</td>
                                                <th>{{$data->subject_name}}</th>
                                                <th>{{$data->volume_name}}</th>
                                                <th>{{$data->version_name}}</th>
                                                <th>{{$data->sort_name}}</th>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                        共{{$datas->total()}}条数据
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                        {{$datas->links()}}

                                    </div>
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
    <script type="text/javascript">
        $(".isbn_nocontent_search").parent().css("display",'block').parent().addClass("active");

    </script>
@endpush