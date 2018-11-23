@extends('layouts.backend')

@push('need_css')
<style>
.profile-user-img{
    width: 300px;
    height: 150px;
}
.user-block img{
    width:100% !important;
    height:100% !important;
}
</style>

@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册详情</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 练习册详情</h3></div>
            <div class="box-body">
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-spot" src="../../dist/img/user4-128x128.jpg" alt="练习册图片">
                            <h3 class="profile-username text-center">{{ $data['book']->name }}</h3>
                            <p class="text-muted text-center">{{ $data['book']->created_at }}</p>
                            <div class="form-group">
                                <a class="label label-info">{{ $data['book']->version_year }}</a>
                                <a class="label label-info">{{ $data['book']->sort_name }}</a>
                                <a class="label label-info">{{ config('workbook.grade')[$data['book']->grade] }}</a>
                                <a class="label label-info">{{ config('workbook.subject_1010')[$data['book']->subject] }}</a>
                                <a class="label label-info">{{ config('workbook.volumes')[$data['book']->volume] }}</a>
                                <a class="label label-info">{{ $data['book']->version_name }}</a>
                            </div>
                            @if($data['book']->status==0)
                                <a href="#" class="btn btn-danger btn-block"><b>转入待购买</b></a>
                            @elseif($data['book']->status==1)
                                <a href="#" class="btn btn-success btn-block"><b>已转入待购买</b></a>
                            @else
                                <a href="#" class="btn btn-primary btn-block"><b>已购买</b></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#activity" data-toggle="tab">店铺有售</a></li>
                                <li><a href="#timeline" data-toggle="tab">同系列练习册情况</a></li>
                                <li><a href="#settings" data-toggle="tab">其它信息</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    @if($data['store'])
                                        @foreach($data['store'] as $value)
                                            <div class="post">
                                                <div class="user-block">
                                                    <span class="username">
                                                      <a href="#">{{ $value[0]->shop_name }}</a>
                                                    </span>
                                                    <span class="description">{{ $value[0]->pingtai }}</span>
                                                   <span>
                                                        @foreach($value as $book)
                                                            <a class="col-md-3 thumbnail text-center change_cover"><img class="img-responsive" data-original="{{ $book->img }}"/>
                                                            <em>{{ $book->price }}</em></a>

                                                        @endforeach
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="tab-pane" id="timeline">
                                   @if($data['sort'])

                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>年份</th>
                                                        <th>书名</th>
                                                        <th>状态</th>
                                                    </tr>
                                                    @foreach($data['sort'] as $value)
                                                        <tr>
                                                            <td>
                                                                {{ $value->version_year }}
                                                            </td>
                                                            <td>
                                                                {{ $value->bookname }}
                                                            </td>
                                                            <td><a class="label label-info">已购买</a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                   @endif
                                </div>
                                <div class="tab-pane" id="settings">

                                </div>
                            </div>

                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
<script>
    var lazy = new LazyLoad();
    var token = '{{ csrf_token() }}';
    var book_id = '{{ $data['book']->id }}';
    $(function () {
//        $('.change_cover').click(function () {
//            var o = {
//                _token:token,
//                id:
//            };
//            $.post('')
//        });
    })
</script>
@endpush
