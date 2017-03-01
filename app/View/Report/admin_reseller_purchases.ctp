<?php
/**
 * Reseller purchases view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Report
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<script>
	function getCookie(c_name) {
		
		var c_value = document.cookie;
		var c_start = c_value.indexOf(" " + c_name + "=");

		if (c_start == -1) {
			c_start = c_value.indexOf(c_name + "=");
		}

		if (c_start == -1) {
			c_value = null;
		} else {
			c_start = c_value.indexOf("=", c_start) + 1;
			var c_end = c_value.indexOf(";", c_start);
			
			if (c_end == -1) {
				c_end = c_value.length;
			}
			c_value = unescape(c_value.substring(c_start,c_end));
		}
		return c_value;
	}
	
	function Export() {

		var datatableInfo = getCookie('ClubPrepago_<?php echo strtolower(urlencode($this->params['pass'][0])); ?>');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}

	function searchData(frmObj) {
		document.getElementById('from_date') . value ='';
		document.getElementById('to_date') . value ='';
		document.getElementById('user') . value ='';
		frmObj.submit();
	}
</script>
<div>
	<ul class="breadcrumb">
		<li>
			<?php
				echo $this->Html->link(
					__('Home'),
					array(
						'controller' => 'cpanel',
						'action'     => 'home'
					)
				);
			?>
		</li>
	</ul>
</div>
<?php
	if ($this->Session->read('alert') != '') {
?>
<div class="alert <?php echo ($this->Session->read('success') == 1) ? 'alert-success' : 'alert-error' ?>">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong>
		<?php 
			echo $this->Session->read('alert');
			$_SESSION['alert'] = '';
		?>
	</strong>
</div>
<?php
	}
?>
<div class="row-fluid ">	
	<div class="box span12">

		<!-- Page icon and title -->
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __(' Reseller Purchases'); ?></h2>
		</div>

		<!-- Generate table -->
		<div class="box-content">

			<!-- Generate search tools -->
			<div class="pull-right"><form action="" class="form-horizontal" method="get"
				id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<input type="date" class="input-small datepicker" id="from_date" name="from_date"
					data-rel='tooltip' data-original-title='From date' placeholder="<?php echo __('From Date'); ?>"
					value="<?php echo @$_REQUEST['from_date'];?>">&nbsp;

				<input type="date" class="input-small datepicker" id="to_date" name="to_date"
					data-rel='tooltip' data-original-title='To date' placeholder="<?php echo __('To Date'); ?>" 
					value="<?php echo @$_REQUEST['to_date'];?>">&nbsp;

				<select name="sponsor" class="input-medium" data-rel=tooltip data-original-title="<?php echo __('Select a Sponsor'); ?>" onchange="searchData(this.form);">
					<option value=""><?php echo __('All Sponsors'); ?></option>
					<?php
						$Sponsordata = $this->requestAction('sponsor/GetSponsor');
						
						foreach ($Sponsordata as $key => $sponsor) {
							$selected = ($key==$_REQUEST['sponsor']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $sponsor . "</option>";
						}
					?>
				</select>

				<select name="reseller" id="reseller" class="input-medium" data-rel=tooltip
					data-original-title="<?php echo __('Select a Reseller'); ?>" onchange="this.form.submit();">
					<option value=""><?php echo __('All Resellers'); ?></option>
					<?php
						$Resellerdata = $this->requestAction('reseller/GetReseller/' . $_REQUEST['sponsor']);
						
						foreach ($Resellerdata as $key=>$sponsor) {
							$selected = ($key == $_REQUEST['reseller']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $sponsor . "</option>";
						}
					?>
				</select>
				<?php
					echo $this->Form->Submit(
						__('Search'),
						array(
							'class'=>'btn btn-primary pull-right',
							'div'=>false
						)
					);
				?>

				<!-- Some blank space for the search button -->
				&nbsp;&nbsp;
				<?php
					echo $this->Form->end();
				?>
			</div>

			<table class="table table-striped table-bordered bootstrap-datatable Reprpurdatatable">
				<thead>
					<tr>
						<th><?php echo __('Payment Number'); ?></th>
						<th class="hidden-phone"><?php echo __('Sponsor'); ?></th>
						<th><?php echo __('Reseller'); ?></th>
						<th class="hidden-phone"><?php echo __('Type'); ?></th>
						<th class="hidden-phone"><?php echo __('Bank'); ?></th>
						<th class="hidden-phone"><?php echo __('Reference'); ?></th>
						<th><?php echo __('Approval Date'); ?></th>
						<th><?php echo __('Payment Amount'); ?></th>
						<th class="hidden-phone"><?php echo __('Tax Deducted'); ?></th>
						<th class="hidden-phone"><?php echo __('Fees Paid'); ?></th>
						<th class="hidden-phone"><?php echo __('Discount Credit'); ?></th>
						<th class="hidden-phone"><?php echo __('Balance Added'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {
							
							foreach($userdata as $val) {
						
								if ($val['Payment']['payment_method'] == 1) {
									$bank = $this->requestAction(
										array(
											'controller' => 'Banks',
											'action'     => 'getBankName',
											$val['Payment']['bank_id']
										)
									);
					?>
					<tr>
						<td style="vertical-align:middle;">
							<?php
								if (strlen($val['Payment']['id']) < 6) {
									$filler = 6 - strlen($val['Payment']['id']);
									
									for ($i = 0; $i < $filler; $i++) {
										echo '0';
									}
									echo $val['Payment']['id'];
								} else {
									echo $val['Payment']['id'];
								}
							?>
						</td>
						<td class="hidden-phone">
							<?php 
								if ($val['Sponsor']['delete_status'] == 0) {
									echo $this->Html->link(
										$val['Sponsor']['name'],
										array(
											'controller' => 'sponsor',
											'action'     => 'view',
											base64_encode($val['Sponsor']['id'])
										)
									);
								} else {
									echo $val['Sponsor']['name'];
								}
							?>
						</td>
						<td>
							<?php 
								if ($val['Reseller']['delete_status'] == 0) {
									echo $this->Html->link(
										$val['Reseller']['name'],
										array(
											'controller' => 'reseller',
											'action'     => 'view',
											base64_encode($val['Reseller']['id'])
										)
									);
								} else {
									echo $val['Reseller']['name'];
								}
							?>
						</td>
						<td class="hidden-phone">
							<?php
									echo $val['Payment']['payment_method'] == 1 ? __('Bank Deposit') : __('Credit card');
							?>
						</td>
						<td class="hidden-phone"><?php echo @$bank['Bank']['bank_name']; ?></td>
						<td class="hidden-phone"><?php echo $val['Payment']['reference_number']; ?></td>
						<td align="center">
							<?php
									echo $val['Payment']['payment_method'] == 1 ? $val['Payment']['change_status_date'] : '';
							?>
						</td>
							<td><?php echo 'B/. ' . $val['Payment']['amount']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['tax']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['fees']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['discount']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['amount_credited']; ?></td>	
						</tr>	
					<?php
								} else {
									$paymentDetail = $this->requestAction(
										'admin/payments/GetTransDetail/' . $val['Payment']['reference_number']);
					?>
						<tr>
							<td style="vertical-align:middle;">
								<?php
									if (strlen($val['Payment']['id']) < 6) {
										$filler = 6 - strlen($val['Payment']['id']);
										
										for ($i = 0; $i < $filler; $i++) {
											echo '0';
										}
										echo $val['Payment']['id'];
									} else {
										echo $val['Payment']['id'];
									}
								?>
							</td>
							<td class="hidden-phone">
								<?php 
									if ($val['Sponsor']['delete_status'] == 0) {
										echo $this->Html->link(
											$val['Sponsor']['name'],
											array(
												'controller' => 'sponsor',
												'action'     => 'view',
												base64_encode($val['Sponsor']['id'])
											)
										);
									} else {
										echo $val['Sponsor']['name'];
									}
								?>
							</td>
							<td>
								<?php 
									if($val['Reseller']['delete_status'] == 0) {
										echo $this->Html->link(
											$val['Reseller']['name'],
											array(
												'controller' =>'user',
												'action'     =>'view',
												base64_encode($val['Reseller']['id'])
											)
										);
									} else {
										echo $val['Reseller']['name'];
									}
								?>
							</td>
							<td class="hidden-phone">
								<?php
									echo $val['Payment']['payment_method'] == 1 ? 'Bank Deposit' : 'Credit card';
								?>
							</td>
							<td class="hidden-phone"><?php echo '-'; ?></td>
							<td class="hidden-phone"><?php echo $paymentDetail['Transaction']['transaction_id']; ?></td>
							<td align="center"><?php echo $val['Payment']['change_status_date']; ?></td>
							<td><?php echo 'B/. ' . $val['Payment']['amount']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['tax']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['fees']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['discount']; ?></td>
							<td class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['amount_credited']; ?></td>
						</tr>	
					<?php	
								}
					
							}
						}	
					?>	
				</tbody>
			</table>
				<?php
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'report',
								'action'     => 'export_reseller_purchases'
							),
							'name'  => 'frm_export',
							'class' => 'form-horizontal'
						)
					);
				?>
				<?php
					echo $this->Form->hidden(
						'Report.data',
						array(
							'value' => '',
							'id'    => 'info'
						)
					);
				?>
				<br/><br/>
				<input type="hidden" id="from_date" name="from_date" value="<?php echo @$_REQUEST['from_date']; ?>">&nbsp;
				<input type="hidden" id="to_date" name="to_date" value="<?php echo @$_REQUEST['to_date']; ?>">&nbsp;
				<?php
					echo $this->html->link(
						__('<i class="icon-download-alt icon-white"></i><span> Export</span>'),
						'javascript:void(0);',
						array(
							'class'   => 'btn btn-primary',
							'escape'  => false,
							'onclick' => 'Export()'
						)
					);
				?>
			</div>
		</div>
	</div>
</div>
