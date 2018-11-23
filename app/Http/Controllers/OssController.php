<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \OSS\Core\OssException;
use \OSS\Http\RequestCore;
use \OSS\Http\ResponseCore;
use OSS\OssClient;


class OssController extends Controller
{
  protected $ossClient;
  protected $bucket;
  protected $nextMarker='';
  public function __construct($ossid='')//ossid对应bucket 查看配置文件
  {
    if($ossid=='') $ossid=1;
    $c=config('oss')[$ossid];

    $this->ossClient =new OssClient($c['id'], $c['key'], $c['endpoint']);

    $this->bucket=$c['bucket'];
  }

  public function save($filename, $content) //写入内容到oss 文件
  {

    $dirname=dirname($filename);

    if($dirname!='.' && !empty($dirname)) $this->ossClient->createObjectDir($this->bucket,$dirname);

    $this->ossClient->putObject($this->bucket, $filename, $content);
  }

  public function uploadfile($full_oss_filename,$full_local_filename)//上传文件
  {
    $this->save($full_oss_filename,file_get_contents($full_local_filename));
  }

  public function uploaddir($oss_pre_path,$localdir,$r=1)//$oss_pre_path可分多级目录 如：data/dir $r=1表示递归，否则不会上传子目录
  {
    if($r==1)
    {
      $a=scandir($localdir);
      foreach($a as $v)
      {
        if($v!='.' && $v!='..' && is_dir($localdir."/".$v))
        {
          $this->uploaddir($oss_pre_path."/".$v,$localdir."/".$v,$r);
        }
      }
    }
    $this->ossClient->uploadDir($this->bucket,$oss_pre_path,$localdir);
  }

    public function getOssClient(){
        return $this->ossClient;
    }

  public function delete($filename) //删除oss 文件
  {
    $this->ossClient->deleteObject($this->bucket, $filename);
  }

  public function fileExist($filename) //判断oss文件是否存在
  {
    return $this->ossClient->doesObjectExist($this->bucket, $filename);
  }

  public function getcontent($filename)//获取文件内容
  {
    return $this->ossClient->getObject($this->bucket,$filename);
  }

//    public function getOssClient(){
//        return $this->ossClient;
//    }

  public function getlist($limit=100)//返回列表文件名filename 若执行多次则按照上一节点继续获取文件列表
  {
    $options = array(
      'delimiter' => '/',
      'prefix' => '',
      'max-keys' => $limit,//每次返回多少条记录 最大为1000
      'marker' => $this->nextMarker,
    );
    $listObjectInfo=$this->ossClient->listObjects($this->bucket,$options);

    $this->nextMarker = $listObjectInfo->getNextMarker();

    $objectList = $listObjectInfo->getObjectList();

    $list=array();
    foreach($objectList as $objectInfo)
    {
      $list[]=$objectInfo->getKey();
    }
    return $list;
  }

  public function getSignedUrlForPuttingObject($filename,$content){
    $timeout = 3600;
    $options = NULL;
    try{
      $signedUrl = $this->ossClient->signUrl($this->bucket, $filename, $timeout, "PUT");
    } catch(OssException $e) {
      printf(__FUNCTION__ . ": FAILED\n");
      printf($e->getMessage() . "\n");
      return;
    }


    $request = new RequestCore($signedUrl);
    $request->set_method('PUT');
    $request->add_header('Content-Type', '');
    $request->add_header('Content-Length', strlen($content));
    $request->set_body($content);
    $request->send_request();
    $res = new ResponseCore($request->get_response_header(),
      $request->get_response_body(), $request->get_response_code());

    if ($res->isOK()) {
      print(__FUNCTION__ . ": OK" . "\n");
    } else {
      print(__FUNCTION__ . ": FAILED" . "\n");
    };
  }


}
