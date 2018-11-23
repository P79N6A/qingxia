

@extends('layouts.simple')
@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/autocompleter/jquery.autocompleter.css">
@endpush
@push('need_js')
    <script src="/adminlte/plugins/autocompleter/jquery.autocompleter.js"></script>
    <script src="/adminlte/plugins/layer/layer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
@endpush
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 4%;"></th>
                            @foreach($data['subject_arr'] as $key=> $subject)
                                    <th>{{ $subject }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($data['grade_arr'] as $grade=>$grade_info)
                            <tr>
                                <td>{{ $grade_info }}</td>
                                @foreach($data['subject_arr'] as $subject=>$subject_info)
                                    <td id="s_{{ $subject }}_g_{{ $grade }}" subject_id="{{ $subject }}" grade_id="{{ $grade }}" sort_id="{{ $data['sort_id'] }}" class="tddata">
                                        @foreach($data['list'] as $k=>$item)
                                            @if($item->grade_id==$grade && $item->subject_id==$subject && $item->find_num!=0)
                                                <a type="button" target="faxian_book" href="{{ route('taobao_search',[$item->search_word,0,$item->sort_id,1,1,1,2]) }}" class="btn btn-block btn-primary">{{ $item->search_word.'发现：'.$item->find_num }}</a>
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-warning dropdown-toggle lookup" data-toggle="dropdown" aria-expanded="false">查看
                                                        <span class="fa fa-caret-down"></span></button>

                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script>
    $(function(){
        $('.lookup').click(function(){
            var td=$(this).parents('td');
            var sort_id=td.attr('sort_id');
            var grade_id=td.attr('grade_id');
            var subject_id=td.attr('subject_id');
            if(td.find('.dropdown-menu').length<=0){
                axios.post('{{ route('show_bought') }}',{sort_id,grade_id,subject_id}).then(response=>{
                    if(response.data.status===1){
                    if(response.data.data.length>0) {
                        td.find('.lookup').after('<ul class="dropdown-menu"></ul>');
                        for (var i in response.data.data) {
                            if(response.data.data[i].status>=4){
                                td.find('.dropdown-menu').append('<li>' + response.data.data[i].newname + '<span class="label label-success">已买</span></li>');
                            }else{
                                td.find('.dropdown-menu').append('<li>' + response.data.data[i].newname + '<span class="label label-danger">未买</span></li>');
                            }

                        }
                    }
                }
            })
            }


        });
    })
</script>
@endpush