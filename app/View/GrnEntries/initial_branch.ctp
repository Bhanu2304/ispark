<script>
//$(document).ready(function(){
//    $("#branchId").on('change',function(){
//        var branch_id=$("#branchId").val();        
//        $.post("get_cost_center",
//            {
//             branchId: branch_id
//            },
//            function(data,status){
//                //alert(data);
//                var text="";
//                var json = jQuery.parseJSON(data);
//                for(var i in json)
//                {
//                    text += '<option value="'+i+'">'+json[i]+'</option>';
//                }
//                //alert(text);
//                $("#costcenterid").empty();
//                $("#costcenterid").html(text);
//            });
//     });   
//});
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>


<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Revenue Entry</h4>

    <?php echo $this->Form->create('GrnEntries',array('class'=>'form-horizontal','action'=>'expense_entry')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('branchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">Year</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('finance_year',array('label' => false,'options'=>array('2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017'),'class'=>'form-control','empty'=>'Select','id'=>'finance_year')); ?>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
            </div>    
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label">Month</label>
        <div class="col-sm-3">
            <div class="input-group">
               <?php echo $this->Form->input('finance_month',array('label' => false,'options'=>array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                   'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),
                   'class'=>'form-control','empty'=>'Select','id'=>'finance_month')); ?>
            
             <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>  
            </div>   
        </div>
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-2">
            <button type='submit' class="btn btn-info" value="Save">Save</button>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
    </div>
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>