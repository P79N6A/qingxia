<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/test/111', 'HomeController@test');
Auth::routes();

Route::any('/saveEventLog', 'TestController@saveEventLog');

Route::get('/home', 'HomeController@index');


Route::group(['prefix'=>'temp','namespace'=>'Temp'],function(){
    Route::get('courseware','CoursewareController@index');
});

Route::group(['prefix'=>'test','namespace'=>'Test'],function(){
    Route::get('/test/{type}','TestController@index');
    Route::get('/google/{num?}','TestController@google');

});

Route::any('/recognition','TestController@recognition');
Route::any('/copy_cover','TestController@copy_cover');

Route::any('/test_book', 'TestController@test_book');
Route::any('/test_pdd','TestController@test_pdd');
//1010jiajiao整理
Route::group(['prefix'=>'manage','namespace'=>'Manage','middleware'=>'auth'],function(){
    //vzy_manage
    //Route::get('/','IndexController@index');
    Route::get('/','IndexController@index')->name('backend');

    Route::get('/lxc_arrange/{subject?}','LxcController@index')->name('lxc');
    Route::get('/lxc_arrange_v2/{subject?}','LxcController@index_v2')->name('lxc_v2');
    Route::get('/lxc_edit/{onlyname?}/{status?}','LxcController@edit')->name('lxc_edit');
    Route::get('/lxc_edit_v2/{onlyname?}/{status?}','LxcController@edit_v2')->name('lxc_edit_v2');
    Route::get('lxc_answer/{bookid}','LxcController@answer')->name('lxc_answer');

    Route::get('/sub_sort_arrange','SubSortController@index')->name('sub_sort');
    Route::get('lxc_sort/{sort}/subject/{subject?}/grade/{grade?}','LxcController@sort')->name('lxc_sort');
    //整理课本
    Route::get('book_arrange/{version?}/{grade?}','BookController@index')->name('book_arrange');
    //整理课本目录
    Route::get('book_chapter/{version?}/{grade?}','BookController@chapter')->name('book_chapter');
    //整理sort_name
    Route::get('sort_name','SortNameController@index')->name('sort_name');
    Route::get('sort_name_detail/{id}/{name}/{press_id?}/{all?}','SortNameController@detail')->name('sort_name_detail');
    Route::get('sort_name_all/{id}/{press_id?}/{sort_name?}/{all?}','SortNameController@all')->name('sort_name_all');
    //遗漏整理
    Route::get('sort_name_v2','SortNameController@index_v2')->name('sort_name_v2');


    //workbook_manage
    Route::get('/workbook_arrange/{subject?}','WorkbookController@index')->name('workbook');
    Route::get('/workbook_edit/{onlyname?}/{status?}','WorkbookController@edit')->name('workbook_edit');
    Route::get('/no_onlyname_edit/{subject}/{status?}','WorkbookController@edit_onlyname')->name('no_onlyname_edit');
    Route::get('workbook/{bookid}','WorkbookController@answer')->name('workbook_answer');

    //workbook_only
    Route::get('/workbook_arrange_only/{subject?}','WorkbookController@index_only')->name('workbook_only');
    Route::get('/workbook_edit_only/{onlycode?}/{status?}','WorkbookController@edit_only')->name('workbook_edit_only');
    Route::get('/no_onlycode_edit/{subject}/{status?}','WorkbookController@edit_onlycode')->name('no_onlycode_edit');

    //workbook_arrange_v2
    Route::get('/workbook_v2','WorkbookController@workbook_arrange')->name('workbook_arrange');
    Route::get('/workbook_edit_v2/{onlyname?}/{status?}','WorkbookController@edit_v2')->name('workbook_edit_v2');
    Route::get('/no_onlyname_edit_v2/{subject}/{status?}','WorkbookController@edit_onlyname_v2')->name('no_onlyname_edit_v2');

    Route::get('workbook_cover/{version?}/{grade?}','WorkbookController@workbook_cover')->name('workbook_cover');



    //video_manage
    Route::group(['middleware'=>'can:video_manage'],function() {
        Route::get('video', 'VideoController@index')->name('video_manage');
        Route::get('video_show/{book_id?}', 'VideoController@show')->name('video_book_show');
    });

    //isbn_manage
    Route::get('isbn_manage','IsbnController@index')->name('isbn_manage');

    //book_recycle
    Route::get('book_recycle','BookRecycleController@index')->name('book_recycle');

    //book_check
    Route::get('book_check/{grade_id?}/{subject_id?}/{volumes?}/{book_version_id?}/{start_time?}/{end_time?}/{isbn?}','BookCheckController@index')->name('book_check');
//    Route::get('book_check',function (){
//
//    });

    //权限管理
    Route::get('system_manage','SystemController@index')->name('system_manage')->middleware('can:manage');


});

