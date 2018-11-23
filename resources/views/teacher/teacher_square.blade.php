@extends('layouts.teacher')

@section('header')
		答题
@endsection

@push('need_css')
		<link rel="stylesheet" href="{{ asset('css/teacher/jquery-weui.css') }}" />
@endpush

@section('content')
		{{--<div class="notification notification-in">--}}
 {{--<a href="{{ route('teacher_pressed_about') }}">--}}
  {{--<div class="notification-inner">--}}
    {{--<div class="notification-media"></div>--}}
    {{--<div class="notification-content">--}}
      {{--<div class="notification-title zijin_biaoti">子衿同学对你回答提出了追问</div>--}}
      {{--<div class="notification-text"></div>--}}
    {{--</div>--}}
    {{--<div class="notification-handle-bar"></div>--}}
  {{--</div>--}}
 {{--</a>--}}
{{--</div>--}}
		<div class="weui_tab zijin_tab">
			<div class="weui_navbar">
				<a href="pressed_about.html" class="weui_navbar_item zijin_item">
					<img class="re" src="{{ asset('images/teacher/xiaoxi.png') }}" alt="" />
					<i>99</i>
				</a>
				<a class="weui_navbar_item weui_bar_item_on">
					全部
					<p>6672</p>
				</a>
				<a class="weui_navbar_item">
					数学
					<p>235</p>
				</a>
				<a class="weui_navbar_item">
					语文
					<p>678</p>
				</a>
				<a class="weui_navbar_item">
					英语
					<p>67</p>
				</a>
			</div>
			<div class="weui_tab_bd">

			</div>
		</div>
        @foreach($data['question'] as $value)
		<div class="weui_cells weui_cells_access zijin_clelrt">
			<a href="{{ route('teacher_question_detail',$value->id) }}">
			<div class="weui_cell">
				<div class="weui_cell_hd"><img src="{{ asset('images/teacher/touxiang.png') }}" alt=""></div>
				<div class="weui_cell_bd weui_cell_primary">
					<p class="user_name">学生姓名</p>
					<p class="time">{{ $value->created_at }}</p>
				</div>
				<div class="weui_cell_biaoti">{{ $value->grade_id.'-'.$value->subject_id }}</div>
			</div>
			<div class="topic">
				<img src="{{ config('weixin.M_PIC').$value->img }}" alt="" />

			</div>
			<p class="text">{{ $value->content }}</p>
			<div class="bd">
				<a href="{{ route('teacher_question_detail',$value->id) }}" class="weui_btn weui_btn_primary">开始解题</a>
			</div>
			</a>
		</div>
        @endforeach
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
				<img src="{{ asset('images/teacher/tili.png') }}" alt="" />
			</div>
			<p class="text">題型文字描述題型文字描述題型文字描述題型文字描述</p>
			<div class="bd  ">
				<a href="javascript:;" onclick="javascript:c2();" class="weui_btn weui_btn_primary">开始解题</a>
			</div>
		</a>
		</div>
@endsection

@push('need_js')
		<script src="{{ asset('js/teacher/jquery-2.1.4.js') }}" type="text/javascript" charset="utf-8"></script>
		<script src="{{ asset('js/teacher/weui.js') }}" type="text/javascript" charset="utf-8"></script>
		<script>
			function c1() {
				weui.confirm("<div class='weui_dialog_confirm'><div class='weui_mask'></div><div class='weui_dialog'><div class='weui_dialog_hd zijin_hd'><span>其他老师已经解答了该题目，请更换<br>其他题目！</span><p>3秒钟之后自动跳转</p></div><div class='weui_dialog_ft'><a href='javascript:;'' class='weui_btn_dialog primary' >确认</a></div></div></div>");
			};

			function c2() {
				weui.confirm("<div class='weui_dialog_confirm'><div class='weui_mask'></div><div class='weui_dialog'><div class='weui_dialog_hd zijin_hd'><span>确定开始答题？</span></div><div class='weui_dialog_ft'><a href='javascript:;'' class='weui_btn_dialog default' >取消</a><a href='' class='weui_btn_dialog primary' >确定</a></div></div></div>");
			}
			c1();
		</script>
@endpush