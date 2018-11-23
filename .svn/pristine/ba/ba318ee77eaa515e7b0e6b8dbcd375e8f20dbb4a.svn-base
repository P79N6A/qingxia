@extends('layouts.backend')

@section('book_buy_index','active')

@push('need_css')
<style>
    .single_book_box img{
        min-height: 214px;
        max-height: 214px;
    }
    .buy_list{
        height:400px;
        overflow-y: scroll;
    }
    .notifications-menu{
        list-style: none;
        position: fixed;
        right: 50px;
        z-index:999
    }
</style>
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">练习册购买管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 练习册购买管理</h3></div>
            <div class="box-body">
                <li class="dropdown notifications-menu pull-right" >
                <a class="dropdown-toggle btn btn-danger" data-toggle="dropdown">购买篮</a>
                <ul class="dropdown-menu">
                    <a class="btn btn-primary" id="buy_all">一键购买</a>
                    <a class="btn btn-primary" id="clear_all">清空</a>
                    <div class="buy_list">

                    </div>
                </ul>
                </li>
                <div class="form-group" id="search_box">
                </div>
                <hr>
            </div>
        </div>
    </section>
@endsection

@push('need_js')

<script src="{{ asset('js/get_search.js').'?t='.time() }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>

<script>
    var token = '{{ csrf_token() }}';
    var lazy = new LazyLoad();
    var version = {!! $data['version'] !!};
    console.log(version);
    var version_all = [];
    for(var i=0;i<80;i++){
        if(version[i]!==undefined){
            version_all[version[i].id] = version[i].name;
        }else{
            version_all[i] = undefined;
        }
    }
    console.log(version_all);

    boSearch.open();
    function add_to_buy(now) {
        let single_box = $(now).parents('.single_book_box');
        let book_id  = $(single_box).attr('data-id');
        axios.post('{{ route('check_it') }}',{book_id}).then(response=>{
            if(response.data.status===0){
                alert(response.data.msg);
            }else{
                single_box.find('.bianji').remove();
                let now_buy_item = single_box.html();
                $('.buy_list').append('<div class="now_add_box" data-id="'+book_id+'" style="background-color:#FBFAD6;width:400px;float:left;padding: 5px; margin: 5px;">'+now_buy_item+'<a class="del_this_book btn btn-xs btn-danger">删除此书</a></div>');
                $(this).parent().remove()
            }
        }).catch(function (error) {
            console.log(error)
        });
    }



    $(function () {
        $(document).on('click','.buy_list',function () {

        });

        $(document).on('click','#clear_all',function () {
           $('.buy_list').html('');
        });
        $(document).on('click','#buy_all',function () {
            var ids = ''
            $('.now_add_box').each(function () {
                ids += $(this).attr('data-id')+','
            });
            var o = {
                _token:token,
                ids:ids
            };
            $.post('{{ route('api_book_buy_add') }}',o,function (s) {
                if(s.status==1){
                    window.location.href='{{ route('book_buy_wait') }}';
                }else{
                    alert(s.msg);
                }
            })
        });

        //删除此书
        $(document).on('click','.del_this_book',function () {
            $(this).parents('.now_add_box').remove();
            event.preventDefault();
        });

    })
    
</script>
@endpush