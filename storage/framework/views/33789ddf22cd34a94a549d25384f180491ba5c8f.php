<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>后台管理</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <link rel="stylesheet" href="/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/ionicons.min.css')); ?>">
    <style>
        .ui-helper-hidden-accessible { display:none; }
    </style>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>
<?php echo $__env->yieldPushContent('need_css'); ?>
    <link rel="stylesheet" href="/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/adminlte/dist/css/skins/_all-skins.min.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-blue sidebar-mini <?php if(isset($_COOKIE['tool_bar']) && $_COOKIE['tool_bar']==1): ?> sidebar-collapse <?php endif; ?> ">
<div class="wrapper">
    <header class="main-header">
        <a href="<?php echo e(route('backend')); ?>" class="logo">
            <span class="logo-mini"><b>后台</b></span>
            <span class="logo-lg"><b>后台管理</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <form action="<?php echo e(route('logout')); ?>" method="post" id="logout">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <a href="javascript:onclick=$('#logout').submit()" style="padding: 15px 15px;color: #fff;" class="pull-right">退出登录</a>
            </form>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php echo e(Auth::user()->name); ?></p>
                    <p>uid: <?php echo e(Auth::id()); ?></p>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">1010jiajiao相关</li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?>
                <li class="treeview hide">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i> <span>练习册购买</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu menu-open">
                        <li class="<?php echo $__env->yieldContent('book_buy_index'); ?>"><a href="<?php echo e(route('book_buy_index')); ?>">练习册搜索及筛选</a></li>
                        <li class="<?php echo $__env->yieldContent('book_buy_wait'); ?>"><a href="<?php echo e(route('book_buy_wait')); ?>">等待购买列表</a></li>
                        <li class="<?php echo $__env->yieldContent('book_buy_done'); ?>"><a href="<?php echo e(route('book_buy_done')); ?>">购买完成</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="treeview hide">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?>
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>练习册整理</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <?php endif; ?>
                <ul class="treeview-menu menu-open">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?><li class="<?php echo $__env->yieldContent('book_new_check'); ?>"><a href="<?php echo e(route('book_new_index')); ?>"><i class="fa fa-check-square-o"></i> <span>唯一表整理</span></a></li><?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?><li class="<?php echo $__env->yieldContent('book_new_sort'); ?>"><a href="<?php echo e(route('book_new_sort')); ?>"><i class="fa fa-check-square-o"></i> <span>系列整理</span></a></li><?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?><li class="<?php echo $__env->yieldContent('book_new_only'); ?>"><a href="<?php echo e(route('book_new_only')); ?>"><i class="fa fa-check-square-o"></i> <span>唯一化练习册</span></a></li><?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?><li class="<?php echo $__env->yieldContent('book_new_chapter'); ?>"><a href="<?php echo e(route('book_new_chapter')); ?>"><i class="fa fa-check-square-o"></i> <span>唯一表章节整理</span></a></li><?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?><li class="<?php echo $__env->yieldContent('workbook_now'); ?>"><a href="<?php echo e(route('workbook_only')); ?>"><i class="fa fa-file-text-o"></i><span>练习册唯一化整理</span></a></li><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sort_name')): ?><li class="<?php echo $__env->yieldContent('sort_name'); ?>"><a href="<?php echo e(route('sort_name')); ?>"><i class="fa fa-files-o"></i><span>sort_name整理</span></a></li><?php endif; ?>
                <li class="<?php echo $__env->yieldContent('lxc_now'); ?> hide"><a href="<?php echo e(route('lxc')); ?>"><i class="fa fa-files-o"></i><span>整理vzy练习册</span></a></li>
                <li class="<?php echo $__env->yieldContent('lxc_now_v2'); ?> hide"><a href="<?php echo e(route('lxc_v2')); ?>"><i class="fa fa-files-o"></i><span>整理vzy练习册(重新整理)</span></a></li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('book_now')): ?><li class="<?php echo $__env->yieldContent('book_now'); ?>"><a href="<?php echo e(route('sub_sort')); ?>"><i class="fa fa-th"></i> <span>vzy子系列整理情况</span></a></li><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('book_now_v2')): ?><li class="<?php echo $__env->yieldContent('book_now_v2'); ?>"><a href="<?php echo e(route('book_arrange')); ?>"><i class="fa fa-book"></i> <span>课本整理</span></a></li><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_cover')): ?><li class="<?php echo $__env->yieldContent('workbook_cover'); ?>"><a href="<?php echo e(route('workbook_cover')); ?>"><i class="fa fa-file-o"></i> <span>练习册封面整理</span></a></li><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('video_manage')): ?><li class="<?php echo $__env->yieldContent('video_manage'); ?>"><a href="<?php echo e(route('video_manage')); ?>"><i class="fa fa-file-video-o"></i> <span>视频管理</span></a></li><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('isbn_manage'); ?>"><a href="<?php echo e(route('isbn_manage')); ?>"><i class="fa fa-th-large"></i> <span>isbn整理</span></a></li><?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('book_recycle')): ?><li class="<?php echo $__env->yieldContent('book_recycle'); ?>"><a href="<?php echo e(route('book_recycle')); ?>"><i class="fa fa-trash-o"></i> <span>回收站</span></a></li><?php endif; ?>
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('book_check')): ?><li class="<?php echo $__env->yieldContent('audit_index'); ?>"><a href="<?php echo e(route('audit_index')); ?>"><i class="fa fa-check-square-o"></i> <span>答案审核</span></a></li><?php endif; ?>

                </ul>
                </li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('workbook_only')): ?>
                <li class="treeview ">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i> <span>练习册审核</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu menu-open">
                        <li class="<?php echo $__env->yieldContent('audit_index'); ?>"><a href="<?php echo e(route('new_audit_index',[0,date("Y-m-d", strtotime("-1 day")),date("Y-m-d")])); ?>"><i class="fa fa-check-square-o"></i> <span>练习册审核</span></a></li>
                        <li class="<?php echo $__env->yieldContent('new_buy_record'); ?>"><a href="<?php echo e(route('audit_booklist')); ?>"><i class="fa fa-th-large"></i> <span>用户上传练习册审核</span></a></li>
                        
                        <li class="<?php echo $__env->yieldContent('new_buy_record'); ?>"><a href="<?php echo e(route('audit_content_booklist')); ?>"><i class="fa fa-th-large"></i> <span>用户上传练习册内容审核</span></a></li>
                        <li class="<?php echo $__env->yieldContent('new_buy_record'); ?>"><a href="<?php echo e(route('answer_user_award')); ?>"><i class="fa fa-th-large"></i> <span>用户Q币奖励发放</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('manage_new_other_temp'); ?>"><a href="<?php echo e(route('manage_new_other_temp')); ?>"><i class="fa fa-th-large"></i> <span>isbn扫描测试</span></a></li><?php endif; ?>



                <div class="hide">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question_manage')): ?>

                <li class="<?php echo $__env->yieldContent('question_manage_index'); ?>">
                <a href="<?php echo e(route('que_manage_index')); ?>"><i class="fa fa-th"></i> <span>解题管理</span></a>
                </li>
                    <li class="<?php echo $__env->yieldContent('audit_answer'); ?>"><a href="<?php echo e(route('audit_answer')); ?>"><i class="fa fa-check-square-o"></i> <span>答案审核</span></a></li>
                    <li class="<?php echo $__env->yieldContent('user_feedback'); ?>">
                        <a href="<?php echo e(route('user_feedback_list')); ?>"><i class="fa fa-question-circle"></i> <span>反馈管理</span></a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('manage_new_oss'); ?>"><a href="<?php echo e(route('manage_new_oss')); ?>"><i class="fa fa-th-large"></i> <span>cip整理</span></a></li><?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('manage_new_local_answer'); ?>"><a href="<?php echo e(route('manage_new_local')); ?>"><i class="fa fa-th-large"></i> <span>本地答案整理</span></a></li><?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('manage_new_other_temp'); ?>"><a href="<?php echo e(route('manage_new_other_temp')); ?>"><i class="fa fa-th-large"></i> <span>isbn扫描测试</span></a></li><?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('manage_new_local_test_answer'); ?>"><a href="<?php echo e(route('manage_new_local_test')); ?>"><i class="fa fa-th-large"></i> <span>本地答案整理_test</span></a></li><?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('isbn_temp_index'); ?>"><a href="<?php echo e(route('isbn_temp_index')); ?>"><i class="fa fa-th-large"></i> <span>sort整理</span></a></li><?php endif; ?>


                <li class="hide <?php echo $__env->yieldContent('homework_manage_index'); ?>">
                    <a href="<?php echo e(route('homework_manage_index')); ?>"><i class="fa fa-th"></i> <span>作业管理</span></a>
                </li>
                <?php endif; ?>
                </div>

                <li class="treeview hide" id="">
                    <a href="javascript:void(0);"><i class="fa fa-line-chart"></i><span>统计</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu menu-open">
                        <li class="favorite_chart_index"><a href="<?php echo e(route("favorite_chart_index")); ?>">收藏统计</a></li>
                        <li class="isbn_chart_index"><a href="<?php echo e(route("isbn_tongji")); ?>">ISBN统计</a></li>
                        <li class="isbn_nocontent_search"><a href="<?php echo e(route("search_tongji")); ?>">ISBN搜索无结果统计</a></li>
                        <li class="reg_chart_index"><a href="<?php echo e(route("reg_chart")); ?>">注册统计</a></li>
                        <li class="taobao_index"><a href="<?php echo e(route("taobao_book2")); ?>">找书</a></li>
                    </ul>
                </li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isbn_manage')): ?><li class="<?php echo $__env->yieldContent('new_buy_again'); ?>"><a href="<?php echo e(route('new_buy_index')); ?>"><i class="fa fa-th-large"></i> <span>系列买书</span></a></li>
                <li class="<?php echo $__env->yieldContent('new_buy_record'); ?>"><a href="<?php echo e(route('new_buy_record',[162])); ?>"><i class="fa fa-th-large"></i> <span>待买列表</span></a></li>
                <li class="<?php echo $__env->yieldContent('new_buy_analyze'); ?>"><a href="<?php echo e(route('new_buy_analyze')); ?>"><i class="fa fa-th-large"></i> <span>待解析列表</span></a></li>
                <li class="hide <?php echo $__env->yieldContent('all_repeat_book'); ?>"><a href="<?php echo e(route('new_buy_repeat_list')); ?>"><i class="fa fa-shopping-cart"></i> <span>重复课本整理</span></a></li><?php endif; ?>
                <li class="<?php echo $__env->yieldContent('new_buy_record'); ?>"><a href="<?php echo e(route('book_list')); ?>"><i class="fa fa-th-large"></i> <span>全书总览</span></a></li>
                <li class="<?php echo $__env->yieldContent('new_buy_record'); ?>"><a href="<?php echo e(route('taobao_search')); ?>"><i class="fa fa-th-large"></i> <span>淘宝搜索</span></a></li>
                <li class="<?php echo $__env->yieldContent('all_repeat_book'); ?>"><a href="<?php echo e(route('check_cover')); ?>"><i class="fa fa-th-large"></i> <span>审核封面</span></a></li>
                <li class="<?php echo $__env->yieldContent('upload_local_imgs'); ?>"><a href="<?php echo e(route('upload_all_imgs')); ?>"><i class="fa fa-th-large"></i> <span>图片上传</span></a></li>
                <li class="<?php echo $__env->yieldContent('all_repeat_book'); ?>"><a href="<?php echo e(route('isbn_list')); ?>"><i class="fa fa-th-large"></i> <span>Isbn求助</span></a></li>
                <div class="hide">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lww')): ?>
                    <li class="header">05网相关</li>
                    <li class="<?php echo $__env->yieldContent('lww_index'); ?>"><a href="<?php echo e(route('lww_index')); ?>"><i class="fa fa-file-text-o"></i> <span>05网练习册管理</span></a></li>
                <?php endif; ?>

                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lww')): ?>
                    <li class="header">05网相关</li>
                    <li class="<?php echo $__env->yieldContent('lww_index'); ?>"><a href="<?php echo e(route('one_lww_sort_index')); ?>"><i class="fa fa-file-text-o"></i> <span>练习册</span></a></li>
                    <li class="<?php echo $__env->yieldContent('part_time_index'); ?>"><a href="<?php echo e(route('part_time_booklist')); ?>"><i class="fa fa-file-text-o"></i> <span>兼职老师个人列表</span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lww')): ?>
                <li class="header">目录管理</li>
                <li class="<?php echo $__env->yieldContent('lww_index'); ?>"><a href="<?php echo e(route('img_upload_logs')); ?>"><i class="fa fa-file-text-o"></i> <span>目录列表</span></a></li>
                <li class="<?php echo $__env->yieldContent('lww_index'); ?>"><a href="<?php echo e(route('hotlist')); ?>"><i class="fa fa-file-text-o"></i> <span>书本热度管理</span></a></li>
                
                
                
                
                
                
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage')): ?>
                <li class="header">系统相关</li>
                <li class="<?php echo $__env->yieldContent('system_manage'); ?>"><a href="<?php echo e(route('system_manage')); ?>"><i class="fa fa-user"></i> <span>权限管理</span></a></li>
                <li class="hide <?php echo $__env->yieldContent('baidu_manage'); ?>"><a href="<?php echo e(route('baidu_manage')); ?>"><i class="fa fa-user"></i> <span>百度统计</span></a></li>
                    <li class="hide <?php echo $__env->yieldContent('new_book_buy'); ?>"><a href="<?php echo e(route('new_book_buy')); ?>"><i class="fa fa-shopping-cart"></i> <span>练习册收藏统计</span></a></li>
                    <li class="<?php echo $__env->yieldContent('new_book_buy_status'); ?> hide"><a href="<?php echo e(route('new_book_buy_status')); ?>"><i class="fa fa-shopping-cart"></i> <span>购买情况统计</span></a></li>
                    <li class="hide <?php echo $__env->yieldContent('all_new_index'); ?>"><a href="<?php echo e(route('new_index_all')); ?>"><i class="fa fa-shopping-cart"></i> <span>单本统计</span></a></li>
                <?php endif; ?>
            </ul>
        </section>
    </aside>
    <div id="app-vue" class="content-wrapper">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <footer class="main-footer"></footer>
</div>
<script src="<?php echo e(asset('js/app.js')); ?>"></script>


    

<script src="/js/js_cookie.js"></script>
<?php echo $__env->yieldPushContent('need_js'); ?>
<script src="/adminlte/plugins/fastclick/fastclick.js"></script>
<script src="/adminlte/dist/js/app.min.js"></script>
<script>
    $(function () {
        $('.sidebar-toggle').click(function () {
            console.log(Cookies.get('tool_bar'));
            if(Cookies.get('tool_bar')==1){
                Cookies.set('tool_bar',null);
            }else{
                Cookies.set('tool_bar','1');
            }
        });
    });
</script>
</body>
</html>