
@extends('layouts.simple')
@push('need_css')
<link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    .raw_title b{
        color: red;
    }
</style>
@endpush
@push('need_js')
<script src="/adminlte/plugins/autocompleter/jquery.autocompleter.js"></script>
<script src="/adminlte/plugins/layer/layer.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
@endpush
@section('content')
    <section class="content-header">
    </section>
    <section class="content">
        <div class="row">
                <div id="box-header" class="box-header">
                    <div class="col-sm-3">
                        <div class="input-group">
                                <span class="input-group-addon">
                                   关键词
                                </span>
                            <input type="text" class="form-control" id="keyword"  value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                                <span class="input-group-addon">
                                   排除词
                                </span>
                            <input type="text" class="form-control" id="remove">
                            <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="search">搜</button>
                             </span>
                        </div>
                    </div>
                </div>
        </div>
        <div class="row">
            <ul class="list" style="overflow: hidden;padding-left:21px;list-style: none;">
                    <div class="box box-widget">
                        <div class="box-body">
                            <div class="media">
                                @foreach($re['list'] as $v)
                                <li style="height:300px;width:250px;float: left;">
                                    <div class="media-body" >
                                        <a target="_blank" href="{{ $v['url'] }}" class="ad-click-event">
                                            <img src="{{ $v['cover_url'] }}" alt="Now UI Kit" class="media-object" style="max-height: 150px; max-width: 150px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                        </a>
                                        <div class="info" style="width: 200px;">
                                            <div style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;overflow:hidden" class="raw_title">
                                                {!! $v['bookname'] !!}
                                            </div>
                                            <div>
                                                ISBN:
                                                <a data-toggle="modal" data-target="#myModal" class="isbn_info" data-isbn="{{ $v['isbn'] }}">
                                                    {{ $v['isbn'] }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
            </ul>
        </div>
        <div>
            {{ $re['paginator']->links() }}
        </div>


    </section>
@endsection

@push('need_js')

<script src="/adminlte/plugins/select2/select2.full.min.js"></script>

<script>
    $("#keyword").val('{{  }}')
    $("#search").click(function () {
        var keyword = $("#keyword").val();
        window.location.href=`{{ route('taobao_search') }}/${keyword}/{{ $re['type'] }}/{{ $re['sort_id'] }}/{{ $re['is_read'] }}/{{ $re['v_status'] }}/{{ $re['remove_isbn'] }}/{{ $re['has_year'] }}/{{ $re['start'] }}/{{ $re['end'] }}`;
    });


    var isCheckAll = false;
    function swapCheck() {
        if (isCheckAll) {
            $("input[type='checkbox']").each(function() {
                this.checked = false;
            });
            isCheckAll = false;
        } else {
            $("input[type='checkbox']").each(function() {
                this.checked = true;
            });
            isCheckAll = true;
        }
    }


</script>
@endpush

