@extends('layouts.backend')

@push('need_css')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/iCheck/all.css') }}">
  <style>
    .answer_now {
      color: deepskyblue;
      text-decoration: underline;
    }

    .answer_now_text {
      display: inline-block;
      padding: 3px 10px 1px 10px;
      margin: 0 3px;
      font-size: 14px;
      min-width: 3em;
      min-height: 16px;
      line-height: 18px;
      height: auto;
      border-bottom: 1px solid green;
      text-decoration: none;
      zoom: 1;
      background: #fff;
      color: #127176;
      word-break: break-all;
    }

    .question img{
        width: 100%;
    }


    .my_answer span{
      display: inline-block;
      vertical-align: middle;
      padding: 0 10px;
      height: 30px;
      background-color: #616a60;
      border: 1px solid #616a60;
      border-radius: 3px;
      text-align: center;
      line-height: 30px;
      color: #fff;
      margin-left: 6px;
    }
    .offical_answer span{
      display: inline-block;
      vertical-align: middle;
      padding: 0 10px;
      height: 30px;
      background-color: #de4a4a;
      border: 1px solid #de4a4a;
      border-radius: 3px;
      text-align: center;
      line-height: 30px;
      color: #fff;
      margin-left: 6px;
    }
    .offical_analysis{
      border: 2px dashed lightskyblue;
    }
    .input_text {
      border: none
    }
  </style>
@endpush

