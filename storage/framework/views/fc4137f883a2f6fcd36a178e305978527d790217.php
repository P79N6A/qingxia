<?php $__env->startSection('new_buy_analyze','active'); ?>

<?php $__env->startPush('need_css'); ?>
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(route('backend')); ?>"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">全书总览</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 收藏人数</h3>
                <div class="col-md-12">
                    <div class="input-group col-md-6">
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        

                    </div>
                    
                    
                    
                    

                    
                    
                </div>
                <form action="" method="post" id="choose_id">
                    <?php echo e(csrf_field()); ?>

                    <div class="box-body">
                        <div class="col-md-12">
                            <table class="table table-striped" style="text-align: center"  style="border:1px solid #ccc">
                                <tbody>
                                <tr>
                                    
                                    <th style="width:18%">目录</th>
                                    <th style="width:20%">系列</th>
                                    <th style="width:7%">科目</th>
                                    <th style="width:10%">年级</th>
                                    <th style="width:10%">卷册</th>
                                    <th style="width:5%">版本</th>
                                    <th style="width:6%">操作</th>
                                    <th style="width:10%">来源</th>
                                </tr>
                                <tr style="background-color:#ccc; ">
                                    <td></td>
                                    <td style="width:100px;">
                                        <div class="input-group pull-left " style="width:100%">
                                            <select id="sort_sel" style="width:100%" name="sort" class="sortall saixuan">
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group pull-left col-md-2" style="width:100%">
                                            <select id="subject_sel" style="width:100%" name="subject" class="saixuan">
                                                <option value="">筛选</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group pull-left col-md-2" style="width:100%">
                                            <select id="grade_sel" style="width:100%" name="grade" class="saixuan">
                                                <option value="">筛选</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group pull-left col-md-2" style="width:100%">
                                            <select id="volumes_sel" style="width:100%" name="volume" class="saixuan">
                                                <option value="">筛选</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group pull-left col-md-3" style="width:100%">
                                            <select id="version_sel" style="width:100%" name="version" class="saixuan">
                                                <option value="">筛选</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-oid="<?php echo e($v->id); ?>"  style="border:1px solid #ccc">
                                        
                                        <td style="border:1px solid #ccc" mulu="">

                                        </td>
                                        <td style="width:100px;" style="border:1px solid #ccc">
                                            <select indextype="preg_sort" class="sortall update_index select_sort"  style=" width:200px;">
                                                <option value=""><?php echo e($v->id); ?></option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_subject" class="update_index select_subject"  >
                                                <option value=""><?php echo e($v->cover); ?></option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_grade" class="update_index select_grade"  style="width:100px;">
                                                <option value=""><?php echo e($v->newname); ?></option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_volume" class="update_index select_volume"  style="width:100px;">
                                                <option value=""><?php echo e($v->onlyid); ?></option>
                                            </select>
                                        </td>
                                        <td style="border:1px solid #ccc">
                                            <select indextype="preg_version" class="update_index select_version"  >
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td style="width:40px;" style="border:1px solid #ccc">
                                            <button type="button" class="btn btn-success move" >匹配</button>
                                        </td>
                                        <td class="input_box" style="width:50px;" style="border:1px solid #ccc" >
                                        </td>
                                    </tr>
                                    <?php $__env->startComponent('components.modal',['id'=>'show_img','title'=>'查看图片']); ?>
                                    <?php $__env->slot('body',''); ?>
                                    <?php $__env->slot('footer',''); ?>
                                    <?php echo $__env->renderComponent(); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_js'); ?>
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>


<script type="text/javascript">

</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>