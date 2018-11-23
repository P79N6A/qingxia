@extends('layouts.backend')

@section('new_buy_record','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@push('need_css')
<link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
@endpush

@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title','查看')
        @slot('body','')
        @slot('footer','')
    @endcomponent
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">购买记录</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header">
                <div class="col-md-12">

                        <select id="sort_id" class="form-control sort_name click_to">
                            @if($data['sort']>-1)
                                <option selected value="{{ $data['sort'] }}">{{ cache('all_sort_now')->where('id',$data['sort'])->first()->name }}</option>
                            @endif
                            <option value="-999">全部系列</option>
                        </select>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="col-md-12">
                    <div class="col-md-3">
                        <select class="select2 form-control subject_select">
                            <option value="-1">全部科目</option>
                            @foreach(config('workbook.subject_1010') as $key=>$subject)
                                @if($key>0)
                                    <option @if($key==$data['subject_id']) selected @endif value="{{ $key }}">{{ $subject }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="select2 form-control grade_select">
                            <option value="-1">全部年级</option>
                            @foreach(config('workbook.grade') as $key=>$grade)
                                @if($key>0)
                                    <option @if($key==$data['grade_id']) selected @endif value="{{ $key }}">{{ $grade }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="select2 form-control version_select">
                            <option value="-1">全部版本</option>
                            @forelse($data['all_version'] as $version)
                                <option @if($version->hasVersion->id==$data['version_id']) selected @endif value="{{ $version->hasVersion->id }}">{{ $version->hasVersion->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="select2 form-control status_select">
                            <option value="-1">全部</option>
                            @foreach($data['buy_status_id'] as $k=>$status)
                                <option @if($k==$data['status']) selected @endif value="{{ $k }}">{{$status['text']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>购买时间</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i>
                    </span>
                            <i class="fa fa-caret-down"></i>{{substr($data['start'],0,10)}}~{{substr($data['end'],0,10)}}
                        </button>
                    </div>
                </div>

                </div>
            <span>
                <a>发现：<img src="{{ asset('images/found.png') }}" alt="" /></a>
                <a>已买：<img src="{{ asset('images/check.png') }}" alt="" /></a>
                <a>已录：<img src="{{ asset('images/check_done.png') }}" alt="" /></a>
                <a>已上：<img src="{{ asset('images/ok.png') }}" alt="" /></a>
                <a class="btn btn-default" target="_blank" href="{{ route('new_buy_return',[$data['sort']]) }}">退货记录</a>
            </span>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>only_id</th>
                        <th style="width:60px;">书名</th>
                        <th>搜索量</th>
                        <th>搜索结果</th>
                        <th>练习册状态</th>
                        <th>购买信息</th>
                        <th>练习册信息</th>
                        <th>价格</th>
                        <th>操作信息</th>
                    </tr>
                    @forelse($data['all_record'] as $record)
                        <tr data-id="{{ $record->only_id }}" data-sort="{{ $record->sort }}">
                            <td>{{ $record->only_id }}</td>
                            <td>{{ $record->hasOnlyDetail?$record->hasOnlyDetail->newname:'' }}</td>
                            <td>{{ $record->searchnum }}</td>
                            <td class="search_status">
                                @if($record->status==0 && $record->has_found_count>0)
                                    <p class="text-center">
                                        <a target="_blank" href="{{ route('taobao_book_simple',[$record->hasSort->name]) }}"><img src="{{ asset('images/found.png') }}" alt="" /></a>
                                    </p>
                                    {{--<p class="hide text-center found_books" data-id="{{ $record->only_id }}">--}}
                                        {{--<a style="cursor: pointer"><img src="{{ asset('images/found.png') }}" alt=""></a></p>--}}
                                @endif
                                @if($record->status>0 && $record->status!=3)
                                    <p class="text-center">
                                        @if($record->status===1 || $record->status===6)
                                        <a target="_blank" href="{{ route('manage_new_local_test_list',[$record->sort,'local_dir',$record->hasNewBook?$record->hasNewBook->id:0]) }}"><img src="{{ asset('images/check.png') }}" alt="" /></a>
                                        @elseif($record->status===4)
                                            <a target="_blank" href="{{ route('manage_new_local_test_list',[$record->sort,'pending',$record->hasNewBook?$record->hasNewBook->id:0]) }}"><img src="{{ asset('images/check_done.png') }}" alt="" /></a>
                                        @elseif($record->status===5)
                                            <a target="_blank" href="{{ route('manage_new_local_test_list',[$record->sort,'done']) }}"><img src="{{ asset('images/ok.png') }}" alt="" /></a>
                                        @endif
                                    </p>
                                @endif

                            </td>
                            <td class="buy_status">
                                <div class="input-group">
                                    <a data-status="{{ $record->status }}" class="get_book_status input-group-addon {{ $data['buy_status_id'][$record->status]['color'] }}">{{ $data['buy_status_id'][$record->status]['text'] }}</a>
                                    @if($record->status==1)
                                        <select class="select2 form-control change_buy_status" data-id="{{ $record->only_id }}" data-type="preg">
                                            <option value="-1">改变当前状态</option>
                                            <option value="3">重新匹配</option>
                                            <option value="6">已买</option>
                                        </select>
                                    @elseif($record->status==6)
                                        <select class="select2 form-control change_buy_status" data-id="{{ $record->only_id }}" data-type="bought">
                                            <option value="-1">改变当前状态</option>
                                            <option value="3">退货并重新购买</option>
                                        </select>
                                    @else
                                    @endif
                                </div>
                                @if($record->status==6)
                                    @if($record->arrived_at)
                                        <a class="btn btn-success">该练习册已到货</a>
                                    @else
                                        <a class="btn btn-danger save_record" data-id="{{ $record->only_id }}" data-type="arrived">确认已到货?</a>
                                    @endif
                                @endif
                                <div class="pull-right">
                                    @if(count($record->hasReturn)>0)
                                        <a class="btn btn-xs btn-default" target="_blank" href="{{ route('new_buy_return',[$record->sort,$record->only_id]) }}">退货记录</a>
                                    @endif
                                </div>
                                </td>
                            <td>
                                <div class="isbn_now input-group">
                                    <a class="input-group-addon">isbn:</a>
                                    <input maxlength="17" class="now_val for_isbn_input form-control input-lg" style="font-size: 24px"  value="{{ $record->isbn?convert_isbn($record->isbn):'978-7-' }}" />
                                    <a class="input-group-addon save_record" data-type="now_isbn">保存</a>
                                </div>
                                <br>
                                <div class="shop_detail input-group">
                                    <a class="input-group-addon">店铺：</a>
                                    <input type="text" class="form-control" disabled value="@if($record->hasGoodsDetail) {{  $record->hasGoodsDetail->nick}} @endif"/>
                                    @if($record->hasGoodsDetail)
                                        <a class="input-group-addon" target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{ $record->hasGoodsDetail->shopLink }}">查看</a>
                                    @endif
                                </div>
                                <br>
                                <div class="input-group">
                                    <a class="input-group-addon">商品id</a>
                                    <input type="text" class="form-control now_val" value="{{  $record->hasGoodsDetail?$record->hasGoodsDetail->detail_url:$record->goods_id}}">
                                    @if($record->hasGoodsDetail || $record->goods_id>0)
                                    <a class="input-group-addon" target="_blank" href="https://item.taobao.com/item.htm?id={{ $record->hasGoodsDetail?$record->hasGoodsDetail->detail_url:$record->goods_id }}">查看</a>
                                    @else
                                        <a class="input-group-addon save_record" data-type="goods_id">保存</a>
                                    @endif
                                </div>
                               {{-- <br>
                                <div class="goods_price input-group">
                                    <a class="input-group-addon">价格</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->goods_price }}" />
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_price">保存</a>
                                </div>
                                <br>
                                <div class="goods_fee input-group">
                                    <a class="input-group-addon">运费</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->goods_fee }}" />
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_fee">保存</a>
                                </div>--}}
                                <br>
                                <div class="input-group">
                                    <a class="input-group-addon">购买依据</a>
                                    <select class="form-control select2 now_val">
                                        <option value="0" @if($record->goods_according_to==0) selected @endif>无</option>
                                        <option value="1" @if($record->goods_according_to==1) selected @endif>提供发票</option>
                                        <option value="2" @if($record->goods_according_to==2) selected @endif>提供收据</option>
                                    </select>
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_according_to">保存</a>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <a class="input-group-addon">练习册页数</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->book_page }}">
                                    <a class="input-group-addon btn btn-primary save_record" data-type="book_page">保存</a>
                                </div>
                                <br>
                                <div class="input-group">
                                    <a class="input-group-addon">答案页数</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->answer_page }}">
                                    <a class="input-group-addon btn btn-primary save_record" data-type="answer_page">保存</a>
                                </div>
                                <br>
                                <div class="input-group">
                                    <a class="input-group-addon">答案状态</a>
                                    <select class="form-control select2 now_val">
                                        <option value="1" @if($record->answer_status==1) selected @endif>有答案</option>
                                        <option value="2" @if($record->answer_status==2) selected @endif>部分答案</option>
                                        <option value="3" @if($record->answer_status==3) selected @endif>无答案</option>
                                    </select>
                                    <a class="input-group-addon btn btn-primary save_record" data-type="answer_status">保存</a>
                                </div>
                                <br>
                                <div>
                                    @if($record->answer_status>1)
                                        @if($record->analyze_status>0)
                                            <a>解析人：{{ \App\User::find($record->analyze_uid)->name }}</a><br>
                                            @if($record->analyze_status==1)
                                            <a>开始解析时间：{{ $record->analyze_start_at }}</a><br>
                                            @endif
                                            @if($record->analyze_status==2)
                                            <a>解析完成时间：{{ $record->analyze_end_at }}</a><br>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="g_price">
                                <div class="goods_price input-group">
                                    <a class="input-group-addon">价格</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->goods_price }}" />
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_price">保存</a>
                                </div>
                                <div class="goods_fee input-group">
                                    <a class="input-group-addon">运费</a>
                                    <input type="text" class="form-control now_val" value="{{ $record->goods_fee }}" />
                                    <a class="input-group-addon btn btn-primary save_record" data-type="goods_fee">保存</a>
                                </div>
                            </td>

                            <td>
                                <p>操作者：{{ \App\User::find($record->uid)->name }}</p>

                                <p>新增时间：{{ $record->created_at }}</p>
                                @if($record->bought_at)
                                    @if($record->bought_uid>0)
                                    <p>购买者：{{ \App\User::find($record->bought_uid)->name }}</p>
                                    @endif
                                    <p>购买时间：{{ $record->bought_at }}</p>
                                @endif
                                @if($record->arrived_at)
                                    <p>到货时间：{{ $record->arrived_at }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div>
                    {{ $data['all_record']->links() }}
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
            $('.select2').select2();
            //保存信息
            $(document).on('click','.save_record',function () {
                if(!confirm('确认操作')){
                    return false;
                }
                let now_box = $(this).parents('tr');
               let now_id = now_box.attr('data-id');
               let now_type = $(this).attr('data-type');
               let now_val = $(this).parent().find('.now_val').val();
               let sort = now_box.attr('data-sort');
               console.log(now_val);
               axios.post('{{ route('ajax_new_buy','save_record') }}',{now_id,now_type,now_val}).then(response=>{

                   if(response.data.status===1){
                       if(now_type==='goods_id'){
                           let goods_detail = response.data.data;
                           $(this).after(`<a class="input-group-addon" target="_blank" href="https://item.taobao.com/item.htm?id=${now_val}">查看</a>`);
                           now_box.find('.shop_detail').html(`<a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id=${goods_detail.shopLink}">${goods_detail.nick}</a>`);
                           now_box.find('.goods_price input').val(goods_detail.goods_price);
                           now_box.find('.goods_fee input').val(goods_detail.goods_fee);
                           now_box.find('.buy_status').html(`<div class="input-group">
                                    <a data-status="1" class="get_book_status input-group-addon bg-blue disabled">已匹配</a>
                                                                            <select class="select2 form-control change_buy_status" data-id="${now_id}" data-type="preg">
                                            <option value="-1">改变当前状态</option>
                                            <option value="3">重新匹配</option>
                                            <option value="6">已买</option>
                                        </select>
                                                                    </div>`);
                           now_box.find('.search_status').html(`<p class="text-center"><a target="_blank" href="{{ route('manage_new_local_test_list') }}/${sort}/local_dir/${goods_detail.new_id}"><img src="http://www.test2.com/images/check.png" alt=""></a>
                                                                            </p>`);
                           $(this).remove();
                       }else if(now_type=='arrived'){
                           $(this).addClass('btn-success').removeClass('btn-danger').html('该练习册已到货');
                       }
                   }else{
                        alert('操作失败');
                    }
               }).catch();
            });


            //更改状态
            $(document).on('change','.change_buy_status',function () {
                let now_only_id =  $(this).attr('data-id');
                let now_status = $(this).val();
                let now_type = $(this).attr('data-type');
                if(now_status>0){
                    axios.post('{{ route('ajax_new_buy','change_status') }}',{now_only_id,now_status,now_type}).then(response=>{
                        if(response.data.status===1){
                            let now_html = $(`.change_buy_status[data-id=${now_only_id}]`).parent();
                            if(now_status==='3'){
                                if(now_type==='preg'){
                                    now_html.html(`<a class="input-group-addon bg-yellow-active">已删除</a>`)
                                }else{
                                    now_html.html(`<a class="input-group-addon bg-yellow-active">已退货</a>`)
                                }
                            }else if(now_status==='4'){
                                now_html.html(`<a class="input-group-addon get_book_status bg-purple disabled" data-status="4">已录</a>`)
                            }else if(now_status==='6'){
                                now_html.html(`<a data-status="1" class="get_book_status input-group-addon bg-blue disabled">已买</a>
                                                                            <select class="select2 form-control change_buy_status" data-id="${now_only_id}" data-type="bought">
                                            <option value="-1">改变当前状态</option>
                                            <option value="3">退货并重新购买</option>
                                        </select>`)
                            }else{}
                        }
                    });
                }
            });

            //选择系列
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

            //切换系列
            $(document).on('change','.sort_name',function () {
                let sort_id = $('.sort_name').val();
                if (sort_id === '-999') {
                    return false;
                }
                window.open('{{ route('new_buy_record') }}' + '/' + sort_id);
            });

            //切换科目
            $(document).on('change','.subject_select,.grade_select,.version_select,.status_select',function () {
                let sort_id = $('.sort_name').val();
                let subject_id = $('.subject_select').val();
                let grade_id = $('.grade_select').val();
                let version_id = $('.version_select').val();
                let status=$('.status_select').val();
                let start=`{{$data['start']}}`;
                let end=`{{$data['end']}}`;
//                if ($(this).hasClass('subject_select') && subject_id === '-1') {
//                    return false;
//                }
//                if ($(this).hasClass('grade_select') && grade_id === '-1') {
//                    return false;
//                }
//                if ($(this).hasClass('version_select') && version_id === '-1') {
//                    return false;
//                }
                window.location.href = `{{ route('new_buy_record') }}/${sort_id}/-1/${subject_id}/${grade_id}/${version_id}/${status}/${start}/${end}`;
            });

            var book_num=$(".table").find("tr").length-1;
            var price=0;
            $(".table").find(".g_price").each(function(i){
                let goods_price=parseFloat($(this).find(".goods_price input").val());
                let goods_fee=parseFloat($(this).find(".goods_fee input").val());
                price+=(goods_price+goods_fee);
            });
        price=price.toFixed(2);

            $(".table").append(`<tr><td>总书本</td><td colspan="3">${book_num}</td><td>总价格</td><td colspan="4">${price}</td></tr>`)

            $('.for_isbn_input').bind('input propertychange', function() {

                if($(this).val().length===3){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length===5){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length>6) {
                    let now_start = $(this).val()[6];
                    if (now_start <= 3) {
                        if ($(this).val().length === 9) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start > 3 && now_start <= 5) {
                        if ($(this).val().length === 10) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '8') {
                        console.log($(this).val().length);
                        if ($(this).val().length === 11) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '9') {
                        if ($(this).val().length === 12) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    }
                    if ($(this).val().length === 15) {
                        $(this).val($(this).val() + '-');
                        this.selectionStart = this.selectionEnd = this.value.length + 1
                    }

                    if($(this).val().length===17){
                        //$('.add_isbn').click();
                        let box = $(this).parents('.book_info_box');
                        let isbn = $(this).val();

                        axios.post('{{ route('manage_new_api','isbn_check') }}',{ isbn }).then(response=>{
                            if(response.data.status===0){
                                $(this).addClass('bg-red').removeClass('bg-blue')
                            }else{
                                $(this).addClass('bg-blue').removeClass('bg-red')
                            }
                        })

                        //get_related_sort
                    }

                }
            });
        });

    </script>