@section('content')

  <div id="practice" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
      <div class="modal-content">
        <div class="modal-header">
          在线练习-{{ $data['book_info']['bookname'] }}
        </div>
        <div class="modal-body">
          <div class="panel-body">
            @foreach( $data['all_timu'] as $value)
              <div class="box box-primary">
                @if($value->question_type===1)
                  <div class="box-header">选择题</div>
                  <div class="box-body">
                    <div class="question">
                      @if($value->question)
                        {!! $value->question !!}
                      @else
                        @forelse($value->timu_pics as $pics)
                          <img src="{{ asset('storage/all_book_pages/'.$data['book_info']->id.'/cut_pages/'.$pics->timu_page.'/'.$pics->sort.'_'.$pics->id.'.jpg') }}"/>
                          @endforeach
                      @endif
                    </div>
                    @if(strpos($value->answer_new,'|')!=false)
                      <div>
                        @php
                          $m_choices = explode(',',$value->answer_new);
													$m_choices_len = count($m_choices);
													$answer_arr = [];
													$choices_arr = [];
													for($i=0;$i<$m_choices_len;$i++){
															$choices = explode('|',$m_choices[$i])[0];
															$choices_len = strlen($choices);
															for($j=0;$j<$choices_len;$j++){
																	$choices_arr[$i][] = $choices[$j];
															}
															$answer_arr[$i][] = explode('|',$m_choices[$i])[1];
													}
                        @endphp

                        <div class="practice_tag" data-timu="{{ $value->timuid }}" data-type="{{ $value->question_type }}">
                        @foreach ($choices_arr as $key => $value_choice)
                          <div>
                            @foreach($value_choice as $value1)
                              <label><input class="radio flat-red"
                                            name="{{ $value->timuid.'_'.$key }}"
                                            type="radio"
                                            value="{{ $value1 }}"/>{{ $value1 }}</label>
                            @endforeach
                          </div>
                          <br>
                        @endforeach
                        </div>
                      </div>
                    @endif
                  </div>
                @elseif($value->question_type===4)
                  <div class="box-header">填空题</div>
                  <div class="box-body">
                    <div class="practice_tag" data-timu="{{ $value->timuid }}" data-type="{{ $value->question_type }}">{!! $value->all_timu_real_question !!}</div>
                    </div>
                @elseif($value->question_type===5)
                  <div class="box-header">解答题</div>
                  <div class="box-body">
                    <div class="question">
                      @if($value->question)
                        {!! $value->question !!}
                      @else
                        @forelse($value->timu_pics as $pics)
                          <img src="{{ asset('storage/all_book_pages/'.$data['book_info']->id.'/cut_pages/'.$pics->timu_page.'/'.$pics->sort.'_'.$pics->id.'.jpg') }}"/>
                          @endforeach
                          @endif
                    </div>
                    <div data-timu="{{ $value->timuid }}" data-type="{{ $value->question_type }}"
                         class="answer_now_text practice_tag"></div>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
        <div class="mdoal-footer">
          <a class="btn btn-primary" id="confirm_done">完成提交</a>
        </div>
      </div>
    </div>
  </div>


  <div class="box box-primary">
    <div class="box-body">
      <div class="main-sidebar-2">
        <ul class="nav nav-pills">
          @foreach($data['book_info']->chapters as $value)
            <li>
              <button class="btn @if($value->pages) @if(in_array(intval($data['page']),explode(',',$value->pages))) btn-danger @else btn-primary @endif @else btn-default @endif btn-xs"
                      data-toggle="dropdown">{{ $value->chaptername }}<span class="caret"></span></button>
              <ul class="dropdown-menu">
                @if($value->pages)
                  @forelse(explode(',',$value->pages) as $page)
                    <li>
                      <a href="{{ route('lww_show_timu',[$value->bookid,$value->id,$page]) }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    @endif
              </ul>
            </li>
          @endforeach
        </ul>
      </div>
      <hr>
      <div class="panel panel-default">
        <div class="panel-heading">试题页面预览</div>
        <div class="panel-body">
          @foreach( $data['all_timu'] as $value)
            <div class="box box-primary">
              @if($value->question_type===1)
                <div class="box-header">选择题</div>
                <div class="box-body">
                  <div class="question">
                    @if($value->question)
                      {!! $value->question !!}
                    @else
                      @forelse($value->timu_pics as $pics)
                        <img src="{{ asset('storage/all_book_pages/'.$data['book_info']->id.'/cut_pages/'.$pics->timu_page.'/'.$pics->sort.'_'.$pics->id.'.jpg') }}"/>
                        @endforeach
                        @endif
                  </div>
                  @if(strpos($value->answer_new,'|')!=false)
                    <div>
                      @php
                        $m_choices = explode(',',$value->answer_new);
												$m_choices_len = count($m_choices);
												$answer_arr = [];
												$choices_arr = [];
												for($i=0;$i<$m_choices_len;$i++){
														$choices = explode('|',$m_choices[$i])[0];
														$choices_len = strlen($choices);
														for($j=0;$j<$choices_len;$j++){
																$choices_arr[$i][] = $choices[$j];
														}
														$answer_arr[$i][] = explode('|',$m_choices[$i])[1];

												}
												//dd($choices_arr);
                      @endphp

                      @foreach ($choices_arr as $key => $value)
                        @foreach($value as $value1)
                          <a class="btn @if(in_array($value1,$answer_arr[$key])) select_choice @endif btn-primary">{{ $value1 }}</a>
                        @endforeach
                        <br>
                      @endforeach
                    </div>
                  @endif
                </div>
              @elseif($value->question_type===4)
                <div class="box-header">填空题</div>
                <div class="box-body">
                  {!! $value->question !!}
                </div>
              @elseif($value->question_type===5)
                <div class="box-header">解答题</div>
                <div class="box-body">
                  <div class="question">
                    @if($value->question)
                      {!! $value->question !!}
                    @else
                      @forelse($value->timu_pics as $pics)
                        <img src="{{ asset('storage/all_book_pages/'.$data['book_info']->id.'/cut_pages/'.$pics->timu_page.'/'.$pics->sort.'_'.$pics->id.'.jpg') }}"/>
                        @endforeach
                        @endif
                  </div>
                  <div class="answer_now">{!! $value->answer_new !!} </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>
        <div class="panel-footer">
          <a class="show_all_answer btn btn-primary btn-xs">显示答案</a>
          <a data-toggle="modal" data-target="#practice" class="btn btn-danger btn-xs">在线练习</a>
        </div>
        <div>
          <a class="btn btn-primary page_choice" data-chapter="{{ $data['prev_chapter'] }}" data-page="{{ $data['prev_page'] }}">上一页</a>
          <a class="btn btn-danger page_choice" data-chapter="{{ $data['next_chapter'] }}" data-page="{{ $data['next_page'] }}">下一页</a>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('need_js')
  <script src="{{ asset('adminlte/plugins/iCheck/icheck.min.js') }}"></script>
  <script>
      $(function () {
          $('.answer_now').each(function (i) {
              $(this).attr('data-answer', $(this).html());
              $(this).html('________');
          });
          $('.answer_now').click(function () {
              if ($(this).html() === '________') {
                  $(this).html($(this).attr('data-answer'));
              } else {
                  $(this).attr('data-answer', $(this).html());
                  $(this).html('________');
              }

          });
          $('.show_all_answer').click(function () {
              if ($(this).attr('status') == 1) {
                  $('.answer_now').each(function (i) {
                      $(this).attr('data-answer', $(this).html());
                      $(this).html('________');
                  });
                  $(this).html('显示答案').attr('status', 0)
                  $('.select_choice').removeClass('btn-danger');
              } else {
                  $('.answer_now').each(function (i) {
                      $(this).html($(this).attr('data-answer'));
                  });
                  $(this).html('隐藏答案').attr('status', 1);
                  $('.select_choice').addClass('btn-danger');
              }

          });

          //翻页
          $('.page_choice').click(function () {
             let chapter = parseInt($(this).attr('data-chapter'));
             let page = parseInt($(this).attr('data-page'));
             if(chapter===0 || page===0){
                 alert('无法继续翻页');
                 return false;
             }
             window.location.href = '{{ route('lww_show_timu',$data['book_info']->id) }}'+`/${chapter}/${page}`;
          });



          //在线练习
          $('.answer_now_text').click(function () {
              if ($(this).html().length === 0) {
                  $(this).html(`<input type="text" class="input_text" value="" />`);
                  $('.input_text').focus();
              } else {
                  if ($(this).find('input').length === 0) {
                      let word = $(this).html();
                      $(this).html(`<input type="text" class="input_text" value="" />`);
                      $('.input_text').val("").focus().val(word);
                  }
              }
          });

          $(document).on('blur', '.input_text', function () {
              $(this).parent().html($(this).val());
          });

          //提交答案
          //取选择题答案
          $('#confirm_done').click(function () {
              let data = {};
              let now = {};
             $('.practice_tag').each(function (i) {
                 let que_type = parseInt($(this).attr('data-type'));
                 let ques_id = $(this).attr('data-timu');
                 if(que_type===1){
                     let answer = [];
                     $(this).find('input[type=radio]:checked').each(function (j) {
                         answer[j] = $(this).val();
                     });
                     data = {ques_id,'answer':answer}

                 }else if(que_type===4){
                     //data[i]['ques_id'] = ques_id;
                     let answer = [];
                     $(this).find('.answer_now_text').each(function (j) {
                         answer[j] = $(this).html();
                     });
                     data = {ques_id,'answer':answer}
                 }else{
                     data = {ques_id,'answer':$(this).html()}
                 }
                 now[i] = data
             });
             console.log(now);
              //测试不提交
              let all_answer = {!! $data['all_answer'] !!}
              $('.practice_tag').each(function (i) {
                  let que_type = parseInt($(this).attr('data-type'));
                  let ques_id = $(this).attr('data-timu');
                  if(que_type===1){
                      //多选
                      if($.isArray(all_answer[i]['answer'])){
                          let html = '<div class="my_answer">您的答案: ';
                          for(let j in now[i]['answer']){
                              html += `<span>${now[i]['answer'][j]}</span>`;
                          }
                          console.log(html);
                          html += '</div><div class="offical_answer">正确答案: ';
                          for(let j in all_answer[i]['answer']) {
                              html += `<span>${all_answer[i]['answer'][j]}</span>`;
                              $(`input[name=${all_answer[i]['timuid']}_${j}][value=${all_answer[i]['answer'][j]}]`).parent().addClass('bg-red')
                          }
                          html += `</div><div class="offical_analysis">该题解析<br>${all_answer[i]['analysis']}</div>`;
                          $(this).append(html);
                      }
                      //单选
                      else{
                          $(`input[name=${all_answer[i]['timuid']}_0][value=${all_answer[i]['answer']}]`).parent().addClass('bg-red')
                          $(this).after(`<div class="my_answer">您的答案: <span>${now[i]['answer']}</span></div><div class="offical_answer">正确答案: <span >${all_answer[i]['answer']}</span></div><div class="offical_analysis">该题解析<br>${all_answer[i]['analysis']}</div>`)
                      }

                  }else if(que_type===4){
                      if($.isArray(all_answer[i]['answer'])){
                          let html = '<div class="my_answer">您的答案: ';
                          for(let j in now[i]['answer']){
                              html += `<span>${now[i]['answer'][j]}</span>`;
                          }
                          html += '</div><div class="offical_answer">正确答案: ';
                          for(let j in all_answer[i]['answer']){
                              html += `<span>${all_answer[i]['answer'][j]}</span>`;
                          }
                          html += `</div><div class="offical_analysis">该题解析<br>${all_answer[i]['analysis']}</div>`;
                          $(this).append(html);
                      }else{
                          $(this).append(`<div class="my_answer">您的答案: <span>${now[i]['answer']}</span></div><div class="offical_answer">正确答案: <span>${all_answer[i]['answer']}</span></div><div class="offical_analysis">该题解析<br>${all_answer[i]['analysis']}</div>`)
                      }
                  }else if(que_type===5){
                      $(this).after(`<div class="my_answer">您的答案:<span >${now[i]['answer']}</span></div><div class="offical_answer">正确答案<span >${all_answer[i]['answer']}</span></div><div class="offical_analysis">该题解析<br>: ${all_answer[i]['analysis']}</div>`)
                  }else{}
              });

          });

          //icheck
          $('input[type="radio"].flat-red').iCheck({
              checkboxClass: 'icheckbox_flat-green',
              radioClass   : 'iradio_flat-green'
          })
      })
  </script>
@endpush