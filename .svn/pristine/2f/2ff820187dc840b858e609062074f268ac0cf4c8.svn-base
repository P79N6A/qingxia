<?php
namespace App\Utils;
class Search
{
	protected $index='zuoyeben';//默认为练习册索引
	protected $host;
	protected $hostnum;//服务器编号 默认0
	protected $hostip;//sphinx服务器 默认为第一个，其他为备用 也可通过set_host指定服务器
	protected $word;
	protected $cl;//SphinxClient
	//protected $groupby='';//设置了groupby的字段名
    protected $unset=[];//设置结果不输出的字段
    protected $total;//搜索结果总条数
    protected $charset='utf-8';
    protected $highLightField='';
	public function __construct($hostnum=0)
	{
		$this->cl = new SphinxClient();
		$this->hostip=['localhost'];
		$this->set_host($hostnum);//->set_match_mode('all')
	}

    public function set_index($index)//设置搜索索引
	{
		$this->index=$index;
		return $this;
	}

	public function sphinx(){
	    return $this->cl;
    }

    //高亮文字 doc支持字符串、数组 word默认为当前搜索词 默认高亮词加b标签
	public function high_light($doc,$word='',$mod=["limit"=>"0","html_strip_mode"=>"retain"]){
	    $index=$this->index;//随便写一个存在的index即可
        if($word==='') $word=$this->word;
        if(is_array($doc)) return $this->cl->BuildExcerpts($doc,$index,$word,$mod);
        else{
            $r= $this->cl->BuildExcerpts([$doc],$index,$word,$mod);
            return $r[0];
        }
    }

    public function set_unset($array_field){//设置结果不输出的字段
        $this->unset=$array_field;
        return $this;
    }

    public function set_host($hostnum)//设置搜索服务器 参数为编号
	{
		$this->hostnum=$hostnum;
		if(!isset($this->hostip[$this->hostnum])) $this->hostnum=0;
		$this->host=$this->hostip[$this->hostnum];
		$this->cl->SetServer ($this->host, 9312);
		return $this;
	}

    public function set_groupby($ziduan,$groupsort="@group desc")
	{
		$this->cl->SetGroupBy($ziduan,SPH_GROUPBY_ATTR,$groupsort);
		//$this->groupby=$ziduan;
		return $this;
	}

    public function set_match_mode($mod)//设置匹配模式
	{
		$modes=array('all'=>SPH_MATCH_ALL,'any'=>SPH_MATCH_ANY,'extend'=>SPH_MATCH_EXTENDED);//all精确 any模糊 extend扩展
		$match_mode=isset($modes[$mod])?$modes[$mod]:$modes['all'];
		$this->cl->SetMatchMode ($match_mode);
		return $this;
	}

    public function set_sort_mode($mod=SPH_SORT_EXTENDED,$val="@weight DESC,rating DESC")//设置排序
	{
		$this->cl->SetSortMode($mod,$val);
		return $this;
	}

    public function set_ranking_mode($formula="sum((4*lcs+exact_hit*1000+exact_order*1000-min_gaps*1000-min_hit_pos*10)*user_weight)")
	{
		//关键词：exact_order顺序权重 min_gaps间隔权重 exact_hit完全相等权重 min_hit_pos起始位置权重
		$this->cl->SetRankingMode ( SPH_RANK_EXPR, $formula);//针对系列匹配算法
		return $this;
	}

    public function set_field_weight($array)//设置字段权重
	{
		$this->cl->SetFieldWeights($array);
		return $this;
	}

    public function set_index_weight($array)//设置索引权重
    {
        $this->cl->SetIndexWeights($array);
        return $this;
    }

    public function set_filter($attribute, $value, $exclude=false)//设置过滤
	{
		if(is_array($value)) $this->cl->SetFilter($attribute, $value, $exclude);//数组值，只能是int类型
		else $this->cl->SetFilterString($attribute, $value, $exclude);//单值过滤 字符串
		return $this;
	}

    public function set_filter_range($attribute, $min,$max, $exclude=false)//设置过滤
    {
        $this->cl->SetFilterRange($attribute, $min,$max, $exclude);
        return $this;
    }

    public function update_attr($array_field,$array_update_data){//更新索引属性
        $this->cl->UpdateAttributes($this->index,$array_field,$array_update_data);
    }

