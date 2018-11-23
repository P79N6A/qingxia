@extends('layouts.backend')

@section('user_feedback','active')


@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">用户反馈</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 反馈详情</h3>
                <div class="input-group" style="width: 40%">
                    <input type="text" value="" class="form-control"/>
                    <a class="input-group-addon btn btn-primary" id="to_book_id">跳转至练习册id</a>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li @if($data['status']==0)class="active" @endif><a href="{{ route('user_feedback_list',[$data['sortBy'],$data['is_book'],0]) }}">待处理</a></li>
                        <li @if($data['status']==1)class="active" @endif><a href="{{ route('user_feedback_list',[$data['sortBy'],$data['is_book'],1]) }}">已处理</a></li>
                        @can('lxc_verify')
                            <li><a href="{{ route('user_feedback_status') }}" target="_blank">处理情况查看</a></li>
                        @endcan
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered">
                                <tr>
                                    <th>练习册名称/反馈次数@if($data['sortBy']==='num')<a class="badge bg-red" href="{{ route('user_feedback_list',['collect',$data['is_book'],$data['status'],$data['has_new_book']]) }}">反馈次数排序↓</a>@else <a class="badge bg-red" href="{{ route('user_feedback_list',['num',$data['is_book'],$data['status'],$data['has_new_book']]) }}">收藏排序↓</a> @endif
                                        @if($data['is_book']==1)<a href="{{ route('user_feedback_list',[$data['sortBy'],0,$data['status'],$data['has_new_book']]) }}" class="badge bg-red">限定课本√</a>
                                        @else
                                            <a href="{{ route('user_feedback_list',[$data['sortBy'],1,$data['status'],$data['has_new_book']]) }}" class="badge bg-black">限定课本</a>
                                        @endif
                                        {{--@if($data['has_new_book']==1)<a href="{{ route('user_feedback_list',[$data['sortBy'],$data['is_book'],$data['status'],0]) }}" class="badge bg-red">有新版√</a>--}}
                                        {{--@else--}}
                                            {{--<a href="{{ route('user_feedback_list',[$data['sortBy'],$data['is_book'],$data['status'],1]) }}" class="badge bg-black">有新版</a>--}}
                                        {{--@endif--}}

                                        <strong style="float: right">历史版本</strong>
                                    </th>
                                    <th>答案不全</th>
                                    <th>图片不清晰或被遮挡</th>
                                    <th>版本太老</th>
                                    <th>图片排序混乱</th>
                                    <th>其它原因</th>
                                    <th>处理人</th>
                                    <th>操作</th>
                                    @if($data['status']==1)
                                        <th>处理时间</th>
                                        @can('lxc_verify')
                                            <th>审核状态</th>
                                        @endcan
                                    @endif
                                </tr>
                                @forelse($data['feedback'] as $feedback)
                                    <tr>
                                        <td>
                                            {{ $feedback->bookid }}<a href="http://www.1010jiajiao.com/daan/bookid_{{ $feedback->bookid }}.html" target="_blank" class="btn btn-primary btn-xs">@if($feedback->bookid<10000000) {{ $feedback->has_book->bookname }} @else {{ $feedback->has_user_book?$feedback->has_user_book->sort_name:'' }} @endif <i class="badge bg-black">{{ $feedback->num }}</i>@if($feedback->bookid<10000000)<i class="badge bg-red">{{{ $feedback->has_book->collect_count }}}</i>@endif</a><strong class="label label-primary">答案页数：{{ $feedback->answer_num }}</strong>
                                            <a style="float: right" @if($feedback->has_book->hasOnly) href="{{ route('new_buy_only_name',$feedback->has_book->hasOnly->newname) }}" @endif target="_blank">
                                                @if($feedback->has_book->hasOnly)
                                                @if($feedback->has_book->hasOnly->book2018>0)<em class="badge bg-primary">2018</em>@endif
                                                @if($feedback->has_book->hasOnly->book2017>0)<em class="badge bg-primary">2017</em>@endif
                                                @if($feedback->has_book->hasOnly->book2016>0)<em class="badge bg-primary">2016</em>@endif
                                                @if($feedback->has_book->hasOnly->book2015>0)<em class="badge bg-primary">2015</em>@endif
                                                @if($feedback->has_book->hasOnly->book2014>0)<em class="badge bg-primary">2014</em>@endif
                                                @endif
                                            </a>
                                        </td>
                                        <td>{{ substr_count($feedback->text,'答案不全') }}</td>
                                        <td>{{ substr_count($feedback->text,'图片不清晰或被遮挡') }}</td>
                                        <td>{{ substr_count($feedback->text,'版本太老') }}</td>
                                        <td>{{ substr_count($feedback->text,'图片排序混乱') }}</td>
                                        <td>{{ substr_count($feedback->text,'其他原因') }}</td>
                                        <td>
                                            @if($data['status']===1)
                                                <a class="label label-info">{{ $feedback->has_user?$feedback->has_user->name:'' }}</a>
                                            @else
                                                @if($feedback->bookid%6===0)
                                                    <a class="label label-info">苏蕾</a>
                                                @elseif($feedback->bookid%6===1)
                                                    <a class="label label-info">张连荣</a>
                                                @elseif($feedback->bookid%6===2)
                                                    <a class="label label-info">肖高萍</a>
                                                @elseif($feedback->bookid%6===3)
                                                    <a class="label label-info">宋晗</a>
                                                @elseif($feedback->bookid%6===4)
                                                    <a class="label label-info">印娜</a>
                                                @elseif($feedback->bookid%6===5)
                                                    <a class="label label-info">张玲莉</a>
                                                @endif
                                            @endif

                                        </td>
                                        <td><a class="btn btn-xs btn-danger" href="{{ route('audit_answer_detail',$feedback->bookid) }}" target="_blank">编辑</a></td>
                                        @if($data['status']==1)
                                            <td>
                                                {{ $feedback->updated_at }}
                                            </td>
                                            @can('lxc_verify')
                                                <td>
                                                    @if($feedback->verified_at>0)
                                                        <i class="badge bg-blue">已审核：{{ $feedback->verified_at }}</i>
                                                    @else
                                                        <i class="badge bg-red">待审核</i>
                                                        <a class="btn btn-danger btn-xs verify_confirm" data-book-id="{{ $feedback->bookid }}">确认无误,审核通过</a>
                                                    @endif
                                                </td>
                                            @endcan
                                        @endif
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
                <div>{{ $data['feedback']->links() }}</div>
            </div>

        </div>
    </section>


@endsection

@push('need_js')
    <script>
        $(function () {
            //跳转
            $('#to_book_id').click(function () {
                let book_id = $(this).prev().val();
                window.open('{{ route('audit_answer') }}'+'/'+book_id);
            });
            //审核通过
            $('.verify_confirm').click(function () {
                let book_id = $(this).attr('data-book-id');
               if(confirm('确认此练习册求助通过审核')){
                   axios.post('{{ route('feedback_api','verify_confirm') }}',{book_id}).then(response=>{
                       if(response.data.status===1){
                           $(this).parent().html(`
                           <i class="badge bg-blue">已审核：{{ date('Y-m-d H',time()) }}</i>
                           `)
                       }
                   }).catch(function () {})
               }
            });
        })
    </script>
@endpush