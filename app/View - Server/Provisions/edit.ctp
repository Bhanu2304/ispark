<?php echo $this->Form->create('Provision',array('class'=>'form-horizontal','url'=>'update','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i>
                    <span>Provision</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
                
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Branch Master</label>
                    <div class="col-sm-2">
                    <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'value' => $provision['Provision']['branch_name'],'required'=>true,'onchange'=>"get_costcenter3(this.value)"));
                    ?>
                    </div>

                    <label class="col-sm-2 control-label">Cost Center</label>
                    <div class="col-sm-2">
                    <?php $cost_center = array($provision['Provision']['cost_center']=>$provision['Provision']['cost_center']);	
                            echo $this->Form->input('cost_center', array('label'=>false,'class'=>'form-control','options' => $cost_center,'value' => $provision['Provision']['cost_center'],'required'=>true)); ?>
                    </div>
                    

                    <label class="col-sm-2 control-label">Financial Year</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('finance_year', array('options' => $finance_yearNew,'value' => $provision['Provision']['finance_year'],'label' => false, 'div' => false,'class'=>'form-control')); ?>
                    </div>
                </div>

		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Month</label>
                    <div class="col-sm-2">
                        <?php	
                                $m = explode('-',$provision['Provision']['month']);
                                $provision['Provision']['month'] = $m['0'];
                                $month = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                                echo $this->Form->input('month', array('label'=>false,'class'=>'form-control','options'=>$month,'value' => $provision['Provision']['month'],'required'=>true));
                         ?>
                    </div>
                    
                    <?php if($provision['Provision']['revenue_active']=='1') { ?>
                    
                    <label class="col-sm-2 control-label">Provision Amount</label>
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('provision', array('label'=>false,'type'=>'text','class'=>'form-control','value'=>$provision['Provision']['provision'],'required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-2 control-label">Provision Balance</label>
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('provision_balance', array('label'=>false,'type'=>'text','class'=>'form-control','value'=>$provision['Provision']['provision_balance'],'readonly'=>true)); ?>
                    </div>
                    
                    <?php }  ?>
                    </div>
                    <?php if($provision['Provision']['billing_active']=='1') { ?>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Billing Amount</label>
                    <div class="col-sm-2">
                        <?php	echo $this->Form->input('billing_amt', array('label'=>false,'type'=>'text','class'=>'form-control','value'=>$provision['Provision']['billing_amt'],'required'=>true)); ?>
                    </div>
                    </div>
                    <?php }  ?>
		
		<div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Remarks</label>
                    <div class="col-sm-6">
                        <?php	echo $this->Form->textarea('remarks', array('label'=>false,'type'=>'text','class'=>'form-control','value'=>'','required'=>true)); ?>
                    </div>
		</div>			
		
		<div class="clearfix"></div>
		<div class="form-group">
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-label-left">
                            Submit
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Form->input('billing_active',array('label'=>false,'value'=>$provision['Provision']['billing_active'],'type'=>'hidden'));
echo $this->Form->input('revenue_active',array('label'=>false,'value'=>$provision['Provision']['revenue_active'],'type'=>'hidden'));
echo $this->Form->input('id',array('label'=>false,'value'=>$provision['Provision']['id'],'type'=>'hidden'));
echo $this->Form->end();
?>