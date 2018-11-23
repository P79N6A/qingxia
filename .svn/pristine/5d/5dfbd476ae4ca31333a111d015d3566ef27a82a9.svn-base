<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OSS\OssClient;

class UploadController extends Controller
{
    public function upload($book_id)
    {
        $output_dir = public_path("uploads/");
        if (isset($_FILES["myfile"])) {
            $ret = array();

//	This is for custom errors;
            /*	$custom_error= array();
                $custom_error['jquery-upload-file-error']="File already exists";
                echo json_encode($custom_error);
                die();
            */
            $error = $_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData()
            if (!is_array($_FILES["myfile"]["name"])) //single file
            {

                $fileName = $_FILES["myfile"]["name"];
//                dd($fileName);
//                dd(Request::capture()->file());die;
//                var_dump(iconv('gbk', 'utf-8',$fileName));die;
                $oss = new OssController();
                $extension = \File::extension($fileName);
//                $utf8_file = iconv('gbk', 'utf-8', $fileName);
                $time = date('Y-m-d', time());
                $oss->save('pic19/' . $book_id . '/new/' . md5_file($_FILES["myfile"]["tmp_name"]) . '.' . $extension, file_get_contents($_FILES["myfile"]["tmp_name"]));
                return response()->json(['status' => 1, 'img' => config('workbook.thumb_image_url').'pic19/' . $book_id . '/new/' . md5_file($_FILES["myfile"]["tmp_name"]) . '.' . $extension]);

                #move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                //$ret[] = $fileName;
            } else  //Multiple files, file[]
            {
                $fileCount = count($_FILES["myfile"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = $_FILES["myfile"]["name"][$i];
                    $oss = new OssController();
                    $extension = \File::extension($fileName);
                    $a = $oss->save('pic19/' . $book_id . '/new/'.md5_file($_FILES["myfile"]["tmp_name"][$i]) . '.' . $extension, file_get_contents($_FILES["myfile"]["tmp_name"][$i]));
                    dd($a);
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $fileName);
                    $ret[] = $fileName;
                }

            }
            echo json_encode($ret);
        }
    }

    public function upload_single()
    {
        if (isset($_FILES["myfile"])) {

            $ret = array();
            $error = $_FILES["myfile"]["error"];
            if (!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName = $_FILES["myfile"]["name"];
                $oss = new OssController();
                $extension = \File::extension($fileName);
                $book_path = 'pic18/'.date('Y-m-d',time()).'/';
                $file_name_last = md5_file($_FILES["myfile"]["tmp_name"]) . '.' . $extension;
                $oss->save($book_path . $file_name_last, file_get_contents($_FILES["myfile"]["tmp_name"]));

                return response()->json(['status' => 1, 'img' => config('workbook.thumb_image_url') . $book_path . $file_name_last]);
                #move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                //$ret[] = $fileName;
            }
        }
    }

    public function upload_book_page($book_id)
    {
        if (isset($_FILES["myfile"])) {

            $ret = array();
            $error = $_FILES["myfile"]["error"];
            if (!is_array($_FILES["myfile"]["name"])) //single file
            {

                $book_path = 'all_book_pages/'.get_bookid_path($book_id).'/pages/';


                $fileName = $_FILES["myfile"]["name"];
                $now_file = substr($fileName, 0, -4);
                if (strlen($now_file) == 5) {
                    $fileName = intval(substr($now_file, 1, 4)).'.jpg';
                }
                $oss = new OssController();
                $oss->save($book_path . $fileName, file_get_contents($_FILES["myfile"]["tmp_name"]));
                return response()->json(['status' => 1, 'img' => config('workbook.workbook_url') . $book_path . $fileName]);

                #move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                //$ret[] = $fileName;
            }
        }
    }
}
