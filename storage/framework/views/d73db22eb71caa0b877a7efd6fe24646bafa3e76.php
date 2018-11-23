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

    
    <table style="width:100%;">
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="border:30px solid white;">
                <div class="box box-info" style="width:100%;">
                    <div class="info-box">
                        <!-- Apply any bg-* class to to the icon to color it -->
                        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo e($value['nick']); ?></span>
                            <span class="info-box-number"><?php echo e($value['item_loc']); ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->


            </td>
            <td style="border:30px solid white;">
                <div class="box box-info" style="width:100%;">
                    <div class="info-box">
                        <!-- Apply any bg-* class to to the icon to color it -->
                        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo e($value['sortname']); ?></span>
                            <span class="info-box-number"><?php echo e($value['shopLink']); ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->

            </td>
            <td style="border:30px solid white;">
                <div class="box box-info" style="width:100%;">
                    <div class="info-box">
                        <!-- Apply any bg-* class to to the icon to color it -->
                        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo e($value['yeaar']); ?></span>
                            <span class="info-box-number"><?php echo e($value['detail_url']); ?></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->

        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>


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