//1010jiajiao相关api
Route::group(['prefix'=>'manage/api','namespace'=>'Manage\Api','middleware'=>'auth'],function(){
  //提交练习册信息
  Route::post('/lxc_update','ApiLxcController@index')->name('lxc_update');
  //完成练习册信息编辑
  Route::post('/lxc_done','ApiLxcController@all_done')->name('lxc_done');

  //系列整理
  Route::post('/sort_done','ApiLxcController@sort_all_done')->name('sort_done');

  //大字更新
  Route::post('/sort_update','ApiLxcController@sort_update')->name('sort_update');
  //大字删除
  Route::post('/sort_del','ApiLxcController@sort_del')->name('sort_del');

  //提交练习册信息
  Route::post('/workbook_update','ApiWorkbookController@index')->name('workbook_update');
  //完成练习册信息编辑
  Route::post('/workbook_done','ApiWorkbookController@all_done')->name('workbook_done');


  //完成练习册信息编辑-only
  Route::post('/workbook_done_only','ApiWorkbookController@all_done_only')->name('workbook_done_only');


  //获取系列
  Route::get('/workbook_sort/','ApiWorkbookController@workbook_sort')->name('workbook_sort');
  //获取出版社
  Route::get('/workbook_press/','ApiWorkbookController@workbook_press')->name('workbook_press');

  //选中标准课本
  Route::post('/book_done','ApiABook1010Controller@book_done')->name('book_done');
  //更新课本
  Route::post('/book_update','ApiABook1010Controller@book_update')->name('book_update');

  //整理章节
  Route::get('/get_book_chapter/{book_id}','ApiBookChapterController@get_chapter')->name('get_chapter');
  //筛选错误章节课本
  Route::post('/mark_book','ApiBookChapterController@mark_book')->name('mark_book');
  //生成课本章节
  Route::post('/set_book_chapter','ApiBookChapterController@set_chapter')->name('set_chapter');
  //生成练习册章节
  Route::post('/set_workbook_chapter','ApiBookChapterController@set_workbook_chapter')->name('set_workbook_chapter');
  //获取练习册章节
  Route::get('/get_workbook_chapter/{book_id}','ApiBookChapterController@get_workbook_chapter')->name('get_workbook_chapter');


  //获取练习册淘宝图片
  Route::post('get_workbook_cover','ApiWorkbookController@get_workbook_cover')->name('get_workbook_cover');
  //删除练习册
  Route::post('delete_this_book','ApiWorkbookController@delete_this_book')->name('delete_this_book');
  //恢复练习册
  Route::post('recovery_this_book','ApiWorkbookController@recovery_this_book')->name('recovery_this_book');

  //获取练习册对应版本数量
  Route::post('get_workbook_version_num','ApiWorkbookController@get_version_num')->name('workbook_version_num');


  Route::post('/video_get','ApiVideoController@get_vid')->name('video_get_vid');
  //新增视频
  Route::post('/video_add','ApiVideoController@save')->name('video_add');
  //删除视频
  Route::post('/video_del','ApiVideoController@delete')->name('video_del');
  //更新视频
  Route::post('/video_modify','ApiVideoController@modify')->name('video_modify');
  //获取视频排序
  Route::post('/video_chapter','ApiVideoController@get_chapter')->name('get_video_chapter');
  Route::post('/set_chapter_sort','ApiVideoController@set_chapter_sort')->name('set_chapter_sort');

  //审核答案不通过
  Route::post('/book_check_now','ApiBookCheckController@check')->name('book_check_api');
  //审核后加一条
  Route::get('/book_check_add/{page?}/{grade_id?}/{subject_id?}/{start_time?}/{end_time?}/{isbn?}','ApiBookCheckController@add')->name('book_check_add');
  //选中系列不通过
  Route::post('all_not_pass','ApiBookCheckController@all_not_pass')->name('all_not_pass');
  //选中isbn不通过
  Route::post('isbn_not_pass','ApiBookCheckController@isbn_not_pass')->name('isbn_not_pass');


  //更多isbn信息
  Route::get('/more_isbn/{total?}','ApiBookCheckController@more_isbn')->name('more_isbn');

  //权限管理
  Route::group(['middleware'=>'can:manage'],function(){
    Route::post('/add_role_permission','ApiSystemManageController@add_role_permission')->name('add_role_permission');
    Route::post('/grant_role_permission','ApiSystemManageController@grant_role_permission')->name('grant_role_permission');
    Route::post('/del_role_permission','ApiSystemManageController@del_role_permission')->name('del_role_permission');
  });

});

