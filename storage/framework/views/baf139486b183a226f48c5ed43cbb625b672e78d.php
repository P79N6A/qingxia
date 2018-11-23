<?php $__env->startSection('lww_index'); ?>
    active
<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_css'); ?>
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
    <style>
        .search_book_cover {
            height: 150px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('components.modal',['id'=>'show_onlyid']); ?>
        <?php $__env->slot('title','查看该onlyid信息'); ?>
        <?php $__env->slot('body',''); ?>
        <?php $__env->slot('footer',''); ?>
    <?php echo $__env->renderComponent(); ?>

    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(route('backend')); ?>"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">本地图片上传</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default color-palette-box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tag"></i> 本地图片上传</h3></div>
            <div class="box-body">
                <?php $__empty_1 = true; $__currentLoopData = $data['all_directories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4">
                        <div class="box box-warning box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo e($dict->path_name); ?></h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="box-body" style="height: 400px; overflow: scroll">
                                <?php 
                                    $all_sub_dir = $dict->hasChildren;
                                 ?>
                                <?php $__empty_2 = true; $__currentLoopData = $all_sub_dir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <div class="box box-default box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo e($dir->path_name); ?></h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <?php 
                                                $all_sub_dir_2 = $dir->hasChildren;
                                             ?>
                                            <?php $__empty_3 = true; $__currentLoopData = $all_sub_dir_2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dir_2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                                <a class="btn <?php if($dir_2->status==0 && $dir_2->onlyid!='' && strpos($dir_2->onlyid,'|')===false): ?>btn-danger <?php else: ?> btn-primary <?php endif; ?>"><?php echo e($dir_2->path_name); ?></a>
                                                <div class="input-group" data-dir="<?php echo e($dir_2->path_name); ?>">
                                                    <label class="input-group-addon show_onlyid_info" data-target="#show_onlyid" data-toggle="modal">查看onlyid</label>
                                                    <input class="form-control" value="<?php echo e($dir_2->onlyid); ?>"/>
                                                    <?php if($dir_2->status===0): ?>
                                                    <a class="input-group-addon btn btn-danger upload_img_now">上传图片</a>
                                                    <?php endif; ?>
                                                </div>
                                                <br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>
                    </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div><?php echo e($data['all_directories']->links()); ?></div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_js'); ?>
    <script>
        $('.upload_img_now').click(function () {
            let onlyid = $(this).prev().val();
            let now_path = $(this).parent().attr('data-dir');
            if(!confirm('确认上传')){
                return false;
            }
            axios.post('<?php echo e(route('upload_all_imgs',['upload_img'])); ?>',{onlyid,now_path}).then(response=>{
                if(response.data.status===1){
                    $(this).remove();
                }
            })
        })

        $('.show_onlyid_info').click(function () {
            let onlyid = $(this).next().val();
            axios.post('<?php echo e(route('upload_all_imgs',['get_onlyid_info'])); ?>',{onlyid}).then(response=>{
                if(response.data.status===1){
                    $('#show_onlyid .modal-body').html(`
                    <div>
                        <img src="${response.data.data[0].cover}" alt="">
                    </div>
                    `)
                }
            })
        })
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>