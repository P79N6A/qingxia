@extends('layouts.teacher')

@section('header')
个人中心
@endsection

@section('content')
		<div class="weui_cells weui_cells_access zijin_view">
			<a class="weui_cell" href="{{ route('teacher_question_detail') }}">
				<div class="weui_cell_hd"></div>
				<div class="weui_cell_bd weui_cell_primary">
					<p>子衿同学提出了追问！</p>
				</div>
				<div class="view">点击查看</div>
			</a>
			<a class="weui_cell" href="{{ route('teacher_question_detail') }}">
				<div class="weui_cell_hd"></div>
				<div class="weui_cell_bd weui_cell_primary">
					<p>子衿同学提出了追问！</p>
				</div>
				<div class="view">点击查看</div>
			</a>
		</div>
@endsection