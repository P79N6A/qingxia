@extends('layouts.teacher')

@section('header') 答题 @endsection

@push('need_css')
    <link rel="stylesheet" href="{{ asset('css/teacher/jquery-weui.css') }}">
@endpush

@section('content')
<div class="weui_tab zijin_tab">
    <div class="weui_navbar">
        <a href="pressed_about.html" class="weui_navbar_item zijin_item">
            <img class="re" src="images/xiaoxi.png" alt=""/>
            <i>99</i>
        </a>
        <div class="weui_navbar_item weui_bar_item_on">
            全部
            <p>6672</p>
        </div>
        <div class="weui_navbar_item">
            数学
            <p>235</p>
        </div>
        <div class="weui_navbar_item">
            语文
            <p>678</p>
        </div>
        <div class="weui_navbar_item">
            英语
            <p>67</p>
        </div>
    </div>
    <div class="weui_tab_bd">
    </div>
</div>
<div class="weui_cells weui_cells_access zijin_clelrt">
    <a href="title_details.html">
        <div class="weui_cell">
            <div class="weui_cell_hd"><img src="{{ asset('images/teacher/touxiang.png') }}" alt=""></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p class="user_names">学生姓名</p>
                <p class="time">2016.03.30 19:00</p>
            </div>
            <div class="weui_cell_biaoti_homework">六年级 数学</div>
        </div>
        <div class="topic">
            <i></i>
            <img src="{{ asset('images/teacher/tili.png') }}" alt=""/>
        </div>
        <p class="text">題型文字描述題型文字描述題型文字描述題型文字描述</p>
    </a>
    <div class="bd answer_input_box">
        <input type="button" value="开始讲解" onclick="c1()" class="weui_btn_primary answer_input jj-red-btn">
    </div>
</div>

<div class="weui_cells weui_cells_access zijin_clelrt">
    <a href="title_details.html">
        <div class="weui_cell">
            <div class="weui_cell_hd"><img src="{{ asset('images/teacher/touxiang.png') }}" alt=""></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p class="user_name">学生姓名</p>
                <p class="time">2016.03.30 19:00</p>
            </div>
            <div class="weui_cell_biaoti">六年级 数学</div>
        </div>
        <div class="topic">
            <img src="{{ asset('images/teacher/tili.png') }}" alt=""/>

        </div>
        <p class="text">題型文字描述題型文字描述題型文字描述題型文字描述</p>
        <div class="bd">
            <a href="javascript:;" onclick="c2();" class="weui_btn weui_btn_primary ">开始解题</a>
        </div>
    </a>
</div>
    <div class='weui_mask'></div>
    <div class='weui_dialog'>
        <div class="clsoe"><i class="weui-icon-cancel"></i></div>
        <div class='weui_dialog_hd'><span>确定开始解题？</span></div>
        <div class='weui_dialog_ft'>
            <a href='javascript:;' class='weui_btn_dialog default'>检查作业</a><a href='javascript:;' class='weui_btn_dialog primary'>开始解题</a>
        </div>
    </div>
@endsection

@push('need_js')
<script src="{{ asset('js/teacher/jquery-2.1.4.js') }}" type="text/javascript" charset="utf-8"></script>
<script>
    $(".jj-red-btn").click(function () {
        $(".weui_mask").addClass("show");
        $(".weui_dialog").addClass("zijin_dalog");
    })
    $(".clsoe,.weui_mask").click(function () {
        $(".weui_mask").removeClass("show");
        $(".weui_dialog").removeClass("zijin_dalog");
    })
</script>
@endpush