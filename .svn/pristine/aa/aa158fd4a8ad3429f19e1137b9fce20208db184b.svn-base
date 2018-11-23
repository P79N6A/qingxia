
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
            <div class="col-xs-12">
                <div id="box-header" class="box-header">
                    <div class="col-sm-3">
                        <div class="input-group">
                                <span class="input-group-addon">
                                   关键词
                                </span>
                            <input type="text" class="form-control" id="keyword"  value="">
                             <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="search">搜</button>
                             </span>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group"><label class="input-group-addon">系列</label>
                            <select data-name="sort" class="form-control sort_name select2">
                                    <option value="" selected>{{isset($re['sort_name'])?$re['sort_name']->name:''}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group">
                                <span class="input-group-addon">
                                   排除词
                                </span>
                            <input type="text" class="form-control" id="remove">
                             <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat remove_word">确认</button>
                             </span>
                        </div>
                    </div>
                   @if($re['type']!=2)
                    <div class="col-sm-3">
                        <a type="button" class="btn btn-primary @if($re['type']==0) active @endif" href="{{ route("taobao_search",[$re['keyword'],0,$re['sort_id'],$re['is_read'],$re['v_status'],$re['remove_isbn'],$re['has_year'],$re['start'],$re['end']]) }}">不按系列搜索</a>
                        <a type="button" class="btn btn-primary @if($re['type']==1) active @endif" href="{{ route("taobao_search",[$re['keyword'],1,$re['sort_id'],$re['is_read'],$re['v_status'],$re['remove_isbn'],$re['has_year'],$re['start'],$re['end']]) }}">按系列搜索</a>
                    </div>
                    @endif
                </div>
                <div class="col-sm-2">
                   {{-- <input type="checkbox" onclick="swapCheck()" />全选--}}
                    <button type="button" class="btn btn-info btn-flat is_read">设为已读</button>
                </div>
                <div class="col-sm-2">
                    <a type="button" class="btn btn-primary @if($re['is_read']==0) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],0,$re['v_status'],$re['remove_isbn'],$re['has_year'],$re['start'],$re['end']]) }}">查看全部</a>
                    <a type="button" class="btn btn-primary @if($re['is_read']==1) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],1,$re['v_status'],$re['remove_isbn'],$re['has_year'],$re['start'],$re['end']]) }}">只看未读</a>
                </div>
                @if($re['type']!=2)
                <div class="col-sm-2">
                    <a type="button" class="btn btn-primary @if($re['v_status']==0) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],0,$re['remove_isbn'],$re['has_year'],$re['start'],$re['end']]) }}">全部</a>
                    <a type="button" class="btn btn-primary @if($re['v_status']==1) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],1,$re['remove_isbn'],$re['has_year'],$re['start'],$re['end']]) }}">发现</a>
                </div>

                <div class="col-sm-2">
                    <a type="button" class="btn btn-primary @if($re['remove_isbn']==0) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],$re['v_status'],0,$re['has_year'],$re['start'],$re['end']]) }}">全部</a>
                    <a type="button" class="btn btn-primary @if($re['remove_isbn']==1) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],$re['v_status'],1,$re['has_year'],$re['start'],$re['end']]) }}">排除已买</a>
                </div>

                <div class="col-sm-2">
                    <a type="button" class="btn btn-primary @if($re['has_year']==0) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],$re['v_status'],$re['remove_isbn'],0,$re['start'],$re['end']]) }}">全部</a>
                    <a type="button" class="btn btn-primary @if($re['has_year']==1) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],$re['v_status'],$re['remove_isbn'],1,$re['start'],$re['end']]) }}">只看有2018</a>
                    <a type="button" class="btn btn-primary @if($re['has_year']==2) active @endif" href="{{ route("taobao_search",[$re['keyword'],$re['type'],$re['sort_id'],$re['is_read'],$re['v_status'],$re['remove_isbn'],2,$re['start'],$re['end']]) }}">只看有2018或没有此isbn的</a>
                </div>
                @endif
                <div class="form-group">
                    <label>商品上架时间筛选</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                      <i class="fa fa-calendar"></i>
                    </span>
                            <i class="fa fa-caret-down"></i>{{$re['start']}}~{{$re['end']}}
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    @if(!empty($re['remove_words'][0]))
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <div>此系列的排除词</div>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body no-padding" style="display: block;">
                                @foreach($re['remove_words'] as $v)
                                    <button class="btn btn-xs btn-primary role-now-btn del_remove" data-remove="{{ $v->remove }}"><strong class="role_about">{{ $v->remove }}</strong><i class="fa fa-times del_this"></i></button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <ul class="list" style="overflow: hidden;padding-left:21px;list-style: none;">
                            <div class="box box-widget">
                                <div class="box-body">
                                    <div class="media">
                                        @if($re['type']<2)
                                        @foreach($re['list'] as $v)
                                        <li style="height:410px;width:250px;float: left;">
                                            <div class="media-body" >
                                                <a target="_blank" href="https://item.taobao.com/item.htm?id={{ $v['detail_url'] }}" class="ad-click-event">
                                                    <img src="{{ $v['pic_url'] }}" alt="Now UI Kit" class="media-object" style="height: 240px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                </a>
                                                <div class="info" style="width: 230px;">
                                                   {{-- @if( $v['is_read']==0)
                                                        <input type="checkbox" class="clear" value="{{ $v['id'] }}">
                                                    @else
                                                        已标记已读
                                                    @endif--}}
                                                    <div style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;overflow:hidden" class="raw_title">
                                                        {!! $v['raw_title'] !!}
                                                    </div>
                                                    <div>店铺:<a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{ $v['shoplink'][0] }}">{{ $v['nick'] }}</a>
                                                       @if($re['type']==0 && $re['sort_id']>0)
                                                        @if(!in_array($v['shoplink'][0],$re['shopLink_arr'])  )
                                                            <a type="button" data-shopLink="{{ $v['shoplink'][0] }}" class="btn btn-primary btn-sm addshop" >添加该店铺</a>
                                                        @endif
                                                       @endif
                                                    </div>
                                                    <div>
                                                        ISBN:
                                                        <a data-toggle="modal" data-target="#myModal" class="isbn_info" data-isbn="{{ $v['isbn'] }}">
                                                            {{ $v['isbn'] }}
                                                        </a>
                                                    </div>
                                                    <div>
                                                        商品图片上架时间:@if($v['pic_addtime']>1){{ date('Y-m-d',$v['pic_addtime']) }}@endif
                                                    </div>
                                                    <div style="color: #F40;font-weight: 700;">￥{{ $v['view_price'] }}
                                                        <strong>邮费:￥{{ $v['view_fee'] }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        @elseif($re['type']==2)
                                            @foreach($re['list2'] as $v)
                                                <li style="height:410px;width:250px;float: left;">
                                                    <div class="media-body" >
                                                        <a target="_blank" href="http://yangkeduo.com/goods.html?goods_id={{ $v['detail_url'] }}" class="ad-click-event">
                                                            <img src="{{ $v['pic_url'] }}" alt="Now UI Kit" class="media-object" style="height: 240px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                        </a>
                                                        <div class="info" style="width: 230px;">
                                                            <div style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;overflow:hidden" class="raw_title">
                                                                {!! $v['raw_title'] !!}
                                                            </div>
                                                            <div>
                                                                商品图片上架时间:@if($v['pic_addtime']>1){{ date('Y-m-d',$v['pic_addtime']) }}@endif
                                                            </div>
                                                            <div style="color: #F40;font-weight: 700;">￥{{ $v['view_price'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif


                                    </div>
                                </div>
                            </div>

                    </ul>
                </div>
            </div>
        </div>
        <div>
            {{ $re['paginator']->links() }}
        </div>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Isbn描述</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('need_js')

<script src="/adminlte/plugins/select2/select2.full.min.js"></script>

<script>
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

    $(function(){
          $('.isbn_info').click(function(){
             var isbn=$(this).attr('data-isbn');
              axios.post('{{ route('show_isbninfo') }}',{isbn}).then(response=>{
                  if(response.data.status===1){
                    $('#myModal').find('.modal-body').html(response.data.des);
                  }
          })
          });

       /* $(".is_read").click(function(){
            var checks = $("input[type='checkbox']:checked");
            var checkData = new Array();
            checks.each(function(){
                if($(this).val()!='on'){
                    checkData.push($(this).val());
                }
            });
            if(checkData.length<=0){alert('无选中！');return;}
            var sort_id={{ $re['sort_id'] }};
            var keyword='{{ $re['keyword'] }}';
            axios.post('{{ route('is_read') }}',{checkData,sort_id,keyword}).then(response=>{
                if(response.data.status===1){
                    window.location.href=`{{ route('taobao_search') }}/${keyword}/{{ $re['type'] }}/${sort_id}/1/{{ $re['v_status'] }}/{{ $re['remove_isbn'] }}/{{ $re['has_year'] }}/{{ $re['start'] }}/{{ $re['end'] }}`;
             }
            })

        });*/

        $(".is_read").click(function(){
            var sort_id={{ $re['sort_id'] }};
            var sort_name='{{ $re['sort_name']->name }}';
            if(!confirm('确认要将'+sort_name+'系列标记为已读吗?')){
                return false;
            }

            axios.post('{{ route('is_read') }}',{sort_id}).then(response=>{
                if(response.data.status===1){
                //window.location.href=`{{ route('taobao_search') }}/{{ $re['keyword'] }}/{{ $re['type'] }}/${sort_id}/1/{{ $re['v_status'] }}/{{ $re['remove_isbn'] }}/{{ $re['has_year'] }}/{{ $re['start'] }}/{{ $re['end'] }}`;
                window.location.href=`{{ route('new_buy_index') }}`;
            }
        })

        });
    })
</script>
<script>
    $(function(){
        $('.select2').select2();
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

        $(".sort_name").change(function(){
            var sort_id=$(this).val();
            var keyword = $("#keyword").val();
            window.location.href=`{{ route('taobao_search') }}/${keyword}/{{ $re['type'] }}/${sort_id}/{{ $re['is_read'] }}/{{ $re['v_status'] }}/{{ $re['remove_isbn'] }}/{{ $re['has_year'] }}/{{ $re['start'] }}/{{ $re['end'] }}`;
        });

        $("#keyword").val('{{ $re['keyword'] }}');
        $("#search").click(function () {
            var keyword = $("#keyword").val();
            window.location.href=`{{ route('taobao_search') }}/${keyword}/{{ $re['type'] }}/{{ $re['sort_id'] }}/{{ $re['is_read'] }}/{{ $re['v_status'] }}/{{ $re['remove_isbn'] }}/{{ $re['has_year'] }}/{{ $re['start'] }}/{{ $re['end'] }}`;
        });
        $('#keyword').bind('keypress',function(event){
            if(event.keyCode == "13")
            {
                $("#search").click();
            }
        });

        $(".addshop").click(function(){
            var shopLink=$(this).attr('data-shopLink');
            var sort_id ={{ $re['sort_id'] }};
            if(sort_id<0) {alert('请先选择系列！');return;}
            var sort_name='{{ $re['sort_name']->name }}';
            if(!confirm('确认要将该店铺添加到'+sort_name+'?')){
                return false;
            }
            axios.post('{{ route('shopLinkBySort') }}',{shopLink,sort_id}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                }
            })
        });

        $(".remove_word").click(function(){
            var sort_name='{{ $re['sort_name']->name }}';
            var remove_word=$("#remove").val();
            if(sort_name=='nosort') {alert('请先选择系列！');return;}
            if(remove_word=='') {alert('排除词不能为空！');return;}
            axios.post('{{ route('remove_word') }}',{sort_name,remove_word}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                }
            })
        });

        $(".del_remove").click(function(){
            var sort_name='{{ $re['sort_name']->name }}';
            var remove_word= $(this).attr('data-remove');
            if(!confirm('确认要将该排除词从'+sort_name+'删除吗?')){
                return false;
            }
            axios.post('{{ route('del_remove') }}',{sort_name,remove_word}).then(response=>{
                if(response.data.status===1){
                    window.location.reload();
                 }
            })
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
                startDate: moment("{{$re['start']}}").utc().subtract(-1,'days'),
                endDate  : moment("{{$re['end']}}").utc().subtract(-1,'days'),
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
                $('#daterange-btn span').html(start.format('YYYY/MM/DD') + ' ~ ' + end.format('YYYY/MM/DD'));
                window.location.href = `{{ route('taobao_search') }}/{{$re['keyword']}}/{{$re['type']}}/{{$re['sort_id']}}/{{$re['is_read']}}/{{$re['v_status']}}/{{ $re['remove_isbn'] }}/{{ $re['has_year'] }}/${start.format("YYYY-MM-DD")}/${end.format("YYYY-MM-DD")}`;
            }
    )
</script>
@endpush
