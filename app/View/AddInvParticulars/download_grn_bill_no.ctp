<?php //print_r($res); ?>

			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','active'); $i=0; ?>
					<tbody>
						<tr class="info">
							<th> Sr. No. </th>
							<th> Branch Name </th>
							<th> Invoice No. </th>
							<th> Amount </th>
							<th> PO No. </th>
							<th> GRN </th>
							<th> Description </th>
							<th colspan="3"> Action </th>
							<th>Files</th>
						</tr>
						<?php if(isset($tbl_invoice)) { foreach($tbl_invoice as $post) :?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>">
							<?php $id = $post['InitialInvoice']['id']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['InitialInvoice']['branch_name']; ?></td>
							<td><?php echo $post['InitialInvoice']['bill_no']; ?></td>
							<td><?php echo $post['InitialInvoice']['total']; ?></td>
							<td><?php echo $post['InitialInvoice']['po_no']; ?></td>
							<td><?php echo $post['InitialInvoice']['grn']; ?></td>
							<td><?php echo $post['InitialInvoice']['invoiceDescription']; ?></td>
							<td><?php echo $this->Html->link(__('PDF'), array('controller'=>'InitialInvoices','action' => 'view_pdfgrn','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?></td>
							<td><?php echo $this->Html->link(__('Letter Head'), array('controller'=>'InitialInvoices','action' => 'view_pdfgrn1','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?></td>
                                                        <td><?php echo $this->Html->link(__('Export'), array('controller'=>'InitialInvoices','action' => 'view_pdfgrn2','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?></td>
								<td>
								<?php 
									$files=explode(',',$post['InitialInvoice']['filepath']);
									
									if(isset($files))
									{
										foreach($files as $links) : 
									?>
										&nbsp; <a href="<?php echo $this->html->webroot('upload'.DS.$links); ?>"><?php echo $links; ?> </a>
									<?php	 endforeach;
									}
								?>
							</td>

						</tr>
						<?php endforeach;} ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>						