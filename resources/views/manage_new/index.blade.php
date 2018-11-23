@extends('layouts.backend')

@section('book_new_check','active')

@section('content')
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('backend') }}"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">唯一表整理</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 唯一表整理</h3></div>
            <div class="box-body">
                <div>
                    <div class="dropdown pull-left">
                        <button class="btn btn-default dropdown-toggle grade_search" data-toggle="dropdown" v-on:click="search_bar('grade')"><strong>年级</strong>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" v-if="grade_info">
                            <li><a>不限</a></li>
                            <li v-for="(grade_now,index) in grade_info"><a v-on:click="select_btn('grade',grade_now.grade_id)"><i class="fa fa-circle-o">@{{ grade_now.grade_id }}</i><small class="label pull-right  bg-blue ">@{{ grade_now.num }}</small></a></li>
                        </ul>
                    </div>
                    <div class="dropdown pull-left">
                        <button class="btn btn-default dropdown-toggle subject_search" data-toggle="dropdown" v-on:click="search_bar('subject')"><strong>科目</strong>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a>不限</a></li>
                            <li v-for="(subject_now,index) in subject_info"><a v-on:click="select_btn('subject',subject_now.subject_id)"><i class="fa fa-circle-o">@{{ subject_now.subject_id }}</i><small class="label pull-right  bg-blue ">@{{ subject_now.num }}</small></a></li>
                        </ul>
                    </div>
                    <div class="dropdown pull-left">
                        <button class="btn btn-default dropdown-toggle volume_search" data-toggle="dropdown" v-on:click="search_bar('volume')"><strong>卷册</strong>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a>不限</a></li>
                            <li v-for="(volume_now,index) in volume_info"><a v-on:click="select_btn('volume',volume_now.volumes_id)"><i class="fa fa-circle-o">@{{ volume_now.volumes_id }}</i><small class="label pull-right  bg-blue ">@{{ volume_now.num }}</small></a></li>
                        </ul>
                    </div>
                    <div class="dropdown pull-left">
                        <button class="btn btn-default dropdown-toggle version_search" data-toggle="dropdown" v-on:click="search_bar('version')"><strong>版本</strong>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a>不限</a></li>
                            <li v-for="(version_now,index) in version_info"><a v-on:click="select_btn('version',version_now.version_id)"><i class="fa fa-circle-o">@{{ version_now.version_id }}</i><small class="label pull-right  bg-blue ">@{{ version_now.num }}</small></a></li>
                        </ul>
                    </div>
                    <div class="dropdown pull-left">
                        <button class="btn btn-default dropdown-toggle sort_search" data-toggle="dropdown" v-on:click="search_bar('sort')"><strong>系列</strong>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a>不限</a></li>
                            <li v-for="(sort_now,index) in sort_info"><a v-on:click="select_btn('sort',sort_now.sort)"><i class="fa fa-circle-o">@{{ sort_now.sort }}</i><small class="label pull-right  bg-blue ">@{{ sort_now.num }}</small></a></li>
                        </ul>
                    </div>
                </div>
                <div style="clear: both"></div>
                <hr>
                <div class="box-body well" v-for="(book,index) in all_book_info">
                    <div class="col-md-6">
                        <div>
                            <a class="btn btn-default">删除</a>
                            <a class="btn btn-default">更改封面</a>
                            <a class="btn btn-default">搜索</a>
                            <a class="btn btn-default">完成</a>
                        </div>
                        <div class="col-md-6">
                            <a class="thumbnail">
                                <img src="http://iph.href.lu/140x200" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="input-group" style="width: 100%">
                        <select class="form-control" v-model="book.version_year">
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="input-group-addon">年级</label>
                        <label class="input-group-addon">id</label>
                        <input type="text" class="form-control" v-model="book.grade_id"/>
                        <label class="input-group-addon">名称</label>
                        <input type="text" class="form-control" v-model="book.grade_name" />
                    </div>
                    <div class="input-group">
                        <label class="input-group-addon">科目</label>
                        <label class="input-group-addon">id</label>
                        <input type="text" class="form-control" v-model="book.subject_id" />
                        <label class="input-group-addon">名称</label>
                        <input type="text" class="form-control" v-model="book.subject_name" />
                    </div>
                    <div class="input-group">
                        <label class="input-group-addon">卷册</label>
                        <label class="input-group-addon">id</label>
                        <input type="text" class="form-control" v-model="book.volumes_id"  />
                        <label class="input-group-addon">名称</label>
                        <input type="text" class="form-control" v-model="book.volumes_name"  />
                    </div>
                    <div class="input-group">
                        <label class="input-group-addon">版本</label>
                        <label class="input-group-addon">id</label>
                        <input type="text" class="form-control" v-model="book.version_id"  />
                        <label class="input-group-addon">名称</label>
                        <input type="text" class="form-control" v-model="book.version_name"  />
                    </div>
                    <div class="input-group" style="width: 100%">
                        <select class="form-control" style="width: 50%">
                            <option>系列</option>
                        </select>
                        <select class="form-control" style="width: 50%">
                            <option>子系列</option>
                        </select>
                    </div>
                    <div class="input-group" style="width: 100%">
                        <select class="form-control">
                            <option>出版社/多选</option>
                        </select>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('need_js')
    <script>
        let app = new Vue({
            el: '#app-vue',
            data: {
                grade_info: {},
                subject_info: {},
                volume_info: {},
                version_info: {},
                sort_info: {},
                select_now:'',
                grade_now: 0,
                subject_now: '',
                volume_now: '',
                version_now: '',
                sort_now: '',
                all_book_info: {}
            },
            created() {
                axios.get('{{ route('book_new_api','get_latest_data') }}').then(response => {
                    if (response.data.status === 1) {
                        this.all_book_info = response.data.data;
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            methods: {
                //搜索栏
                search_bar(type) {
                    if(this.select_now===type){
                        return false;
                    }
                    let o ={
                        search_type:type,
                        grade_now:this.grade_now,
                        subject_now:this.subject_now,
                        volume_now:this.volume_now,
                        version_now:this.version_now,
                    };

                    axios.post('{{ route('book_new_api','get_search_bar') }}', o).then(response => {
                        let res = response.data
                        if (res.status === 1) {
                            if(type==='grade'){
                                this.grade_info = res.data;
                            }else if(type==='subject'){
                                this.subject_info = res.data;
                            }else if(type==='volume'){
                                this.volume_info = res.data;
                            }else if(type==='version'){
                                this.version_info = res.data;
                            }else{
                                this.sort_info = res.data;
                            }
                            this.select_now = type;
                        }
                    }).catch(function (error) {
                        console.log(error);
                    })
                },
                //选择栏
                select_btn(type,id){
                    if(type==='grade'){
                        this.grade_now = id;
                    }else if(type==='subject'){
                        this.subject_now = id;
                    }else if(type==='volume'){
                        this.volume_now = id;
                    }else if(type==='version'){
                        this.version_now = id;
                    }else if(type==='sort'){
                        this.sort_now = id;
                    }
                    let o ={
                        grade_now:this.grade_now,
                        subject_now:this.subject_now,
                        volume_now:this.volume_now,
                        version_now:this.version_now,
                    };
                    axios.post('{{ route('book_new_api','get_book_info') }}', o).then(response => {
                        if(response.data.status===1){
                            this.all_book_info = response.data.data;
                        }
                    }).catch(function (error) { console.log(error) });
                },
            },
        });


        {{--$('.grade_search').click(function () {--}}
            {{--let grade_id = $('.grade_search').attr('data-id');--}}
            {{--let subject_id = $('.subject_search').attr('data-id');--}}
            {{--let volume_id = $('.volume_search').attr('data-id');--}}
            {{--let sort_id = $('.sort_search').attr('data-id');--}}
            {{--let o = {--}}
                {{--grade_id,subject_id,volume_id,sort_id--}}
            {{--};--}}
            {{--if($(this).hasClass('grade_search')){--}}
                {{--axios.post('{{ route('book_new_api','get_grade_num') }}',o).then(response=>{--}}
                    {{--if(response.data.status===1){--}}
                        {{--console.log(response.data.data);--}}
                        {{--let grade_html = '';--}}
                        {{--for (let grade_info of response.data.data) {--}}
                            {{--grade_html += `<li><a href="http://www.test1.com/manage/book_arrange/0/0"><i class="fa fa-circle-o">全部</i><small class="label pull-right  bg-blue ">179</small></a></li>`--}}
                        {{--}--}}
                    {{--}--}}
                {{--}).catch(function (error) { console.log(error); })--}}
            {{--}else if($(this).hasClass('subject_search')){--}}

            {{--}else if($(this).hasClass('volume_search')){--}}

            {{--}else if($(this).hasClass('sort_search')){--}}

            {{--}else{--}}

            {{--}--}}

        {{--});--}}
    </script>
@endpush