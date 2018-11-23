<?php

namespace App\Http\Controllers\Lww;

use App\LwwBook;
use App\LwwBookChapter;
use App\LwwBookQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimuController extends Controller
{
    public function index($book_id,$chapter_id,$page_id)
    {
      $data['book_info'] = LwwBook::where('id',$book_id)->with('chapters:id,bookid,chaptername,pages')->first(['id','bookname','grade_id','subject_id','volumes_id','version_id','version_year','uid']);
      $all_pages = $data['book_info']->chapters->pluck('pages','id');
	    $data['prev_chapter'] = $data['next_page'] = $data['next_chapter']= $data['prev_page'] =0;
	    foreach ($all_pages as $key=>$value){
		    if($value){
			    $page_arr = explode(',', $value);
			    $page_key = array_search($page_id, $page_arr);
			    if($page_key!==false){
				    if(isset($page_arr[$page_key+1]) || isset($page_arr[$page_key-1])){
					    if(isset($page_arr[$page_key+1])){
						    $data['next_page'] = $page_arr[$page_key+1];
						    $data['next_chapter'] = $key;
					    }
					    if(isset($page_arr[$page_key-1])){
						    $data['prev_page'] = $page_arr[$page_key-1];
						    $data['prev_chapter'] = $key;
					    }
				    }else{
					    $data['next_page'] = 0;
					    $data['next_chapter'] = 0;
				    }
			    }
		    }
	    }

//      if(in_array($page_id, explode(',', $data['book_info']->chapters->where('id',$chapter_id)->first()->pages))){
//
//      }
      $data['page'] = $page_id;
      $data['all_timu'] = LwwBookQuestion::where(['bookid'=>$book_id,'chapterid'=>$chapter_id,'pageid'=>$page_id])->with('timu_pics:timuid,id,timu_page,sort')->select('id','pageid','timuid','question','question_type','answer','answer_new','analysis')->get();
	    $data['all_answer'] = [];
      if(count($data['all_timu'])>0){
	      foreach ($data['all_timu'] as $key=>$value){
		      $data['all_timu'][$key]['all_timu_real_question'] = preg_replace('/\<span class=\"answer_now\">(.*?)\<\/span\>/', '<span class="answer_now_text" data-type="'.$value->question_type.'" data-timu="'.$value->timuid.'"></span>', $value->question);
		      $data['all_answer'][$key]['timuid'] = $value->timuid;

		      if($value->question_type===1){

	      	if(strpos($value->answer_new,',')>0){
			      foreach (explode(',', $value->answer_new) as $key1=> $value1){
				      $data['all_answer'][$key]['answer'][$key1] = explode('|', $value1)[1];
			      }
		      }else{
			      $data['all_answer'][$key]['answer'] = explode('|', $value->answer_new)[1];
		      }

	      }else if($value->question_type===4){
	        $data['all_answer'][$key]['answer'] = json_decode($value->answer_new);
        }else if($value->question_type===5){
	        $data['all_answer'][$key]['answer'] = $value->answer_new;
        }else{ }
	      $data['all_answer'][$key]['question_type'] = $value->question_type;
        $data['all_answer'][$key]['analysis'] = $value->analysis;
      }

	      $data['all_answer'] = collect($data['all_answer'])->toJson();
      }


      //377/25541/4
        if(empty($data['all_answer'])){
          $data['all_answer'] = '';
        }
//      if($book_id==377 and $chapter_id==25541 && $page_id == 4){
//          $data['all_answer'] = '';
//      }

      return view('lww.show_timu',compact('data'));
    }
}
