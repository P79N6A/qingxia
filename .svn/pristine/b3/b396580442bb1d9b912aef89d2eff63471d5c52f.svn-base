@extends('layouts.backend')

@section('book_new_check','active')

@push('need_css')

@endpush

@section('content')
    <div class="modal fade" id="show_img">
        <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">唯一表整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 唯一表整理</h3>
                <a class="btn btn-danger pull-right" href="{{ route('book_new_index') }}">返回</a>
            </div>

            <div class="box-body">
                <div>
                    <a class="btn btn-primary" href="{{ route('book_new_status') }}">所有</a>
                    @foreach($data['user'] as $user)
                        <a class="btn btn-info label_user" href="{{ route('book_new_status',$user->update_uid) }}" data-uid="{{ $user->update_uid }}">{{ $user->name }}</a>
                    @endforeach
                </div>
                <div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>id</th>
                            <th>书名</th>
                            <th>系列</th>
                            <th>练习册信息</th>
                            <th>操作者</th>
                            <th>更新时间</th>
                        </tr>
                        @foreach($data['all_record'] as $record)
                            <tr>
                                <td><a data-img="{{ $record->cover }}" data-target="#show_img" data-toggle="modal" class="show_img_btn">{{ $record->id }}</a></td>
                                <td>
                                    <div>
                                        <a @if($record->uid==Auth::id())href="{{ route('book_new_index','finished').'?edit_id='.$record->id }}" @endif>{{ $record->bookname }}</a>
                                    </div>
                                    <div>
                                        <a class="label label-success">{{ $record->sub_sort_name }}</a>
                                        <a class="label label-success">{{ $record->grade_name }}</a>
                                        <a class="label label-success">{{ $record->subject_name }}</a>
                                        <a class="label label-success">{{ $record->volume_name }}</a>
                                        <a class="label label-success">{{ $record->a_version_name }}</a>
                                    </div>
                                    <span class="pull-right check_box">
                                        @php
                                            if(strstr($record->grade_name,'高中必修') or strstr($record->grade_name,'高中选修')){
                                                $record->grade_name = str_replace('必修','',$record->grade_name);
                                                $record->grade_name = str_replace('选修','',$record->grade_name);
                                            }
                                        @endphp
                                        @if(strstr($record->bookname,$record->sub_sort_name?$record->sub_sort_name:'1') && strstr($record->bookname,$record->grade_name?$record->grade_name:'1') && strstr($record->bookname,$record->subject_name?$record->subject_name:'1') && strstr($record->bookname,$record->volume_name?$record->volume_name:'1') && strstr($record->bookname,$record->a_version_name?$record->a_version_name:'1'))
                                        <i class="glyphicon glyphicon-ok"></i>
                                        @else
                                            @php
                                                $new_word = $record->bookname;
                                                foreach (array($record->grade_name,$record->subject_name,$record->volume_name,$record->a_version_name) as $search){
                                                $new_word = str_replace($search,'',$new_word);
                                                }
                                            @endphp
                                            @if(strstr($new_word,$record->sub_sort_name))
                                                <i class="glyphicon glyphicon-ok"></i>
                                            @else
                                                <i class="glyphicon glyphicon-remove bg-red"></i>
                                            @endif
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $record->sort_name }}</td>
                                <td>{{ config('workbook.grade')[$record->grade_id] }}
                                    {{ config('workbook.subject_1010')[$record->subject_id] }}
                                    {{ config('workbook.volumes')[$record->volumes_id] }}
                                    {{ $record->version_name }}</td>
                                <td><a class="label label-danger">{{ $record->username }}</a></td>
                                <td>{{ $record->updated_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $data['all_record']->links() }}
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('need_js')
    <script>
        let app = new Vue({
            el: '#app-vue',
            data: {

            },
            created(){
                console.log('qweqweqwe');

            },
            methods:{

            },

        });

        $(function () {
            $('.show_img_btn').click(function () {
                let img = $(this).attr('data-img');
                $('#show_img .modal-body').html(`<a class="thumbnail"><img src="${img}"></a>`)
            });
        })

    </script>
@endpush