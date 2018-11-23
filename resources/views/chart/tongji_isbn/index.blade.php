@extends('layouts.backend')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/datatables/dataTables.bootstrap.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">ISBN统计</li>
        </ol>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">ISBN收藏/关注统计</h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="isbn_data" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>ISBN</th>
                                            <th><a href="{{route("isbn_tongji",["field"=>"1","sort"=>$sort])}}">收藏人数</a> <i class="{{$class}}"></i></th>
                                            <th><a href="{{route("isbn_tongji",["field"=>"2","sort"=>$sort])}}">关注人数</a><i class="{{$class}}"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($datas as $data)
                                            <tr>
                                                <td>{{$data->isbn}}</td>
                                                <td>{{$data->collect_num}} </td>
                                                <td>{{$data->concern_num}}</td>
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
        $(".isbn_chart_index").parent().css("display",'block').parent().addClass("active");
    </script>
@endpush