<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function test($startId,$endId){   //转格式，加水印
        $bookid=$startId;
        $image=new \Intervention\Image\ImageManager();
        while($bookid<=$endId){
            if(!is_dir('//Qingxia23/www/pic19/'.$bookid)){
                $bookid++;
                continue;
            }else{
                $all_files = \File::allFiles('//Qingxia23/www/pic19/'.$bookid);
                foreach($all_files as $file) {
                    if(!in_array(str_slug($file->getExtension()), ['jpg','png','gif','jpeg'])){
                        continue;
                    }
                    $picpath = $file->getRealPath();
                    echo $picpath."\n";
                    $img_info = getimagesize($picpath);
                    $wh_scale=$img_info[0]/$img_info[1];
                    $arr = explode('.',$picpath);
                    $ext=$arr[count($arr)-1];
                    if($img_info[0]==1000 || $img_info[0]==1500){
                        if($ext=='png'){
                            $image_now = $image->make($picpath);
                            $image_now->save(str_replace('.png','.jpg',$picpath));
                            file_put_contents($picpath, file_get_contents(str_replace('.png','.jpg',$picpath)));
                            unlink(str_replace('.png','.jpg',$picpath));
                        }
                    }else{
                        if($wh_scale>0.8){
                            $this->img2thumb($picpath,$picpath,1500);
                        }else{
                            $this->img2thumb($picpath,$picpath,1000);
                        }
                        $this->watermark($picpath);
                    }
                    //$this->watermark($picpath);
                }
                $bookid++;
            }
        }
    }


    public function  watermark ($picpath){ //添加水印
        $image=new \Intervention\Image\ImageManager();
        // echo M_SITE;die;
        $image_now = $image->make($picpath);
        $rand_num = rand(1,200);
        $path =  public_path('watermark/'.$rand_num.'.png');
        $mask_now = $image->make($path)->rotate(random_int(-50,50));
        $x = rand(50,$image_now->width()-200);
        $y = rand(50,ceil($image_now->height()/2)-50);
        $image_now->insert($mask_now,'top-left',$x,$y);
        $image_now->save($picpath);
    }


    public  function img2thumb($src_img, $dst_img, $width = 1000, $height = 0, $cut = 0, $proportion = 0)//缩略图 最大宽度限定到1000
    {
        ini_set('memory_limit',-1);
        $srcinfo = getimagesize($src_img);
        if(!$srcinfo){@unlink($src_img);return false;}//如果不是图片则删除原始文件
        $savepath=dirname($dst_img);
        if(!is_dir($savepath)) mkdir($savepath,0777,true);
        $ot = pathinfo($dst_img, PATHINFO_EXTENSION);
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);

        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
        if($src_w<$src_h && $width>1000) $width=1000;//解决除用于周报外的图片最大宽度
        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;

        /**
         * 缩略图不超过源图尺寸（前提是宽或高只有一个）
         */
        if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
        {
            $proportion = 1;
        }
        if($width> $src_w)
        {
            $dst_w = $width = $src_w;
        }
        if($height> $src_h)
        {
            $dst_h = $height = $src_h;
        }

        if(!$width && !$height && !$proportion)
        {
            return false;
        }
        if(!$proportion)
        {
            if($cut == 0)
            {
                if($dst_w && $dst_h)
                {
                    if($dst_w/$src_w> $dst_h/$src_h)
                    {
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    }
                    else
                    {
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                }
                else if($dst_w xor $dst_h)
                {
                    if($dst_w && !$dst_h)  //有宽无高
                    {
                        $propor = $dst_w / $src_w;
                        $height = $dst_h  = $src_h * $propor;
                    }
                    else if(!$dst_w && $dst_h)  //有高无宽
                    {
                        $propor = $dst_h / $src_h;
                        $width  = $dst_w = $src_w * $propor;
                    }
                }
            }
            else
            {
                if(!$dst_h)  //裁剪时无高
                {
                    $height = $dst_h = $dst_w;
                }
                if(!$dst_w)  //裁剪时无宽
                {
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int)round($src_w * $propor);
                $dst_h = (int)round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        }
        else
        {
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width  = $dst_w = $src_w * $proportion;
        }

        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        if(function_exists('imagecopyresampled'))
        {
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        else
        {
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }
}
