<?php $__env->startSection('baidu_manage','active'); ?>

<?php $__env->startPush('need_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('adminlte')); ?>/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>控制面板</h1>
        
            
            
        
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                详细信息统计
            </div>
            <div class="box-body">
                
                <div class="box box-success">
                    <form action="<?php echo e(route('stophere')); ?>" method="post" id="form_submit">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="isbn" value="<?php echo e($data['isbn']); ?>">
                        <input type="hidden" name="start" value="<?php echo e($data['start']); ?>" id="starttime">
                        <input type="hidden" name="end" value="<?php echo e($data['end']); ?>" id="endtime">
                       <span>
                        <label>时间区间:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="reservation">
                            <span id="get_search" class="input-group-addon btn btn-primary">查询</span>
                        </div>
                    </span>
                    </form>

                    <a class="btn btn-primary hide" id="get_now">确认</a>
                    <hr>
                    <a href="http://www.1010jiajiao.com/daan/bookid_.html" target="_blank">http://www.1010jiajiao.com/daan/bookid_.html</a>

                    
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="datelineChart" style="height:230px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

            <hr>
            
            
                
                    
                        
                            
                                
                                
                                
                            
                        
                        
                            
                        
                        
                    
                
            

            
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="chart">
                            <canvas id="sectionChart" style="height:500px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>



            
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="chart">
                            <canvas id="areaChart" style="height:500px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>

            
            <div class="row">
            <div class="col-md-12">
            <div class="box box-info">
            <div class="box-header with-border">
            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
            </div>
            <div class="chart">
            <canvas id="evaluateChart" style="height:500px"></canvas>
            </div>
            <!-- /.box-body -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script>
    $(function () {
        //时间区间input绑定change事件
        $('#reservation').change(function(){
            var str=$(this).val();
            var arr=str.split('-');
            $('#starttime').val(arr[0].replace(' ',''));
            $('#endtime').val(arr[1].replace(' ',''));
//            console.log(arr);
        });

        //查询绑定点击事件提交表单
        $('#get_search').click(function(){
           $('#form_submit').submit();
        });

        //时间区间显示
        $('#reservation').daterangepicker({
            language:'zh-CN',
            startDate:"<?php echo $data['start']; ?>",
            endDate:"<?php echo $data['end']; ?>"
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

        //时间人数统计表
        var ctx = document.getElementById("evaluateChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo collect($data['num_date']['arrdate']); ?>,
                datasets: [
                    {label: '好评',
                    backgroundColor:'rgba(255,99,132,1)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1,
                    data: <?php echo collect($data['num_collect']['good_evaluate']); ?>

                    },
                    {label: '差评',
                        backgroundColor:'rgba(1,99,132,255)',
                        borderColor: 'rgba(1,99,132,255)',
                        borderWidth: 1,
                        data: <?php echo collect($data['num_collect']['bad_evaluate']); ?>

                    },
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },

            }
        });

        //时间人数折线图
        var ctx1 = document.getElementById("datelineChart").getContext('2d');
        var myChart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?php echo collect($data['num_date']['arrdate']); ?>,
                datasets: [
                    {
                        label: '停留量',
                        backgroundColor: 'rgba(255,0,0,0.5)',
                        borderColor: 'rgba(255,0,0,0.5)',
                        fill: false,
//                        hidden: true,
                        data: <?php echo collect($data['num_date']['arrnum']); ?>

                    },
                    {
                        label: '收藏量',
                        backgroundColor: 'rgba(255,150,50,100.50)',
                        borderColor: 'rgba(255,150,50,100.50)',
                        fill: false,
                        hidden: true,
                        data: <?php echo collect($data['num_collect']['collect_count']); ?>

                    },
                    {
                        label: '分享量',
                        backgroundColor: 'rgba(100,0,0,100.5)',
                        borderColor: 'rgba(100,0,0,100.5)',
                        fill: false,
                        hidden: true,
                        data: <?php echo collect($data['num_collect']['sharenum']); ?>

                    },
                    {
                        label: '搜索量',
                        backgroundColor: 'rgba(50,0,100,0.255)',
                        borderColor: 'rgba(50,0,100,0.255)',
                        fill: false,
                        hidden: true,
                        data: <?php echo collect($data['num_collect']['searchnum']); ?>

                    },

                ]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: '数据变化图'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },

            }
        });

        //章节人数统计图
        var ctx2 = document.getElementById("sectionChart").getContext('2d');
        var myChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo collect($data['num_section']['arrsection']); ?>,
                datasets: [{
                    label: '章节人数统计',
                    backgroundColor:'rgba(255,99,132,1)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1,
                    data: <?php echo collect($data['num_section']['arrnum']); ?>

                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },

            }
        });

        //章节人数折线图
        
        
            
            
                
                
                    
                        
                        
                        
                        

                        
                    

                
            
            
                
                
                    
                    
                
                
                    
                    
                
                
                    
                    
                

            
        

        //地区人数统计图
        var ctx4 = document.getElementById("areaChart").getContext('2d');
        var myChart = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: <?php echo collect($data['num_area']['arrarea']); ?>,
//                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '地区人数统计',
                    backgroundColor:'rgba(255,99,132,1)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1,
                    data: <?php echo collect($data['num_area']['arrnum']); ?>

                    //                    data:[12, 19, 3, 5, 2, 3],
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },

            }
        });

        //地区人数折线图
        
        
            
            
                
                
                    
                        
                        
                        
                        

                        
                    

                
            
            
                
                
                    
                    
                
                
                    
                    
                
                
                    
                    
                

            
        

    });

</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>