<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">编辑</h4>
</div>
<div class="modal-body">
    <form  id="isbnbuyform" class="form-horizontal" method="post" action="">
    <div class="box-body">
        <div class="form-group">
            <label for="price" class="col-sm-2 control-label">购买价格</label>
            <div class="col-sm-10">
                <input type="text" name="price" class="form-control" id="price" placeholder="购买价格">
            </div>
        </div>
        <div class="form-group">
            <label for="purchaser"  class="col-sm-2 control-label">购买人</label>
            <div class="col-sm-10">
                <input type="text" name="purchaser" class="form-control" id="purchaser" placeholder="购买人">
            </div>
        </div>
        <div class="form-group">
            <label for="buydate" class="col-sm-2 control-label">购买时间</label>
            <div class="col-sm-10">
                <input type="text" name="buydate" class="form-control" id="buydate" placeholder="购买时间">
            </div>
        </div>
        <div class="form-group">
            <label for="grade_name"  class="col-sm-2 control-label">年级</label>
            <div class="col-sm-10">
                <input type="text" name="grade_name" class="form-control" id="grade_name" placeholder="年级">
            </div>
        </div>
        <div class="form-group">
            <label for="version_year"  class="col-sm-2 control-label">年份</label>
            <div class="col-sm-10">
                <input type="text" name="version_year" class="form-control" id="version_year" placeholder="年份">
            </div>
        </div>

        <div class="form-group">
            <label for="subject_name"  class="col-sm-2 control-label">学科</label>
            <div class="col-sm-10">
                <input type="text" name="subject_name" class="form-control" id="subject_name" placeholder="学科">
            </div>
        </div>

        <div class="form-group">
            <label for="version_name"  class="col-sm-2 control-label">版本</label>
            <div class="col-sm-10">
                <input type="text" name="version_name" class="form-control" id="version_name" placeholder="版本">
            </div>
        </div>

        <div class="form-group">
            <label for="volume_name"  class="col-sm-2 control-label">册次</label>
            <div class="col-sm-10">
                <input type="text" name="volume_name" class="form-control" id="volume_name" placeholder="册次">
            </div>
        </div>

        <div class="form-group">
            <label for="sort_name"  class="col-sm-2 control-label">系列</label>
            <div class="col-sm-10">
                <input type="text" name="sort_name" class="form-control" id="sort_name" placeholder="系列">
            </div>
        </div>

        <input type="hidden" name="isbn" value="{{$isbn}}" />
        <input type="hidden" name="_token" value="{{csrf_token()}}">
    </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
    <button type="button" class="btn btn-primary" id="isbnbuysumbit">保 存</button>
</div>
</div>
<script  src="/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script  src="/adminlte/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js"></script>
<script type="text/javascript">
    $("#isbnbuysumbit").click(function () {
        var formData = $("#isbnbuyform").serialize();
        $.post("{{route('buybookbyisbn',['isbn'=>$isbn])}}",formData,function (data) {
            if(data.status == 0){
                alert(data.msg);
            }else{
                $("#modal-default").modal('hide')
            }
        });
    });
    $("#buydate").datepicker({
        autoclose: true,
        todayHighlight: true,
        language:"zh-CN",
        format:"yyyy-mm-dd",
        endDate : new Date()
    });
</script>