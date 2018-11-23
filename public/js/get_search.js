/**
 * Created by qwerty on 2017/8/29.
 */
/*
 练习册搜索结果封装
 搜索直接调用boSearch.open() 如果需要在哪个标签附件位置打开窗口，则带上参数为此对象或标签#id
 boSearch.setWH(w,h)设置弹框的宽高 默认为360 500
 依赖jquery jquery-ui bootstrap
 */
var boSearch={
    filters:[],
    grade_names:['未选择','一年级','二年级','三年级','四年级','五年级','六年级','七年级','八年级','九年级','高一','高二','高三','高中必修','高中选修'],
    subject_1010_names:['未选择','语文','数学','英语','物理','化学','地理','历史','政治','生物','科学','综合'],
    subject_hd_names:['未选择','数学','语文','英语','物理','化学','历史','地理','生物','政治','科学','综合'],
    volume_names: ['未选择','上册','下册','全一册','中考用书','高考用书','暑假作业','寒假作业','小升初用书','必修1','必修2','必修3','必修4','必修5','必修其它','选修1系列','选修2系列','选修3系列','选修4系列','选修其它','其它'],
    filter_name:{'grade':'年级','volumes':'卷册','subject':'科目','version':'版本'},
    keyword:'',
    page:1,
    boxWidth:360,
    boxHeight:500,
    ajaxUrl:"http://www.1010jiajiao.com/html5app/ajax/jsonp",
    isInit:0,
    init:function(){
        if(boSearch.isInit) return;
        boSearch.isInit=1;
        $("#search_box").append('<div id="bosearch" title="练习册搜索" style="min-width:300px;"><form class="form-inline"><div class="form-group"><div class="input-group"><input type="text" id="bosearch_word" autocomplete="off" style="width:245px;"  class="form-control"></div></div><button type="submit" class="btn btn-primary">搜索</button></form><div class="filter-box"><div id="bosearch_filter"><div class="btn-group" id="bosearch_filter_grade"><button type="button" class="btn btn-default"><span class="filter_name">年级</span><span class="caret"></span></button><ul class="dropdown-menu"></ul></div><div class="btn-group" id="bosearch_filter_volumes"><button type="button" class="btn btn-default"><span class="filter_name">卷册</span><span class="caret"></span></button><ul class="dropdown-menu"></ul></div><div class="btn-group" id="bosearch_filter_subject"><button type="button" class="btn btn-default"><span class="filter_name">科目</span><span class="caret"></span></button><ul class="dropdown-menu"></ul></div><div class="btn-group" id="bosearch_filter_version"><button type="button" class="btn btn-default"><span class="filter_name">版本</span><span class="caret"></span></button><ul class="dropdown-menu"></ul></div></div></div><div id="bosearch_daan_box"></div><div style="width: 100%;text-align: center;line-height: 28px;background-color: #2693AF;color: #FFFFFF;margin:10px 0; clear:both" onclick="boSearch.goSearch()" id="bosearch_more">加载更多</div></div>');
        boSearch.setFilters();
        $(".form-inline").submit(function(){
            boSearch.page=1;
            boSearch.goSearch();
            return false;
        });

    },
    setFilters:function(){//筛选条件
        var now_grade_name = this.grade_names;
        var now_subject_name = this.subject_hd_names;
        var now_volume_name = this.volume_names;
        boSearch.page=1;
        $("#bosearch_filter .btn-group .btn").click(function(){
            keyword=$.trim($("#bosearch_word").val());
            if(keyword==''){alert('关键词不能为空');return false;}
            var thisbt=$(this).parent();
            var ftype=(thisbt.attr("id")).replace(/bosearch_filter_/,'');
            var filter_pars='a=search&index=test_book&limit=200&word='+encodeURI(keyword);
            for(var i in boSearch.filters) if(ftype!=i) filter_pars+="&"+i+"_id="+boSearch.filters[i].id;
            filter_pars+="&groupby="+ftype;
            $.ajax({
                url: boSearch.ajaxUrl,
                type: "POST",
                jsonp: "callback",
                dataType: "jsonp",
                data:filter_pars,
                success: function( s ) {
                    var fhtm='<li><a onclick="boSearch.resetFiltersGo(\''+ftype+'\',-1,\'不限\')">不限</a></li>';
                    for(var i in s)
                    {
                        if(ftype==='grade'){
                            if(s[i].id==-1){
                                var f_name = '未选择';
                            }else{
                                var f_name = now_grade_name[s[i].id];
                            }
                        }else if(ftype==='volumes'){
                            if(s[i].id==-1){
                                var f_name = '未选择';
                            }else {
                                var f_name = now_volume_name[s[i].id];
                            }
                        }else if(ftype==='subject'){
                            if(s[i].id==-1){
                                var f_name = '未选择';
                            }else {
                                var f_name = now_subject_name[s[i].id];
                            }
                        }else if(ftype=='version'){
                            if(s[i].id==-1){
                                var f_name = '未选择';
                            }else {

                                var f_name = version_all[s[i].id];
                            }
                        }

                        fhtm+='<li><a onclick="boSearch.resetFiltersGo(\''+ftype+'\','+s[i].id+',\''+f_name+'\')"><span>'+f_name+'</span>&nbsp;&nbsp;<i class="badge">'+s[i].count+'</i></a></li>';
                    }
                    thisbt.find(".dropdown-menu").html(fhtm);
                    thisbt.addClass('open').siblings().removeClass('open');
                }
            });
        });
    },
    resetFiltersGo:function(type,id,name){
        $("#bosearch_filter .btn-group").removeClass('open');
        boSearch.page=1;
        boSearch.filters[type]={};
        boSearch.filters[type].id=id;
        boSearch.filters[type].name=name;
        for(var i in boSearch.filters)
        {
            $("#bosearch_filter_"+i+" .filter_name").html("<b>"+boSearch.filters[i].name+"</b>");
        }
        boSearch.goSearch();
    },
    goSearch:function(){
        keyword=$.trim($("#bosearch_word").val());
        if(keyword==''){alert('关键词不能为空');return false;}
        var filter_pars='a=search&index=test_book&sp=1&limit=200&word='+encodeURI(keyword);
        for(var i in boSearch.filters)
        {
            if(boSearch.filters[i].id>-1){
                filter_pars+="&"+i+"_id="+boSearch.filters[i].id;
                filter_pars+="&"+i+"_name="+boSearch.filters[i].name;
            }
        }
        filter_pars+="&page="+boSearch.page;
        $.ajax({
            url: boSearch.ajaxUrl,
            type: "POST",
            jsonp: "callback",
            dataType: "jsonp",
            data:filter_pars,
            success: function( res ) {
                var htm='';
                if(res.length>0){
                    htm=boSearch.handleResult(res);
                    $("#bosearch_more").html(res.length>9?'加载更多':'没有更多结果了');
                }else{
                    htm='';
                    $("#bosearch_more").html('没有更多结果了');
                }
                boSearch.show(htm);
                var lazy = new LazyLoad();
            }
        });
    },
    handleResult:function(res){//改动功能需要重写的函数 基本功能为搜索结果
        var htm='';
        var new_res = [];
        res = this.sortByKey(res,'grade_id');
        new_res = res.reduce(function(result, current) {
            result[current.grade_id] = result[current.grade_id] || [];
            result[current.grade_id].push(current);
            return result;
        }, {});

        // for(var i in new_res){
        //
        //     new_res[i].grade_name = new_res[i][0].grade_name;
        //     new_res[i] = subject_res_now;
        // }
        // console.log(new_res);

        for(var i in new_res){
            htm+= '<div class="row"><h3>'+this.grade_names[new_res[i][0].grade_id]+'</h3>';
            var subject_res = this.sortByKey(new_res[i],'subject_id');
            var subject_res_now = []
            subject_res_now = subject_res.reduce(function(result, current) {
                result[current.subject_id] = result[current.subject_id] || [];
                result[current.subject_id].push(current);
                return result;
            }, {});

            for(var s in subject_res_now){
                htm+= '<div class="col-md-12"><h4>'+this.subject_hd_names[subject_res_now[s][0].subject_id]+'</h4>'
                for(var j in subject_res_now[s]){
                    htm+='<div class="single_book_box" data-id="'+subject_res_now[s][j].id+'" style="background-color:#FBFAD6;width:400px;float:left;padding: 5px; margin: 5px;">';
                    htm+='	<div class="text-center" style="float: left">';
                    htm+='	<a ><img style="width:150px;" data-original="'+subject_res_now[s][j].cover+'"  /></a>';
                    htm+='	</div>';
                    htm+='	<ul style="line-height: 25px;  padding-left: 6px;float: left;max-width: 240px;list-style: none;">';
                    htm+='		<li>id： '+subject_res_now[s][j].id+'</li>';
                    htm+='		<li>isbn： '+subject_res_now[s][j].isbn+'</li>';
                    htm+='		<li>年级： '+this.grade_names[subject_res_now[s][j].grade_id]+'</li>';

                    htm+='		<li>科目： '+this.subject_hd_names[subject_res_now[s][j].subject_id]+'</li>';

                    htm+='		<li>卷册： '+this.volume_names[subject_res_now[s][j].volumes_id]+'</li>';
                    htm+='		<li>版本： '+version_all[subject_res_now[s][j].version_id]+'</li>';
                    htm+='		<li class="bianji">';
                    htm+='			<a onclick="add_to_buy(this)" data-id="'+subject_res_now[s][j].id+'" class="btn btn-xs btn-warning">购买此书</a>';
                    htm+='		</li>';
                    htm+='	</ul>';
                    htm+='</div>';
                }
                htm += '</div>'
            }

            htm+= '</div>'
        }

        // for(var i in res){
        //     htm+='<div class="single_book_box" data-id="'+res[i].id+'" style="background-color:#FBFAD6;width:400px;float:left;padding: 5px; margin: 5px;">';
        //     htm+='	<div class="text-center" style="float: left">';
        //     htm+='	<a href="http://www.1010jiajiao.com/daan/bookid_'+res[i].id+'.html" target="_blank"><img style="width:150px;" src="'+res[i].cover+'"  /></a>';
        //     htm+='	</div>';
        //     htm+='	<ul style="line-height: 25px;  padding-left: 6px;float: left;max-width: 240px;list-style: none;">';
        //     htm+='		<li>id： '+res[i].id+'</li>';
        //     htm+='		<li>系列： '+res[i].sort_name+'</li>';
        //     htm+='		<li>年级： '+res[i].grade_name+'</li>';
        //     htm+='		<li>科目： '+res[i].subject_name+'</li>';
        //     htm+='		<li>卷册： '+res[i].volumes_name+'</li>';
        //     htm+='		<li>版本： '+res[i].version_name+'</li>';
        //     htm+='		<li class="bianji">';
        //     htm+='			<a onclick="add_to_buy(this)" data-id="'+res[i].id+'" class="btn btn-xs btn-warning">购买此书</a>';
        //     htm+='		</li>';
        //     htm+='	</ul>';
        //     htm+='</div>';
        // }
        return htm;
    },
    show:function(htm){
        boSearch.page==1?$("#bosearch_daan_box").html(htm):$("#bosearch_daan_box").append(htm);
        boSearch.page++;
    },
    open:function(nearby){//如果需要在哪个标签附件位置打开窗口，则带上参数为此对象
        if(!boSearch.isInit) boSearch.init();
        //$("#bosearch").dialog({height:boSearch.boxHeight,width:boSearch.boxWidth});
        //$(".ui-dialog").position({my:"right center",at: "right bottom",of:nearby?nearby:""});
    },
    setWH:function(width,height){
        boSearch.boxWidth=width;
        boSearch.boxHeight=height;
    },
    sortByKey:function(array, key) {
        return array.sort(function(a, b) {
            var x = a[key]; var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
    },
}