//练习册整理 v2
Route::group(['prefix'=>'manage_new','namespace'=>'ManageNew','middleware'=>'auth'],function(){
  Route::get('/workbook_new_status/{uid?}','ManageNewController@status')->name('book_new_status');
  Route::get('/workbook_new/{type?}/{sort?}','ManageNewController@index')->name('book_new_index');
  Route::any('/workbook_new_api/{type}','ManageNewApiController@index')->name('book_new_api');
  Route::any('/workbook_new_api/new/{type}','ManageNewApiController@workbook_new')->name('book_new_workbook_api');

  //系列相关
  Route::get('workbook_new_sort','ManageNewController@sort_index')->name('book_new_sort');
  Route::get('/workbook_sub_sort_arrange/{sort?}/{sub_sort?}','ManageNewController@subsort_arrange')->name('book_new_subsort_arrange');

  //唯一化
  Route::get('workbook_new_only/{subject?}','ManageNewController@only')->name('book_new_only');
  Route::get('workbook_new_only/{sort}/{sub_sort}/{grade_id}/{subject_id}/{volumes_id}/{version_id}/{version_year?}','ManageNewController@only_detail')->name('book_new_only_detail');

  //根据章节答案整理
  Route::get('workbook_new_chapter/{grade?}/{subject?}/{volumes?}/{version?}/{type?}','ManageNewController@chapter_info')->name('book_new_chapter');


  //根据cip整理isbn
    Route::get('workbook_new_isbn/{type?}','ManageIsbnController@index')->name('book_new_isbn');
    Route::post('workbook_new_isbn_api/{type}','ManageIsbnController@api')->name('book_new_isbn_api');

    //根据cip整理答案
    Route::group(['prefix'=>'workbook_cip'],function(){
       Route::get('/list/{type?}/{single_id?}','ManageOssController@index')->name('manage_new_oss');
       Route::any('/api/{type}','ManageOssController@api')->name('manage_new_api');
       Route::get('/status/{start?}/{end?}','ManageOssController@status')->name('manage_new_oss_status');
    });

    //本地答案整理_bd
    Route::group(['prefix'=>'workbook_local'],function(){
        Route::get('/list/{type?}/{single_id?}','ManageLocalController@index')->name('manage_new_local');
        Route::get('/detail/{sort_name}/{type?}','ManageLocalController@sort_list')->name('manage_new_local_list');
        Route::any('/api/{type}','ManageLocalController@api')->name('manage_new_local_api');
    });
    //本地答案整理_test
    Route::group(['prefix'=>'workbook_local_test'],function(){
        //所有系列显示
        Route::get('/list/{type?}/{single_id?}','ManageLocalTestController@index')->name('manage_new_local_test');
        //单系列显示
        Route::get('/sort_detail/{sort_id?}','ManageLocalTestController@sort_single')->name('manage_new_local_test_sort');
        Route::get('/detail/{sort_id?}/{type?}/{now_id?}','ManageLocalTestController@sort_list')->name('manage_new_local_test_list');
        Route::any('/api/{type}','ManageLocalTestController@api')->name('manage_new_local_test_api');
    });

    //isbn_扫描测试
    Route::group(['prefix'=>'other_thing'],function(){
        Route::get('/list/{sort?}','OtherThingController@temp_isbn')->name('manage_new_other_temp');
        Route::any('/found_about/{type?}','OtherThingController@found_about')->name('manage_new_found_about');
        Route::post('/save_online_isbn','OtherThingController@save_online_isbn')->name('manage_new_save_online_isbn');
    });

    //isbn_temp
    Route::group(['prefix'=>'isbn_temp'],function (){
       Route::get('/list/{type?}/{single_id?}','ManageIsbnTempController@index')->name('isbn_temp_index');
        Route::get('/by_sort/{sort?}','ManageIsbnTempController@by_sort')->name('isbn_temp_by_sort');
       Route::get('/detail/{isbn?}','ManageIsbnTempController@detail')->name('isbn_temp_detail');
        Route::any('/api/{type}','ManageIsbnTempController@api')->name('isbn_temp_api');
    });
});



//练习册购买
/*************************************************************/
Route::group(['prefix'=>'book_buy','namespace'=>'BookBuy','middleware'=>'auth'],function (){
  Route::get('/index','IndexController@index')->name('book_buy_index');
  Route::get('/wait','IndexController@wait')->name('book_buy_wait');
  Route::get('/done','IndexController@done')->name('book_buy_done');
  Route::get('/detail/{id}','IndexController@detail')->name('book_buy_detail');
  Route::group(['prefix'=>'api','namespace'=>'Api'],function (){
    Route::get('/same_sort_about/{sort}','IndexController@sort_about')->name('api_book_buy_sort');
    Route::post('check_it/','IndexController@check_it')->name('check_it');
    Route::post('add_to_buy','IndexController@add_all')->name('api_book_buy_add');
    Route::post('delete_this_book','IndexController@delete_book')->name('api_book_delete');
    Route::post('add_to_done','IndexController@add_done')->name('api_book_buy_done');
    Route::post('done_status/{type}','IndexController@done_status')->name('api_book_buy_finish');
    Route::post('add_new_book','IndexController@add_new_book')->name('api_book_add_new');
    Route::post('mark_buy_status','IndexController@mark_buy_status')->name('mark_buy_status');

    Route::post('new_book_buy_api/{type}','IndexController@new_book_buy_api')->name('new_book_buy_api');
  });

    Route::get('/new_buy/{type?}/','NewController@index')->name('new_book_buy');
    Route::get('/new_book_answer/{book_id?}/','NewController@show_answer')->name('new_book_show_answer');
    Route::get('/new_buy_detail/{sort?}/{version?}','NewController@detail')->name('new_book_buy_detail');
    Route::get('/new_buy_status/{start?}/{end?}','NewController@status')->name('new_book_buy_status');
    Route::get('/new_buy_history_book/{book_id?}/{grade_id?}/{subject_id?}/{volumes_id?}/{version_id?}/{sort?}','NewController@history')->name('new_book_history');

    Route::get('/new_index_all/{sort?}/{volumes_id?}/{need_buy?}','NewController@new_index')->name('new_index_all');

    Route::post('/upgrade_book','NewController@upgrade_book')->name('book_buy_upgrade_book');

});

