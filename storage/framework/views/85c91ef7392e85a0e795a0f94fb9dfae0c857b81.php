<div class="modal fade" id="<?php echo e($id); ?>">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header"><?php echo e($title); ?></div>
            <div class="modal-body"><?php echo e($body); ?></div>
            <div class="modal-footer"><?php echo e($footer); ?></div>
        </div>
    </div>
</div>

<?php $__env->startPush('need_js'); ?>
    <script>
        $(function () {
            //展示图片
            $(document).on('click','.answer_pic',function () {
                $(this).attr('data-status','now_modal_content');
                let img = $(this).attr('src');
                let data_id = $(this).attr('data-id');
                $('#show_img').modal('show');
               // $('#show_img .modal-body').html(`<a>${data_id}</a>`);
                $('#show_img .modal-body').html(`<img data-id="${data_id}" width="100%" src="${img}" />`);
            });
            $('#<?php echo e($id); ?>').on('hide.bs.modal', function (e) {
                $(`img[data-status='now_modal_content']`).removeAttr('data-status');
            })
        })
    </script>
<?php $__env->stopPush(); ?>