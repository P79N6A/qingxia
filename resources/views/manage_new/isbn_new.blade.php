@extends('layouts.backend')

@section('book_new_isbn','active')

@push('need_css')
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">唯一表整理</li>
        </ol>
    </section>
    <section class="content">
        @component('components.modal')
            @slot('id','show_big_photo')
            @slot('title','查看图片')
            @slot('body','')
            @slot('footer')
                <div class="input-group" id="modify_footer" data-id="0">
                    <input class="form-control" value="" />
                    <a class="btn btn-primary add_isbn input-group-addon">添加</a>
                </div>
            @endslot
        @endcomponent
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> isbn整理</h3></div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li @if($type==='pending') class="active" @endif><a href="{{ route('book_new_isbn','pending') }}">未整理</a></li>
                        <li @if($type==='done') class="active" @endif><a href="{{ route('book_new_isbn','done') }}">已整理</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            @forelse($data as $isbn)
                                <div class="single_box_info col-md-12" data-id="{{ $isbn->id }}">
                                    <div class="box-header"><a href="http://www.1010jiajiao.com/daan/bookid_{{ $isbn->id }}.html" target="_blank">{{ $isbn->bookname }}</a>
                                        <a class="btn btn-primary">
                                            @if(Auth::id()===2)
                                                {{--@if($isbn->id%5===0)--}}
                                                    {{--黄少敏--}}
                                                {{--@elseif($isbn->id%5===1)--}}
                                                    {{--苏蕾--}}
                                                {{--@elseif($isbn->id%5===2)--}}
                                                    {{--张连荣--}}
                                                {{--@elseif($isbn->id%5===3)--}}
                                                    {{--陈卓--}}
                                                {{--@elseif($isbn->id%5===4)--}}
                                                    肖高萍
                                            @endif
                                            @else
                                                    肖高萍
                                                {{--{{ Auth::user()->name }}--}}
                                            @endif
                                        </a></div>
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            <a class="thumbnail show_big">
                                                <img src="{{ $isbn->cover }}" alt="">
                                            </a>
                                            <div class="input-group" style="width: 100%">
                                                <select class="form-control sort_name click_to select2">
                                                    @if($isbn->sort>0)
                                                        <option value="{{ $isbn->sort }}">{{ App\Sort::find($isbn->sort)?App\Sort::find($isbn->sort)->name:'' }}</option>
                                                    @else
                                                        @forelse($all_like_book as $like_isbn_now)
                                                            @if($like_isbn_now['name']==$isbn->new_bookname)
                                                                @forelse($like_isbn_now['sorts'] as $sort)
                                                            <option @if($loop->first) selected="selected" @endif value="{{ $sort->sort }}">{{ $sort->has_sort?$sort->has_sort->name.'_'.$sort->sort:'' }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                        <option value="-999">搜索系列</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <br>
                                            <div class="input-group" data-id="{{ $isbn->id }}">
                                                <input maxlength="17" class="for_isbn_input form-control input-lg" style="font-size: 24px" value="{{ $isbn->isbn?$isbn->isbn:'978-7-' }}" />
                                                <a class="btn btn-primary add_isbn input-group-addon">添加</a>
                                            </div>
                                        </div>
                                        <div class="clo-md-8">
                                            <a class="col-md-8 thumbnail show_big">
                                                <img data-original="{{ 'http://image.hdzuoye.com/'.$isbn->cip_photo }}" alt="{{ $isbn->isbn }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script>
        $(function () {
            var lazy = new LazyLoad();
            $('.show_big').click(function () {
                let data_id = $(this).parents('.single_box_info').attr('data-id');
                let now_value = $(this).parents('.single_box_info').find('input').val();
                $('#show_big_photo').modal('show');
                $('#show_big_photo #modify_footer').attr('data-id',data_id);
                $('#show_big_photo #modify_footer input').val(now_value);
                $('#show_big_photo .modal-body').html(`<a class="thumbnail">${$(this).html()}</a>`);
            });

            //保存isbn
            $(document).on('click','.add_isbn',function () {
                let book_id = $(this).parent().attr('data-id');
                let isbn = $(this).prev().val();
                let sort_id = $(`.single_box_info[data-id=${book_id}] .sort_name`).val();
                axios.post('{{ route('book_new_isbn_api','modify_isbn') }}', {book_id, isbn, sort_id}).then(response => {
                    if (response.data.status === 1) {
                        $('#show_big_photo').modal('hide');
                        $(`.single_box_info[data-id=${book_id}]`).remove();
                        var lazy = new LazyLoad();
                    }else{
                        alert('isbn有误');
                    }
                }).catch();
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

            //系列选择获取对应旧版isbn
            $(document).on('change', '.sort_name', function () {
                let book_id = $(this).parents('.single_box_info').attr('data-id');
                let sort_id = $(this).val();
                axios.post('{{ route('book_new_isbn_api','get_isbn') }}', {book_id, sort_id}).then(response => {
                    if (response.data.status === 1) {
                        $(`.old_isbn_box[data-id=${book_id}]`).remove();
                        if (response.data.data.length > 1) {
                            $(this).parent().parent().append(`<div class="old_isbn_box" data-id="${book_id}"></div>`)
                        } else {
                            $(this).parent().parent().append(`<div class="old_isbn_box" data-id="${book_id}">暂无对应旧版练习册</div>`)
                        }
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            });

            //间隔
            //$('.for_isbn_input')
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
                }
            });
        });

    </script>

@endpush