/**新买书界面**/
Route::group(['prefix'=>'new_buy','namespace'=>'NewBuy','middleware'=>'auth'],function (){
    Route::get('/index/{order?}','SortListController@index')->name('new_buy_index');
    Route::get('/list/{sort_id?}/{version_id?}','SortListController@sort_list')->name('new_buy_sort_list');
    Route::get('/only_detail/{sort}/{grade}/{subject}/{volumes}/{version}','SortListController@only')->name('new_buy_only');
    Route::get('/only_detail_name/{newname}','SortListController@only_name')->name('new_buy_only_name');
    Route::get('/repeat_book/list','RepeatAnswerController@repeat_list')->name('new_buy_repeat_list');
    Route::get('/repeat_book/detail/{newname}','RepeatAnswerController@repeat_detail')->name('new_buy_repeat_detail');
    Route::get('/repeat_book/detail_books/{grade_id}/{subject_id}/{volumes_id}/{version_id}/','RepeatAnswerController@repeat_detail_books')->name('new_buy_repeat_detail_books');


    Route::get('/bought_record/{sort?}/{only_id?}/{subject_id?}/{grade_id?}/{version_id?}/{status?}/{start?}/{end?}','BoughtRecordController@bought_list')->name('new_buy_record');
    Route::get('/bought_return/{sort?}/{only_id?}','BoughtRecordController@return_list')->name('new_buy_return');

    Route::get('/analyze_record/{sort?}/{only_id?}/{grade_id?}/{order?}','AnalyzeRecordController@analyze_list')->name('new_buy_analyze');

    Route::get('/book_list/{sort?}/{volumes_id?}/{word?}','BookListController@index')->name('book_list');
    Route::get('/bought_record_list/{sort?}/{only_id?}/{subject_id?}/{grade_id?}/{version_id?}','BookListController@bought_record_list')->name('bought_record_list');
});

Route::group(['prefix'=>'ajax','namespace'=>'Ajax','middleware'=>'auth'],function (){
    Route::any('/for_new_buy/{type}','AjaxNewBuyController@ajax')->name('ajax_new_buy');

    Route::any('/mark','AjaxBookListController@mark')->name('ajax_book_list');

    Route::group(['prefix'=>'for_audit'],function() {
       Route::any('list/{type}','AjaxAuditController@byIsbn')->name('ajax_new_audit_list');
       Route::any('repeat/','AjaxAuditController@repeat_book')->name('ajax_new_repeat_books');
    });
    //采集书本ajax
    Route::any('for_caiji_book/{type}','AjaxCaijiBookController@ajax')->name('ajax_caiji_book');
});


/*************************************************************/
//解题相关
Route::group(['prefix'=>'question_manage','namespace'=>'QuestionManage','middleware'=>'auth'],function (){
  Route::get('/','IndexController@index')->name('que_manage_index');
  Route::get('/detail/{id}','IndexController@detail')->name('que_manage_detail');
  Route::get('/show','IndexController@status')->name('que_manage_status');
  Route::group(['prefix'=>'api','namespace'=>'Api'],function (){
    Route::any('/index/{action}','IndexController@index')->name('api_que_manage_index');
    Route::any('/ocr','IndexController@ocr_it')->name('api_que_manage_ocr');
    Route::any('/search','IndexController@search_it')->name('api_que_search');
    Route::any('/detail/{id}/{action}','IndexController@detail')->name('api_que_manage_detail');
    Route::post('/save_pic_to_oss','IndexController@save_pic_to_oss')->name('save_pic_to_oss');
    Route::post('/move_to/{type}','IndexController@move_to')->name('question_move_to');
  });
});

Route::any('/test_arrange','TestController@test_book')->name('test_arrange');


Route::group(['prefix'=>'homework_manage','namespace'=>'HomeworkManage','middleware'=>'auth'],function (){
  Route::get('/index/{type?}','HomeworkController@index')->name('homework_manage_index');


  //api
  Route::any('/api/{type?}','HomeworkApiController@index')->name('homework_manage_api_index');
});

Route::group(['prefix'=>'question_manage_test','namespace'=>'QuestionManage','middleware'=>'auth'],function (){
  Route::get('/','TestController@index')->name('que_manage_test');


});

/***************************************************/

Route::group(['prefix'=>'teacher','namespace'=>'Teacher','middleware'=>'auth'],function (){
  Route::get('/','IndexController@index')->name('teacher_index');
  Route::get('/teacher_square','IndexController@teacher_square')->name('teacher_square');
  Route::get('/teacher_center','IndexController@teacher_center')->name('teacher_center');
  Route::get('/teacher_pressed_about','IndexController@teacher_pressed_about')->name('teacher_pressed_about');
  Route::get('/teacher_question_detail/{id}','IndexController@teacher_question_detail')->name('teacher_question_detail');
  Route::get('/teacher_question_reply','IndexController@teacher_question_reply')->name('teacher_question_reply');
  Route::post('/teacher_img_download','WxApiController@download_img')->name('teacher_img_download');
  Route::post('/teacher_voice_upload','WxApiController@upload_voice')->name('teacher_voice_upload');
  Route::post('/teacher_img_update','WxApiController@update_img')->name('teacher_img_update');
});



