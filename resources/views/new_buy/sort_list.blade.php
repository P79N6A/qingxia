@extends('layouts.backend')

@section('new_buy_again','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <div id="isbn_model" class="hide" style="position: fixed;z-index: 998;width: 80%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">Isbn信息
                    <span class="close close_modal">&times;</span></div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>


    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">本地答案整理</li>
        </ol>
    </section>
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
            <div class="col-md-12">
            <div class="input-group col-md-6">
                <select id="sort_id" class="form-control sort_name click_to">
                    <option value="-999">全部系列</option>
                </select>
                <a class="input-group-addon btn btn-primary" id="select_sort">查看</a>
                <a class="input-group-addon btn btn-primary" id="add_sort">新增已有系列至待购买</a>
            </div>
            <div class="input-group col-md-6">
                <input type="text" class="form-control" id="sort_name_now">
                <a class="input-group-addon btn btn-primary" id="create_sort">创建系列并新增至待购买</a>
            </div>
            <div class="input-group  col-md-6">
                <select class="select2 form-control volume_change">
                    <option value="1" @if(cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id===1) selected @endif>上册</option>
                    <option value="2" @if(cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id===2) selected @endif>下册</option>
                </select>
            </div>
            </div>
        </div>
        <div class="col-md-12">
        <div class="input-group  col-md-2">
            <select class="select2 form-control order_change">
                <option value="sort" @if($data['order']=='sort') selected @endif>按系列排序</option>
                <option value="find" @if($data['order']=='find') selected @endif>按发现排序</option>
            </select>
        </div>

        <div class="input-group  col-md-4" style="float: right;">
            <input type="text" class="form-control" id="isbn"  placeholder="输入Isbn">
             <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat" id="isbn_info">查看信息</button>
             </span>
        </div>
        </div>
        <div class="box-body">
            <div class="col-md-12">
                   {{-- @forelse($data['all_sort'] as $key=>$sort)
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4 style="font-size: 24px">
                                    <small>待买</small><strong>{{ count($sort->hasOnlyBooks) }}</strong>&nbsp;&nbsp;
                                    <small>发现</small><strong>{{ $data['hasGoods'][$key] }}</strong>
                                    <small>已买</small><strong>{{ $data['hasBought'][$key] }}</strong>
                                </h4>
                                <p>{{ $sort->sort_name }}</p>
                            </div>
                            <a target="_blank" href="{{ route('new_buy_sort_list',[$sort->sort_id]) }}" class="small-box-footer">立即处理<i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endforeach--}}

                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>id</th>
                                <th>发现</th>
                                <th>系列名称</th>
                                <th>上册</th>
                                <th>下册</th>
                                <th>全一册</th>
                                <th>总计</th>
                                <th>待买</th>
                                <th>发现</th>
                                <th>发现2</th>
                                <th>拼多多发现</th>
                                <th>已买</th>
                                <th>按年级学科发现</th>
                                <th>整理</th>
                                <th>购买</th>
                                <th>录入</th>
                            </tr>
                            @foreach($data['all_sort'] as $key=>$sort)
                            <tr>
                                <td>{{ $sort->id }}</td>
                                <td><a class="btn btn-xs @if($sort->has_kd==1) btn-primary @else btn-default @endif find_new" data-type="kd" data-id="{{ $sort->id }}">快对发现</a><a class="btn btn-xs @if($sort->has_hd==1) btn-primary @else btn-default @endif find_new" data-type="hd" data-id="{{ $sort->id }}">互动发现</a></td>
                                <td>{{ $sort->sort_name }}</td>
                                <td>{{$data['has_shang'][$key]}}</td>
                                <td>{{$data['has_xia'][$key]}}</td>
                                <td>{{$data['has_quan'][$key]}}</td>
                                <td>{{ count($sort->hasOnlyBooks) + $data['hasBought'][$key] }}</td>
                                <td>{{ count($sort->hasOnlyBooks) }}</td>
                                <td>@if($data['order']=='find')
                                        <a type="button" target="faxian" href="{{ route('taobao_search',[$sort->sort_name,0,$sort->sort_id,1,1,1,1]) }}" {{--class="btn btn-block btn-primary"--}}>{{ $sort->find_num }}</a>
                                    @else
                                        <a type="button" target="faxian" href="{{ route('taobao_search',[$sort->sort_name,0,$sort->sort_id,1,1,1,1]) }}" >{{ $sort->hasFindBook->find_num }}</a>
                                    @endif
                                </td>
                                <td>@if($data['order']=='find')
                                        <a type="button" target="faxian" href="{{ route('taobao_search',[$sort->sort_name,0,$sort->sort_id,1,1,1,2]) }}" >{{ $sort->find_num_new }}</a>
                                    @else
                                        <a type="button" target="faxian" href="{{ route('taobao_search',[$sort->sort_name,0,$sort->sort_id,1,1,1,2]) }}" >{{ $sort->hasFindBook->find_num_new }}</a>
                                    @endif
                                </td>
                                <td>@if($data['order']=='find')
                                        <a type="button" target="faxian" href="{{ route('taobao_search',[$sort->sort_name,2,$sort->sort_id,1,1,1,2]) }}" >{{ $sort->find_pinduoduo }}</a>
                                    @else
                                        <a type="button" target="faxian" href="{{ route('taobao_search',[$sort->sort_name,2,$sort->sort_id,1,1,1,2]) }}" >{{ $sort->hasFindBook->find_pinduoduo }}</a>
                                    @endif
                                </td>
                                <td>{{ $data['hasBought'][$key] }}</td>
                                <td>
                                    @if($sort->find_book>0)
                                    <a type="button" target="find" href="{{ route('goods_list',[$sort->sort_id]) }}" class="btn btn-block btn-primary">发现</a>
                                    @else
                                        未发现
                                    @endif
                                </td>
                                <td><a type="button" target="zhengli" href="{{ route('new_buy_sort_list',[$sort->sort_id]) }}" class="btn btn-block btn-primary">整理</a></td>
                                <td>
                                    <a type="button" target="goumai" href="{{ route('taobao_search',[$sort->sort_name,0,$sort->sort_id,1]) }}" class="btn btn-block btn-primary">购买</a>
                                </td>
                                <td><a type="button" target="luru" href="{{ route('manage_new_local_test_list',[$sort->sort_id,'local_dir']) }}" class="btn btn-block btn-primary">录入</a></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

            </div>
            <div>
                {{ $data['all_sort']->links() }}
            </div>
        </div>
    </div>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
            $('.order_change').change(function(){ //选择排序方式
                var order=$(this).val();
                window.location.href=`{{ route('new_buy_index') }}/${order}`;
            });


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
                var  sort_id = $('.sort_name').val();
                if (sort_id === '-999') {
                    return false;
                }
                window.open('{{ route('new_buy_sort_list') }}' + '/' + sort_id);
            });

            //新增系列
            $('#add_sort').click(function () {
                var sort_id = $('.sort_name').val();

                if (sort_id === '-999') {
                    return false;
                }
                var sort_name = $('.sort_name').select2('data')[0].name;
                axios.post('{{ route('ajax_new_buy','add_new_sort') }}',{sort_id}).then(response=>{
                    if(response.data.status===1){
                        window.open(`http://www.test2.com/new_buy/list/${sort_id}`);
                    //     $('.col-md-12').prepend(`<div class="col-lg-3 col-xs-6">
                    //     <div class="small-box bg-aqua">
                    //         <div class="inner">
                    //             <h3>${sort_name}</h3>
                    //         </div>
                    //         <a target="_blank" href="http://www.test2.com/new_buy/list/${sort_id}" class="small-box-footer">立即处理<i class="fa fa-arrow-circle-right"></i></a>
                    //     </div>
                    // </div>`)
                    }else{
                        alert('新增失败');
                        //window.open(`http://www.test2.com/new_buy/list/${sort_id}`);
                    }
                }).catch(function () {

                });
            });
            $('.volume_change').change(function () {
                var volumes_id = $(this).val();
                axios.post('{{ route('ajax_new_buy','change_volume') }}',{volumes_id}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                })
            });



            $('#isbn_info').click(function(){
                var isbn=$('#isbn').val();
                if(isbn==''){alert('未输入isbn');return;}
                axios.post('{{ route('show_isbninfo') }}',{isbn}).then(response=>{
                    if(response.data.status===1){
                       $('#isbn_model').removeClass('hide');
                       $('#isbn_model').find('.modal-body').html(response.data.des);
                    }else{
                        alert('未找到！');
                    }
                })
            });

            $('.close_modal').click(function(){
                $('#isbn_model').addClass('hide');
            });


	    $('#isbn_info').click(function(){
                var isbn=$('#isbn').val();
                if(isbn==''){alert('未输入isbn');return;}
                axios.post('{{ route('show_isbninfo') }}',{isbn}).then(response=>{
                    if(response.data.status===1){
                       $('#isbn_model').removeClass('hide');
                       $('#isbn_model').find('.modal-body').html(response.data.des);
                    }else{
                        alert('未找到！');
                    }
                })
            });

            $('.close_modal').click(function(){
                $('#isbn_model').addClass('hide');
            });
	    
            $('.find_new').click(function () {
                let data_id = $(this).attr('data-id');
                let data_type = $(this).attr('data-type');
                axios.post('{{ route('ajax_new_buy','find_new') }}',{data_id,data_type}).then(response=>{
                    if(response.data.status===1){
                        if($(this).hasClass('btn-default')){
                            $(this).removeClass('btn-default').addClass('btn-primary');
                        }else{
                            $(this).removeClass('btn-primary').addClass('btn-default');
                        }

                    }
                })
            })


            //创建系列
            $('#create_sort').click(function () {
                if(!confirm('确认创建新系列')){
                    return false;
                }
                let sort_name = $('#sort_name_now').val();
                axios.post('{{ route('ajax_new_buy','create_new_sort') }}',{'sort_name':sort_name}).then(response=>{
                    if(response.data.status===1){
                        window.location.href = `http://www.test2.com/new_buy/list/${response.data.data.new_id}`;
                    }else{
                        alert('创建失败');
                    }
                })
            })
        });
    </script>
@endpush