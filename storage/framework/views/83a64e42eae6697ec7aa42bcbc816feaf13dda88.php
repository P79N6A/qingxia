<?php $__env->startSection('manage_new_other_temp','active'); ?>

<?php $__env->startPush('need_css'); ?>
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>控制面板</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(route('backend')); ?>"><i class="fa fa-dashboard"></i> 主导航</a></li>
            <li class="active">isbn扫描测试</li>
        </ol>
    </section>



    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> 本地答案整理</h3>
        </div>


            <div class="input-group col-md-6">
                <a class="input-group-addon">id</a>
                <input id="for_isbn_id" type="number" style="font-size: 24px" class="form-control input-lg" placeholder="练习册id"/>
                <a class="input-group-addon">isbn:</a>
                <input maxlength="17" class="now_val for_isbn_input form-control input-lg" style="font-size: 24px"  />
                <a class="input-group-addon save_record" data-type="now_isbn">保存</a>
            </div>
        <div class="input-group col-md-6">
            <select id="sort_id" class="form-control sort_name click_to">
                <option value="-999">选择系列</option>
                <?php if($data['sort']!=-1): ?>
                    <option selected value="<?php echo e($data['sort']); ?>"><?php echo e(cache('all_sort_now')->where('id',$data['sort'])->first()->name); ?></option>
                <?php endif; ?>
            </select>
        </div>


        <?php $__env->startComponent('components.modal',['id'=>'show_img','title'=>'查看图片']); ?>
            <?php $__env->slot('body',''); ?>
            <?php $__env->slot('footer',''); ?>
        <?php echo $__env->renderComponent(); ?>

        <div class="input-group hide">
            <?php $__currentLoopData = [19=>'印娜',20=>'张玲莉',21=>'朱春萍',22=>'李靖雯',23=>'石璐扬',27=>'徐娟',28=>'欧秀芝']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uid=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="btn <?php if($uid==Auth::id()): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>" href="<?php echo e(route('manage_new_other_temp',$uid)); ?>"><?php echo e($name); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="box-body">
            <div class="row">
                <?php $barcodeGenerator = app('Picqer\Barcode\BarcodeGeneratorPNG'); ?>
                <?php $__empty_1 = true; $__currentLoopData = $data['all_isbn']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $isbn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-MD-6 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="row">
                                <div class="col-md-5" style="padding: 50px">
                                    <h3><?php echo e($isbn->isbn); ?></h3>
                                    <?php 
                                        try{
                                            echo '<img style="width: 200px;height: 80px;" src="data:image/png;base64,' . base64_encode($barcodeGenerator->getBarcode(str_replace(['-','|'],'',$isbn->isbn), $barcodeGenerator::TYPE_EAN_13)) . '">';
                                        }catch (Exception $e){
                                            echo '无法生成此isbn的条形码';
                                        }
                                     ?>

                                    <a class="btn <?php if($isbn->found): ?>btn-danger <?php else: ?> btn-default <?php endif; ?> had_found" data-isbn="<?php echo e($isbn->isbn); ?>">已发现</a>
                                    <a class="btn <?php if($isbn->other_found): ?>btn-danger <?php else: ?> btn-default <?php endif; ?> other_found" data-isbn="<?php echo e($isbn->isbn); ?>">已采集</a>

                                </div>
                                <div class="col-md-7" style="padding: 50px;">
                                    <div style="max-height: 300px;overflow: scroll">
                                    <?php if($isbn->has_need_book): ?>
                                        <?php $__empty_2 = true; $__currentLoopData = $isbn->has_need_book; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                            <?php if($loop->index<20): ?>
                                                <a class="col-md-4 thumbnail" style="max-height: 300px"><img class="answer_pic" src="<?php echo e(config('workbook.user_image_url').$book->cover_img); ?>" alt=""></a>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    </div>
                                    <p><?php echo e($isbn->description); ?></p>
                                </div>

                            </div>
                            <a target="_blank" href="http://www.1010jiajiao.com/daan/search.php?subject=alls&word=<?php echo e($isbn->isbn); ?>"><i class="badge bg-blue" style="font-size: 20px">1010jiajiao网站搜索</i></a>
                            <?php if($isbn->taobao==2): ?>
                                <a target="_blank" href="<?php echo e(route('taobao_search',[$isbn->isbn])); ?>"><i class="badge bg-red" style="font-size: 20px">本地搜索</i></a>
                            <?php endif; ?>
                            <a style="font-size: 24px" target="_blank" href="https://s.taobao.com/search?q=<?php echo e($isbn->isbn); ?>" class="small-box-footer">
                                <i class="badge bg-blue">id：<?php echo e($isbn->id); ?></i>淘宝搜索 <i class="badge bg-red">搜索量：<?php echo e($isbn->searchnum); ?></i>
                                <i class="badge bg-red">搜索天数：<?php echo e($isbn->days); ?></i>
                                <i class="badge bg-red">搜索权重：<?php echo e($isbn->searchrate); ?></i>
                            </a>
                        </div>
                    </div>
                    
                            
                                
                            
                                
                            
                                
                            
                                
                            
                                
                            
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </div>
            <div>
                <?php echo e($data['all_isbn']->links()); ?>

            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('need_js'); ?>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>
        $(function () {
            $('.other_found').click(function () {
                let found = 1;
                let isbn = $(this).attr('data-isbn');
                axios.post('<?php echo e(route('manage_new_found_about',['other_found'])); ?>',{found,isbn}).then(response=>{
                    if(response.data.status===1){
                        if($(this).hasClass('btn-default')){
                            $(this).removeClass('btn-default').addClass('btn-danger');
                        }else{
                            $(this).removeClass('btn-danger').addClass('btn-default');
                        }

                    }
                });
            })

            $('.had_found').click(function () {
                let found = 1;
                let isbn = $(this).attr('data-isbn');
                axios.post('<?php echo e(route('manage_new_found_about')); ?>',{found,isbn}).then(response=>{
                    if(response.data.status===1){
                        if($(this).hasClass('btn-default')){
                            $(this).removeClass('btn-default').addClass('btn-danger');
                        }else{
                            $(this).removeClass('btn-danger').addClass('btn-default');
                        }
                    }
                });
            });

            //系列
            $(".sort_name").select2({
                language: "zh-CN",
                ajax: {
                    type: 'GET',
                    url: "<?php echo e(route('workbook_sort','sort')); ?>",
                    dataType: 'json',
                    delay: 100,
                    data: function (params) {
                        return {
                            word: params.term, // search term 请求参数
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items,//itemList
                        };
                    },
                    cache: true
                },

                escapeMarkup: function (markup) {
                    return markup;
                }, // 自定义格式化防止xss注入
                minimumInputLength: 1,//最少输入多少个字符后开始查询
                templateResult: function formatRepo(repo) {
                    if (repo.loading) return repo.text;
                    return '<option value="' + repo.id + '">' + repo.name + '_' + repo.id + '</option>';
                }, // 函数用来渲染结果
                templateSelection: function formatRepoSelection(repo) {
                    //alert(repo.name || repo.text);
                    return repo.name || repo.text;
                },

            });
            $('.sort_name').change(function () {
               let now_sort = $(this).val();
               if(now_sort!=999){
                   window.location.href = '<?php echo e(route('manage_new_other_temp')); ?>/'+now_sort;
               }
            })

            //isbn
            $('.for_isbn_input').bind('input propertychange', function() {

                if($(this).val().length===3){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length===5){
                    $(this).val($(this).val()+'-');
                    this.selectionStart = this.selectionEnd = this.value.length+1
                }
                if($(this).val().length>6) {
                    let now_start = $(this).val()[6];
                    if (now_start <= 3) {
                        if ($(this).val().length === 9) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start > 3 && now_start <= 5) {
                        if ($(this).val().length === 10) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '8') {
                        console.log($(this).val().length);
                        if ($(this).val().length === 11) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    } else if (now_start === '9') {
                        if ($(this).val().length === 12) {
                            $(this).val($(this).val() + '-');
                            this.selectionStart = this.selectionEnd = this.value.length + 1
                        }
                    }
                    if ($(this).val().length === 15) {
                        $(this).val($(this).val() + '-');
                        this.selectionStart = this.selectionEnd = this.value.length + 1
                    }

                    if($(this).val().length===17){
                        //$('.add_isbn').click();
                        let box = $(this).parents('.book_info_box');
                        let isbn = $(this).val();

                        axios.post('<?php echo e(route('manage_new_api','isbn_check')); ?>',{ isbn }).then(response=>{
                            if(response.data.status===0){
                                $(this).addClass('bg-red').removeClass('bg-blue')
                            }else{
                                $(this).addClass('bg-blue').removeClass('bg-red')
                            }
                        })

                        //get_related_sort
                    }

                }
            });

            $('.save_record').click(function () {
                let book_id = $('#for_isbn_id').val();
                let isbn = $(this).parent().find('.for_isbn_input').val();
                if(book_id.length<1 || isbn.length<13){
                    return false;
                }
                axios.post('<?php echo e(route('manage_new_save_online_isbn')); ?>',{book_id,isbn}).then(response=>{
                    if(response.data.status===1){
                        alert('保存成功');
                    }else{
                        alert(response.data.msg);
                    }
                })
            })
        })
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>