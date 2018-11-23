<?php

if (! function_exists('auth_url')) {
    function auth_url($url) {
        $timestamp = time() + 3600;
        $key = 'burglar88372';
        $hashvalue = md5($url . '-' . $timestamp . '-0-0-' . $key);
        return $url . '?auth_key=' . $timestamp . '-0-0-' . $hashvalue;
    }
}

if(!function_exists('convert_isbn')){
    function convert_isbn($isbn)
    {
        if(strlen($isbn)==13 and is_numeric($isbn)){
            $isbn = substr_replace($isbn, '-', 3, 0);

            if ($isbn[5] <= 3) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 9, 0);
            }
            if ($isbn[5] > 3 and $isbn[6] <= 5) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 10, 0);
            }
            if ($isbn[5] == 8) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 11, 0);
            }
            if ($isbn[5] == 9) {
                $isbn = substr_replace($isbn, '-', 5, 0);
                $isbn = substr_replace($isbn, '-', 12, 0);
            }
            $isbn = substr_replace($isbn, '-', -1, 0);
        }
        return $isbn;
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