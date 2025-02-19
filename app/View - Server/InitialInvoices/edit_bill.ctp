			<?php //print_r($inv_particulars); ?>
<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'update_bill')); ?>
						<?php  foreach ($tbl_invoice as $post): ?>
						<?php $data=$post; ?>
						<?php endforeach; ?><?php unset($InitialInvoice); ?>
						
						<?php  foreach ($cost_master as $post): ?>
						<?php $dataX=$post; ?>
						<?php endforeach; ?><?php unset($CostCenterMaster); ?>
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
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Edit Invoice</span>
                                        
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
                            <h4><?php echo $this->Session->Flash();?></h4>
					<!---	creating hide array for particulars table and hidden fields -->
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
						<tr align="center">
							<td>Branch</td>
							<td>Cost Center</td>
							<td>Financial Year</td>
							<td>Month for</td>
                                                        <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
                                                         <td>GST No.</td>
                                                         <td>Client GST No.</td>
                                                        <?php } ?>
						</tr>
						<tr align="center">
							<td class="info"><?php echo $data['InitialInvoice']['branch_name'];  ?></td>
							<td class="danger"><?php echo $data['InitialInvoice']['cost_center']; ?></td>
							<td class="info"><?php echo $data['InitialInvoice']['finance_year'];?></td>
							<td class="danger"><?php echo $data['InitialInvoice']['month'];?></td>
                                                        <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
                                                        <td class="info"><?php echo $cost_master['CostCenterMaster']['ServiceTaxNo'];?>
                                                            <td class="info"><?php echo $cost_master['CostCenterMaster']['VendorGSTNo'];?>
                                                        <?php } ?>        
							</td>
						</tr>
				</table>
                                        
                                <div class="form-group">
                <table class="table table-striped">
                    <tr>
                        <th>Revenue</th>
                        <?php
                                foreach($monthMaster as $mnt=>$mntRevenue)
                                {
                                    echo '<td>'.$mnt.'</td>';
                                }
                        ?>
                        <th>Total Revenue</th>
                    </tr>
                    
                    <tr>
                        <th>Revenue Remaining</th>
                        <?php
                                foreach($monthMaster as $mnt=>$mntRevenue)
                                {
                                    echo '<td>'.$ActualRevenue[$mnt].'</td>';
                                }
                        ?>
                        <th><?php echo array_sum($ActualRevenue); ?></th>
                    </tr>
                    
                    <tr>
                        <th>Revenue Choosen</th>
                        <?php
                                foreach($monthMaster as $mnt=>$mntRevenue)
                                {
                                    echo '<td>';
                                    echo $this->Form->input('InitialInvoice.revenue_arr.'.$mnt, array('label'=>false,'value'=>$mntRevenue,'onKeypress'=>'return isNumberKey(event)','type'=>'text','align'=>'right'));
                                    echo '</td>';
                                }
                        ?>
                        <th><?php echo $revenue; ?></th>
                    </tr>
                </table>
                <h6><b><font color="red">Note:-</font></b>Revenue Chosen will be smaller or equal to Total Revenue Remaining</h6>
            </div>        
					</div>
					</div>
				</div>
			</div>
