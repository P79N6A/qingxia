@extends('layouts.backend')

@section('question_manage_index')
    active
@endsection

@push('need_css')
<style>

</style>
@endpush

@section('content')

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">题目详情</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 题目详情</h3>

            </div>
            <div class="box-body">
                <div class="row" v-if="question_detail">
                    <div class="box-body">
                        <div class="col-md-7">
                            <p>@{{ question_detail.content }}</p>
                            <a class="thumbnail">
                                <img :src="'http://thumb.1010pic.com/'+question_detail.img" alt="">
                            </a>
                            <div>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div v-if="question_detail.status==0">
                                <p>回答区域</p>
                            <textarea name="" id="answer" cols="30" rows="10" class="form-control" v-model="answer_text"></textarea>
                                <a style="margin-top: 20px" v-on:click="confirm_answer()" class="btn btn-primary pull-right">确认回答</a>
                            </div>
                            <div v-else>
                                <p>我的回答</p>
                                <strong>@{{ answer_text }}</strong>
                            </div>
                        </div>
                    </div>

                </div>
                <div v-else>
                    <strong>该问题已被解答,请到已完成查看</strong>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('need_js')
<script>
    var token = '{{ csrf_token() }}';
    const id = '{{ $id }}';
    var app = new Vue({
        el: '#app-vue',
        data:{
            answer_text:'',
            question_detail:[],
        },
        created(){
            this.get_detail(id);
        },
        methods:{
            get_detail(id){
                axios.get('{{ route('api_que_manage_detail',[$id,'get_detail']) }}').then(response=>{

                    this.question_detail = response.data.detail;
                    console.log(this.question_detail);
                }).catch(function (error) {
                    console.log(error);
                })
            },
            confirm_answer(){
                let o ={
                  _token:token,
                  answer:this.answer_text
                };
                axios.post('{{ route('api_que_manage_detail',[$id,'answer']) }}',o).then(reponse=>{
                    if(reponse.data.status==1){
                        this.question_detail.status = 1;
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            }
        }
    })
</script>
@endpush