    public function total(){
        return $this->total;
    }

    public function page($word,$page=1,$limit=10){
        if($page<1) $page=1;
        return $this->get($word,($page-1)*$limit,$limit);
    }

    public function get($word,$offset=0,$limit=10)//返回精简查询结果
	{
		$req=$this->go($word,$offset,$limit);
        $this->total=$req['total'];
		if(!isset($req['matches'])) return array();
		$res=array();
		foreach($req['matches'] as $k=>$v)
		{
			$res[$k]['id']=$v['id'];
            if(!empty($this->unset)){
                foreach($this->unset as $field) unset($v['attrs'][$field]);
            }
            $res[$k]+=$v['attrs'];
		}

		return $res;
	}

    public function go($word,$offset=0,$limit=10)//参数依次为搜索词，起始位置，限制数量
	{
		$this->word=trim($word);
		if($offset>1000 || $limit>1000) return array();//限制搜索数量

		$this->cl->SetConnectTimeout ( 1 );
		$this->cl->SetArrayResult ( true );
		//if(in_array($this->index,array('zuoyeben','zuoyeben2','zuoyeben,zuoyeben3'))) $this->cl->SetSortMode(SPH_SORT_EXTENDED,"@weight DESC,rating DESC");
		$this->cl->SetLimits($offset,$limit,1001);
		$req = $this->cl->Query($this->word,$this->index);
		while($this->cl->IsConnectError()===true)//如果是连接错误，自动切换到备用服务器
		{
			$this->hostnum++;
			if($this->hostnum==count($this->hostip)) return array();
			$this->set_host($this->hostnum);
			$this->cl->SetServer ($this->host, 9312);
			$req = $this->cl->Query($this->word,$this->index);
		}
		/*if($this->index=='zuoyeben')//特殊处理不显示有水印的数据
		{
			if($req['total']>1 && $req['total']<80)
			{
				$req2=$req;
				foreach($req['matches'] as $k=>$v)
				{
					if($v['attrs']['hdid']>31690 && $v['attrs']['hdid']<68227)
					{
						unset($req['matches'][$k]);
						$req['total']--;
					}
				}
				if($req['total']==0) $req=$req2;
			}
		}*/
		$this->searchcount=intval($req['total']);
		return $req;
	}

    public function record($comefrom,$page=1)//搜索记录
	{
		db::set_charset('utf8');
		db::insert("a_tongji_search",array('uid'=>UID,'word'=>$this->word,'page'=>$page,'comefrom'=>$comefrom,'SearchCount'=>$this->searchcount,'ip'=>\lib\ip::get()));
	}

	public function gbk(){
        $this->charset='gbk';
        return $this;
    }

    public function timu($word,$subject='',$page=1,$limit=10)
	{
        $this->set_match_mode('extend');
        $this->cl->SetRankingMode (SPH_RANK_EXPR,"20*sum(lcs*user_weight)-sum(min_best_span_pos)" );
        $this->cl->SetSortMode(SPH_SORT_EXTENDED,"@weight DESC,rank DESC");
        $req=$this->timu_search($word,$subject,$page,$limit);
        $all_search_ids=$this->get_ids($req);
		return $this->get_timu_data($all_search_ids);
	}

	public function get_ids($req){
        $all_search_ids=[];
        foreach($req['matches'] as $k=>$v){
            $all_search_ids[]=$v['id'];
        }
        return $all_search_ids;
    }

	public function timu_search($word,$subject='',$page=1,$limit=10){
        if($this->charset=='gbk') $word=iconv("gbk","utf-8",$word);
        $this->word=trim($word);
        $allsub=array(21=>'czdl',22=>'czhx',23=>'czls',24=>'czsw',25=>'czsx',26=>'czwl',27=>'czyw',28=>'czyy',29=>'czzz',31=>'gzdl',32=>'gzhx',33=>'gzls',34=>'gzsw',35=>'gzsx',36=>'gzwl',37=>'gzyw',38=>'gzyy',39=>'gzzz',15=>'xxsx',17=>'xxyw',18=>'xxyy');
        if(!in_array($subject,$allsub))$subject="allsub";
        if($page<1) $page=1;
        $this->cl->SetConnectTimeout ( 1 );
        $this->cl->SetArrayResult ( true );

        $this->cl->SetLimits(($page-1)*$limit,$limit,1001);

        $req = $this->cl->Query($word,$subject);
        while($this->cl->IsConnectError()===true)//如果是连接错误，自动切换到备用服务器
        {
            $this->hostnum++;
            if($this->hostnum==count($this->hostip)) return array();
            $this->set_host($this->hostnum);
            $this->cl->SetServer ($this->host, 9312);
            $req = $this->cl->Query($word,$subject);
        }
        return $req;
    }

