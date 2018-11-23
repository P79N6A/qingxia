@extends('layouts.backend')

@section('audit_answer','active')

@push('need_css')
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
    <style>
        .answer_pic{
            min-width: 150px;
            max-height: 350px;
            min-height:200px;
        }
        #img_download_box img{
            max-width: 200px;
        }
        #show_img{
            z-index:1051;
        }
        #show_img .modal-dialog{
            width:80%;
        }
        #upload_img .modal-dialog{
            width: 70%
        }
        .for_isbn_input{
            font-size: 24px;
        }
        #done_img{
            overflow: auto;
            max-height: 300px;
        }
    </style>
@endpush

@section('content')
    @component('components.modal',['id'=>'show_img'])
        @slot('title')
            <strong>查看图片</strong>
            <span class="pull-right">
                <a class="page_now btn btn-primary" data-type="prev">上一页</a>
                <a class="page_now btn btn-primary" data-type="next">下一页</a>
            </span>
        @endslot
        @slot('body','')
        @slot('footer')
            <span class="pull-right">
                <a class="page_now btn btn-primary" data-type="prev">上一页</a>
                <a class="page_now btn btn-primary" data-type="next">下一页</a>
            </span>
        @endslot
    @endcomponent

    {{--@component('components.modal',['id'=>'upload_img','title'=>'答案上传(拖动排序)'])--}}
        {{--@slot('body')--}}
            {{--<div id="fileuploader">Upload</div>--}}
            {{--<div id="done_img" class="row"></div>--}}
        {{--@endslot--}}
        {{--@slot('footer')--}}
            {{--<a class="btn btn-warning confirm_answer" data-type="append">确认无误,按当前顺序追加答案</a>--}}
            {{--<a class="btn btn-danger confirm_answer" data-type="update">确认无误,按当前顺序替换答案</a>--}}
        {{--@endslot--}}
    {{--@endcomponent--}}

    {{--@component('components.modal',['id'=>'replace_img','title'=>'替换'])--}}
        {{--@slot('body')--}}
            {{--<div id="fileuploader_single">Upload</div>--}}
            {{--<div id="done_img" class="row"></div>--}}
        {{--@endslot--}}
        {{--@slot('footer','')--}}
    {{--@endcomponent--}}

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">答案审核</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header">已有答案</div>
            <div class="box-body">

                <div class="panel panel-danger">
                    <div class="panel-heading">用户上传答案
                        <a class="btn btn-xs btn-danger img_all_choose">全选/反选</a>
                        @if($data['bookid'])
                        <a class="btn btn-success" target="_blank" href="{{ route('audit_answer_detail',$data['bookid']->id) }}">上传答案</a>
                        没找到合适答案？@if($data['need_buy']->has_newonly->need_buy==0)<a class="btn btn-primary need_buy">加入待购买</a>@else<a class="btn btn-primary">已添加待购买</a>@endif
                        @endif
                    </div>
                    <div class="panel-body">
                        @foreach($data['list'] as $user_key=> $user_answer)
                            @if($user_answer->answers)
                            @php
                                $imgs = explode('|',$user_answer->answers->answer_img);
                            @endphp
                            <div class="col-md-8" style="overflow-y: auto;display: flex">
                                @forelse($imgs as $key=> $img)
                                    @if(strlen($img)>20)
                                        <div class="img_box" data-id="user_{{ $user_answer->up_uid }}_{{ $key }}">
                                            <button class="btn btn-xs btn-primary img_choose">选中</button>
                                            <a class="thumbnail">
                                                <img class="answer_pic" data-id="user_{{ $user_answer->up_uid }}_{{ $key }}" src="{{ config('workbook.user_image_url').$img }}">
                                            </a>
                                        </div>
                                    @endif
                                    @endforeach
                            </div>
                            <div class="col-md-4">
                                <div class="box box-warning direct-chat direct-chat-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">审核反馈</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>

                                    <div class="box-body">
                                        <div class="direct-chat-messages">
                                            @forelse($data['user_message'][$user_key] as $key1=>$value1)
                                                <div class="direct-chat-msg @if($value1->uid != $value1->huid) right @endif ">
                                                    <div class="direct-chat-info clearfix">
                                                        <span class="direct-chat-timestamp pull-right">{{ $value1->add_time }}</span>
                                                    </div>
                                                    <div class="direct-chat-text">
                                                        {{ $value1->msg }}
                                                    </div>
                                                </div>
                                                @endforeach
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="input-group" data-id="{{ $user_answer->id }}" data-up-id = "{{ $user_answer->up_uid }}">
                                            <input type="text" name="message" placeholder="输入审核意见" class="form-control">
                                            <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning btn-flat send_msg">发送</button>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-id="{{ $user_answer->id }}" data-up-id="{{ $user_answer->up_uid }}">
                                <a class="hide btn btn-info">通过此答案</a>
                            </div>
                            <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div style="position: fixed;bottom: 20px;right:10px;z-index: 999;">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">已选择答案<a class="btn btn-xs btn-primary" id="download_all_pic">下载</a>&nbsp;<a class="btn btn-xs btn-danger" id="clear_all_pic">清空</a></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="img_download_box" style="display: flex;width: 800px;overflow-y: auto;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/7.2.0/lazyload.transpiled.min.js"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
  {{--  <script>
        $(function () {

            var lazyLoadInstances = [];
            var lazyLazy = new LazyLoad({
                elements_selector: ".cover-box",
                callback_set: function(el) {
                    var oneLL = new LazyLoad({
                        container: el
                    });
                    lazyLoadInstances.push(oneLL);
                }
            });
            var lazy = new LazyLoad();
            $('.select2').select2();
            const book_id = '{{ $data['book_id'] }}';
            //答案选中
            $('.img_choose').click(function () {
                let now_img = $(this).parents('.img_box').find('img').attr('data-id');
                if($(`#img_download_box img[data-id="${now_img}"]`).length>0){
                    $(`#img_download_box img[data-id="${now_img}"]`).parent().parent().remove();
                    $(this).removeClass('btn-danger').addClass('btn-primary').html('选中');
                }else{
                    $('#img_download_box').append(`<div class="single_download">
                    <span class="pull-right del_single_img" style="cursor: pointer">&times;</span>
                        <a class="thumbnail">${$(this).parents('.img_box').find('img').parent().html()}</a>
                    </div>`);
                    $(this).removeClass('btn-primary').addClass('btn-danger').html('已选中');
                }
            });

            //全选
            $('.img_all_choose').click(function () {
                $(this).parent().parent().find('.img_choose').each(function () {
                    $(this).click();
                })
            });

            //选中答案移除
            $(document).on('click','.del_single_img',function () {
                let now_img = $(this).next().find('img').attr('data-id');
                $(`img[data-id="${now_img}"]`).parents('.img_box').find('.img_choose').click();

            });

            //清空
            $('#clear_all_pic').click(function () {
                $('.img_choose.btn-danger').click();
            });

            //将所有答案移至回收站
            $('.move_to_trash').click(function () {
               if(confirm('确认将所有答案移至回收站')){
                   axios.post('{{ route('audit_api','move_to_trash') }}',{book_id}).then(response=>{
                       if(response.data.status===1){
                           window.location.reload();
                       }
                   }).catch(function () {});
               }
            });

            //下载选中答案
            $('#download_all_pic').click(function () {
                let book_id = '{{ $data['book_id'] }}';
                let all_img = [];
                $('#img_download_box .answer_pic').each(function () {
                    all_img.push($(this).attr('src'));
                });
                axios.post('{{ route('audit_api','download_img') }}',{book_id,all_img}).then(response=>{
                    if(response.data.status===1){
                        window.open(response.data.zip);
                    }else{
                        alert(response.data.msg);
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });
            //显示大图
//            $(document).on('click','.answer_pic',function () {
//                let img = $(this).attr('src');
//                $('#show_img').modal('show');
//                $('#show_img .modal-body').html(`<img width="100%" src="${img}" />`);
//            });

            //排序
            $( "#done_img" ).sortable();

            //上传
            $("#fileuploader").uploadFile({
                url:"{{ route('upload_now',$data['book_id']) }}",
                fileName:"myfile",
                allowedTypes:"jpg,png,gif",
                showStatusAfterSuccess:false,
                onSuccess:function(files,data,xhr,pd)
                {
                    if(data.status===1){
                        $('#done_img').append(`<div class="col-md-3"><span class="close del_upload_img">&times;</span><a class="thumbnail"><img class="answer_pic" src="${data.img}"></a>`);
                    }
                },
            });

            //替换
            //上传
            $("#fileuploader_single").uploadFile({
                url:"{{ route('upload_now',$data['book_id']) }}",
                fileName:"myfile",
                allowedTypes:"jpg,png,gif",
                multiple:false,
                showStatusAfterSuccess:false,
                onSuccess:function(files,data,xhr,pd)
                {
                    if(data.status===1){
                        let now_img = data.img;
                        let answer_id = $('#replace_img').attr('data-id');
                        axios.post('{{ route('audit_api','replace_img') }}',{book_id,answer_id,now_img})
                            .then(response=>{

                            }).catch(function (error) {
                            console.log(error);
                        });
                        $('#replace_img').modal('hide');
                        $(`img[data-id="offical_${answer_id}"]`).attr('src',now_img);
                    }
                },
            });


            //删除已上传答案
            $(document).on('click','.del_upload_img',function () {
                $(this).parent().remove();
            });

            $('.append_last').click(function () {
                $('#upload_img').removeAttr('data-id');
                $('#upload_img').modal('show');
            });
            //更新答案
            $('.confirm_answer').click(function ()  {
                let update_type = $(this).attr('data-type');
                let append_id = $('#upload_img').attr('data-id');
                if(!append_id){
                    append_id = 0;
                }
                if(update_type==='update'){
                    if(append_id){
                        alert('当前为追加模式');
                        return false;
                    }else{
                        if(!confirm('确认覆盖当前答案')){
                            return false;
                        }
                    }

                }else{
                    if(!confirm('确认追加答案')){
                        return false;
                    }
                }
                let all_img = [];
                $('#done_img .answer_pic').each(function () {
                    all_img.push($(this).attr('src'));
                });
                if(all_img.length<1){
                    alert('请先上传图片');return false;
                }
                axios.post('{{ route('audit_api','update_answer') }}',{append_id,book_id,all_img,update_type})
                    .then(response=>{
                        if(response.data.status===1){
                            alert(response.data.msg);
                            window.location.reload();
                        }
                    }).catch(function (error) {
                    console.log(error);
                })
            })

            //发送消息给上传者
            $('.send_msg').click(function () {
                let box = $(this).parents('.direct-chat').find('.direct-chat-messages');
                let book_id = $(this).parent().parent().attr('data-id');
                let up_uid = $(this).parent().parent().attr('data-up-id');
                let msg = $(this).parent().prev().val();
                axios.post('{{ route('audit_api','send_msg') }}',{book_id,up_uid,msg}).then(response=>{
                    if(response.data.status===1){
                        box.append(`<div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-timestamp pull-right">{{ date('Y-m-d H:i:s',time()) }}</span>
                                        </div>
                                        <div class="direct-chat-text">${msg}</div>
                                    </div>`);
                        $(this).parent().prev().val('');
                    }else{
                        alert('发送失败,请重试');
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            });

            //删除答案
            $('.img_delete').click(function () {
                let answer_id = $(this).parents('.img_box').attr('data-id');
                axios.post('{{ route('audit_api','delete_answer') }}',{book_id,answer_id}).then(
                    response=>{
                        if(response.data.status===1){
                            $(this).parents('.img_box').find('.img_replace').remove();
                            $(this).parents('.img_box').find('.img_delete').remove();
                            $(`.img_box[data-id=${answer_id}]`).prependTo($('#recycle_box'));
                        }else{
                            alert('删除失败');
                        }
                    }
                ).catch(function (error) {
                    console.log(error);
                })
            });
            //替换答案
            $('.img_replace').click(function () {
                let answer_id = $(this).parents('.img_box').attr('data-id');
                $('#replace_img').attr('data-id',answer_id);
            });
            //追加答案
            $('.img_append').click(function () {
                let answer_id = $(this).parents('.img_box').attr('data-id');
                $('#upload_img').modal('show');
                $('#upload_img').attr('data-id',answer_id)
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

            //isbn
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

            $('.img_move').click(function () {
                let move_type = $(this).attr('data-type');
                if (move_type === 'right') {
                    $(this).parent().next().insertBefore($(this).parent());
                } else {
                    $(this).parent().insertBefore($(this).parent().prev());
                }
            });
            //按当前排序保存答案
            $('.save_answer_order').click(function () {
                let img_ids = [];
                $('.all_answer_now .img_box').each(function () {
                    img_ids.push($(this).attr('data-id'));
                });
                if(img_ids.length<=1){
                    alert('保存失败');
                    return false;
                }
                axios.post('{{ route('audit_api','save_order') }}',{book_id,img_ids}).then(response=>{
                }).catch();
            });


            //保存练习册信息
            $('.save_book').click(function () {
                let now_book_id = $(this).attr('data-id');
                let now_book_box = $(this).parents('.book_info_box');
                let book_name = now_book_box.find('.book_name').val();
                let redirect_id = now_book_box.find('.book_redirect_id').val();
                let isbn = now_book_box.find('.for_isbn_input').val();
                let version_year = now_book_box.find('.version_year').val();
                let grade_id = now_book_box.find('.grade_id').val();
                let subject_id = now_book_box.find('.subject_id').val();
                let volumes_id = now_book_box.find('.volumes_id').val();
                let version_id = now_book_box.find('.version_id').val();
                let sort = now_book_box.find('.sort_name').val();
                axios.post('{{ route('audit_api','save_book') }}',{redirect_id,now_book_id,book_name,isbn,version_year,grade_id,subject_id,volumes_id,version_id,sort}).then(response=>{
                    if(response.data.status===0){
                        alert('保存失败');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
                console.log()
            });


            //处理完毕
            $('#confirm_done').click(function () {
                if(confirm('确认处理完毕')){
                    axios.post('{{ route('audit_api','confirm_done') }}',{book_id}).then(response=>{

                    }).catch(function (error) {
                        console.log(error);
                    })
                }
            });

            //无需处理
            $('#not_need_deal').click(function () {
                if(confirm('确认无需处理')){
                    axios.post('{{ route('audit_api','not_need_deal') }}',{book_id}).then(response=>{

                    }).catch(function (error) {
                        console.log(error);
                    })
                }
            });

            //翻页
            $('.page_now').click(function () {
                let page_to = $(this).attr('data-type');
                let now_img = $(this).parents('.modal-dialog').find('img')

                if(page_to=='prev'){
                    let prev_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').prev().find('img');
                    if(prev_img.length>0){
                        now_img.attr({'src':prev_img.attr('src'),'data-id':prev_img.attr('data-id')});
                    }
                }else{
                    let next_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').next().find('img');
                    if(next_img.length>0){
                        now_img.attr({'src':next_img.attr('src'),'data-id':next_img.attr('data-id')});
                    }
                }
//                $(`img[data-status='now_modal_content']`).removeAttr('data-status');
//                now_img.attr('data-status','now_modal_content');
            })

            $('.select2').change(function () {
                $(this).parents('.book_info_box').find('.generate_name').click();
            });
            //生成书名
            $('.generate_name').click(function () {
                let book_info_box = $(this).parents('.book_info_box');
                let version_year = book_info_box.find('.version_year').val();
                let sort_name = '';

                let now_name = book_info_box.find('.sort_name').select2('data')[0].name;
                let now_text = book_info_box.find('.sort_name').select2('data')[0].text;
                if(now_name!==undefined){
                    sort_name = now_name
                }else{
                    sort_name = now_text
                }

                let grade_name = book_info_box.find('.grade_id option:selected').text();
                let subject_name = book_info_box.find('.subject_id option:selected').text();
                let volume_name = book_info_box.find('.volumes_id option:selected').text();
                let version_name = book_info_box.find('.version_id option:selected').text();
                let book_name = version_year+'年'+sort_name+grade_name+subject_name+volume_name+version_name;

                book_name = book_name.replace('上册','下册');
                book_name = book_name.replace('全一册上','全一册下');
                book_name = book_name.replace('全一册','全一册下');
                book_name = book_name.replace('思想品德','道德与法治');
                book_info_box.find('.book_name').val(book_name)
            });


            //保存跳转id
            $(document).on('click','.save_redirect_id',function () {
                let book_id = $(this).attr('data-id');
                let redirect_id = $(this).prev().val();
                axios.post('{{ route('audit_api','update_redirect') }}',{book_id,redirect_id}).then(response=>{

                }).catch();
            });
        });
    </script>--}}


<script>
    $(function(){
        //全选
        $('.img_all_choose').click(function () {
            $(this).parent().parent().find('.img_choose').each(function () {
                $(this).click();
            })
        });
        //答案选中
        $('.img_choose').click(function () {
            let now_img = $(this).parents('.img_box').find('img').attr('data-id');

            if($(`#img_download_box img[data-id="${now_img}"]`).length>0){
                $(`#img_download_box img[data-id="${now_img}"]`).parent().parent().remove();
                $(this).removeClass('btn-danger').addClass('btn-primary').html('选中');
            }else{
                $('#img_download_box').append(`<div class="single_download">
                <span class="pull-right del_single_img" style="cursor: pointer">&times;</span>
                <a class="thumbnail">${$(this).parents('.img_box').find('img').parent().html()}</a>
                </div>`);
                $(this).removeClass('btn-primary').addClass('btn-danger').html('已选中');
            }
        });

        //显示大图
//          $(document).on('click','.answer_pic',function () {
//                let img = $(this).attr('src');
//                $('#show_img').modal('show');
//                $('#show_img .modal-body').html(`<img width="100%" src="${img}" />`);
//            });


        //发送消息给上传者
        $('.send_msg').click(function () {
            let box = $(this).parents('.direct-chat').find('.direct-chat-messages');
            let book_id = $(this).parent().parent().attr('data-id');
            let up_uid = $(this).parent().parent().attr('data-up-id');
            let msg = $(this).parent().prev().val();
            axios.post('{{ route('audit_api','send_msg') }}',{book_id,up_uid,msg}).then(response=>{
                if(response.data.status===1){
                box.append(`<div class="direct-chat-msg right">
                <div class="direct-chat-info clearfix">
                <span class="direct-chat-timestamp pull-right">{{ date('Y-m-d H:i:s',time()) }}</span>
                </div>
                <div class="direct-chat-text">${msg}</div>
                </div>`);
                $(this).parent().prev().val('');
            }else{
                alert('发送失败,请重试');
            }
        }).catch(function (error) {
            console.log(error);
        })
    });

    //选中答案移除
    $(document).on('click','.del_single_img',function () {
        let now_img = $(this).next().find('img').attr('data-id');
        $(`img[data-id="${now_img}"]`).parents('.img_box').find('.img_choose').click();

    });

    //清空
    $('#clear_all_pic').click(function () {
        $('.img_choose.btn-danger').click();
    });

    //翻页
    $('.page_now').click(function () {

        let page_to = $(this).attr('data-type');
        let now_img = $(this).parents('.modal-dialog').find('img');

        if(page_to=='prev'){
            let prev_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').prev().find('img');
            if(prev_img.length>0){
                now_img.attr({'src':prev_img.attr('src'),'data-id':prev_img.attr('data-id')});
            }
        }else{
            let next_img = $(`img[data-id=${now_img.attr('data-id')}][src='${now_img.attr('src')}']`).parents('.img_box').next().find('img');
            if(next_img.length>0){
                now_img.attr({'src':next_img.attr('src'),'data-id':next_img.attr('data-id')});
            }
        }

    });

    //下载选中答案
    $('#download_all_pic').click(function () {
        let isbn = '{{ $data['isbn'] }}';
        let all_img = [];
        $('#img_download_box .answer_pic').each(function () {
            all_img.push($(this).attr('src'));
        });
        axios.post('{{ route('new_audit_api','download_img') }}',{isbn,all_img}).then(response=>{
            if(response.data.status===1){
            window.open(response.data.zip);
        }else{
            alert(response.data.msg);
        }
    }).catch(function (error) {
        console.log(error);
    })
    });

    $(".need_buy").click(function(){
       let bookid= '{{ $data['bookid']['id'] }}';
        axios.post('{{ route('ajax_new_audit_list','add_bought_record') }}',{bookid})
                .then(response=>{
            if(response.data.status===1){
                window.location.reload();
            }
        });
    });


    });
</script>
@endpush