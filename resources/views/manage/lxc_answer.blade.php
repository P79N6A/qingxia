<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">查看图片</h4>
</div>
<div class="modal-body">
    @if(!empty($answers))
        <div id="myCarousel" class="carousel slide">
            <div class="carousel-inner">
                @foreach($answers as $key => $answer)
                    @if(is_array($answer['answers']))
                        @foreach($answer['answers'] as $answer_img)
                            <div class="item @if ($loop->first && $key==0) active  @endif">
                                <img src="{{ url('http://121.199.15.82/standard_answer/'.$answer_img) }}"
                                     alt="First slide">
                                <div class="carousel-caption text-orange">{{ $answer['chapter_name'] }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="item" @if ($loop->first && $key==0) active @endif>
                            <img src="{{ url('http://121.199.15.82/standard_answer/'.$answer_img) }}" alt="First slide">
                            <div class="carousel-caption text-orange FontBig">{{ $answer['chapter_name'] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
            <a class="carousel-control  left" href="#myCarousel"
               data-slide="prev"><i style="left:0" class="bg-blue fa fa-fw fa-arrow-circle-left"></i></a>
            <a class="carousel-control right" href="#myCarousel"
               data-slide="next"><i style="right:0" class="right bg-blue fa fa-fw fa-arrow-circle-right"></i></a>
        </div>
    @else
        <p>暂无对应答案</p>
    @endif
</div>