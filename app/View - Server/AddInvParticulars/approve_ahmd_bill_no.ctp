<?php //print_r($res); ?>
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr class="active" align="center">
							<td>Sr. No.</td>
							<td>Branch Name</td>
							<td>Invoice No.</td>
							<td>Amount</td>
							<td>PO No.</td>
							<td>GRN</td>
							<td>Description</td>
							<td>Action</td>
						</tr>
						<?php if(isset($tbl_invoice)) { foreach($tbl_invoice as $post):?>
						<tr class="<?php  echo $case[$i%3]; $i++;?>" align="center">
							<?php $id= $post['InitialInvoice']['id']; ?>
							<td><code><?php echo $i; ?></code></td>							
							<td><code><?php echo $post['InitialInvoice']['branch_name']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['bill_no']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['total']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['po_no']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['grn']; ?></code></td>
							<td><code><?php echo $post['InitialInvoice']['invoiceDescription']; ?></code></td>
							<td>
								<code><?php echo $this->Html->link('Approve To Ahmedabad',
							array('controller'=>'InitialInvoices','action'=>'approve_ahmd','?'=>array('id'=>$id),'full_base' => true)); ?></code>
							</td>
						</tr>
						<?php endforeach;} ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>						