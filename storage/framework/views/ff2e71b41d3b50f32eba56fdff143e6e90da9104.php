<?php $__env->startSection('baidu_manage','active'); ?>

<?php $__env->startPush('need_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('adminlte')); ?>/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<style>
    tr:nth-child(odd)
    {
        background:lightblue;
    }

    tr:nth-child(even)
    {
        background:lightblue;
    }

    #title
    {
        background:#8c8c8c;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>控制面板</h1>
        
            
            
        
    </section>
    <section class="content">
        <div class="box box-primary">
            
                
            
            
                
                    
                    
                    
                    
                    
                        
                            
                            
                        
                    
                
                
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body">
                            <div>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select data-name="grade" id="grade_id"
                                        class="grade_id form-control select2 pull-left" tabindex="-1"
                                        aria-hidden="true" name="grade">
                                    <option value="-5">全部年级</option>
                                </select>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select name="subject" data-name="subject" id="subject_id" class="subject_id form-control select2"
                                        tabindex="-1" aria-hidden="true">
                                    <option value="-5">全部科目</option>
                                </select>
                            </div>
                            <div class="input-group pull-left" style="width:20%">
                                <select name="volumes" data-name="volumes" id="volumes_id" class="volumes_id form-control select2">
                                    <option value="-5">全部卷册</option>
                                </select>

                            </div>
                            <div class="input-group pull-left" style="width: 20%">
                                <select name="version" data-name="version" id="version_id" class="version_id form-control select2"
                                        tabindex="-1" aria-hidden="true">
                                    <option value="-5">全部版本</option>
                                </select>
                            </div>
                            <div class="input-group" style="width: 20%">
                                <select name="the_sort" data-name="sort" id="sort_id" class="form-control sort_name click_to">
                                    <option value="-5">全部系列</option>
                                </select>
                            </div>
                            <span>
                        <label>时间区间:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="time" type="text" class="form-control pull-right" id="reservation">
                            <span id="get_search" class="input-group-addon btn btn-primary">查询</span>
                        </div>
                </span>
                            <a class="btn btn-primary hide" id="get_now">确认</a>
                            <hr>
                            <table  class="table table-bordered">
                                <tr id="title">
                                    <th>书类名称</th>
                                    <th style="width:90px;">停留量<a href="">
                                            <a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_stay/desc"><em style="color:white">▲</em></a><a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_stay/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">收藏量<a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_collect_count/desc"><em style="color:white">▲</em></a><a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_collect_count/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">分享量<a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_sharenum/desc"><em style="color:white">▲</em></a><a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_sharenum/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">搜索量<a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_searchnum/desc"><em style="color:white">▲</em></a><a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_searchnum/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">评价<a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_good_evaluate/desc"><em style="color:white">▲</em></a><a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_good_evaluate/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th style="width:90px">纠错<a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_correct/desc"><em style="color:white">▲</em></a><a href="<?php echo e(route('hotlist')); ?>/<?php echo $data['de_grade']; ?>/<?php echo $data['de_subject']; ?>/<?php echo $data['de_volumes']; ?>/<?php echo $data['de_version']; ?>/<?php echo $data['de_the_sort']; ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>/sum_correct/asc"><em style="color:white">▼</em>
                                        </a></th>
                                    <th>书本编码</th>
                                </tr>
                                <?php $barcodeGenerator = app('Picqer\Barcode\BarcodeGeneratorPNG'); ?>
                                <?php $__currentLoopData = $data['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                        <td><strong ><p><?php echo e($v->bookname); ?></p>
                                                <p><a target="_blank" href="http://www.1010jiajiao.com/daan/bookid_<?php echo e($v->id); ?>.html">http://www.1010jiajiao.com/daan/bookid_<?php echo e($v->id); ?>.html</a></p>
                                            <?php 
                                                try{
                                                echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$v->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                                }catch (Exception $e){
                                                echo '无法生成此isbn的条形码';
                                                }
                                             ?>
                                        </td>
                                        <td><p><a target="_blank" href="<?php echo e(route('stophere')); ?>/<?php echo e($v->isbn); ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>" class="btn btn-success btn-primary btn-xs" ><?php echo e($v->sum_stay); ?><em class="badge bg-blue">图表</em> </a></p></td>
                                    <td><p><a href="<?php echo e(route('stophere')); ?>/<?php echo e($v->isbn); ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>" class="btn btn-success btn-primary btn-xs" ><?php echo e($v->sum_collect_count); ?><em class="badge bg-blue">图表</em> </a></p></td>
                                    <td><p><a href="<?php echo e(route('stophere')); ?>/<?php echo e($v->isbn); ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>" class="btn btn-success btn-primary btn-xs" ><?php echo e($v->sum_sharenum); ?><em class="badge bg-blue">图表</em> </a></p></td>
                                    <td><p><a href="<?php echo e(route('stophere')); ?>/<?php echo e($v->isbn); ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>" class="btn btn-success btn-primary btn-xs" ><?php echo e($v->sum_searchnum); ?><em class="badge bg-blue">图表</em> </a></p></td>
                                    <td>
                                        <p><a href="<?php echo e(route('stophere')); ?>/<?php echo e($v->isbn); ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>" class="btn btn-success btn-primary btn-xs">好评<em class="badge bg-red"><?php echo e($v->sum_good_evaluate); ?></em></a></p>
                                        <p><a href="<?php echo e(route('stophere')); ?>/<?php echo e($v->isbn); ?>/<?php echo e($data['start']); ?>/<?php echo e($data['end']); ?>" class="btn btn-danger btn-primary btn-xs">差评<em class="badge bg-#ccc"><?php echo e($v->sum_bad_evaluate); ?></em></a></p>
                                    </td>
                                    <td>
                                        <a class="label label-info">反馈统计<em class="badge bg-red"><?php echo e($v->sum_correct); ?></em></a>
                                    </td>
                                    <td><?php echo e($v->onlyid); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                    </div>
                    <div>
                        <?php echo e($data['data']->links()); ?>

                    </div>
                </div>
            </div>


        </div>

    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo e(asset('adminlte')); ?>/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo e(asset('adminlte')); ?>/plugins\datepicker\locales\bootstrap-datepicker.zh-CN.js"></script>
<script src="/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
<script>
    $(function () {
        $('#reservation').daterangepicker({
            language:'zh-CN',
            startDate:'<?php echo e($data['start']); ?>',
            endDate:'<?php echo e($data['end']); ?>',
        });
        $('#daterange-btn').daterangepicker(
                {
                    language: 'zh-CN',
                    autoclose: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
        );
        //select2初始化
        $('select[data-name="grade"]').select2({data: $.parseJSON('<?php echo $data['attr']['grade']; ?> '),});
        $('select[data-name="subject"]').select2({data: $.parseJSON('<?php echo $data['attr']['subject']; ?> '),});
        $('select[data-name="volumes"]').select2({data: $.parseJSON('<?php echo $data['attr']['volumes']; ?> '),});
        $('select[data-name="version"]').select2({data: $.parseJSON('<?php echo $data['attr']['version']; ?> '),});
        $('select[data-name="sort"]').select2({data: $.parseJSON('<?php echo $data['attr']['sort']; ?> '),});

        //select默认值
        $("#grade_id").val(['<?php echo $data['de_grade']; ?>']).trigger('change');
        $("#volumes_id").val(['<?php echo $data['de_volumes']; ?>']).trigger('change');
        $("#version_id").val(['<?php echo $data['de_version']; ?>']).trigger('change');
        $("#subject_id").val(['<?php echo $data['de_subject']; ?>']).trigger('change');
        $("#sort_id").val(['<?php echo $data['de_the_sort']; ?>']).trigger('change');


        $('#get_search').click(function(){
            var grade_id=$('#grade_id').val();
            var subject_id=$('#subject_id').val();
            var volumes_id=$('#volumes_id').val();
            var version_id=$('#version_id').val();
            var sort_id=$('#sort_id').val();
            var time=$('#reservation').val();
            var arr=time.split('-');
            var start=arr[0].replace(' ','');
            start=start.replace('/','_').replace('/','_');
            var end=arr[1].replace(' ','');
            end=end.replace('/','_').replace('/','_');
            console.log(start);
            console.log(end);

            window.location.href ='<?php echo e(route('hotlist')); ?>'+'/'+grade_id+'/'+subject_id+'/'+volumes_id+'/'+version_id+'/'+sort_id+'/'+start+'/'+end;
        });

        
            
            
            
            
            
            
            
            
            
            
        


        
            
            
            
            
            
            
            
            

            
        

        
        
            
                
                
            
            
            
            
            
                
                
            
        
                
            
        


    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>