@endpush


@push('need_js')
<script  src="/adminlte/plugins/daterangepicker/moment.js"></script>
<script  src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
<script>
    $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                    '今天'       : [moment(), moment()],
                    '昨天'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '最近7天' : [moment().subtract(6, 'days'), moment()],
                    '最近30天': [moment().subtract(29, 'days'), moment()],
                    '本月'  : [moment().startOf('month'), moment().endOf('month')],
                    '上个月'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens : 'right', //日期选择框的弹出位置
                startDate: moment("{{$data['start']}}").utc().subtract(-1,'days'),
                endDate  : moment("{{$data['end']}}").utc().subtract(-1,'days'),
                format : 'YYYY/MM/DD', //控件中from和to 显示的日期格式
                locale : {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '起始时间',
                    toLabel : '结束时间',
                    customRangeLabel : '自定义',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                        '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                    firstDay : 1
                }
            },
            function (start, end) {
                let sort_id = $('.sort_name').val();
                let subject_id = $('.subject_select').val();
                let grade_id = $('.grade_select').val();
                let version_id = $('.version_select').val();
                let status=$('.status_select').val();

                $('#daterange-btn span').html(start.format('YYYY/MM/DD') + ' ~ ' + end.format('YYYY/MM/DD'));
                window.location.href = `{{ route('new_buy_record') }}/${sort_id}/-1/${subject_id}/${grade_id}/${version_id}/${status}/${start.format("YYYY-MM-DD")}/${end.format("YYYY-MM-DD")}`;
                //window.location.href = '{{ route('user_feedback_status') }}/'+start.format("YYYY-MM-DD")+"/"+end.format("YYYY-MM-DD");
            }
    )
</script>
@endpush