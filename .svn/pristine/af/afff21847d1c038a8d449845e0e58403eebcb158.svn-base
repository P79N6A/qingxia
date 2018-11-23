@extends('layouts.backend')

@section('lww_index')
    active
@endsection

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .search_book_cover {
            height: 150px;
        }
    </style>
@endpush

@section('content')

    @component('components.modal',['id'=>'show_onlyid'])
        @slot('title','查看该onlyid信息')
        @slot('body','')
        @slot('footer','')
    @endcomponent

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">本地图片上传</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 本地图片上传</h3></div>
            <div class="box-body">
                @forelse($data['all_directories'] as $dict)
                    <div class="col-md-4">
                        <div class="box box-warning box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ $dict->path_name }}</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="box-body" style="height: 400px; overflow: scroll">
                                @php
                                    $all_sub_dir = $dict->hasChildren;
                                @endphp
                                @forelse($all_sub_dir as $dir)
                                    <div class="box box-default box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">{{ $dir->path_name }}</h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @php
                                                $all_sub_dir_2 = $dir->hasChildren;
                                            @endphp
                                            @forelse($all_sub_dir_2 as $dir_2)
                                                <a class="btn @if($dir_2->status==0 && $dir_2->onlyid!='' && strpos($dir_2->onlyid,'|')===false)btn-danger @else btn-primary @endif">{{ $dir_2->path_name }}</a>
                                                <div class="input-group" data-dir="{{ $dir_2->path_name }}">
                                                    <label class="input-group-addon show_onlyid_info" data-target="#show_onlyid" data-toggle="modal">查看onlyid</label>
                                                    <input class="form-control" value="{{ $dir_2->onlyid }}"/>
                                                    @if($dir_2->status===0)
                                                    <a class="input-group-addon btn btn-danger upload_img_now">上传图片</a>
                                                    @endif
                                                </div>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach

                            </div>
                        </div>
                    </div>

                    @endforeach
            </div>
            <div>{{ $data['all_directories']->links() }}</div>
        </div>
    </section>
@endsection

@push('need_js')
    <script>
        $('.upload_img_now').click(function () {
            let onlyid = $(this).prev().val();
            let now_path = $(this).parent().attr('data-dir');
            if(!confirm('确认上传')){
                return false;
            }
            axios.post('{{ route('upload_all_imgs',['upload_img']) }}',{onlyid,now_path}).then(response=>{
                if(response.data.status===1){
                    $(this).remove();
                }
            })
        })

        $('.show_onlyid_info').click(function () {
            let onlyid = $(this).next().val();
            axios.post('{{ route('upload_all_imgs',['get_onlyid_info']) }}',{onlyid}).then(response=>{
                if(response.data.status===1){
                    $('#show_onlyid .modal-body').html(`
                    <div>
                        <img src="${response.data.data[0].cover}" alt="">
                    </div>
                    `)
                }
            })
        })
    </script>
@endpush