<div class="row">
	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Bill To</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
						<tbody>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['client'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['bill_to'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address1'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address2'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address3'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address4'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address5'];?></th></tr>
						</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Ship To</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['client'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['ship_to'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address1'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address2'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address3'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address4'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address5'];?></th></tr>						
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
					
						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>Bill Date</th>
								<td><?php $date=date_create($data['InitialInvoice']['invoiceDate']); 
										 echo $date= "".date_format($date,"d-M-Y").""; 
										  //echo $this->Form->input('InitialInvoice.invoiceDate', 
								//array('label'=>false,'value'=>$date,'class'=>'form-control'));
									?>
								</td>
						</tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<th>Date Change</th>
							<td><?php	
							 $dat =$data['InitialInvoice']['invoiceDate'];
							 $dat=$dat." 00:00:00";
							 $dat=date_create($dat);
							 $dat = date_format($dat,"d-m-Y");

										echo $this->Form->input('InitialInvoice.invoiceDate', 
										array('label'=>false,'class'=>'form-control','value'=>$dat,
										'onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');","readonly"=>true)); 
								?>	</td>
						</tr>

						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>Bill No.</th><td><?php	echo $this->Form->input('InitialInvoice.bill_no', 
								array('label'=>false,'value'=>$data['InitialInvoice']['bill_no'],'class'=>'form-control','readOnly'=>true)); ?></td>
						</tr>

						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>JCC No.</th><td><?php	echo $this->Form->input('InitialInvoice.jcc_no', 
								array('label'=>false,'value'=>$data['InitialInvoice']['jcc_no'],'class'=>'form-control')); ?></td>
						</tr>

						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>PO No.</th><td><?php	echo $this->Form->input('InitialInvoice.po_no', 
								array('label'=>false,'value'=>$data['InitialInvoice']['po_no'],'class'=>'form-control')); ?></td>
						</tr>
						
						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>GRN</th><td><?php	echo $this->Form->input('InitialInvoice.grn', 
								array('label'=>false,'value'=>$data['InitialInvoice']['grn'],'class'=>'form-control','readonly'=>'true')); ?></td>
						</tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>Description</th><td><?php	echo $this->Form->input('InitialInvoice.invoiceDescription', 
								array('label'=>false,'value'=>$data['InitialInvoice']['invoiceDescription'],'class'=>'form-control')); ?></td>
						</tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>">
								<th>Month</th><td><?php	echo $this->Form->input('InitialInvoice.month', 
								array('label'=>false,'value'=>$data['InitialInvoice']['month'],'class'=>'form-control')); ?></td>
						</tr>
																								
					</tbody>
				</table>

				</div>
		   </div>
	</div>
</div>
			
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Bill Details</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr>
							<th> S.No. </th>
							<th> Particulars </th>
							<th> Qty. </th>
							<th> Rate </th>
							<th> Amount </th>
							
						</tr>
						<?php $idx=''; ?>
						<?php  foreach ($inv_particulars as $post): ?>
							<?php $idx.=$post['Particular']['id'].','; ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.particulars',array('label'=>false,'value'=>$post['Particular']['particulars'],'class'=>'form-control')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.qty',array('label'=>false,'value'=>$post['Particular']['qty'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.rate',array('label'=>false,'value'=>$post['Particular']['rate'],'class'=>'form-control','onBlur'=>'getAmount1(this.id)','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.amount',array('label'=>false,'value'=>$post['Particular']['amount'],'class'=>'form-control','readonly'=>true)); ?></td>
							</tr>
						<?php endforeach; ?><?php unset($Particular); ?>
						<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
						</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Any Deduction</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr>
							<th> S.No. </th>
							<th> Particulars </th>
							<th> Qty. </th>
							<th> Rate </th>
							<th> Amount </th>
							
						</tr>
						<?php $idxd=''; ?>
						<?php  foreach ($inv_deduct_particulars as $post): ?>
						<?php $idxd.=$post['DeductParticular']['id'].','; ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.particulars',array('label'=>false,'value'=>$post['DeductParticular']['particulars'],'class'=>'form-control'));?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.qty',array('label'=>false,'value'=>$post['DeductParticular']['qty'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)'));?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.rate',array('label'=>false,'value'=>$post['DeductParticular']['rate'],'class'=>'form-control','onBlur'=>'getAmount2(this.id)','onkeypress'=>'return isNumberKey(event)'));?></td>							
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.amount',array('label'=>false,'value'=>$post['DeductParticular']['amount'],'class'=>'form-control','readOnly'=>true));?></td>
							</tr>
						<?php endforeach; ?><?php unset($DeductParticular); ?>
						<?php echo $this->Form->input('a.idxd',array('label'=>false,'value'=>$idxd,'type'=>'hidden','id'=>'idxd')); ?>
						</tbody>
				</table>

				<div id="nn"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				<table class="table table-striped">
                                    <thead> 
                                        <tr>

                                        <th>Total:</th>
                                        <td>
                                            <?php echo $this->Form->input('InitialInvoice.total',
                                        array('label'=>false,'value'=>$data['InitialInvoice']['total'],'readonly'=>true));?>
                                        </td>
                                        </tr>
                                    
						<?php if($data['InitialInvoice']['app_tax_cal']=='1') {
                                                    if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30"))
                                                    {
                                                      if($data['InitialInvoice']['GSTType']=='Integrated')
                                                      {
                                                    ?>
                                                <tr>
                                                    <th>IGST @ 18%</th>
                                                    <td><?php echo $this->Form->input('InitialInvoice.igst', 
                                                        array('label'=>false,'value'=>round($data['InitialInvoice']['igst']),'readonly'=>true)); ?>
                                                    </td>
                                                </tr>
                                                <?php      }
                                                else {?>
                                                    <tr>
                                                    <th>CGST @ 9%</th>
                                                    <td><?php echo $this->Form->input('InitialInvoice.cgst', 
                                                        array('label'=>false,'value'=>round($data['InitialInvoice']['cgst']),'readonly'=>true)); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>SGST @ 9%</th>
                                                    <td><?php echo $this->Form->input('InitialInvoice.sgst', 
                                                        array('label'=>false,'value'=>round($data['InitialInvoice']['sgst']),'readonly'=>true)); ?>
                                                    </td>
                                                </tr>
                                                <?php }
                                                    }
                                                    else {
                                                    
                                                    ?>
						<tr>
                                                    <th>Service Tax @ 14.00%</th>	<td><?php echo $this->Form->input('InitialInvoice.tax',
						array('label'=>false,'value'=>$data['InitialInvoice']['tax'],'readonly'=>true));?></td>
                                                </tr>
                                    </thead>   
						<?php if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14")){  ?>
						<tr><th>SBC @ 0.5%</th><td><?php	echo $this->Form->input('InitialInvoice.sbctax', array('label'=>false,'value'=>$data['InitialInvoice']['sbctax'],'readonly'=>true)); ?></td></tr>						
						<?php } ?>
                                                <?php if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")){  ?>
						<tr><th>KKC @ 0.5%</th><td><?php	echo $this->Form->input('InitialInvoice.krishi_tax', array('label'=>false,'value'=>$data['InitialInvoice']['krishi_tax'],'readonly'=>true)); ?></td></tr>
                                                <?php }}} ?>
						<tr><thead> <th>Grand Total:</th>			<td><?php echo $this->Form->input('InitialInvoice.grnd',
						array('label'=>false,'value'=>$data['InitialInvoice']['grnd'],'readonly'=>true));?></td></thead></tr>
						<tr>
							<td>
                                                            <div class="col-sm-2"><button name="Submit" type="submit" value="Edit" class="btn btn-success btn-label-left" onClick="return validate_edit()"><b>Update</b></button></div>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if(in_array('5',$roles)){
                                                                    echo $this->Html->link('  Back',array('action'=>'view'),array('class'=>'btn btn-primary'));
                                                                    ?>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name="Reject" value="Reject" type="submit" class="btn btn-danger btn-label-left" onClick="return validate_edit()"><b>Reject</b></button>
                                                                <?php }
									  else{echo $this->Html->link('  Back',array('action'=>'branch_view'),array('class'=>'btn btn-primary'));}	 ?>
							</td>
							<td></td>
						</tr> 
						
				</table>
			</div>
		</div>
	</div>
</div>
<?php	echo $this->Form->input('InitialInvoice.revenue', 	array('label'=>false,'value'=>$revenue,'type'=>'hidden')); ?>
<?php	
echo $this->Form->input('InitialInvoice.id', 	array('label'=>false,'value'=>$data['InitialInvoice']['id'],'type'=>'hidden')); 
	echo $this->Form->input('InitialInvoice.branch_name', 	array('label'=>false,'value'=>$data['InitialInvoice']['branch_name'],'type'=>'hidden')); 
echo $this->Form->input('InitialInvoice.finance_year', 	array('label'=>false,'value'=>$data['InitialInvoice']['finance_year'],'type'=>'hidden')); 
echo $this->Form->input('InitialInvoice.cost_center', 	array('label'=>false,'value'=>$data['InitialInvoice']['cost_center'],'type'=>'hidden')); 
echo $this->Form->input('InitialInvoice.apply_krishi_tax', 	array('label'=>false,'value'=>$data['InitialInvoice']['apply_krishi_tax'],'type'=>'hidden')); 
echo $this->Form->input('InitialInvoice.apply_service_tax', 	array('label'=>false,'value'=>$data['InitialInvoice']['apply_service_tax'],'type'=>'hidden')); 
echo $this->Form->input('InitialInvoice.apply_gst', 		array('label'=>false,'value'=>$data['InitialInvoice']['apply_gst'],'type'=>'hidden'));
echo $this->Form->input('GSTType', 		array('label'=>false,'value'=>$data['InitialInvoice']['GSTType'],'type'=>'hidden'));
	//echo $this->Form->input('InitialInvoice.finance_year',		array('label'=>false,'value'=>$data['InitialInvoice']['finance_year'],'type'=>'hidden')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

			
		