<?php

use App\Http\Controllers\OssController;

if (! function_exists('auth_url')) {
    function auth_url($url) {
        $timestamp = time() + 3600;
        $key = 'burglar88372';
        //$key = 'burglar88399';
        $hashvalue = md5($url . '-' . $timestamp . '-0-0-' . $key);
        return $url . '?auth_key=' . $timestamp . '-0-0-' . $hashvalue;
    }
}

if(!function_exists('check_isbn')){
    function check_isbn($isbn){
        $isbn_length = strlen($isbn);
        if ($isbn_length == 13 && is_numeric($isbn)) {
            if (intval(substr($isbn,0, 3)) != 978) return 0;
            $isbn_last = 0;
            for ($i = 0; $i < 12; $i++) {co
                if (($i + 1) % 2 == 0) {
                    $isbn_last += intval($isbn[$i] * 3);
                } else {
                    $isbn_last += intval($isbn[$i]);
                }
            }
            $isbn_last = 10 - $isbn_last % 10;
            if($isbn_last==10){
                $isbn_last=0;
            }
            if ($isbn_last == substr($isbn, $isbn_length - 1, 1)) {
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
}

if(!function_exists('get_press')){
    function get_press($isbn){
        $press = 0;
        $press_start = intval($isbn[4]);
        if ($press_start <= 3) {
            $press = substr($isbn, 4,3);
        }else if($press_start>3 and $press_start<=5){
            $press = substr($isbn, 4,4);
        }else if($press_start===8){
            $press = substr($isbn, 4,5);
        }else if($press_start===9){
            $press = substr($isbn, 4,5);
        }
        return $press;
    }
}

if(!function_exists('convert_isbn')){
    function convert_isbn($isbn)
    {

        if(strlen($isbn)==13 and is_numeric($isbn)){
            $isbn = substr_replace($isbn, '-', 3, 0);
            if (intval($isbn[5]) <= 3) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 9, 0);
            }
            if (intval($isbn[5]) > 3 and intval($isbn[5]) <= 5) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 10, 0);
            }
            if (intval($isbn[5]) == 8) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 11, 0);
            }
            if (intval($isbn[5]) == 9) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 12, 0);
            }
            $isbn = substr_replace($isbn, '-', -1, 0);
        }
        return $isbn;
    }
}

if(!function_exists('download_hd_img')){
    function download_hd_img($oss_path,$hd_pic_path)
    {
        $full_path = config('workbook.auth_url').auth_url($hd_pic_path);
        $oss = new OssController();

        return $oss->save($oss_path,file_get_contents($full_path));
//        if($oss->save($oss_path,file_get_contents($full_path))){
//            return $oss_path;
//        }else{
//            return false;
//        }
    }
}



if(! function_exists("getLastBookInfoByIsbn")){
    function getLastBookInfoByIsbn($isbn){
        $data = \DB::connection("mysql_local")->table("a_workbook_1010")->where("isbn",$isbn)
            ->select(["version_year","subject_name","version_name","volume_name","sort_name","grade_name"])
            ->orderBy("version_year","desc")->first();
        if(!isset($data)){
            $data["version_year"] = "暂无";
            $data["subject_name"] = "暂无";
            $data["version_name"] = "暂无";
            $data["volume_name"] = "暂无";
            $data["sort_name"] = "暂无";
            $data["grade_name"] = "暂无";
        }
       // dump($data);
        return $data;
    }
}

if(! function_exists("checkDateFormat")){
    function checkDateFormat($date){
        //匹配日期格式
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //检测是否为日期
            if(checkdate($parts[2],$parts[3],$parts[1]))
                return true;
            else
                return false;
        }
        else
            return false;
    }
}


if(!function_exists("checkBuyByIsbn")){
    function checkBuyByIsbn($isbn){
       $count = \App\LModel\LBuyBookWithIsbnModel::where('isbn',$isbn)->count("isbn");
       if($count){
           return "是";
       }else{
           return "否";
       }
    }
}

if(!function_exists("getSubject")){
    function getSubject($subject){
        $a  = ['1' => "语文",'2' => '数学','3' => '英语','4' => '物理','5' => '化学','6' => '生物','7' => '政治',
            '8' => '历史','9' => '地理','10' => '科学'];
        return $a[$subject];
    }
}

if(!function_exists("getGrade")){
    function getGrade($grade){
        $a  = ['1' => "一年级",'2' => '二年级','3' => '三年级','4' => '四年级','5' => '五年级','6' => '六年级','7' => '七年级',
            '8' => '八年级','9' => '九年级'];
        return $a[$grade];
    }
}