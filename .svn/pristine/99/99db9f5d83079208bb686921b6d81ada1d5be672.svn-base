@extends('layouts.backend')

@section('homework_manage_index') active @endsection

@push('need_css')

@endpush


@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">作业管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3>作业管理</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="@if($data['type']==='index') active @endif"><a href="{{ route('homework_manage_index','index') }}">作业大厅<em class="badge bg-light-blue square_num"></em></a></li>
                        <li class="@if($data['type']==='feedback') active @endif"><a href="{{ route('homework_manage_index','feedback') }}">反馈站<em class="badge bg-light-blue feedback_num"></em></a></li>
                        <li class="@if($data['type']==='workspace') active @endif"><a href="{{ route('homework_manage_index','workspace') }}">工作台<em class="badge bg-light-blue workspace_num"></em></a></li>
                        <li class="@if($data['type']==='done') active @endif"><a href="{{ route('homework_manage_index','done') }}">已完成<em class="badge bg-light-blue done_num"></em></a></li>
                        <li class="@if($data['type']==='recycle') active @endif"><a href="{{ route('homework_manage_index','recycle') }}">回收站<em class="badge bg-light-blue recycle_num"></em></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane @if($data['type']==='index') active @endif" id="tab_1">
                            @if($data['type']==='index')
                                <div class="row">
                                @forelse($data['zyq'] as $value)
                                    <div class="col-md-6 homework_box">
                                        <div class="box box-widget" style="min-height:300px;max-height: 300px;overflow: auto">
                                            <div class="box-header with-border">
                                                <div class="user-block">
                                                    <img class="img-circle" src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="User Image">
                                                    <span class="username"><a href="#">{{ isset($value->has_user)?$value->has_user->username:'test11111' }}</a></span>
                                                    <span class="description">{{ $value->add_time }}</span>
                                                </div>

                                                <div class="box-tools hide">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>

                                            <div class="box-body">
                                                <p>{{ $value->descript }}</p>
                                                @if(strpos($value->pic,'|')!== false)
                                                    @forelse(explode('|',$value->pic) as $img)
                                                        @if($img)
                                                        <a class="thumbnail col-md-4">
                                                            <img src="{{ config('weixin.M_PIC').$img }}" />
                                                        </a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <a class="thumbnail">
                                                        <img src="{{ config('weixin.M_PIC').$value->pic }}" />
                                                    </a>
                                                @endif
                                                <div>

                                                </div>
                                            </div>

                                            <div class="box-footer box-comments">

                                                @forelse($value->has_comments as $comment)

                                                    <div class="box-comment">
                                                        <img class="img-circle img-sm" src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="User Image">
                                                        <div class="comment-text">
                                              <span class="username">
                                                Maria Gonzales
                                                <span class="text-muted pull-right">8:03 PM Today</span>
                                              </span>
                                                            {{ $comment->comment }}
                                                        </div>
                                                    </div>
                                                    @endforeach

                                            </div>



                                        </div>
                                        <div class="box-footer">
                                            <a data-hid="{{ $value->id }}" data-move="workspace" class="btn btn-xs btn-primary move_it">移至工作台</a>
                                            <a data-hid="{{ $value->id }}" data-move="recycle" class="btn btn-xs btn-danger move_it">移至回收站</a>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                                <div>{{ $data['zyq']->links() }}</div>
                            @endif
                        </div>
                        <div class="tab-pane @if($data['type']==='feedback') active @endif" id="tab_2">
                            @if($data['type']==='feedback')

                            @endif
                        </div>
                        <div class="tab-pane @if($data['type']==='workspace') active @endif" id="tab_3">
                            @if($data['type']==='workspace')
                                <div class="row">
                                    @forelse($data['zyq'] as $value)
                                        <div class="col-md-6 homework_box">
                                            <div class="box box-widget" style="min-height:300px;max-height: 300px;overflow: auto">
                                                <div class="box-header with-border">
                                                    <div class="user-block">
                                                        <img class="img-circle" src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="User Image">
                                                        <span class="username"><a href="#">{{ isset($value->has_user)?$value->has_user->username:'test11111' }}</a></span>
                                                        <span class="description">{{ $value->add_time }}</span>
                                                    </div>

                                                    <div class="box-tools hide">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <p>{{ $value->descript }}</p>
                                                    @if(strpos($value->pic,'|')!== false)
                                                        @forelse(explode('|',$value->pic) as $img)
                                                            @if($img)
                                                                <a class="thumbnail col-md-4">
                                                                    <img src="{{ config('weixin.M_PIC').$img }}" />
                                                                </a>
                                                            @endif
                                                            @endforeach
                                                            @else
                                                                <a class="thumbnail">
                                                                    <img src="{{ config('weixin.M_PIC').$value->pic }}" />
                                                                </a>
                                                            @endif
                                                            <div>

                                                            </div>
                                                </div>

                                                <div class="box-footer box-comments">

                                                    @forelse($value->has_comments as $comment)

                                                        <div class="box-comment">
                                                            <img class="img-circle img-sm" src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="User Image">
                                                            <div class="comment-text">
                                              <span class="username">
                                                Maria Gonzales
                                                <span class="text-muted pull-right">8:03 PM Today</span>
                                              </span>
                                                                {{ $comment->comment }}
                                                            </div>
                                                        </div>
                                                        @endforeach

                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <a data-hid="{{ $value->id }}" data-move="square" class="btn btn-xs btn-primary move_it">移至作业大厅</a>
                                                <a data-hid="{{ $value->id }}" data-move="recycle" class="btn btn-xs btn-danger move_it">移至回收站</a>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                                <div>{{ $data['zyq']->links() }}</div>
                            @endif
                        </div>
                        <div class="tab-pane @if($data['type']==='done') active @endif" id="tab_4">
                            @if($data['type']==='done')

                            @endif
                        </div>
                        <div class="tab-pane @if($data['type']==='recycle') active @endif" id="tab_5">
                            @if($data['type']==='recycle')
                                <div class="row">
                                @forelse($data['zyq'] as $value)
                                    <div class="col-md-6 homework_box">
                                        <div class="box box-widget" style="min-height:300px;max-height: 300px;overflow: auto">
                                            <div class="box-header with-border">
                                                <div class="user-block">
                                                    <img class="img-circle" src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="User Image">
                                                    <span class="username"><a href="#">{{ isset($value->has_user)?$value->has_user->username:'test11111' }}</a></span>
                                                    <span class="description">{{ $value->add_time }}</span>
                                                </div>

                                                <div class="box-tools hide">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>

                                            <div class="box-body">
                                                <p>{{ $value->descript }}</p>
                                                @if(strpos($value->pic,'|')!== false)
                                                    @forelse(explode('|',$value->pic) as $img)
                                                        @if($img)
                                                            <a class="thumbnail col-md-4">
                                                                <img src="{{ config('weixin.M_PIC').$img }}" />
                                                            </a>
                                                        @endif
                                                        @endforeach
                                                        @else
                                                            <a class="thumbnail">
                                                                <img src="{{ config('weixin.M_PIC').$value->pic }}" />
                                                            </a>
                                                        @endif
                                                        <div>
                                                        </div>
                                            </div>
                                            <div class="box-footer box-comments">

                                                @forelse($value->has_comments as $comment)

                                                    <div class="box-comment">
                                                        <img class="img-circle img-sm" src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="User Image">
                                                        <div class="comment-text">
                                          <span class="username">
                                            Maria Gonzales
                                            <span class="text-muted pull-right">8:03 PM Today</span>
                                          </span>
                                                            {{ $comment->comment }}
                                                        </div>
                                                    </div>
                                                    @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                                <div>{{ $data['zyq']->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script>
        $(function () {

            axios.get('{{ route('homework_manage_api_index','num') }}').then(response=>{
                if(response.data.status===1){
                    let num_data = response.data.data;
                    $('.square_num').html(num_data['square_num']);
                    $('.workspace_num').html(num_data['work_num']);
                    $('.done_num').html(num_data['done_num']);
                    $('.recycle_num').html(num_data['recycle_num']);
                }
            }).catch(function (error) { console.log(error) });

            //移动作业
            $(document).on('click','.move_it',function () {
                let hid = $(this).attr('data-hid');
                let type = $(this).attr('data-move');
                if(type==='workspace'){

                }else if(type==='recycle'){

                }else if(type==='square'){

                }else{
                    return false;
                }
                axios.post('{{ route('homework_manage_api_index','move') }}',{hid,type}).then(response=>{
                if(response.data.status===1){
                    $(`a[data-hid=${hid}]`).parents('.homework_box').remove();
                }
                }).catch(function (error) { console.log(error); })
            })


        })

    </script>
@endpush