//05wang管理
Route::group(['prefix'=>'05wang','namespace'=>'Lww','middleware'=>['auth','can:lww']],function(){
    Route::get('/','IndexController@index')->name('lww_index');
    Route::get('/sid/{subject_id?}/gid/{grade_id?}/word/{word?}','IndexController@index_search')->name('lww_index_search');
    Route::get('/book/{bookid}/year/{year}/volume/{volume}/{single_book_id?}','IndexController@chapter')->name('lww_chapter');//章管理
    Route::get('/lww_add/{id?}','IndexController@add')->name('lww_add');//书新增
    Route::get('/lww_add_chapter/{id?}','IndexController@add_chapter')->name('lww_add_chapter');//章节新增
    //解析管理
    Route::get('/lww_jiexi_page/{book_id}/{chapter_id}','IndexController@show_page')->name('lww_show_page');
  //点读管理
    Route::get('/lww_diandu_page/{book_id}/{chapter_id}','IndexController@diandu_page')->name('lww_diandu_page');
    //上传页管理
    Route::get('/lww_upload_page/{book_id?}/{volume_id?}','IndexController@upload_page')->name('lww_upload_page');

    //统一ocr入库
    Route::get('lww_ocr_results','IndexController@ocr_results')->name('ocr_results');

    //题目展示
    Route::get('lww_show_timu/{book_id}/{chapter_id?}/{page_id?}','TimuController@index')->name('lww_show_timu');



    //05wang相关api
    Route::group(['prefix'=>'api','namespace'=>'Api'],function (){
      //更改练习册所属人
      Route::post('lww_change_user','BookController@change_lxc_user')->name('lww_change_user');

      //搜索相关练习册
      Route::post('/lww_search_book','BookController@search_book')->name('lww_search_book');
      Route::post('/add_book','BookController@add')->name('lww_add_book');
      Route::get('/get_book_chapter/{book_id}','BookController@get_chapter')->name('lww_get_chapter');
      //审核相关
      Route::any('lww_book_verify','BookController@verify_about')->name('lww_book_verify');


      //确认新增练习册
      Route::post('/add_check','BookController@get_check')->name('add_check_it');

      //编辑页面
      Route::any('/lww_book_page_edit','BookController@edit_page')->name('lww_page_edit');
      //获取题目及答案
      Route::post('lww_book_page_question','BookQuestionController@page_question')->name('lww_page_question');
      //题目相关请求
      Route::any('lww_book_page_request','BookQuestionController@question_about')->name('lww_page_question_about');
      //生成章节
      Route::post('/set_book_chapter','BookController@set_chapter')->name('lww_set_chapter');
      //通过课本生成章节
      Route::post('/make_book_chapter','BookController@make_chapter')->name('set_lwwbook_chapter');
      //设置章节对应页面
      Route::post('/set_chapter_page','BookController@set_chapter_page')->name('lww_set_chapter_page');
        //05网
        Route::post('/save_chapter_page','BookController@save_chapter_page')->name('lww_save_chapter_page');

        Route::post('/save_answer_chapter_page','BookController@save_answer_chapter_page')->name('lww_save_answer_chapter_page');

        //05网a_thread_book关联a_book的id
        Route::post('/update_bookid','BookController@update_bookid')->name('lww_update_bookid');
        //获取书本图片
        Route::any('/get_bookimgs','BookController@get_bookimgs')->name('lww_get_bookimgs');

        //获取书本图片
        Route::any('/get_answer_bookimgs','BookController@get_answer_bookimgs')->name('lww_get_answer_bookimgs');


        //升级练习册
        Route::post('/upgrade_year','BookController@upgrade_year')->name('lww_upgrade_year');
        //设置当前展示学年
        Route::post('/set_year','BookController@set_year')->name('lww_set_year');
        //确认章节解析完毕
        Route::post('/set_jiexi_done','BookController@set_jiexi_done')->name('lww_set_jiexi_done');



      //设置页面排序
      Route::post('/set_pages_order','BookController@set_pages_order')->name('lww_set_pages_order');
      //重新生成页码
      Route::post('/set_pages_number','BookController@set_pages_number')->name('lww_set_pages_number');
      //统一图片大小
      Route::post('/lww_set_image_size','BookController@set_image_size')->name('lww_set_image_size');
      //删除页面
      Route::post('/lww_del_page','BookController@del_page')->name('lww_del_page');
        //删除页面
        Route::post('/lww_del_page_online','BookController@del_page_online')->name('lww_del_page_online');
      //识别图片
      Route::post('lww_ocr_page','BookController@ocr_page')->name('lww_ocr_page');
      //获取章节题目
      Route::post('get_chapter_timu','BookController@get_chapter_timu')->name('get_chapter_timu');

      //点读相关
      Route::any('/lww_book_diandu_edit','BookController@diandu_edit')->name('lww_diandu_edit');
      Route::post('/diandu','BookController@diandu')->name('lww_voice_post');
    });



});


//百度统计
Route::group(['prefix'=>'baidu','middleware'=>'auth','namespace'=>'Baidu',], function(){

    Route::any('/api','GetDataController@api')->name('baidu_manage_api');

    Route::get('/answer/{time?}/{grade_id?}/{subject_id?}/{volume_id?}/{version_id?}/{sort_id?}','BaiduController@index')->name('baidu_manage');
    Route::get('/question/{time?}/{type?}/','BaiduController@question')->name('baidu_manage_question');
    Route::get('/xiti/{time?}/{type?}/','BaiduController@xiti')->name('baidu_manage_xiti');
    Route::post('/add_jiexi','BaiduController@add_jiexi')->name('baidu_add_jiexi');
    Route::post('/add_xiti','BaiduController@add_xiti')->name('baidu_add_xiti');
    Route::get('/baidu_book_detail/{id}/{time?}','BaiduController@book_detail')->name('baidu_book_detail');
    Route::get('/baidu_shiti_detail/{type}/{id}/{time?}','BaiduController@shiti_detail')->name('baidu_shiti_detail');
    Route::get('/baidu_xiti_detail/{type}/{id}/{time?}','BaiduController@xiti_detail')->name('baidu_xiti_detail');
    Route::get('/portal/{time?}/','BaiduController@portal')->name('baidu_manage_portal');
    Route::get('/portal_detail/{id}/{time?}/','BaiduController@portal_detail')->name('baidu_portal_detail');
    Route::get('/new_portal/{time?}','BaiduController@new_portal')->name('baidu_new_portal');
    Route::post('/add_portal/','BaiduController@add_portal')->name('baidu_add_portal');

    //无答案试题处理
    Route::get('/no_answer_shiti/{status?}/{type?}','BaiduController@question_no_answer')->name('baidu_question_no_answer');
    Route::post('/baidu_add_shiti_dann/','BaiduController@add_answer_for_shiti')->name('baidu_add_shiti_dann');
});

