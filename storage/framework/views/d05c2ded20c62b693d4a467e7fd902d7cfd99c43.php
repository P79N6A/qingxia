<?php $__env->startSection('part_time_index','active'); ?>

<?php $__env->startPush('need_css'); ?>
   
    <link rel="stylesheet" href="<?php echo e(asset('css/jstree.style.min.css')); ?>"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

<section class="content-header">
    <h1>我的任务</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo e(route('backend')); ?>"><i class="fa fa-dashboard"></i> 主导航</a></li>
        <li class="active"></li>
    </ol>
</section>
<section class="content">
    <div class="box box-default color-palette-box">
        <div id="rightContent">
        <div class="book_box">
            <div id="box-header" class="box-header">
                <a class="btn btn-primary <?php if($data['status']==0): ?> active <?php endif; ?>" href="<?php echo e(route('part_time_booklist',0)); ?>">全部</a>
                <a class="btn btn-primary <?php if($data['status']==1): ?> active <?php endif; ?>" href="<?php echo e(route('part_time_booklist',1)); ?>">未完成</a>
                <a class="btn btn-primary <?php if($data['status']==2): ?> active <?php endif; ?>" href="<?php echo e(route('part_time_booklist',2)); ?>">已完成</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>bookid</th>
                            <th>onlyid</th>
                            <th>书名</th>
                            <th>兼职老师</th>
                            <th>分配时间</th>
                            <?php if($data['status']==2): ?>
                            <th>完成时间</th>
                            <th>操作</th>
                            <?php endif; ?>
                        </tr>
                        <?php $__currentLoopData = $data['list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($v['bookinfo']['id']); ?></td>
                                <td><?php echo e($v['onlyid']); ?></td>
                                <td> <a target="_blank" href="<?php echo e(route('part_time_workbook',$v['bookinfo']['id'])); ?>"><?php echo e($v['bookinfo']['bookname']); ?></a></td>
                                <td><?php echo e($v['part_time_name']); ?></td>
                                <td><?php echo e($v['created_at']); ?></td>
                                <?php if($data['status']==2): ?>
                                    <td><?php echo e($v['done_at']); ?></td>
                                    <td>
                                        <?php if(empty($v['confirm_at'])): ?>
                                            <button class="btn btn-primary lookup" data-id="<?php echo e($v['id']); ?>">未查看</button>
                                        <?php else: ?>
                                            <button class="btn btn-primary disabled">已查看</button>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

            </div>
        </div>
          <?php echo e($data['list']->links()); ?>

    </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_js'); ?>
<script>
    $(function(){
        $('lookup').click(function(){
            var btn=$(this);
            var id=$(this).attr('data-id');
            axios.post('<?php echo e(route('part_time_confirm')); ?>',{id}).then(response=>{
                if(response.data.status===1){
                    btn.addClass('disabled').html('已查看');
                }
            })
        })
    })
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>