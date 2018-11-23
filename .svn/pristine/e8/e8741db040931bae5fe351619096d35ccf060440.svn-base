var underlineMakeups = '<span class="fill-in">&nbsp;</span>';
var separatorMakeups = '<span class="separator"></span>';
/*-----------henry-------------*/	
// 	$(".qdp li").live("click",function(){
//
// 		qid = $(this).parent().attr("id");
//
// 		$.get(DT_STATIC+"ajax/credit.php?type=addcredit&credit="+$(this).attr("qcredit")+"&qid="+qid,function(data){
// 			if(data!=0)
// 		$("#"+qid).html(data);
// 		});
//
// });
// $(".qpl li").click(function(){
//
// });
// $(".pbt button").click(function(){
// if($(".pbt button").html()!="发表评论") return;
// qid = $(this).parent().attr("vid");
// message = $("#message"+qid).val();
// if(message.length<4){alert("内容太短"); return;}
//
// $("#message"+qid).val("");
//
// $(".pbt button").html("提交中..");
//
// 		$.get(DT_STATIC+"ajax/credit.php?type=addcomment&comment="+message+"&qid="+qid,function(data){
// 			if(data!=0)
// 		$("#comcontent_"+qid).html(data);
//
// 		setTimeout("$('.pbt button').html('发表评论')",5000);
// 		});
//
//
// });

/*---------------bocai---------------*/
// var request_url=DT_STATIC+"ajax/zujuan.php";
	
function is_tag_closed(str){//检查标签是否闭合
	var arrTags=["span","font","b","u","i","h1","h2","h3","h4","h5","h6","em","strong","p","li","ul","table","tr","td","div","tbody"]; 
	for(var i=0;i<arrTags.length;i++){ 
		var intOpen=0; 
		var intClose=0; 
		var re=new RegExp("<"+arrTags[i]+"( [^<>]*|)>","ig");  
		var arrMatch=str.match(re); 
		if(arrMatch!=null) intOpen=arrMatch.length; 
		re=new RegExp("</"+arrTags[i]+">","ig");
		arrMatch=str.match(re); 
		if(arrMatch!=null) intClose=arrMatch.length; 
		if(intOpen!=intClose){alert(arrTags[i]+'标签不匹配');return false;}
	} 
	return true;
}