//答案审核
Route::group(['prefix'=>'audit','middleware'=>'auth','namespace'=>'AnswerAudit'],function(){
    //isbn降序
    Route::get('/by_isbn','IndexController@by_isbn')->name('audit_index');
    //答案
    Route::get('/by_answer','IndexController@by_answer')->name('audit_answer');

    Route::get('/by_answer/{id}','IndexController@answer_detail')->name('audit_answer_detail');


    //获取isbn封面列表
    Route::get('/by_isbn/{isbn}','IndexController@isbn_detail')->name('audit_isbn_detail');

    //获取isbn对应所有封面
    Route::get('/get_isbn_cover/{isbn?}','IndexController@isbn_cover')->name('audit_isbn_cover');

    Route::any('/api/{type}','IndexController@api')->name('audit_api');

    Route::group(['prefix'=>'oss'], function(){

       Route::get('/','OssToAnswerController@index')->name('audit_oss_index');
    });


});

//图片上传
Route::group(['prefix'=>'upload','milldeware'=>'auth'],function(){
    Route::post('/upload_now/single/to_upload','UploadController@upload_single')->name('upload_single');
   Route::post('/upload_now/{book_id}','UploadController@upload')->name('upload_now');
   //零五网图片上传
    Route::post('/upload_book_page/{book_id}','UploadController@upload_book_page')->name('upload_book_page');
});

//用户反馈
Route::group(['prefix'=>'user_about','middleware'=>'auth','namespace'=>'UserAbout'],function(){
   Route::get('/feedback_list/{sortBy?}/{is_book?}/{status?}','FeedbackController@index')->name('user_feedback_list');
   Route::get('/feedback_status/{start?}/{end?}','FeedbackController@status')->name('user_feedback_status');
   Route::any('feedback_api/{type?}','FeedbackController@api')->name('feedback_api');
   Route::get('/hd_answer/{isbn}','FeedbackController@isbn_search')->name('user_isbn_search');
});

//收藏统计
Route::group(['prefix'=>'favorite_chart','middleware'=>'auth','namespace'=>'Chart'],function(){
    Route::get("/","FavoriteChartController@index")->name("favorite_chart_index");
    Route::post("/ajax","FavoriteChartController@indexAjax")->name("favorite_chart_ajax_index");
});

Route::group(['prefix'=>'isbn_tongji','middleware'=>'auth','namespace'=>'Chart'],function (){
    Route::get("/",'TongJiIsbnController@index')->name('isbn_tongji');
});
//搜索统计
Route::group(['prefix'=>'searchTongji','middleware'=>'auth','namespace'=>'Chart'],function (){
    Route::get('/index/{start?}/{end?}','SearchChartController@index')->name("search_tongji");
    Route::get("/bookInfoByIsbn/{isbn}","SearchChartController@bookInfoByIsbn")->name("book_info_by_isbn");
    Route::get("/edit/{isbn?}","SearchChartController@edit")->name("buybookbyisbn");
    Route::post("/edit/{isbn}","SearchChartController@edit")->name("buybookbyisbnsave");
});
Route::group(["prefix"=>'reg_chart','middleware'=>'auth','namespace'=>'Chart'],function(){
    Route::get("/index/{start?}/{end?}","RegChartController@index")->name("reg_chart");
    Route::get("/ajax/{start?}/{end?}","RegChartController@ajax")->name("reg_chart_ajax");
});

