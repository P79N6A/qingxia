
@if(isset($item) && count($item) > 0)
    <div class="box box-widget">
        <div class="box-header with-border">
            <div class="user-block">
                <span style="float: left;">
                    <a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item['attrs']['detail_url']}}">{{$item['attrs']['nick']}}</a><input type="checkbox" @if($item['attrs']['shoptop']) checked @endif value="{{$item['attrs']['shoplink']}}" /></span>
            </div>
            <!-- /.user-block -->
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool removeid" removeid="{{$item['attrs']['detail_url']}}"><i class="fa fa-times"></i></button>
            </div>
            <!-- /.box-tools -->
        </div>
        <div class="box-body">
            <div class="media">
                <div class="media-body">
                    <a target="_blank" href="https://item.taobao.com/item.htm?id={{$item['attrs']['detail_url']}}" class="ad-click-event" >
                        <img src="{{$item['attrs']['pic_url']}}_230x230.jpg_.webp" alt="Now UI Kit" class="media-object" style="height: 230px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                    </a>
                    <p style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;">
                        {!! $item['attrs']['raw_title'] !!}
                    </p>

                    <a class="gotolist" grade="{{$grade}}" href="{{route('taobao_getBookList',['keyword'=>$keyword,'subject'=>$subject,'grade'=>$grade,'contain'=>$contain,'remove'=>$remove?:''])}}">查看全部</a>
                    <span style="color: #F40;font-weight: 700;" class="pull-right text-muted">￥{{$item['attrs']['view_price']}}
                        @if($item['attrs']['view_fee'] == 0)
                            <div style="background: url(//img.alicdn.com/tps/i3/TB1bh5IMpXXXXacaXXXrG06ZpXX-316-272.png);background-position: -42px -139px;width: 27px;height: 14px; float: right; margin-top: 2px;"></div>
                        @else
                            <strong>邮费:￥{{$item['attrs']['view_fee']}}</strong>
                        @endif
                </span>
                </div>
            </div>
        </div>
    </div>
@else
    <h3><i class="fa fa-warning text-yellow"></i> 没有!</h3>
@endif
