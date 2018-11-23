@if(isset($item) && !empty($item))
<div class="box box-widget">
    <div class="box-header with-border">
        <div class="user-block">
            <span style="float: left;">
                <!--<a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item->shopLink}}">{{$item->nick}}</a>-->
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">{{$item->nick}}
                        <span class="fa fa-caret-down"></span></button>
                      <ul class="dropdown-menu">
                        <li><a grade="{{$item->nick}}" class="gotolist" href="{{route('shopList',['shopId'=>$item->shopLink])}}">店铺全部书</a></li>
                        <li><a target="_blank" href="https://store.taobao.com/shop/view_shop.htm?user_number_id={{$item->shopLink}}">淘宝店铺</a></li>
                      </ul>
                    <button value="{{$item->shopLink}}" widget="shopTop" type="button" class="shopTop btn @if($item->shopTop >0) btn-info @else btn-default @endif ">店铺顶</button>
                    <button value="{{$item->id}}" widget="bookTop" type="button" class="shopTop btn @if($item->bookTop >0) btn-info @else btn-default @endif">本书顶</button>
                    <!--<button value="{{$item->shopLink}}" widget="3" type="button" class="shopTop btn @if($item->shopTop >=3) btn-info @else btn-default @endif">3</button>-->
                </div>
                <!--
                <input type="checkbox" @if($item->shopTop) checked @endif value="{{$item->shopLink}}" />-->
            </span>
        </div>
        <!-- /.user-block -->
        <div class="box-tools" style="z-index:9999;">
            <button type="button" class="btn btn-box-tool removeid" removeid="{{$item->detail_url}}"><i class="fa fa-times"></i></button>
        </div>
        <!-- /.box-tools -->
    </div>
    <div class="box-body">

        <div class="media">
            <div class="media-body">
                <a target="_blank" href="https://item.taobao.com/item.htm?id={{$item->detail_url}}" class="ad-click-event" >
                    <img src="{{$item->pic_url}}_230x230.jpg_.webp" alt="Now UI Kit" class="media-object" style="height: 230px; max-width: 230px; border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                </a>
                <p style=" text-align: center;  margin-top: 5px; height: 50px;font-size: 13px;">
                    {{$item->raw_title}}
                </p>


                <span style="color: #F40;font-weight: 700;" class="pull-right text-muted">￥{{$item->view_price}}
                    @if($item->view_fee == 0)
                        <div style="background: url(//img.alicdn.com/tps/i3/TB1bh5IMpXXXXacaXXXrG06ZpXX-316-272.png);background-position: -42px -139px;width: 27px;height: 14px; float: right; margin-top: 2px;"></div>
                        @else
                        <strong>邮费:￥{{$item->view_fee}}</strong>
                    @endif
                </span>
            </div>
        </div>
        <ul class="nav navbar-nav">
            <li>
                <a class="gotolist" grade="{{$grade}}年级" href="{{route('taobao_getBookList',['keyword'=>$keyword,'subject'=>$subject,'grade'=>$grade,'contain'=>$contain,'remove'=>$remove?:''])}}">查看全部</a>
            </li>
            @php $record = getRecord($item->detail_url); @endphp
            @if(isset($record) && count($record) > 0)
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"  href="#"> 加入待购买</a>
                <ul class="dropdown-menu" role="menu">

                   @forelse($record as $citem)
                       <li>
                        <a class="addCart" href="{{route('taobao_addChart',['goodsId'=>$item->detail_url,'jId'=>$citem->hasOnly->id])}}">{{$citem->hasOnly->newname}}</a>
                       </li>
                   @endforeach
                </ul>
            </li>
                @else
                <li class="dropdown">
                    <a class="dropdown-toggle get_need_buy_books" data-goods-id="{{ $item->detail_url }}" data-sort="{{ $sort }}" data-subject="{{ $subject }}" data-grade="{{ $grade }}" data-toggle="dropdown" aria-expanded="false"> 加入待购买</a>
                    <ul class="dropdown-menu need_buy_books" role="menu">

                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
    @else
    <h3><i class="fa fa-warning text-yellow"></i> 没有!</h3>
    @endif


