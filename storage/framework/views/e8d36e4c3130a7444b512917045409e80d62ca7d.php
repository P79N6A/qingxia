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
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
           <div class="col-md-12">
                <div class="input-group col-md-6">
                    
                        
                            
                                
                            
                            
                                
                            
                                
                            
                            
                        
                    
                </div>
               <div class="input-group pull-left col-md-3" >
                   <select id="volumes_sel" class="form-control pull-left" style="width:50%">
                       <option value="0">卷册</option>
                       <option value="1">上册</option>
                       <option value="2">下册</option>
                       <option value="3">全一册</option>
                   </select>
            </div>
               <div class="input-group pull-left col-md-3">
                   <input class="form-control" id="search_word" placeholder="练习册名称" type="text" value="" />
                   <a class="input-group-addon btn btn-primary" id="search_book_btn">搜索</a>
               </div>

               <button type="button" class="btn btn-primary" style="margin-left: 20px;" id="AddMark">加入待购买</button>
               <button type="button" class="btn btn-danger" style="margin-left: 20px;" id="DelMark">作废</button>
        </div>

        <div class="box-body">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th><input type="checkbox" onclick="swapCheck()"/>选择</td></th>
                        <th>书名</th>
                        <th>购买状态</th>
                        <th>搜索次数</th>
                        <th>有无答案</th>
                        <th>收藏2018</th>
                        <th>收藏2017</th>
                        <th>收藏2016</th>
                        <th>收藏2015</th>
                    </tr>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-oid="<?php echo e($v->id); ?>">
                            <td><input type="checkbox" class="check"></td>
                            <td><?php echo e($v->sortname); ?></td>
                            <td>
                                 
                                    
                                
                                    
                                
                                    
                                
                                    
                                
                                    
                                
                            </td>
                            <td></td>
                            <td>
                                
                                    
                                
                                    
                                
                                   
                                
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

            </div>
            <div>
                <?php echo e($data->links()); ?>

            </div>

        </div>
    </div>
    </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_js'); ?>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    
        
            
            
                
                
                    
                    
                    
                    
                    
                        
                            
                        
                    
                    
                        
                            
                        
                    
                    
                
                
                    
                
                
                
                    
                    
                
                
                    
                    
                

            

            
                
                
                
                
            



            
            
        
    

<script>
    //checkbox 全选/取消全选
    var isCheckAll = false;
    function swapCheck() {
        if (isCheckAll) {
            $("input[type='checkbox']").each(function() {
                this.checked = false;
            });
            isCheckAll = false;
        } else {
            $("input[type='checkbox']").each(function() {
                this.checked = true;
            });
            isCheckAll = true;
        }
    }

    $(function(){
       $("#AddMark").click(function(){
           if(confirm('确定要加入待购买吗?')){
               let checks = $(".check:checked");
               let checkData = new Array();
               checks.each(function(){
                   checkData.push($(this).parents("tr").attr('data-oid'));
               });
               axios.post('<?php echo e(route('ajax_book_list')); ?>',{checkData}).then(response=>{
                   if(response.data.status===1){
                        window.location.reload();
                    }
                });

           }
       });

        $("#DelMark").click(function(){
            if(confirm('确定要作废吗?')){
                let checks = $(".check:checked");
                let checkData = new Array();
                checks.each(function(){
                    checkData.push($(this).parents("tr").attr('data-oid'));
                });
                axios.post('<?php echo e(route('ajax_book_list')); ?>',{checkData}).then(response=>{
                    if(response.data.status===1){
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>