Route::group(["prefix"=>"taobaoBook",'middleware'=>'auth','namespace'=>'Chart'],function(){
    Route::get("index/{keyword?}/{contain?}/{remove?}","TaobaoBookController@index")->name("taobao_book");
    Route::get("/getSortByKey","TaobaoBookController@getSortByKey")->name("getSortByKey");
    Route::get("/hideItem/{id?}","TaobaoBookController@hideItem")->name("hideItem");
    Route::get("index2","TaobaoBookController@index2")->name("taobao_book2");
    Route::get("getBookInfo","TaobaoBookController@getBookInfo")->name("taobao_getBookInfo");
    Route::get("getBookList/{keyword}/{subject}/{grade}/{contain?}/{remove?}",'TaobaoBookController@getBookList')->name("taobao_getBookList");
    Route::get("shopTop","TaobaoBookController@shopTop")->name("taobao_shopTop");
    Route::get("shopList/{shopId}","TaobaoBookController@shopList")->name('shopList');
    Route::post('saveRemove',"TaobaoBookController@saveRemove")->name('saveRemove');
    Route::get("getRemove",'TaobaoBookController@getRemove')->name('getRemove');
    Route::get("addChart/{goodsId?}/{jId?}",'TaobaoBookController@addChart')->name("taobao_addChart");
    Route::get("cartList/{uid?}","TaobaoBookController@cartList")->name("tao_cartList");
    Route::get("simpleindex/{sortname?}/{contain?}/{remove?}","TaobaoBookController@simpleindex")->name("taobao_book_simple");


});
//买书-淘宝
Route::group(["prefix"=>"taobao",'middleware'=>'auth','namespace'=>'Taobao'],function(){
    Route::get("buybook/{sortname?}/{contain?}/{remove?}",'TaobaoBookController@buybook')->name('taobao_buybook');
    Route::get("new_bookList/{shopId}","TaobaoBookController@new_bookList")->name('new_bookList');
    Route::get("new_shopList/{keyword}/{subject}/{grade}/{contain?}/{remove?}",'TaobaoBookController@new_shopList')->name("new_shopList");
    Route::get("/hideItem/{id?}","TaobaoBookController@hideItem")->name("new_hideItem");
    Route::get("getBookInfo","TaobaoBookController@getBookInfo")->name("new_getBookInfo");
    Route::get("shopTop","TaobaoBookController@shopTop")->name("new_shopTop");
    Route::post("findClear","TaobaoBookController@findClear")->name("findClear");

    Route::get("search/{keyword?}/{type?}/{sort_id?}/{is_read?}/{v_status?}/{remove_isbn?}/{has_year?}/{start?}/{end?}","TaobaoBookController@search")->name("taobao_search");
    Route::post("shopLinkBySort","TaobaoBookController@shopLinkBySort")->name("shopLinkBySort");
    Route::post("remove_word","TaobaoBookController@remove_word")->name("remove_word");
    Route::post("del_remove","TaobaoBookController@del_remove")->name("del_remove");
    Route::post("is_read","TaobaoBookController@is_read")->name("is_read");
    Route::get("goods_list/{sort_id?}","TaobaoBookController@goods_list")->name("goods_list");
    Route::post("show_isbninfo","TaobaoBookController@show_isbninfo")->name("show_isbninfo");
    Route::post("show_bought","TaobaoBookController@show_bought")->name("show_bought");
});

Route::group(['prefix'=> 'searchApi','namespace'=>'Chart'],function (){
    Route::get("/","SearchApiController@index")->name("searchapi");
    Route::get("/","SearchApiController@index")->name("searchapi");
    Route::post("/hdAdd","SearchApiController@hdAdd")->name("hdAdd");
});

//更新本地数据
Route::group(['prefix'=>'task','namespace'=>'Task'],function(){
   Route::get('/update_feedback','UpdateFeedbackController@index')->name('task_feedback');
   Route::get('/update_oss','UpdateOssController@index')->name('task_oss');
});


//新答案审核
Route::group(['prefix'=>'Newaudit','middleware'=>'auth','namespace'=>'NewAnswerAudit'],function(){
    Route::get('/by_isbn/{status?}/{start?}/{end?}','IndexController@by_isbn')->name('new_audit_index');
    Route::get('/by_answer/{isbn}','IndexController@answer_detail')->name('new_audit_answer');
    Route::any('/api/{type}','IndexController@api')->name('new_audit_api');

    //审核用户上传的答案
    Route::get('/booklist/{type?}','BookListController@booklist')->name('audit_booklist');
    Route::get('/auditing/{bookid?}/{type?}/{page?}','BookListController@auditing')->name('user_audit');
    Route::post('/updateStatus','BookListController@updateStatus')->name('updateStatus');
    Route::post('/answerpass','BookListController@answerpass')->name('answerpass');
    Route::post('/rotate_img','BookListController@rotate_img')->name('rotate_img');
    Route::post('/update_answer','BookListController@update_answer')->name('update_answer');
    Route::post('/update_bookstatus','BookListController@update_bookstatus')->name('update_bookstatus');
    Route::post('/cancel_pass','BookListController@cancel_pass')->name('cancel_pass');

    //用户奖励发放审核
    Route::get('/userlist/{status?}/{start?}/{end?}','UserAwardController@userlist')->name('answer_user_award');
    Route::post('/award_user','UserAwardController@award_user')->name('award_user');
    Route::post('/award_show_answer','UserAwardController@award_show_answer')->name('award_show_answer');
});


//新05网
Route::group(['prefix'=>'one_lww','middleware'=>'auth','namespace'=>'OneLww'],function() {
    Route::get('/sort_index/{type?}/{order?}/{asc?}', 'IndexController@sort_index')->name('one_lww_sort_index');
    Route::get('/xilie/{district?}/{order?}/{asc?}', 'IndexController@index')->name('one_lww_index');

    Route::get('/booklist/{onlyid?}/{ssort_id?}/{sort_id?}/{grade_id?}/{subject_id?}/{version_id?}/{order?}', 'IndexController@booklist')->name('one_lww_booklist');

    Route::get('/hotbooklist/{ssort_id?}/{sort_id?}/{grade_id?}/{subject_id?}/{version_id?}/{order?}', 'IndexController@hotbooklist')->name('one_lww_hotbooklist');

    Route::get('/workbook_list/{ssort_id?}/{sort_id?}/{grade_id?}/{subject_id?}/{version_id?}', 'IndexController@workbook_list')->name('one_lww_workbook_list');
    Route::get('/chapter/{onlyid}/{year?}/{volume_id?}', 'IndexController@chapter')->name('one_lww_chapter');

    Route::any('/ajax/{type}', 'AjaxController@switch_action')->name('one_lww_ajax');

    //无章节分页直接处理解析
    Route::get('no_chapter_analysis/{onlyid}/{year}/{volume}/{bookid}','NoChapterController@index')->name('no_chapter_analysis_index');
    Route::post('no_chapter_ajax/{type?}','NoChapterController@ajax')->name('no_chapter_ajax');

    //审核预览解析页
    Route::get('preview_analysis/{onlyid}/{year}/{volume}/{bookid}','PreviewController@index')->name('preview_analysis_index');
    Route::post('preview_ajax/{type}','PreviewController@ajax')->name('preview_analysis_ajax');
});