	public function get_timu_data($all_search_ids){
        db::set_host(11);
        if($this->charset=='gbk') db::set_charset('gbk');
        $allsub=array(21=>'czdl',22=>'czhx',23=>'czls',24=>'czsw',25=>'czsx',26=>'czwl',27=>'czyw',28=>'czyy',29=>'czzz',31=>'gzdl',32=>'gzhx',33=>'gzls',34=>'gzsw',35=>'gzsx',36=>'gzwl',37=>'gzyw',38=>'gzyy',39=>'gzzz',15=>'xxsx',17=>'xxyw',18=>'xxyy');
        if($all_search_ids)
        {
            foreach($all_search_ids as $k=>$v)
            {
                $subid = intval($v/100000000);
                if(!isset($allsub[$subid])) return [];
                $sub = $allsub[$subid];
                $v =$v-$subid*100000000;
                $sql = "select id,md5id,question,answer,qtype,diff,source from `mo_$sub` where `id` =".$v;
                $ret= db::select($sql);
                $ret = $ret[0];
                $ret['question'] = str_replace(array("<b>","</b>"),"",$ret['question']);
                $ret['subject'] = $sub;
                $res[$k]=$ret;
            }
            return $res;
        }
        return array();
    }

    public function set_hostip($ip,$port=9312){
        $this->cl->SetServer ($ip, 9312);
        return $this;
    }

    public function parse_bookname($bookname){//解析书名关键词
        $bookname=str_replace('答案','',trim($bookname));
        $bookname=strtr($bookname,['初一'=>'七年级','初二'=>'八年级']);
        $this->parse_result=[];
        $filter=config('filter');
        $bookname=preg_replace_callback('#一年级|二年级|三年级|四年级|五年级|六年级|七年级|八年级|九年级|高一|高二|高三#',function ($m){$this->parse_result['grade_name']=$m[0];return '';},$bookname);
        $bookname=preg_replace_callback('#全一册|上册|下册#',function ($m){$this->parse_result['volumes_name']=$m[0];return '';},$bookname);
        if(!isset($this->parse_result['grade_name']) && !isset($this->parse_result['volumes_name'])) $bookname=preg_replace_callback('#(一|二|三|四|五|六|七|八|九)(上|下)#',function ($m){$this->parse_result['grade_name']=$m[1].'年级';$this->parse_result['volumes_name']=$m[2].'册';return '';},$bookname);
        $bookname=preg_replace_callback('#语文S版|语文版|人教版|北师大版|苏教版|冀教版|外研版|湘教版|译林版|华师大版|浙教版|鲁教版|青岛版#',function ($m){$this->parse_result['version_name']=$m[0];return '';},$bookname);
        $bookname2=preg_replace_callback('#语文|数学|英语|物理|化学|地理|历史|政治|生物#',function ($m){$this->parse_result['subject_name']=$m[0];return '';},$bookname,-1,$count);
        if($count==1){//当关键词里只存在一个学科的时候
            $this->parse_result['sort_name']=trim($bookname2);
            $this->parse_result['subject_id']=array_search($this->parse_result['subject_name'],$filter['subject']);
        }else{
            $this->parse_result['sort_name']=trim($bookname);
        }
        if(isset($this->parse_result['grade_name'])) $this->parse_result['grade_id']=array_search($this->parse_result['grade_name'],$filter['ini']['grade']);
        if(isset($this->parse_result['volumes_name'])) $this->parse_result['volumes_id']=array_search($this->parse_result['volumes_name'],$filter['ini']['volumes']);
        if(isset($this->parse_result['version_name'])) $this->parse_result['version_id']=array_search($this->parse_result['version_name'],$filter['ini']['version']);
        //if($this->parse_result['sort_name']=='') $this->parse_result['sort_id']=0;
        //print_r($this->parse_result);
        return $this->parse_result;
    }
}
?>