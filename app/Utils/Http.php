<?php
namespace App\Utils;
class Http
{
	protected static $setheader=array();
	protected static $getheader=0;//是否获取返回header
	protected static $user_agent_key=-1;
	protected static $status=array(
				301=>"Moved Permanently",//永久跳转
				302=>"Moved Temporarily",//临时跳转
				404=>"Not Found",//页面不存在
	);

	public static function setHeader($arr){//设置头部信息
		self::$setheader=$arr;
		//return http;
	}

	public static function getHeader(){//获取头部信息
		self::$getheader=1;
		//return http;
	}

	public static function setUserAgent($user_agent_key){//浏览器
		self::$user_agent_key=$user_agent_key;
		//return http;
	}

	public static function gets($url,$postdata='')
	{
		$ch = curl_init();
		curl_setopt ($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$user_agent=array(//$u=0电脑用户，1手机用户，2百度蜘蛛
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
			'Mozilla/5.0 (Linux; U; Android 0.5; en-us) AppleWebKit/522+ (KHTML, like Gecko) Safari/419.3',
			'Baiduspider+(+http://www.baidu.com/search/spider.htm)',
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11)  Firefox/1.5.0.11; 360Spider');

		if(self::$user_agent_key<0 || self::$user_agent_key>count($user_agent)) self::$user_agent_key=0;
		curl_setopt($ch,CURLOPT_USERAGENT,$user_agent[self::$user_agent_key]);
		if($postdata!='') curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		if(!empty(self::$setheader)) curl_setopt($ch, CURLOPT_HTTPHEADER,self::$setheader);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		if(self::$getheader) curl_setopt($ch,CURLOPT_HEADER,1);//包含头信息
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);//跟踪跳转
		$res = curl_exec($ch);
		curl_close ($ch);
		return $res;
	}

	public static function get($url){
		return self::gets($url);
	}

	public static function post($url,$postdata=[]){
		return self::gets($url,$postdata);
	}

	public static function getFileSize($url,$unit='b'){
        $r=get_headers($url);
        $lens='Content-Length: ';
        foreach ($r as $v){
            if(strstr($v,$lens)) $byte=(int)str_replace($lens,'',$v);
        }
        if(!$byte) return 0;
        if($unit=='b') return $byte;
        if($unit=='k') return round($byte/1024);
        if($unit=='m') return round($byte/(1024*1024));
    }

	public static function uploadFile($url,$file)//上传文件
	{
		//$postdata = array("file"=>'@'.$r_file);//文件路径，前面要加@，表明是文件上传.(适合之前php版本)
		$postdata=array("file"=>new CURLFile($file));
		return self::post($url,$postdata);
		/*$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$handle_url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl,CURLOPT_POST,true);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$postdata);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);  //$result 获取页面信息
		curl_close($curl);*/
		return $result ; //输出 页面结果
	}

	public static function code($url){//返回状态码
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		$code=curl_getinfo($ch);
		curl_close($ch);
		return $code;
	}

	public static function location($url='',$code=0){//页面跳转
		if(isset(self::$status[$code])) header("HTTP/1.1 $code ".self::$status[$code]);
		if($url=='') $url=M_SITE;
		header("Location: ".$url);
		echo '<script>window.location.href="'.$url.'"</script>';
		die();
	}
}
?>