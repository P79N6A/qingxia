<?php

namespace App\Http\Controllers\Manage\Api;

use App\ABook1010;
use App\ABookKnow;
use App\AWorkbookKnow;
use App\Workbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiBookChapterController extends Controller
{
    public function get_chapter($book_id){

        $book_now = ABook1010::find($book_id);
        if(count($book_now)>0){
            $book_chapters = $book_now->chapters()->where('chapter','<>','00'.$book_now->booksort)->orderBy('chapter','asc')->get();
            $now_key_1 = -1;
            $now_key_2 = -1;
            $now_key_3 = -1;
            $now_key_4 = 0;
            $now_key_5 = 0;
            $now_key_6 = 0;
            $book_first_chapters = '';
            $book_new = array();
            if(count($book_chapters)>0){
                foreach ($book_chapters as $key=>$value){
                if(strlen($value->chapter)==10){
                    $now_key_1 +=1;
                    $now_key_2 = -1;
                    $book_new[$now_key_1]['id'] = $value->chapter;
                    $book_new[$now_key_1]['text'] = $value->chaptername;
                }elseif(strlen($value->chapter)==12){
                    $now_key_2 +=1;
                    $now_key_3 = -1;
                    $book_new[$now_key_1]['children'][$now_key_2] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                }elseif(strlen($value->chapter)==14){
                    $now_key_3+=1;
                    $now_key_4 = -1;
                    $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                }elseif(strlen($value->chapter)==16){
                    $now_key_4+=1;
                    $now_key_5 = -1;
                    $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3]['children'][$now_key_4] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                }elseif(strlen($value->chapter)==18){
                    $now_key_5+=1;
                    $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3]['children'][$now_key_4]['children'][$now_key_5] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                }
            }
                $book_first_chapters = $book_now->chapters()->orderBy('chapter','asc')->select('chaptername')->first()->chaptername;
            }
            $chapter_root = !empty($book_first_chapters)?$book_first_chapters:'新建章节';
            $final_chapter_all = array( "id" => "$book_now->booksort", "text" => "$chapter_root", "type" => "root" ,"state"=> array("opened"=> true),"children"=>$book_new);

            return response()->json($final_chapter_all);

        }
    }

    public function get_workbook_chapter($book_id){
        $book_now = Workbook::find($book_id);
        if(count($book_now)>0){
            $book_chapters = $book_now->chapters()->where('chapter','<>','00'.$book_now->booksort)->orderBy('chapter','asc')->get();
            $now_key_1 = -1;
            $now_key_2 = -1;
            $now_key_3 = -1;
            $now_key_4 = 0;
            $now_key_5 = 0;
            $now_key_6 = 0;
            $book_first_chapters = '';
            $book_new = array();
            if(count($book_chapters)>0){
                foreach ($book_chapters as $key=>$value){
                    if(strlen($value->chapter)==10){
                        $now_key_1 +=1;
                        $now_key_2 = -1;
                        $book_new[$now_key_1]['id'] = $value->chapter;
                        $book_new[$now_key_1]['text'] = $value->chaptername;
                    }elseif(strlen($value->chapter)==12){
                        $now_key_2 +=1;
                        $now_key_3 = -1;
                        $book_new[$now_key_1]['children'][$now_key_2] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                    }elseif(strlen($value->chapter)==14){
                        $now_key_3+=1;
                        $now_key_4 = -1;
                        $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                    }elseif(strlen($value->chapter)==16){
                        $now_key_4+=1;
                        $now_key_5 = -1;
                        $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3]['children'][$now_key_4] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                    }elseif(strlen($value->chapter)==18){
                        $now_key_5+=1;
                        $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3]['children'][$now_key_4]['children'][$now_key_5] = array('id'=>$value->chapter,'text'=>$value->chaptername);
                    }
                }
                $book_first_chapters = $book_now->chapters()->orderBy('chapter','asc')->select('chaptername')->first()->chaptername;
            }
            $chapter_root = !empty($book_first_chapters)?$book_first_chapters:'新建章节';
            $final_chapter_all = array( "id" => "$book_now->booksort", "text" => "$chapter_root", "type" => "root" ,"state"=> array("opened"=> true),"children"=>$book_new);

            return response()->json($final_chapter_all);

        }
    }

    public function set_chapter(Request $request){
        $get_book_id = $request->get('id');
        $booksort_now = ABook1010::find($get_book_id)->booksort;
        $get_chapters = $request->get('chapters');
        $chapter_now = json_decode($get_chapters);

        $now_key_1 = 0;
        $now_key_2 = 0;
        $now_key_3 = 0;
        $now_key_4 = 0;
        $now_key_5 = 0;
        $now_key_6 = 0;
        $chapter_final = [];
        foreach ($chapter_now as $key=>$value){
            if($value->level==1){
                $chapter_final[$key]['chapter'] = $booksort_now;
            }elseif ($value->level==2){
                $now_key_1 += 1;
                $now_key_2 = 0;
                $in_key_1 = strlen($now_key_1)>1?$now_key_1:'0'.$now_key_1;
                $chapter_final[$key]['chapter'] = $booksort_now.$in_key_1;

            }elseif ($value->level==3){
                $now_key_2 += 1;
                $now_key_3 = 0;
                $in_key_1 = strlen($now_key_1)>1?$now_key_1:'0'.$now_key_1;
                $in_key_2 = strlen($now_key_2)>1?$now_key_2:'0'.$now_key_2;
                $chapter_final[$key]['chapter'] = $booksort_now.$in_key_1.$in_key_2;
            }elseif ($value->level==4){
                $now_key_3 += 1;
                $now_key_4 = 0;
                $in_key_1 = strlen($now_key_1)>1?$now_key_1:'0'.$now_key_1;
                $in_key_2 = strlen($now_key_2)>1?$now_key_2:'0'.$now_key_2;
                $in_key_3 = strlen($now_key_3)>1?$now_key_3:'0'.$now_key_3;
                $chapter_final[$key]['chapter'] = $booksort_now.$in_key_1.$in_key_2.$in_key_3;
            }elseif ($value->level==5){
                $now_key_4 += 1;
                $now_key_5 = 0;
                $in_key_1 = strlen($now_key_1)>1?$now_key_1:'0'.$now_key_1;
                $in_key_2 = strlen($now_key_2)>1?$now_key_2:'0'.$now_key_2;
                $in_key_3 = strlen($now_key_3)>1?$now_key_3:'0'.$now_key_3;
                $in_key_4 = strlen($now_key_4)>1?$now_key_4:'0'.$now_key_4;
                $chapter_final[$key]['chapter'][] = $booksort_now.$in_key_1.$in_key_2.$in_key_3.$in_key_4;
            }elseif ($value->level==6){
                $now_key_5 += 1;
                $now_key_6 = 0;
                $in_key_1 = strlen($now_key_1)>1?$now_key_1:'0'.$now_key_1;
                $in_key_2 = strlen($now_key_2)>1?$now_key_2:'0'.$now_key_2;
                $in_key_3 = strlen($now_key_3)>1?$now_key_3:'0'.$now_key_3;
                $in_key_4 = strlen($now_key_4)>1?$now_key_4:'0'.$now_key_4;
                $in_key_5 = strlen($now_key_5)>1?$now_key_5:'0'.$now_key_5;
                $chapter_final[$key]['chapter'] = $booksort_now.$in_key_1.$in_key_2.$in_key_3.$in_key_4;
            }elseif ($value->level==7){
                $now_key_6 += 1;
                $in_key_1 = strlen($now_key_1)>1?$now_key_1:'0'.$now_key_1;
                $in_key_2 = strlen($now_key_2)>1?$now_key_2:'0'.$now_key_2;
                $in_key_3 = strlen($now_key_3)>1?$now_key_3:'0'.$now_key_3;
                $in_key_4 = strlen($now_key_4)>1?$now_key_4:'0'.$now_key_4;
                $in_key_5 = strlen($now_key_5)>1?$now_key_5:'0'.$now_key_5;
                $in_key_6 = strlen($now_key_5)>1?$now_key_6:'0'.$now_key_6;
                $chapter_final[$key]['chapter'] = $booksort_now.$in_key_1.$in_key_2.$in_key_3.$in_key_4.$in_key_5.$in_key_6;
            }
            $chapter_final[$key]['booksort'] = $booksort_now;
            $chapter_final[$key]['chaptername'] = $value->text;

        }

        ABookKnow::where('booksort',$booksort_now)->delete();
        if(ABookKnow::insert($chapter_final)){
            return response()->json(['status'=>1,'msg'=>'更新成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'更新失败']);
        }

    }

    public function set_workbook_chapter(Request $request){
        $book_id = $request->get('book_id');
        $workbook_id = $request->get('workbook_id');
        $book_now = ABook1010::find($book_id);

        $chapters_now = $book_now->chapters()->orderBy('chapter','asc')->select()->get();
        $workbook_now = Workbook::find($workbook_id);

        foreach ($chapters_now as $key => $value){
            $data[$key]['book_id'] = $workbook_id;
            $data[$key]['booksort'] = $workbook_now->booksort;
            $data[$key]['chapter'] = $value->chapter;
            $data[$key]['chaptername'] = $value->chaptername;
        }

        $deleted = AWorkbookKnow::where('book_id',$workbook_id)->delete();
        if(AWorkbookKnow::insert($data)){
            $workbook_now->chapter_confirm = 1;
            $workbook_now->save();
            return response()->json(['status'=>1,'msg'=>'生成成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'生成失败']);
        }

    }

    public function mark_book(Request $request){
        $book_id = $request->get('id');
        $a_book_1010 = ABook1010::find($book_id);
        if($a_book_1010->wrong_chapter==0){
            $a_book_1010->wrong_chapter=1;
        }else{
            $a_book_1010->wrong_chapter=0;
        }
        $a_book_1010->save();
        return response()->json(['status'=>1,'msg'=>'更新成功']);
    }
}