//用户上传内容审核
Route::group(['prefix'=>'AuditContent','namespace'=>'AuditContent'],function(){
    Route::get('/booklist/{type?}','UserContentController@booklist')->name('audit_content_booklist');
    Route::get('/auditing/{bookid?}/{type?}/{page?}','UserContentController@auditing')->name('user_content_audit');
    Route::post('/updateStatus','UserContentController@updateStatus')->name('UpdateContentStatus');
    Route::post('/contentpass','UserContentController@contentpass')->name('ContentPass');
    Route::post('/cancel_pass','UserContentController@content_cancel')->name('Content_cancel');
});

//审核封面
Route::group(['prefix'=>'Cover','namespace'=>'Cover'],function(){
    Route::get('/CheckCover','CoverController@CheckCover')->name('check_cover');
    Route::post('/CopyCover','CoverController@CopyCover')->name('choose_book');
    Route::post('/is_check','CoverController@is_check')->name('is_check');
    Route::any('/save_pic','CoverController@save_pic')->name('save_pic_to_cover_isbn');
    Route::any('/recognition','CoverController@recognition')->name('cip_recognition');
    Route::any('/copy_cover','CoverController@copy_cover')->name('copy_cover');
});

//采集书本整理
Route::group(['prefix'=>'CaijiBook','namespace'=>'CaijiBook'],function(){
    Route::any('search/{word?}/{remove?}','CaijiBookController@search')->name('caiji_book_search');
    Route::get('/caijibook_by_sort','CaijiBookController@caijibook_by_sort')->name('caijibook_by_sort');
    Route::get('/caiji_booklist/{sortid?}','CaijiBookController@caiji_booklist')->name('caiji_booklist');
});


//兼职个人任务
Route::group(['prefix'=>'PartTimeWork','namespace'=>'PartTimeWork'],function(){
    Route::get('/booklist/{status?}','PartTimeWorkController@booklist')->name('part_time_booklist');
    Route::get('/book/{bookid?}/{page?}','PartTimeWorkController@book')->name('part_time_workbook');
    Route::post('parttime_work_ajax/{type?}','PartTimeWorkController@ajax')->name('parttime_work_ajax');
    /*Route::post('/book_success','PartTimeWorkController@book_success')->name('book_success');
    Route::post('/part_time_confirm','PartTimeWorkController@part_time_confirm')->name('part_time_confirm');*/
});


Route::group(['prefix'=>'local_img_upload','namespace'=>'UploadLocalImg'],function (){
    Route::any('/index/{type?}','IndexController@local_dispatch')->name('upload_all_imgs');
});

//测试控制器
Route::get('/new','Mytest\NewController@index')->name('a_book_goods');
Route::get('/list','Mytest\NewController@list')->name('a_book_list');
Route::get('/logs','Mytest\NewController@logs')->name('img_upload_logs');
Route::post('/logs','Mytest\NewController@logs')->name('img_upload_logs');
Route::post('/change','Mytest\Api\ChangeController@change')->name('change_path_name');
Route::post('/move','Mytest\Api\MoveController@move')->name('move_files');
Route::any('/updatefile','Mytest\Api\MoveController@updatefile')->name('updatefile');

//目录列表
Route::group(['namespace'=>'Mytest'],function (){
    Route::any('/getsort','Api\SortController@get_sort')->name('get_sort');
    Route::any('/test','TestController@test')->name('test_test');


});

//书本热度管理
Route::group(['prefix'=>'hot_tongji','namespace'=>'HotTongji'],function (){
    Route::any('/hotlist/{grade_id?}/{subject_id?}/{volumes_id?}/{version_id?}/{sort_id?}/{start?}/{end?}/{attr?}/{type?}','ATongJiHotBookController@hotlist')->name('hotlist');
    Route::any('/stophere/{isbn?}/{start?}/{end?}','ATongJiHotBookController@stophere')->name('stophere');
    Route::any('/hotcollect','ATongJiHotBookController@hotcollect')->name('hotcollect');
    Route::any('/hotsearch','ATongJiHotBookController@hotsearch')->name('hotsearch');
    Route::any('/hotshare','ATongJiHotBookController@hotshare')->name('hotshare');
    Route::any('/hotevaluate','ATongJiHotBookController@hotevaluate')->name('hotevaluate');
    Route::any('/hotcorrect','ATongJiHotBookController@hotcorrect')->name('hotcorrect');
});


//isbn整理
Route::group(['prefix'=>'IsbnArrange','namespace'=>'IsbnArrange'],function(){
    Route::any('/IsbnArrange_ajax/{type?}','IsbnArrangeController@ajax')->name('IsbnArrange_ajax');
    //Route::any('/get_ssort','IsbnArrangeController@get_ssort')->name('get_ssort');
    //Route::any('/sve_book','IsbnArrangeController@save_book')->name('IsbnArrange_savebook');
    //Route::any('/end_edit','IsbnArrangeController@end_edit')->name('IsbnArrange_end_edit');
    Route::get('/index/{sort_id?}/{area?}/{type?}/{status?}','IsbnArrangeController@isbn_list')->name('isbn_list');
    Route::get('/book_list/{isbn?}','IsbnArrangeController@book_list')->name('isbn_book_list');
});

