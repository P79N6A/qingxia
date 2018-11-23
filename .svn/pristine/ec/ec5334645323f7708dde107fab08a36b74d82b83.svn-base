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

    {{--页面主题部分--}}
    <table style="width:100%;">
        @foreach($data as $value)
        <tr>
            <td style="border:30px solid white;">
                <div class="box box-info" style="width:100%;">
                    <div class="info-box">
                        <!-- Apply any bg-* class to to the icon to color it -->
                        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{$value['nick']}}</span>
                            <span class="info-box-number">{{$value['item_loc']}}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->


            </td>
            <td style="border:30px solid white;">
                <div class="box box-info" style="width:100%;">
                    <div class="info-box">
                        <!-- Apply any bg-* class to to the icon to color it -->
                        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{$value['sortname']}}</span>
                            <span class="info-box-number">{{$value['shopLink']}}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->

            </td>
            <td style="border:30px solid white;">
                <div class="box box-info" style="width:100%;">
                    <div class="info-box">
                        <!-- Apply any bg-* class to to the icon to color it -->
                        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{$value['yeaar']}}</span>
                            <span class="info-box-number">{{$value['detail_url']}}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->

        </tr>
        @endforeach
    </table>


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