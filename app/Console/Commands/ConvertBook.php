<?php

namespace App\Console\Commands;

use App\LwwBook;
use App\PreMWorkbookAnswerUser;
use App\PreMWorkbookUser;
use App\TempModel\HdBook;
use App\TempModel\HdBookAnswer;
use Illuminate\Console\Command;

class ConvertBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'convert the answer to need table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        #INSERT INTO `workbook`.`pre_m_workbook_user` (`id`, `onlyname`, `sort_name`, `book_id`, `to_book_id`, `subject_id`, `grade_id`, `volumes_id`, `version_id`, `version_year`, `cover_img`, `cip_img`, `chapter_img`, `isbn`, `version`, `sort_id`, `banci`, `yinci`, `relatedid`, `credit`, `need_num`, `up_uid`, `status`, `addtime`, `source`, `hdid`, `jiexi`, `diandu`, `gendu`, `tingxie`, `update_uid`) VALUES ('10000017', '294|1|9|8|0', '智慧课堂好学案', '0', '0', '9', '1', '8', '0', '0', 'pic18/user_photo/20161226/dac118c718ac97d82df5086d4cd6ace9.jpg', 'pic18/user_photo/20161226/000206ddcdc239d494013e5fca233738.jpg', NULL, '9787535392435', '人教版/R/RJ', '294', '', '', NULL, '0', '1', '1810286', '2', '2016-08-24 18:10:11', NULL, '0', '0', '0', '0', '0', '0');

        $all_books = LwwBook::where(['verify_status'=>3])->select('id')->get();

        foreach ($all_books as $book){
            ignore_user_abort(true);
            $all_cut_pages = LwwBookPageTimupos::where('bookid',$id)->select(['pageid','timu_page','sort','id'])->get();
            $all_pic = [];
            foreach ($all_cut_pages as $value){
                $all_pic[] = "all_book_pages/{$id}/cut_pages/{$value->timu_page}/{$value->sort}_{$value->id}.jpg";
            }
            $all_now_pic = Storage::allFiles("all_book_pages/{$id}/cut_pages/");

            $not_need_pics = collect($all_now_pic)->diff($all_pic);
            foreach ($not_need_pics as $pic){
                $s = Storage::delete($pic);
            }
        }








//        $books = HdBook::where('status',2)->select()->get();
//        foreach ($books as $item){
//            $data['onlyname'] = $item->sortId.'|'.$item->gradeId.'|'.$item->subjectId.'|'.$item->volumes.'|'.$item->bookVersionId;
//            $data['sort_name'] = $item->bookName;
//            $data['subject_id'] = $item->subjectId>=0?$item->subjectId:0;
//            $data['grade_id'] = $item->gradeId>=0?$item->gradeId:0;
//            $data['volumes_id'] = $item->volumes>=0?$item->volumes:0;
//            $data['version_id'] = $item->bookVersionId>=0?$item->bookVersionId:0;;
//            $data['version_year'] = 2018;
//            $data['cover_img'] = config('workbook.hd_url').$item->coverImage;
//            $data['isbn'] = $item->isbn;
//            $data['sort_id'] = $item->sortId;
//            $data['status'] = 99;
//            $data['up_uid'] = 9999;
////        PreMWorkbookUser::create($data);
//
//            #INSERT INTO `workbook`.`hd_book_answer_2018` (`id`, `answerPathImage`, `answerType`, `clientUUID`, `createTime`, `height`, `num`, `objectId`, `objid`, `visibleType`, `width`, `addtime`) VALUES ('7909718', 'zone/answer/2018-07-11/1531280496966_IMG_20180707_210631.jpg', '0', 'c82130d7cbca49fba2f98c28611509bb', '2018-07-11 11:42:50', '1365', '0', '1707939', 'evKXNvJ/1rPaFxLsffm++dDPQl0AIOYX9HjePfhd93I=', '0', '2592', '2018-07-11 15:54:31');
//
//            $answers = HdBookAnswer::where('objid',$item->objectId)->select('answerPathImage','objectId')->get();
//            if(count($answers)>0){
//                $data['hdid'] = $answers[0]->objectId;
//                $new_book = PreMWorkbookUser::create($data);
//                $data_answer['answer_img'] = $answers->pluck('answerPathImage')->map(function ($value,$key){
//                    return config('workbook.hd_url').$value;
//                })->implode('|');
//                $data_answer['book_id'] = $new_book->id;
//                $data_answer['up_uid'] = 9999;
//                $data_answer['hdid'] = $answers[0]->objectId;
//                PreMWorkbookAnswerUser::create($data_answer);
//            }
//
//        }


//        HdBookAnswer::where('objid',$item->objectId)->select();
        
        $this->line('test');
    }
}