function find_not_close_tag(str){//查找未闭合标签，记录数量返回
	var arrTags=["span","font","b","u","i","h1","h2","h3","h4","h5","h6","em","strong","p","li","ul","td","tr","tbody","table","div"]; 
	var ret=[];
	for(var i=0;i<arrTags.length;i++){ 
		var intOpen=0; 
		var intClose=0; 
		var re=new RegExp("<"+arrTags[i]+"( [^<>]*|)>","ig"); 
		var arrMatch=str.match(re); 
		if(arrMatch!=null) intOpen=arrMatch.length; 
		re=new RegExp("</"+arrTags[i]+">","ig");
		arrMatch=str.match(re); 
		if(arrMatch!=null) intClose=arrMatch.length; 
		
		var tn=intOpen-intClose;
		if(tn<0){alert("代码异常");return;}
		else if(tn>0){
			ret[i]={};
			ret[i].n=tn;
			ret[i].tag=arrTags[i];
		}
	} 
	return ret;
}

	function changeWH(type)
	{
		var fd=50;//变化幅度
		var fra=$(".edui-editor-iframeholder.edui-default");
		if(type==1)
		{
			var newW=fra.width()+fd;
			fra.width(newW);
			fra.parent().width(newW);
			fra.parent().parent().width(newW);
		}
		else if(type==2)
		{
			var newW=fra.width()-fd;
			fra.width(newW);
			fra.parent().width(newW);
			fra.parent().parent().width(newW);
		}
		else if(type==3)
		{
			var newH=fra.height()+fd;
			fra.height(newH);
			var newcontH=$(".cont").height()+fd*2;
			$(".cont").height(newcontH);
		}
		else if(type==4)
		{
			var newH=fra.height()-fd;
			fra.height(newH);
			var newcontH=$(".cont").height()-fd*2;
			$(".cont").height(newcontH);
		}
	}

	function del_nousetag(str)
	{
		str=str.replace(/<span[^>]+underline[^>]*>([^<]*)<\/span>/ig,"<u>$1</u>");
		
		var rp=new RegExp("</*(font|a|st1)[^<>]*>","ig");
		str=str.replace(rp,"");
		
		str=str.replace(/line-height: normal;\s*|white-space: normal;\s*|text-align: justify;\s*|font-family:[^;>]+;\s*/ig,"");//删除不需要代码
		str=str.replace(/font-stretch: normal;\s*|font-family: '[^']*'/ig,"");//删除不需要代码
		str=str.replace(/font-family: [^"]*">/ig,'">');//删除不需要代码
		
		str=str.replace(/\s*style=""|<p><\/p>/ig,"");//删除不需要代码
		
		str=str.replace(/<u>([^<]*)<\/u>/ig,"<span style=\"text-decoration: underline;\">$1</span>");//替换回来解决编辑器不能识别下划线的问题
		return str;
	}

function addbyue(uobj,type)
{
	uobj.ready(function(){
		//$(document.getElementById('ueditor_0').contentWindow.document).click(function(e){
		if(type=='addque')
		{
			var fr_add1=frames['ueditor_0'].contentWindow;
			
			//题目框加入划词搜索功能
			uobj.addListener( 'afterSelectionChange', function (){
				//var select_txt=fr_add1.getSelection().toString();
				var select_txt=uobj.selection.getText();
				if(select_txt.length>3 && select_txt.length<100)//划词的长度满足条件
				{
					auto_search(select_txt);
				}
	   		});
		}
		else if(type=='addans') var fr_add1=frames['ueditor_1'].contentWindow;
		var fen='||||||||||';
		
		
		uobj.addListener( 'click', function (){
			if(fr_add1.event.ctrlKey)
			{
				if(type=='addque'){
                    var questionType = $("#question_type").val();
                    if(!Number(questionType)) {
                        alert('请选择类型！');
                        return false;
                    }
                }
                var htm8=$(fr_add1.document.body).html();//没修改的原始代码
				uobj.execCommand("inserthtml",fen);
				var htm=$(fr_add1.document.body).html();
				var fpos=htm.indexOf(fen);
				var qie=htm.substr(0,fpos);
               
				//$(fr_add1.document).find(".fenge:eq(0)").remove();
				var ret=find_not_close_tag(qie);
				var addclose='',addopen='';
				if(ret.length>0)//如果有未闭合的标签自动补上
				{
					for(var i in ret)
					{
						for(i2=0;i2<ret[i].n;i2++)
						{
							addclose+="</"+ret[i].tag+">";
						}
					}
					ret.reverse();
					for(var i in ret)
					{
						for(i2=0;i2<ret[i].n;i2++)
						{
							addopen+="<"+ret[i].tag+">";
						}
					}
					qie+=addclose;
				}
				qie=del_nousetag(qie);
                 
				if(is_tag_closed(qie))
				{
                    if(type=='addque'){addque(qie,questionType);} else if(type=='addans'){addans(qie);}
                    uobj.setContent(addopen+htm.substr(fpos+fen.length));
				}
				else uobj.setContent(htm8);
			}
		});
	});	
}

$(function(){
	// addbyue(ue1,'addque');
	// addbyue(ue2,'addans');
});
	
	function addque(ti_que,type)
	{
		var timuid=uid;
		ti_que = ti_que.replace(new RegExp(underlineMakeups.replace(/ = /g,' ?= ?'),'g'),underlineMakeups.replace(/>&nbsp;</,'><'));
		if($(".inorder").length>0) var orderid=parseInt($(".inorder:last").val())+1;
		else var orderid=1;
		var order_input='序号：<input type="text" class="inorder" value="'+orderid+'" />';
        var selectText = '<select name = "question_type" style = "font-size:0.8em;color:#118F99;margin-left:0.8em;">'
                   + '<option value = "0">题型</option>'
                   + '<option value = "1">单项选择</option>'
                   + '<option value = "2">多项选择</option>'
                   + '<option value = "3">判断题</option>'
                   + '<option value = "4">填空题</option>'
                   + '<option value = "5">解答题</option>'
		           + '</select>';
        selectText = selectText.replace(new RegExp('(value = "' + type + '")'),'$1 selected = "selected"');
        var t_count=$("#timu_box .xiti").length+1;
		
		timuid=timuid+"_"+Math.round(new Date().getTime()/10);
			var questionType = type;
            var ti_ans = '';
			var ti_pre='<div class="xiti" id="m_'+add_timuid+'" uid="'+uid+'">'+order_input+selectText+'<div class="xtq"><div>';
			var ti_next='</div></div></div>';
			var request_pars = "type=addtoWorkbook&timuid="+add_timuid+"&bookid="+bookid+"&chapterid="+chapterid+"&t_count="+t_count+"&pageid="+pageid+"&qtype="+questionType;
			axios.post(request_url+"?"+request_pars , {question:ti_que}).then(response=>{
				if(response.data.status==1)
				{
					$('#m_'+add_timuid+' .que').html(ti_que);
                    // var ti_html=ti_pre+'<div class="que">'+ti_que+'</div>'+'<div class="ans">'+ti_ans+'</div>'+'<div class="analysis"></div><div class="remark"></div>' + ti_next;
                    // $("#timu_box").append(ti_html);
                    // $('#timu_box .xiti:last select').change(function() {
                    //     changeQuestionType(add_timuid,this.value);
                    // });
					var h = $(document).height()-$(window).height();
					$(document).scrollTop(h);
				 }
				 else alert("添加失败");
			});
	}
	
	function addans(str)
	{
        $(".xiti").each(function(){
            if(!$(this).attr("added"))
            {
                var questionType = $('#question_type').val();
				if($('.ans',this).html())
                    $(this).attr("added",1);
                else {
                    var timuid=$(this).attr("id").substr(2);
                    $(this).attr("added",1);
                    $(document).scrollTop($(this).offset().top);
                    var arr = str.split(/<p[^<>]*>\s*<br ?\/?>\s*<\/p>/);
                    var data = {};
                    switch(questionType) {
                        case '1' :{
                        }
                        case '2' :{
                            var answer = '';
                            var uniAnswer = '';
                            var matchs;
                            if(matchs = arr[0].match(/([1-9]\d|[1-9])(?:\s|&nbsp;)*[-\u301c~](?:\s|&nbsp;)*([1-9]\d|[1-9])\.?(?:\s|&nbsp;)*([A-D]{1,10})/g)) {
                                var length = matchs.length;
                                for(var i=0;i<length;i++) {
                                    var match = matchs[i].match(/([1-9]\d|[1-9])(?:\s|&nbsp;)*[-\u301c~](?:\s|&nbsp;)*([1-9]\d|[1-9])\.?(?:\s|&nbsp;)*([A-D]{1,10})/);
                                    answer += ' ' +　match[0];
                                    for(var k=0,j=Number(match[1]);j<=Number(match[2]);j++,k++) 
                                        uniAnswer += ',ABCD|' + match[3][k];
                                }
                            } else if (matchs = arr[0].match(/(?:[1-9]\d|[1-9])\.(?:\s|&nbsp;)*([A-D]{1,4})/g)) {
                                var len = matchs.length;
                                for(var i=0;i<len;i++) {
                                    var match = matchs[i].match(/(?:[1-9]\d|[1-9])\.(?:\s|&nbsp;)*([A-D]{1,4})/);
                                    answer += ' ' +　match[0];
                                    uniAnswer += ',ABCD|' +match[1];
                                }
                            }
                            if(answer) {
                                data.answer = answer.substr(1);
                                $(this).children(".xtq").children().children(".ans").html(data.answer);
                                data.uni_answer = uniAnswer.substr(1);
                                if(!arr[0].match(/(?:(?:[1-9]\d|[1-9])\.(?:\s|&nbsp;)*[A-D]{1,4}(?:\s|&nbsp;)*)+(?:<\/p>)?$/) && 
                                   !arr[0].match(/((?:[1-9]\d|[1-9])(?:\s|&nbsp;)*[-\u301c~](?:\s|&nbsp;)*(?:[1-9]\d|[1-9])\.?(?:\s|&nbsp;)*[A-D]{1,10}(?:\s|&nbsp;)*)+(?:<\/p>)?$/)) {
                                    data.analysis = arr[0];
                                    $(this).children(".xtq").children().children(".analysis").html(data.analysis); 
                                }
                            } else {
                                data.answer = arr[0];
                                $(this).children(".xtq").children().children(".ans").html(data.answer);
                            }
                            break;
                        }
                        case '3' :{
                        }
                        case '4' :{
                        }
                        case '5' :{
                            data.answer = arr[0];
                            $(this).children(".xtq").children().children(".ans").html(data.answer);
                            break;
                        }
                        default:break;
                    }
                    var request_pars="type=add_ans&timuid="+timuid+"&chapterid="+chapterid;
                    axios.post(request_url+"?"+request_pars , data).then(response=>{
                        if(response.data.status==1){}
                        else alert("答案保存失败");
                    });
                    if($(this).children(".xtq").children().children(".uni-answer").html()) {
                        $('textarea',$(this).children(".xtq").children().children(".uni-answer")).html(data.uni_answer);
                    } else {
                        $(this).children(".xtq").children().children(".ans")
                               .before('<p class="uni-answer"><textarea name = "uni-answer"  rows = "2" cols = "75" readonly = "readonly">' + (data.uni_answer?data.uni_answer:'') + '</textarea></p>');
                    }
                    $('textarea',$(this)).dblclick(function() {
                        this.readOnly = false;
                    });
                    $('textarea',$(this)).mouseout(function() {
                        this.readOnly = 'readonly';
                    });
                    $('textarea',$(this)).change(function() {
                        changeUniAnswer(this.parentNode.parentNode.parentNode.parentNode.id.substr(2),this.value);
                    });
                    return false;
                }
            }
        });
	}
	
	function set_ans_added()
	{
		var num=parseInt($("#ans_added").val());
		
		$(".inorder").each(function(){
			if($(this).val()<=num) $(this).parent().parent().attr("added",1);
		});
	}

	function popWin(a) {
        var editWindow = document.getElementById(a),mouseUp = true,mouseStartX,mouseStartY;
        editWindow.style.zIndex = 2;
        editWindow.style.display = 'block';
        editWindow.style.left = (window.innerWidth + window.innerWidth%2)/2 -  ($(editWindow).width() + $(editWindow).width()%2)/2  + 'px';
        editWindow.style.top = (window.innerHeight + window.innerHeight%2)/2 -  ($(editWindow).height() + $(editWindow).height()%2)/2  + 'px';
        editWindow.getElementsByTagName('div')[0].onmousedown = function(e) {
            mouseUp = false;
            event = e || window.event;
            mouseStartX = event.clientX-parseInt(editWindow.style.left);
            mouseStartY = event.clientY-parseInt(editWindow.style.top);
        }
        editWindow.onmouseup = function() {
            mouseUp = true;
        }
        editWindow.onmousemove = function(e) {
            if(!mouseUp) {
                event = e || window.event;
                this.style.left = event.clientX - mouseStartX + 'px';
                this.style.top = event.clientY - mouseStartY + 'px';
            }
            return false;
        }
        editWindow.getElementsByTagName('div')[0].getElementsByTagName('i')[0].onclick = function(){t_close(a);}
        document.onkeydown = function(e) {
            var currentKey,event = e || window.event;
            currentKey = event.keyCode || event.which || event.charCode;
            if(currentKey == 27) t_close(a);
        }
    }


	function t_edit(timuid)
	{
		var tbox=$("#m_"+timuid);
		var t=$("#m_"+timuid+" .xtq>div");
		var que=tbox.find(".que").html();

		var que_type = parseInt(tbox.attr('data-type'));
		$('#question_type').val(que_type);
        $('#answer_new').remove();
		if(que_type===1){
			var que_now_ans = tbox.find('.choice').html();
			$('#question_type').after(`<input class="form-control" type="text" id="answer_new" placeholder="如ABCD|A,ABCD|C,判断题如TF|T" value="${que_now_ans}"/>`)
		}
        que = que.replace(new RegExp(underlineMakeups.replace(/ = /g,' ?= ?').replace(/&nbsp;/g,''),'g'),underlineMakeups);
		var ans=tbox.find(".ans").html();
		var analysis = tbox.find(".analysis").html();
		var remark = tbox.find(".remark").html();
		var answerText = ans;

		if(remark)
			answerText += '<p><br></p>' + remark;
		//$("#E_edit1").html(que);
		//$("#E_edit2").html(ans);
		um1.ready(function(){
			um1.setContent(que);um2.setContent(answerText);
			if(analysis){
				um3.setContent(analysis);
			}else{
				um3.setContent('');
			}

		});
		 um2.focus(true);
		$("#floatbox_edit").attr("timuid",timuid);
		popWin('floatbox_edit');
		var questionBodyElement = document.getElementById('ueditor_2').contentWindow.document.getElementsByTagName('body')[0];
		questionBodyElement.onkeydown = function(e) {
			var currentKey,event = e || window.event;
			currentKey = event.keyCode || event.which || event.charCode;
			if(event.ctrlKey && currentKey == 91) {
				um1.focus();
				um1.execCommand('inserthtml',underlineMakeups);
				questionBodyElement.innerHTML = questionBodyElement.innerHTML
					.replace(new RegExp('[(\\uff08](?:\\s|&nbsp;)*'　+　underlineMakeups.replace(/ = /g,' ?= ?') +　'(?:\\s|&nbsp;)*[)\\uff09]'),'(<span class="paren"></span>)');
				return false;
			}
		}


	}

	function t_search_edit(timuid) {
		$("#floatbox_edit").attr("timuid",timuid);
		popWin('floatbox_edit');
	}
    
    function changeQuestionType(timuid,type) {
        $.ajax({
            type:'GET',
            url:request_url,
            data:{
                type:'changeQuestionType',
                timuid:timuid,
                question_type:type
            },
            success:function(response){
                if(response.status!="1")
                    alert("修改类型失败！");
            }
        });
    }
    
    function changeUniAnswer(timuid,uniAnswer) {
        $.ajax({
            type:'GET',
            url:request_url,
            data:{
                type:'changeUniAnswer',
                timuid:timuid,
                uni_answer:uniAnswer
            },
            success:function(response){
                if(response!="1")
                    alert("答案规范化失败！");
            }
        });
    }
    
    function testOnlineExercise() {
        //alert('abcdabc'.match(/(abc)d\1/));
        $.ajax({
            type:'GET',
            url:request_url,
            data:{
                type:'getExercisesBychapter',
                chapterid:chapterid
            },
            success:function(response) {
                var jsonObject = eval('(' + response +')');
                var length = jsonObject.length;
                var exercisesDiv = document.createElement('div');
                exercisesDiv.id = "exercises";
                var htmlText = '<ul>';
                for(i=0;i<length;i++) {
                    switch(jsonObject[i]['type']) {
                        case '1' :{
                            var question = jsonObject[i]['question'].replace(/([A-D]\.)/g,'<input type = "radio" name = "t_' + jsonObject[i]['timuid'] + '" /> $1');
                            for(j=0;j<5;j++)
                                question = question.replace(/(<p[^<>]*>(?:\s|&nbsp)*)(\d{1,2})(\.(?:\s|&nbsp){1}[\s\S]+?<input type = "radio" name = ")(t_\d+_\d+)(" \/> [A-D]\.)/g,'$1$2$3$4_$2$5');
                            htmlText += '<li id = "t_' + jsonObject[i]['timuid'] 
                                     + '" class="exercise"><div>' 
                                     + question + '</div></li>';  
                            break;
                        }
                        case '2' :{
                            htmlText += '<li id = "t_' + jsonObject[i]['timuid'] 
                                     + '" class="exercise"><div>' 
                                     + jsonObject[i]['question'].replace(/([A-D]\.)/g,'<input type = "checkbox" name = "t_' + jsonObject[i]['timuid'] + '[]" /> $1') + '</div></li>';
                            break;
                        }
                        case '3' :{
                            break;
                        }
                        case '4' :{
                            
                        }
                        case '5' :{
                            htmlText += '<li id = "t_' + jsonObject[i]['timuid'] + '" class="exercise"><div>' + jsonObject[i]['question'] + '</div></li>';
                            break;
                        }
                        default:break;
                    }
                          
                }
                htmlText += '</ul>';
                exercisesDiv.innerHTML = htmlText;
                var closeButton = document.createElement('input');
                closeButton.type = 'button';
                closeButton.value = '关闭';
                closeButton.className = 'close';
                closeButton.onclick = function(){
                    document.body.removeChild(boxDiv);
                    document.body.removeChild(exercisesDiv);
                }
                var boxDiv = document.createElement('div');
                boxDiv.id = "box";
                //boxDiv.appendChild(exercisesDiv);
                document.body.appendChild(boxDiv);
                document.body.appendChild(exercisesDiv);
                boxDiv.appendChild(closeButton);
            }
        });
    }
	
	function get_timupic()
	{
		if($("[name='sel_page']").html().length==0)
		{
			var request_pars="type=get_timupic&bookid="+bookid;
			$.get(request_url , request_pars , function(data){
				var pics=eval("("+data+")");
				if(pics.length==0){
					alert('本书暂时还没有内容');
					t_close('floatbox_timupic');
					return;
				}
				var ops='';
				for(var k in pics) ops+='<option value="'+pics[k].question+'">'+pics[k].text+'</option>';
				$("#sel_page [name='sel_page']").html(ops);
				$("#timupic").attr("src",DT_PIC+pics[0].question);
			});
		}
		$("[name='sel_page']").change(function(){
			$("#timupic").attr("src",DT_PIC+$(this).val());
		});
		
		popWin('floatbox_timupic');
	}
	
	function t_del(timuid)//删除题目
	{

		var t_count=$("#timu_box .xiti").length-1;
		var request_pars = "type=t_del&chapterid="+chapterid+"&timuid="+timuid+"&t_count="+t_count;
		axios.post(request_url , request_pars).then(response=>{
			if(response.data.status==1)
			{
				$("#m_"+timuid+" .question_type").remove();
				$("#m_"+timuid+" .que").html('');
				$("#m_"+timuid+" .ans").html('');
			}
			else{
				alert("删除失败");
				// $("#m_"+timuid+" .question_type").show();
				// $("#m_"+timuid+" .que").show();
				// $("#m_"+timuid+" .ans").show();
			}
		});
	}
	
	function update_editable()//变更可编辑状态
	{
		if(confirm("确定后章节将不能再编辑")==false) return;
		var request_pars = "type=update_editable&bookid="+bookid;
		$.get(request_url , request_pars , function(data){
			if(data==1)
			{
				alert('设置成功');
				window.location="?bookid="+bookid+"&action=show";
			}
			else{
				alert("操作失败");
			}
		});
	}
	
	function t_complete()//章节题目编辑完成状态更新
	{
		var request_pars = "type=t_complete&bookid="+bookid+"&chapterid="+chapterid;
		$.get(request_url , request_pars , function(data){
			if(data==1)
			{
				alert('状态设置成功！');
			}
			else{
				alert("操作失败");
			}
		});
	}
	
	function t_save()//编辑时保存
	{
		var editbar_sc=$("#editbar2").html();

		var timuid=$("#floatbox_edit").attr("timuid");
		var timutype = parseInt($('#question_type').val());
		var answer_new = '';
		if($('#answer_new').length>0){
			answer_new = $('#answer_new').val();
		}
		if(timutype===0){
			alert('请选择题型');
			return false;
		}
		if(timutype===1){
            if(answer_new.indexOf('|')===-1){
                alert('格式有误,请检查');
                return false;
            }
		}else if(timutype===2){
            if(answer_new.indexOf('|')===-1 || answer_new.indexOf(',')===-1){
                alert('格式有误,请检查');
                return false;
            }
		}else if(timutype===3){
            if(answer_new.indexOf('|')===-1){
                alert('格式有误,请检查');
                return false;
            }
		}else{}


        var orderid=$("#m_"+timuid+" .inorder").val();
		var request_pars = {
			type:'t_save',
			chapterid:chapterid,
			timuid:timuid,
			question_type:timutype,
			bookid:bookid,
			pageid:pageid,
			answer_new:answer_new
		};
		um1.ready(function(){
            var ti_que=um1.getContent();
            var ti_ans=um2.getContent();
            var ti_analysis = um3.getContent();
            ti_que = ti_que.replace(new RegExp(underlineMakeups.replace(/ = /g,' ?= ?'),'g'),underlineMakeups.replace(/>&nbsp;</,'><'));
            var arr = [];
			arr = ti_ans.split(/<p[^<>]*>\s*<br ?\/?>\s*<\/p>/);
            //arr[3] = 5;
            var data = {};
			request_pars['question'] = ti_que;
            arr[0]=ti_ans;
            arr[1]='';
            arr[2]='';
            if(timutype===4){
                if(ti_que.indexOf('answer_now')===-1){
                	alert('格式有误,请检查');
                	return false;
				}
            }
            $("#editbar2").html(loading_msg("正在保存，请稍等"));
			request_pars['answer'] = arr[0],request_pars['analysis'] = ti_analysis||'',request_pars['remark'] = arr[2]||'';
			axios.post(request_url, request_pars).then(response=>{
				if(response.data.status===1)
				{
                    $('.xiti[id="m_'+timuid+'"]').attr('data-type',timutype);
					$('.xiti[id="m_'+timuid+'"] .que').html(ti_que);
					$('.xiti[id="m_'+timuid+'"] .ans').html(arr[0]);
					if($('.xiti[id="m_'+timuid+'"] .analysis').length>0){
                        $('.xiti[id="m_'+timuid+'"] .analysis').html(ti_analysis);
					}else{
                        $('.xiti[id="m_'+timuid+'"] .ans').after(`<div class="analysis">${ti_analysis}</div>`);
					}

					$('.xiti[id="m_'+timuid+'"] .remark').html(arr[2]);
					t_close('floatbox_edit');
					$("#m_"+timuid+" .xth>span").remove();
					$("#m_"+timuid).children(".bottom").html('<a href="javascript:void(0)" class="add_timu">搜索</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\''+timuid+'\')">编辑</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_del(\''+timuid+'\')">删除</a>');//编辑完后修改删除权限
				}
				else alert("保存失败");
				$("#editbar2").html(editbar_sc);

			});
            // $.post(request_url+"?"+request_pars , data , function(data){
            // if(data==1)
			// {
             //    $('#m_' +　timuid　+ ' div.que').html(ti_que);
             //    $('#m_' +　timuid　+ ' div.ans').html(arr[0]);
             //    $('#m_' +　timuid　+ ' div.analysis').html(arr[1]);
             //    $('#m_' +　timuid　+ ' div.remark').html(arr[2]);
			// 	//bindzhankai();
			// 	t_close('floatbox_edit');
			// 	$("#m_"+timuid+" .xth>span").remove();
			// 	$("#m_"+timuid).children(".bottom").html('<a href="javascript:void(0)" onclick="t_edit(\''+timuid+'\')">编辑</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_del(\''+timuid+'\')">删除</a>');//编辑完后修改删除权限
			// }
			// else alert("保存失败");
			// $("#editbar2").html(editbar_sc);
			// });
		});
	}
	
	function t_close(boxid)//关闭编辑窗口
	{
		// if(boxid=='floatbox_add')//如果是添加题目窗口 关闭时清除不需要的内容
		// {
		// 	$("#txtbox").text('');
		// 	ue1.ready(function(){ue1.setContent('');ue2.setContent('');});
		// }
		$("#"+boxid).hide();
		//if(!$(".floatbox").is(":visible")) $("#maskLayer").remove();//没有可见的窗口后移除透明遮罩层
	}
	
	function t_hide(timuid)
	{
		$("#m_"+timuid).hide();
		var qid=$("#m_"+timuid).attr("qid");
		var request_pars = "type=t_hide&chapterid="+chapterid+"&qid="+qid;
		$.get(request_url , request_pars , function(data){
			if(data==1)
			{
				$("#m_"+timuid).remove();
			}
			else{
				alert("隐藏失败");
				$("#m_"+timuid).show();
			}
		});
	}

	function auto_timubar()
	{
		//if(is_teacher==='1'){}
		
			$(".xiti").each(function(){
				if($(this).children(".bottom").length==0)
				{
					var timuid=$(this).attr("id").substr(2);
					var edit_uid=$(this).attr("uid");
					console.log($(this).attr('data-created')!='null');
                    if($(this).attr('data-vid')!=''){
                        video_str = '<button data-toggle="modal" data-vid="'+$(this).attr('data-vid')+'" data-timu-id="'+timuid+'" data-target="#for_uploaded" class="btn btn-danger btn-bg pull-left check_upload_btn">查看上传视频</button>';
                    }else{
                        video_str = '<button data-toggle="modal" data-vid="'+$(this).attr('data-vid')+'" data-timu-id="'+timuid+'" data-target="#for_upload" class="btn btn-primary btn-bg pull-left video_upload_btn">上传视频</button>';
                    }
					if($(this).attr('data-created')!='null'){
						//	console.log('edit_uid:'+edit_uid+"=>"+uid);
						var toolcon=edit_uid==uid?'<a href="javascript:void(0)" onclick="t_del(\''+timuid+'\')">删除</a>':'<font color="#E2E2E2">删除</font>';//&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_hide(\''+timuid+'\')">隐藏</a>
						

						
						var instr=video_str+'<p class="bottom"><a href="javascript:void(0)" class="add_timu">搜索</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\''+timuid+'\')">编辑</a>&nbsp;&nbsp;'+toolcon+'</p>';
						if(edit_uid==uid || uid==3448093) $(this).append(instr);
					}else{
						$(this).append(video_str+'<p class="bottom"><a href="javascript:void(0)" class="add_timu">新增</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\''+timuid+'\');">编辑</a></p>');

					}

				}
			});
		
	}
	
	function loading_msg(note)
	{
		return note+'... ...<img src="/images/loading.gif" />';
	}
	
	function update_orderid()//更新题号
	{
		var n=$(".inorder").length;
		if(n==0){alert('本章节还没有题目');return;}
		$('#editbar3').html(loading_msg('正在更新，请稍等'));
		var i=0;
		$(".inorder").each(function(){
			var timuid=$(this).parent().attr("id").substr(2);
			var orderid=$(this).val();
			$.ajax({url:request_url+"?type=update_orderid&timuid="+timuid+"&orderid="+orderid,type:"GET",success:function(data){
					i++;
					if(n==i){alert("更新完成！");location.reload();}
				}
			});
		});
	}
	
	function timu2pic()//生成课件
	{
		var qidstr='';
		$(".kejian_sel").each(function(){
			if($(this).attr("checked")=="checked"){
				var qid=$(this).parent().parent().parent().attr("qid");
				qidstr+=qidstr==''?qid:(","+qid);
			}
		});
		if(qidstr==''){alert('请先将要生成课件的题目加入课件后再生成！');return;}
		$('#editbar3').html(loading_msg('请勿关闭当前页面，正在生成课件'));
		$.getScript("http://ck.1010jiajiao.com/ajax/jsonp.php?type=timu2pic&chapterid="+chapterid+"&qidstr="+qidstr+"&call=timu2pic_check");
	}
	
	function timu2pic_check(data)
	{
		var status=data.substr(0,2);
		
		if(status=='s|')//课件生成成功
		{
			var roomid=data.substr(2);
			alert("生成完毕，即将转入房间");
			window.location="http://zhibo.1010jiajiao.com/tc/room.php?roomID="+roomid+"&account=1";
			return;
		}
		else
		{
			if(data==5)
			{
				alert("请先发布本章节课程后才能生成课件哦！");
			}
			else alert("生成失败");
			$('#editbar3').html(editbar3);
		}
	}
	
	function add_chapter_des()
	{
		var des=$("[name='chapter_des']").val();
		var request_pars ="type=add_chapter_des&chapterid="+chapterid;
		$.post(request_url+"?"+request_pars , {des:des} , function(data){
			 if(data==1)
			 {
				alert("更新成功！");
			 }
			 else alert("添加失败");
		});
	}
	
	function addtoWorkbook(timuid,timumd5id,from)//from 来自题库还是手动录入
	{
		//var scro=$(document).height()+1000;alert(scro);$(document).scrollTop(scro);
		/*$(document.getElementById('ueditor_0').contentWindow.document.body).keydown(function(key){
			alert(key.keyCode);
		});*/
		if($(".inorder").length>0) var orderid=parseInt($(".inorder:last").val())+1;
		else var orderid=1;
		var order_input='序号：<input type="text" class="inorder" value="'+orderid+'" />';
		
		var t_count=$("#timu_box .xiti").length+1;
		
		if(from=='tiku')
		{
			t_close('floatbox_add');

			t_search_edit(timuid);
	   		var ti_que=$("#t_"+timumd5id+" .xtq>div>.que").html();
			var ti_ans=$("#t_"+timumd5id+" .xtq>div>.ans").html();
			um1.setContent(ti_que);um2.setContent(ti_ans);
			// var ti_pre='<div class="xiti" id="m_'+timuid+'" uid="'+uid+'">'+order_input+'<div class="xtq"><div>';
			// var ti_next='</div></div></div>';
			// var qtype=$("#question_type").val();
			// ue1.setContent(ti_que);ue2.setContent(ti_ans);
			$("#m_"+timuid).children(".bottom").html('<a href="javascript:void(0)" class="add_timu">搜索</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_edit(\''+timuid+'\')">编辑</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_del(\''+timuid+'\')">删除</a>');//编辑完后修改删除权限
			// if(qtype==0){alert('请选择类型！');return;}
			// var request_pars = "type=addtoWorkbook&timuid="+timuid+"&bookid="+bookid+"&chapterid="+chapterid+"&t_count="+t_count+"&orderid="+orderid+"&qtype="+qtype;
			// axios.post(request_url+"?"+request_pars , {question:ti_que,answer:ti_ans}).then(response=>{
			//  if(response.data.status==1)
			//  {
			//  	$('#m_'+timuid+' .que').html(ti_que);
			// 	 $('#m_'+timuid+' .ans').html(ti_ans);
			// 	 var toolcon = '<a href="javascript:void(0)" onclick="t_del(\''+timuid+'\')">删除</a>';//&nbsp;&nbsp;<a href="javascript:void(0)" onclick="t_hide(\''+timuid+'\')">隐藏</a>
			// 	 var instr='<p class="bottom"><a href="javascript:void(0)" onclick="t_edit(\''+timuid+'\')">编辑</a>&nbsp;&nbsp;'+toolcon+'</p>';
			// 	 $('#m_'+timuid+' .bottom').html(instr);
			// 	 t_close('floatbox_add');
			// 	 jyload();
			// 	// var ti_html=ti_pre+'<div class="que">'+ti_que+'</div><div class="ans">'+ti_ans+'</div>'+ti_next;
			// 	// $("#timu_box").append(ti_html);
			// 	// //bindzhankai();
			// 	// auto_timubar();
			// 	// var h = $(document).height()-$(window).height();
			// 	// $(document).scrollTop(h);
			//  }
			//  else if(data==8) alert("该题目已经添加过了");
			//  else alert("添加失败");
			// });
		}
		else if(from=='luru')
		{
			timuid=timuid+"_"+Math.round(new Date().getTime()/1000);
			//ue2.getKfContent(function(){
			//ue1.getKfContent(function(){
				
				var ti_que=ue1.getContent();
				var ti_ans=ue2.getContent();
				var ti_pre='<div class="xiti" id="m_'+timuid+'" uid="'+uid+'">'+order_input+'<div class="xtq"><div>';
				var ti_next='</div></div></div>';
				var request_pars = "type=addtoWorkbook&timuid="+timuid+"&bookid="+bookid+"&chapterid="+chapterid+"&t_count="+t_count+"&orderid="+orderid;
				$.post(request_url+"?"+request_pars , {question:ti_que,answer:ti_ans} , function(data){
					 if(data==1)
			 		{
						ue1.setContent('');ue2.setContent('');
						var ti_html=ti_pre+'<div class="que">'+ti_que+'</div><div class="ans">'+ti_ans+'</div>'+ti_next;
						$("#timu_box").append(ti_html);
						//bindzhankai();
						auto_timubar();
						 var h = $(document).height()-$(window).height();
						 $(document).scrollTop(h);
					 }
					 else alert("添加失败");
				});
			//});
			//});
		}
		else alert("miss para: from");
		//t_close('floatbox_add');
	}
	
	function auto_search(word)
	{
		$('#txtword').val(word);
		$('#txtbox').html(loading_msg("正在搜索，请稍候"));
	    // var request_pars = "type=gettimulist&subject=alls&word="+word;//请求参数
		$.getScript('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word='+word+'&callback=get_search',function () {

		});
	}

	function get_search(content) {
		var len = content.length;
		var s = '';
		for(var i=0;i<len;i++){
		s += "<div class='xiti' id='t_"+content[i].md5id+"'><div class='xtq'><div><div class='que'>"+content[i].question+"</div><div class='ans'>"+content[i].answer+"</div></div></div> <p class='bottom'><a href=\"javascript:void(0)\"onclick=\"addtoWorkbook('"+add_timuid+"','"+content[i].md5id+"','tiku');\" class='daan'>加入练习册</a></p> </div>"
		}
		$('#txtbox').html(s);
		jyload();
	}

	function get_search_auto(content) {
		var len = content.length;
		var s = '';
		for(var i=0;i<len;i++){
			s += "<div class='xiti' id='t_"+content[i].md5id+"'><div class='xtq'><div><div class='que'>"+content[i].question+"</div><div class='ans'>"+content[i].answer+"</div></div></div> <p class='bottom'><a href=\"javascript:void(0)\"onclick=\"addtoWorkbook('"+add_timuid+"','"+content[i].md5id+"','tiku');\" class='daan'>加入练习册</a></p> </div>"
		}
		$('#txtbox').html(s);
		jyload();
	}

	function gettimulist(gradeid,subject_id)
	{
		jQuery('#txtbox').html(loading_msg("正在搜索，请稍候"));
		$.getScript('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word='+$('#txtword').val()+'&callback=get_search',function () {

		});
		// jQuery.get(request_url, request_pars, function(content){
		// 	jQuery('#txtbox').html(content);//+'<div style="clear:both;"></div>'
		// 	//bindzhankai();
        //
		// });

	}
	

	function pdelete(id){
        $.ajax({
            type: "POST",
            url: request_url+"?type=del_chapter",
            data: "id="+id,
            success: function(data){
               if(data!=1) alert('删除失败');
            }
        });
		
    }
	
	function addrow(obj, type) {
        var table = obj.parentNode.parentNode.parentNode.parentNode.parentNode;
        if(!addrowdirect) {
            var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex);
        } else {
            var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex + 1);
        }
        var typedata = rowtypedata[type];
        for(var i = 0; i <= typedata.length - 1; i++) {
            var cell = row.insertCell(i);
            cell.colSpan = typedata[i][0];
            var tmp = typedata[i][1];
            if(typedata[i][2]) {
                cell.className = typedata[i][2];
            }
            tmp = tmp.replace(/\{(n)\}/g, function($1) {return addrowkey;});
            tmp = tmp.replace(/\{(\d+)\}/g, function($1, $2) {return addrow.arguments[parseInt($2) + 1];});
            cell.innerHTML = tmp;
        }
        addrowkey ++;
        addrowdirect = 0;
    }


    function deleterow(obj) {
        var table = obj.parentNode.parentNode.parentNode.parentNode.parentNode;
        var tr = obj.parentNode.parentNode.parentNode;
        table.deleteRow(tr.rowIndex);
    }


    function validate(){
        document.getElementById('myform').submit();
//        window.location.reload();
//        window.location.href=history.back();
//        window.location.reload();
    }


    function showlastchild(){
        var a = $('tr');

        for(var i =0; i< a.length-1; i++){
            if(a.eq(i).attr('class').substr(5,1)>=a.eq(i+1).attr('class').substr(5,1)){

                var oldclass = a.eq(i).attr('class');
                //转换为6显示删除按钮
                a.eq(i).attr('class','hover6');

                $('.hover6 .changerow').attr("style","display:;");

                //恢复class
                a.eq(i).attr('class',oldclass);

            }
        }
    }
    
    function autoCompose() {
        var questionHtml,answerHtml;
        questionHtml = compose('ueditor_0');
        answerHtml = compose('ueditor_1');
        ue1.setContent(questionHtml);
        ue2.setContent(answerHtml);
    }
    
    function compose(frameId) {
        var input = frames[frameId].contentWindow;
        var htmlText = $(input.document.body).html();
        
        var range_chinese_unicodes = '\\u3400-\\u4DB5\\u4E00-\\u9FA5\\u9FA6-\\u9FBB\\uF900-\\uFA2D\\uFA30-\\uFA6A\\uFA70-\\uFAD9'
                                   + '\\uFF00-\\uFFEF\\u2E80-\\u2EFF\\u3000-\\u303F\\u31C0-\\u31EF\\u2F00-\\u2FDF\\u2FF0-\\u2FFF'
                                   + '\\u3100-\\u312F\\u31A0-\\u31BF\\u3040-\\u309F\\u30A0-\\u30FF\\u31F0-\\u31FF\\uAC00-\\uD7AF'
                                   + '\\u1100-\\u11FF\\u3130-\\u318F\\u4DC0-\\u4DFF\\uA000-\\uA48F\\uA490-\\uA4CF\\u2800-\\u28FF'
                                   + '\\u3200-\\u32FF\\u3300-\\u33FF\\u2700-\\u27BF\\u2600-\\u26FF\\uFE10-\\uFE1F\\uFE30-\\uFE4F\\u6765';
        htmlText = htmlText.replace(new RegExp('([' + range_chinese_unicodes + ']+)(?:&nbsp;| |(?:</span>(?:&nbsp;| )*<span[^<>]*>))*([' + range_chinese_unicodes + ']+)'),"$1$2");
        
        htmlText = htmlText.replace(/[\u25a0\u2666|\u4e28]/g,"");
		htmlText = htmlText.replace(/{/g,"(");
		htmlText = htmlText.replace(/}/g,")");
        htmlText = htmlText.replace(/\uff08/g,"(");
		htmlText = htmlText.replace(/\uff09/g,")");
        htmlText = htmlText.replace(/[\u3011\u3017]/g,"]");
        htmlText = htmlText.replace(/[\u3010\u3016]/g,"[");
        htmlText = htmlText.replace(/VI/g,"\u2165");
        htmlText = htmlText.replace(/(?:VI|\u2165)[IL]/g,"\u2166");
        htmlText = htmlText.replace(/ii\./g,"\u2161.");
        htmlText = htmlText.replace(/<i>(?:&nbsp;|\s)*([\uff0c\.])(?:&nbsp;|\s)*<\/i>/g,"$1");
        htmlText = htmlText.replace(/<em>(?:&nbsp;|\s)*([\uff0c\.])(?:&nbsp;|\s)*<\/em>/g,"$1");
        htmlText = htmlText.replace(/<strong>(?:&nbsp;|\s)*(\uff0c\.)(?:&nbsp;|\s)*<\/strong>/g,"$1");
        htmlText = htmlText.replace(/<\/strong>(?:&nbsp;|\s)*<strong>/g,"");
        htmlText = htmlText.replace(/([\u2160-\u216b])(?:&nbsp;|\s)*[^<>\.]/g,"$1. ");
        htmlText = htmlText.replace(/<\/?a[^<>]*>/g,"");
        htmlText = htmlText.replace(/(?:\((?:&nbsp;|\s)*(?:>|&gt;)|(?:<|&lt;)(?:&nbsp;|\s)*\))/g,"( &nbsp; )");
        htmlText = htmlText.replace(/[\uff0c,\u2019']{2}/g,"\u201d");
        htmlText = htmlText.replace(/<span>(?:&nbsp;| )*<\/span>/g,"");
        htmlText = htmlText.replace(/<br[^<>]*>/g,"</p><p>");
        htmlText = htmlText.replace(/<b>([^<>]+)<\/?b>/g,"<strong>$1<\/strong>");
        htmlText = htmlText.replace(/<u>([^<>]+)<\/u>/g,"$1");
        htmlText = htmlText.replace(/<\/?ul[^<>]*>/g,"");
        htmlText = htmlText.replace(/<p[^<>]*>(?:&nbsp;|\s|<br[^<>]*>|<span[^<>]*>(?:&nbsp;|\s|<br[^<>]*>)*<\/span>)*<\/p>/g,"");
        htmlText = htmlText.replace(/<\/?st1:[^<>]*>/g,"");
        htmlText = htmlText.replace(/<sub>([:])<\/sub>/g,"$1");
        htmlText = htmlText.replace(/<p[^<>\/]+>/g,"<p>");
        
        /* remove <span> tags */
        for(var i = 0;i < 5;i++) {
            htmlText = htmlText.replace(/<span[^<>]*style[^<>]*>[\u9]?<\/span>/g," ");
            htmlText = htmlText.replace(/<span[^<>]*style[^<>]*>((?:[^<>]|<[^<>]*su[bp][^<>]*>)*)<\/span>/g,"$1");
            htmlText = htmlText.replace(/<span>([^<>]*)<\/span>/g,"$1");
        }
        htmlText = htmlText.replace(/\u9/g," ");
        
        /* correct punctuation */
        htmlText = htmlText.replace(/([a-zA-Z\d]{1,2})(?:&nbsp;| )*[\uff1b](?:&nbsp;| )*([a-zA-Z\d]{1,2})/g,"$1;$2");
		htmlText = htmlText.replace(/([a-zA-Z\d]{1,2})(?:&nbsp;| )*[,\uff0c](?:&nbsp;| )*([a-zA-Z\d]{1,2})/g,"$1,$2");
        htmlText = htmlText.replace(/([a-zA-Z\d]{1,2})(?:&nbsp;| )*[!\uff01](?:&nbsp;| )*([a-zA-Z\d]{1,2})/g,"$1!$2");
        htmlText = htmlText.replace(/([A-Z][a-z]+|[a-z]{2,})(?:&nbsp;|\s)*(\d*)(?:&nbsp;|\s)*\uff0c/g,"$1 $2,");
        htmlText = htmlText.replace(/(I)[,\uff0c\u2019?]([dm]|ll|ve)/g,"$1'$2");
        htmlText = htmlText.replace(/(don|can|mustn|didn)[,\uff0c\u2019?](t)/g,"$1'$2");
        htmlText = htmlText.replace(/(It|it|Where|That)[,\uff0c\u2019?](s)/g,"$1'$2");
        htmlText = htmlText.replace(/([a-z]+)[\uff0c\u2019](s\b)/g,"$1'$2");
		htmlText = htmlText.replace(/text-align:\s*(?:right|center);?/g,"");
		htmlText = htmlText.replace(/margin(?:-left|-top|-right)?:[^<>:;"']+;?/g,"");
		htmlText = htmlText.replace(/text-indent:[^<>:;"']+;?/g,"");
        htmlText = htmlText.replace(/letter-spacing:[^<>:;"']+;?/g,"");
        htmlText = htmlText.replace(/line-height:[^<>:;"']+;?/g,"line-height:21px;");
        htmlText = htmlText.replace(/_src ?= ?['"][^'"<>\:]*['"]/g,"");
        htmlText = htmlText.replace(/([A-D]),/g,"$1.");
		htmlText = htmlText.replace(/([A-D]\.|\))(?:&nbsp;|\s)*(\S)/g,"$1 $2");
        htmlText = htmlText.replace(/([^\d][\d]{1,2}\.)(?:&nbsp;|\s)*(\S)/g,"$1 $2");
        htmlText = htmlText.replace(/<span[^<>]*>(?:&nbsp;|\s)*([A-D]\.)(?:&nbsp;|\s)*<\/span>/g,"$1");
        htmlText = htmlText.replace(/(<span[^<>]*>(?:&nbsp;|\s)*)([A-D]\.)/g,"$2$1");
        htmlText = htmlText.replace(/(?:&nbsp;|\s)*([B-D]\.)/g,"&nbsp;&nbsp;&nbsp;$1");
        htmlText = htmlText.replace(/(<p[^<>]*>)(?:&nbsp;|\s)*([A-H]\.)/g,"$1&nbsp;&nbsp;&nbsp;$2");
        htmlText = htmlText.replace(/(\()(?:&nbsp;|\s)*(\))/g,"$1 &nbsp; $2");
        
        
        
        /* add a customized underline for multiple blank space */
        if(frameId == 'ueditor_0') {
            htmlText = htmlText.replace(/([a-z]{2,}|[1-9]\d\.|[1-9]\.|[\)\.\-\u4e00])(?:&nbsp;|\s){6,}([a-z]{2,}|[\.\?\!,I\(])/ig,'$1 ' + underlineMakeups + ' $2');
        }
        
        /* remove redundant blank space and be normalized */
        htmlText = htmlText.replace(/([a-z]+|\d+)(?:(?:&nbsp;|\s){2,}|&nbsp;)([a-z]+|\d+)/g,"$1 $2");
        
        /* normalize punctuation and words*/
        htmlText = htmlText.replace(/([a-z]{2,} (?:\d{1,2}\.?)?)(?:&nbsp;|\s)*[\uff1f]/ig,'$1?');
        // htmlText = htmlText.replace(/([^\-A-Za-z][A-Z]?[a-z]+) ?\- ?([a-z]+[^\-A-Za-z\:])/g,"$1$2");
        htmlText = htmlText.replace(/([a-z]{2,}\.)[\u201d]/ig,"$1&quot;");
        htmlText = htmlText.replace(/(\[[12]\d{3})(?:&nbsp;|\s|[\?*\u2022\.])*([\u4E00-\u9FA5\uF900-\uFA2D]{2,3}\])/g,"$1 \u2022 $2");
        htmlText = htmlText.replace(/\u4e01he/ig,'The');
        //htmlText = htmlText.replace(/I\)/ig,'D');
        htmlText = htmlText.replace(/\)\:/g,'C');
        htmlText = htmlText.replace(/([a-z]+|\))(?:&nbsp;|\s)*\u2022/ig,'$1.');
        
        /* add a customized underline in other case */
        if(frameId == 'ueditor_0') {
            htmlText = htmlText.replace(/_{6,}/g,underlineMakeups);
            
            htmlText = htmlText.replace(/(?:&nbsp;|\s){12,}/g,underlineMakeups);
            htmlText = htmlText.replace(/(?:&nbsp;|\s){4,}/g,' ');
            htmlText = htmlText.replace(new RegExp('([' + range_chinese_unicodes + '])' + '(?:&nbsp;|\\s){3,5}','g'),'$1' + underlineMakeups);
            htmlText = htmlText.replace(/[_]{6,}/g,underlineMakeups);
            htmlText = htmlText.replace(/([^_])[_]{6,}\s+[_]([^_])/g,'$1 ' + underlineMakeups + ' $2');
            htmlText = htmlText.replace(/([^_])[_]+([^_])/g,'$1 ' + underlineMakeups + ' $2');
            htmlText = htmlText.replace(/((?:[^.] |p>)(?:[a-z']{2,}|[I,"]|[A-Z][a-z]+))(?:&nbsp;|\s)*([1-8]?[0-9]\.?)(?:&nbsp;|\s)+([a-z]{2,}|[\?\!,\uff0c\uff01]|[A-Z][a-z]*)/g,
                                        "$1 $2 " + underlineMakeups + "$3");
            htmlText = htmlText.replace(/((?: [a-z]+){2,} [1-8]?[0-9]\.?)(?:&nbsp;|\s)*([\?\!,])/ig,'$1 ' + underlineMakeups + '$2');
            htmlText = htmlText.replace(/([a-z]{2,}[\.\?]|[a-z]+'[a-z]+)(?:&nbsp;|\s)*([1-8]?[0-9]\.?)(?:&nbsp;|\s)*([a-z]{2,})/ig,'$1 $2' + underlineMakeups + ' $3');
            htmlText = htmlText.replace(/([a-z]{2,} [1-8]?[0-9]\.?)(?:&nbsp;|\s)+(\.)(?:&nbsp;|\s)*([a-z]{2,})/ig,'$1 ' + underlineMakeups + '$2 $3');
            htmlText = htmlText.replace(/(\b[a-z]+\b \b[a-z]+\b \d{1,2})(?:&nbsp;|\s|\u9)([\.,])/g,'$1 ' + underlineMakeups + '$2');
            htmlText = htmlText.replace(/([\.,]|\b[a-z]+\b)(?:&nbsp;|\s|\u9)*( ?\d{1,2})( \b[a-z]+\b \b[a-z]+\b)/ig,'$1 $2 ' + underlineMakeups + ' $3');
            var measureWords = "("
                             + "years|seasons|months|days|hours|minutes|seconds|am|pm"
                             + "|million|billion|trillion"
                             + "|[Mm]iles"
                             + "|per"
                             + "|pounds"
                             + "|men|women"
                             + "|calls"
                             + "|of"
                             + ")";
            htmlText = htmlText.replace(new RegExp('([1-3]?[0-9]) ' + underlineMakeups.replace(/ = /g,' ?= ?') + measureWords,'g'),"$1 $2");
            var ordinalWords = "("
                             + "between|over|of|"
                             + "[Pp]aragraph|[Gg]rade|[Mm]odule|aged|[Ll]ine"
                             + "|January|February|March|April|May|June|July|August|September|October|November|December"
                             + ")";
            htmlText = htmlText.replace(new RegExp(ordinalWords + '(?:&nbsp;|\\s)*([1-3]?[0-9]) ' + underlineMakeups.replace(/ = /g,' ?= ?'),'g'),'$1 $2 ');
            htmlText = htmlText.replace(/((?:(?:&nbsp;|\s)+|[\?\.]|[A-Za-z]{2,},)[b-hj-z])(?:&nbsp;|\s)+([a-z']+)/g,'$1 ' + underlineMakeups + ' $2');
            htmlText = htmlText.replace(new RegExp('([A-Z][a-z]+|[a-z]{2,})(?:&nbsp;|\\s){2,}(\\([?:nbsp;|[A-za-z\s]*[' + range_chinese_unicodes + '])','g'),'$1 ' + underlineMakeups + ' $2');
            htmlText = htmlText.replace(/([IaA,])(?:&nbsp;|\s)*(\()/g,'$1 ' + underlineMakeups + ' $2');
            htmlText = htmlText.replace(/([A-Z][a-z]+|[a-z]{2,}|[Ia]|\d{1,2}\.)(?:&nbsp;|\s)*(\([a-z ]+\))/g,'$1 ' + underlineMakeups + ' $2');
            htmlText = htmlText.replace(/([A-Z][\uff1a:][^<>]*\d{1,2}\.?)(?:&nbsp;|\s)*([A-Za-z?]|<\/p>)/g,'$1 ' + underlineMakeups + ' $2');
            
            htmlText = htmlText.replace(/(\d{1,2})[\.,](?:&nbsp;|\s)*(?=\d{1,2}[\.,])/g,'$1. ' + underlineMakeups + ' ');
            htmlText = htmlText.replace(new RegExp('(' +　underlineMakeups.replace(/ = /g,' ?= ?') + ')(?:&nbsp;|\\s)*(\\d{1,2})[\\.,](?:&nbsp;|\\s)*(?=</p>)','g'),'$1 $2. ' + underlineMakeups + ' ');
            htmlText = htmlText.replace(new RegExp(underlineMakeups.replace(/ = /g,' ?= ?') + '(?:&nbsp;|\\s)*([B-D]\\.)','g'),'&nbsp;&nbsp;&nbsp;$1');
            
        }
        
        if(frameId == 'ueditor_1') {
            htmlText = htmlText.replace(/(\d{1,2})[,\uff0c]/g,"$1.");
            htmlText = htmlText.replace(/([a-z]{2,})(?:&nbsp;|\s)*(\d{1,2}\.)/g,"$1 &nbsp; $2");
            htmlText = htmlText.replace(/(<p>)(?:&nbsp;|\s)*(\d{1,2}\.)/g,"$1$2");
        }
        htmlText = htmlText.replace(/([a-z]+|>)(?:&nbsp;|\s)*(\()/g,'$1 $2');
        htmlText = htmlText.replace(/([\dA-D\)])[\uff0c\u3002\u2022\u25a0](.)/g,"$1.$2");
        htmlText = htmlText.replace(/([a-z])\uff0c(.)/g,"$1,$2");
		htmlText = htmlText.replace(/([a-z][\s\S]{0,5}?)[\uff1b](.)/g,"$1;$2");
		htmlText = htmlText.replace(/([a-z][\s\S]{0,5}?)[\u2022](.)/g,"$1.$2");
        htmlText = htmlText.replace(/([a-z>]+)(?:&nbsp;|\s){2,}([a-z\?\!][^<>\.][a-z]*|<span)/ig,"$1 $2");
        htmlText = htmlText.replace(/([a-z]{2,})(?:(?:&nbsp;|\s){2,}|&nbsp;)([\?\!\.])/ig,"$1$2");
        htmlText = htmlText.replace(/(\d{1,2}\.)(?:&nbsp;|\s)*(<span)/g,"$1 $2");
        
        /* merged into one line */
        htmlText = htmlText.replace(/([a-z]+|[,\-]|[a-z]+'[a-z]+|\d+)(?:&nbsp;|\s)*<\/p>(?:&nbsp;|\s)*<p[^<>]*>(?:&nbsp;|\s)*([a-z]{2,}[\.]?|\([^()<>&]{2,})/ig,"$1 $2");
        htmlText = htmlText.replace(/([a-z]+)(?:&nbsp;|\s)*<\/p>(?:&nbsp;|\s)*<p[^<>]*>(?:&nbsp;|\s)*((?:[a-z]{2,}[\.]?|[1-8]?[0-9])[^<>\.])/ig,"$1 $2");
        htmlText = htmlText.replace(new RegExp('\(' + underlineMakeups.replace(/ = /g,' ?= ?') + '\)(?:&nbsp;|\s)*<\/p>(?:&nbsp;|\s)*<p[^<>]*>(?:&nbsp;|\s)*\(' + underlineMakeups + '\)','g'),"$1 $2");
        htmlText = htmlText.replace(new RegExp('([\u4E00-\u9FA5\uF900-\uFA2D]+)(?:&nbsp;|\\s)*<\\/p>(?:&nbsp;|\\s)*<p[^<>]*>(?:&nbsp;|\\s)*([\u4E00-\u9FA5\uF900-\uFA2D]+)','g'),"$1$2");
        htmlText = htmlText.replace(/(\))(?:&nbsp;|\s)*<\/p>(?:&nbsp;|\s)*<p[^<>]*>(?:&nbsp;|\s)*([a-z']{2,})/ig,"$1 $2");
        htmlText = htmlText.replace(/([A-Za-z]+(?:&nbsp;|\s)*[\.\?\!])(?:&nbsp;|\s)*((?:[A-D\u2160-\u216b]|\d+)\.)/g,"$1 $2");
        
        /* divided into two line */
        htmlText = htmlText.replace(/([^>])(?:&nbsp;|\s)*(\([^\(\)]+\) \d{1,2}\.)/g,"$1</p><p>$2");
        htmlText = htmlText.replace(/([\u3002\uff1f\uff01])(?:&nbsp;|\s)*([a-z]+)/ig,"$1</p><p>$2");
        htmlText = htmlText.replace(/([\u3002\uff1f\uff01])(?:&nbsp;|\s)*([a-z]+)/ig,"$1</p><p>$2");
        htmlText = htmlText.replace(/(D\. [A-Za-z]+(?:&nbsp;|[A-Za-z\s,])*)(\d{2,}\.)/ig,"$1</p><p>$2");
        htmlText = htmlText.replace(/([A-Z]?[a-z]+(?:&nbsp;|\s)*[\?\.,\uff0c])(?:&nbsp;|\s)*(A\. [A-Za-z\u4E00-\u9FA5\uF900-\uFA2D])/g,"$1</p><p>$2");
        htmlText = htmlText.replace(new RegExp('\\((?:\\s|&nbsp;)*\\)','g'),'(<span class="paren"></span>)');
        return htmlText;
    }

function jyload() {
	var elements = $("div[muststretch='v']");
	for (var i = 0; i < elements.length; i++) {

		var parent = elements.eq(i).parent();
		if (parent.tagName != 'td' && parent.tagName != 'TD') {
			parent = parent.parent();
		}

		var H = parent.height();
		if (H != null) {

			if (elements.eq(i).css("background").indexOf('8730U.png')!=-1) {
				elements.eq(i).css('height', (H - 10) + 'px');
			} else if (elements.eq(i).css("background").indexOf('{M.png')!=-1) {
				elements.eq(i).css('height', (H - 41) / 2 + 'px');
			}
		}
	}
	var elements2 = $('div[muststretch=h]');
	for (var i = 0; i < elements2.length; i++) {
		var parent = elements.eq(i).parent();
		if (parent.tagName != 'td' && parent.tagName != 'TD') {
			parent = parent.parent();
		}
		var W = parent.height();
		if (W != 0) {
			elements.eq(i).css('width', (W - 0) + 'px');
		}
	}
}

    
window.onload = function() {
    // document.getElementById('editbar3').getElementsByTagName('a')[0].onclick = function() {
    //     popWin('floatbox_add');
    //     var bookNameElement = document.getElementById('bookname');
    //     var PElements = bookNameElement.getElementsByTagName('p');
    //     grade = PElements[1].firstChild.data.substr(3);
    //     subject = PElements[2].firstChild.data.substr(3);
    //     if(!document.getElementById("question-info")) {
    //         var ue2 = document.getElementById("E_add2");
    //         var questionInfoNode = document.createElement("div");
    //         questionInfoNode.id = "question-info";
    //         questionInfoNode.style.textAlign = "right";
    //         questionInfoNode.style.color = "#378DD5";
    //         var options = '<option value = "0">题型</option>'
    //                     + '<option value = "1">单项选择</option>'
    //                     + '<option value = "2">多项选择</option>'
    //                     + '<option value = "3">判断题</option>'
    //                     + '<option value = "4">填空题</option>'
    //                     + '<option value = "5">解答题</option>';
    //         questionInfoNode.innerHTML = grade + subject + '：'
    //                                    + '<select name = "question_type" id="question_type" style = "font-size:0.8em;color:#118F99;margin-right:0.8em;">' + options +'</select>'
    //                                    + '知识点：<input name = "knowledge-point" size = "16" style = "font-size:0.8em;color:#118F99;margin-right:0.8em;" />';
    //         ue2.parentNode.insertBefore(questionInfoNode,ue2);
    //     }
    //     var questionBodyElement = document.getElementById('ueditor_0').contentWindow.document.getElementsByTagName('body')[0];
    //     questionBodyElement.onkeydown = function(e) {
    //         var currentKey,event = e || window.event;
    //         currentKey = event.keyCode || event.which || event.charCode;
    //         if(event.ctrlKey && currentKey == 91) {
    //             ue1.focus();
    //             ue1.execCommand('inserthtml',underlineMakeups);
    //             questionBodyElement.innerHTML = questionBodyElement.innerHTML
    //                                           .replace(new RegExp('[(\\uff08](?:\\s|&nbsp;)*'　+　underlineMakeups.replace(/ = /g,' ?= ?') +　'(?:\\s|&nbsp;)*[)\\uff09]'),'(<span class="paren"></span>)');
    //             return false;
    //         }
    //     }
    // }
    $(document).on('change','#timu_box .xiti select',function () {
		changeQuestionType(this.parentNode.parentNode.parentNode.id.substr(2),this.value);
	});

    $('#timu_box .xiti textarea').dblclick(function() {
        this.readOnly = false;
    });
    $('#timu_box .xiti textarea').mouseout(function() {
        this.readOnly = 'readonly';
    });
    $('#timu_box .xiti textarea').change(function() {
        changeUniAnswer(this.parentNode.parentNode.parentNode.parentNode.id.substr(2),this.value);
    });




}