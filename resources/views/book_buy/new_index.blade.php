@extends('layouts.backend')

@section('new_book_buy','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册收藏统计</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-body">
                <div class="input-group" style="width: 50%">
                    <select id="sort_id" class="form-control sort_name click_to">
                        <option value="-999">全部系列</option>
                    </select>
                    <a class="input-group-addon btn btn-primary" id="select_sort">查看</a>
                </div>
                <ul class="nav nav-tabs">
                    <li @if($data['type']==='jiajiao') class="active" @endif><a class="nav-pills"
                                                                                href="{{ route('new_book_buy','jiajiao') }}">精英家教网</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <ul class="list-unstyled">
                        @forelse($data['all_sort'] as $key_main=> $value)

                            <li class="clearfix" style="margin: 20px 0px;">
                            <span data-sort="{{ $value->sort }}">
                                <a class="btn btn-lg btn-info" target="_blank"
                                   href="{{ route('new_book_buy_detail',$value->sort) }}">{{ $value->sort_name }}<i class="badge bg-red">{{ $value->collect_count }}</i><i class="badge bg-black">{{ $value->wrong_num }}本需重新编辑或添加</i>
                                </a>
                                <div class="btn-group">
                                    <a class="btn btn-default now_user">
                                    @if(Auth::id()===2)
                                            {{ \App\User::find($value->update_uid)->name }}
                                        @else
                                            {{ Auth::user()->name }}
                                        @endif
                                    </a>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                <ul class="dropdown-menu" role="menu" data-sort="{{ $value->sort }}">
                                    <li><a class="change_owner" data-uid="5">黄少敏</a></li>
                                    <li><a class="change_owner" data-uid="8">苏蕾</a></li>
                                    <li><a class="change_owner" data-uid="11">张连荣</a></li>
                                    <li><a class="change_owner" data-uid="14">陈卓</a></li>
                                    <li><a class="change_owner" data-uid="17">肖高萍</a></li>
                                    <li><a class="change_owner" data-uid="18">宋晗</a></li>
                                  </ul>
                                </div>
                                <br>
                                @php $sorted_value = $value->has_self_new_book->groupBy('version_id')->transform(function ($item,$k){
                                return $item->groupBy('subject_id')->transform(function ($item1,$k1){
                                        return $item1->groupBy('grade_id')->sortBy(function($value,$key){
                    return $key;})->sortBy(function($value,$key){
                    return $key;});})->sortBy(function ($value,$key){
                    return $key;});
                                })->sortBy(function ($value,$key){
                    return $key;});
                                @endphp
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        @forelse($sorted_value as $version=>$version_value)
                                        <li class="@if($loop->first) active @endif"><a data-toggle="tab" href="#{{ $key_main }}_version_{{ $version }}">{{ cache('all_version_now')->where('id',$version)->first()->name }}</a></li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content">
                                        @forelse($sorted_value as $version=>$version_value)
                                            <div class="tab-pane @if($loop->first) active @endif" id="{{ $key_main }}_version_{{ $version }}">

                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th>年级/科目</th>
                                                        @forelse($version_value as $subject=>$subject_value)
                                                            <th>{{ config('workbook.subject_1010')[$subject] }}</th>
                                                        @endforeach
                                                        </tr>
                                                        @foreach([3,4,5,6,7,8,9] as $grade_id_now)
                                                            <tr>
                                                                <td>{{ config('workbook.grade')[$grade_id_now] }}</td>

                                                                    @forelse($version_value as $subject=>$subject_value)

                                                                        @if(isset($subject_value[$grade_id_now]))
                                                                        @php $book_old = $subject_value[$grade_id_now]->sortBy('version_year')->first();  @endphp
                                                                        @if(in_array('2018',$subject_value[$grade_id_now]->pluck('version_year')->toArray()))
                                                                        <td>
                                                                            <a class="btn btn-primary" href="{{ route('new_book_history',[0,$grade_id_now,$subject,2,$version,$value->sort]) }}" target="_blank" >已更新 <i class="badge bg-red">{{ $book_old->has_main_book?$book_old->has_main_book->collect_count:0 }}</i>
                                                        <i class="badge bg-red">{{ $book_old->has_main_book?$book_old->has_main_book->concern_num:0 }}</i></a></td>
                                                                        @else

                                                                            @if(\App\AWorkbook1010::where(['version_year'=>'2018','grade_id'=>$grade_id_now,'subject_id'=>$subject,'volumes_id'=>2,'version_id'=>$version,'sort'=>$value->sort])->count()>0)
                                                        <td><a class="btn btn-danger">已有
                                                                <i class="badge bg-red">{{ $book_old->has_main_book?$book_old->has_main_book->collect_count:0 }}</i>
                                                        <i class="badge bg-red">{{ $book_old->has_main_book?$book_old->has_main_book->concern_num:0 }}</i>
                                                            </a></td>
                                                                            @else
                                                                                <td>
                                                                                    <a target="_blank" href="{{ route('new_book_history',[0,$grade_id_now,$subject,2,$version,$value->sort]) }}" class="btn btn-danger">未更新
                    <i class="badge bg-red">{{ $book_old->has_main_book?$book_old->has_main_book->collect_count:0 }}</i>
                                                        <i class="badge bg-red">{{ $book_old->has_main_book?$book_old->has_main_book->concern_num:0 }}</i>
                                                                                    </a></td>
                                                                            @endif
                                                                        @endif
                                                                        @else
                                                                            <td></td>
                                                                        @endif
                                                                    </td>
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    {{ $data['all_sort']->links() }}
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
            {{--$(document).on('click', '.has_buy_btn', function () {--}}
                {{--let id = $(this).attr('data-id');--}}
                {{--let status_badge = $(this).find('.buy_status');--}}
                {{--axios.post('{{ route('mark_buy_status') }}', {id}).then(response => {--}}
                    {{--if (response.data.status === 1) {--}}
                        {{--if (status_badge.hasClass('bg-aqua')) {--}}
                            {{--status_badge.removeClass('bg-aqua').addClass('bg-red').html('已购买')--}}
                        {{--} else {--}}
                            {{--status_badge.removeClass('bg-red').addClass('bg-aqua').html('未购买')--}}
                        {{--}--}}
                    {{--}--}}
                {{--}).catch(function (error) {--}}
                    {{--console.log(error);--}}
                {{--})--}}
            {{--});--}}

            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "{{ route('workbook_sort','sort') }}",
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
                    return '<option value="' + repo.id + '">' + repo.name + '_' + repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });
            $('#select_sort').click(function () {
                let sort_id = $('.sort_name').val();
                if (sort_id === '-999') {
                    return false;
                }
                window.open('{{ route('new_book_buy_detail') }}' + '/' + sort_id);
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
            })
        })
    </script>
@endpush