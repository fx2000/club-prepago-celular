<?php
/**
 * Transactions report view
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

		var c_value = document.cookie;//alert(c_value);
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

	function FillBlank(elm) {
		if (elm == 1) {
			document.getElementById('reseller').value = '';
		} else {
			document.getElementById('username').value = '';
		}
	}
</script>
<style>
	.dataTables_filter {display:none;}
	.dataTables_length {display:none;}
</style>
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
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Transactions'); ?></h2>
		</div>
		<div class="box-content " style="margin-bottom:20px;">
			<div class="pull-right"><form action="" class="form-horizontal" method="get"
				id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<select name="username" id="username" class="input-medium" data-rel=tooltip
					data-original-title='User Name' onchange="FillBlank(1);">
					<option value=""><?php echo __('All Users'); ?></option>
					<?php
						$Userdata = $this->requestAction('user/getUser/');
						
						foreach ($Userdata as $key => $user) {
							$selected = ($key == $_REQUEST['username']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $user . "</option>";
						}
					?>
				</select>
				<select name="reseller" id="reseller" class="input-medium" data-rel=tooltip
					data-original-title='Reseller Name' onchange="FillBlank(2)">
					<option value=""><?php echo __('All Resellers'); ?></option>
						<?php
							$Resellerdata = $this->requestAction('reseller/getReseller/');
							
							foreach ($Resellerdata as $key => $sponsor) {
								$selected = ($key == $_REQUEST['reseller']) ? 'selected="selected"' : '';
								echo "<option value='" . $key . "' " . $selected . ">" . $sponsor . "</option>";
							}
						?>
				</select>
				<select name="payment_method" class="input-medium" data-rel=tooltip data-original-title='Payment method'>
					<option value=""><?php echo __('Payment Method'); ?></option>
					<option value='1'<?php echo (@$_REQUEST['payment_method'] == '1') ? 'selected="selected"':''; ?>>
						<?php echo __('Prepaid Balance'); ?>
					</option>
					<option value='2'<?php echo (@$_REQUEST['payment_method'] == '2') ? 'selected="selected"':''; ?>>
						<?php echo __('Credit Card'); ?>
					</option>
					<option value='3'<?php echo (@$_REQUEST['payment_method'] == '3') ? 'selected="selected"':''; ?>>
						<?php echo __('Reward Points'); ?>
					</option>
				</select>
				<input type="date" class="input-small datepicker" id="input_date" name="input_date" 
					placeholder="Input Date" data-rel='tooltip' data-original-title='Input date'
					value="<?php echo @$_REQUEST['input_date'];?>">&nbsp;
				<select name="status" class="input-medium" data-rel=tooltip data-original-title='Status'>
					<option value="">
						<?php echo __('Status'); ?>
					</option>
					<option value='0' <?php echo (@$_REQUEST['status'] == '0') ? 'selected="selected"' : ''; ?>>
						<?php echo __('Failed'); ?>
					</option>
					<option value='1' <?php echo (@$_REQUEST['status'] == '1') ? 'selected="selected"' : ''; ?>>
						<?php echo __('Successful'); ?>
					</option>
				</select>
				<?php
					echo $this->Form->Submit(
						__('Submit'),
						array(
							'class' => 'btn btn-primary pull-right',
							'div'   => false
						)
					);
				?>&nbsp;&nbsp;
				<?php echo $this->Form->end();?>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Reptrxdatatable">
				<thead>
					 <tr>
						<th class="hidden-phone"><?php echo __('Transaction ID'); ?></th>
						<th class="hidden-phone"><?php echo __('User'); ?></th>
						<th class="hidden-phone"><?php echo __('Reseller'); ?></th>
						<th class="hidden-phone"><?php echo __('Mobile Operator'); ?></th>
						<th><?php echo __('Phone Number'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th class="hidden-phone"><?php echo __('Payment Method'); ?></th>
						<th><?php echo __('Date & Time'); ?></th>
						<th class="hidden-phone"><?php echo __('Points Awarded'); ?></th>
						<th><?php echo __('Status'); ?></th>
						</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {

							foreach($userdata as $val) {
					?>
					<tr>
						<td class="hidden-phone">
							<?php
								echo $this->Html->link(
									$val['Recharge']['merchant_txn_id'],
									array(
										'controller' => 'Recharge',
										'action'     => 'view_status',
										base64_encode($val['Recharge']['merchant_txn_id'])
									)
								);
							?>
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Recharge']['user_type'] == 1) {
									$userId = $val['Recharge']['user_id'];
									if ($userId != 0) {
										echo $this->Html->link(
											$val['User']['name'],
											array(
												'controller' => 'user',
												'action'     => 'view',
												base64_encode($userId)
											)
										);
									}
								} else {
									echo '-';
								}
							?>
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Recharge']['user_type'] == 2) {
									$userId = $val['Recharge']['user_id'];
									if ($userId != 0) {
										echo $this->Html->link(
											$val['User']['name'],
											array(
												'controller' => 'reseller',
												'action'     => 'view',
												base64_encode($userId)
											)
										);
									}
								} else {
									echo '-';
								}
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Operator']['name'];?></td>
						<td><?php echo $val['Recharge']['phone_number']; ?></td>
						<td><?php echo 'B/. ' . $val['Recharge']['amount']; ?></td>
						<td class="hidden-phone">
							<?php 							
								if ($val['Recharge']['payment_method'] == 1) {
									echo __("Prepaid Balance");
								} else if ($val['Recharge']['payment_method'] == 2) {
									echo __("Credit Card");
								} else if ($val['Recharge']['payment_method'] == 3) {
									echo __("Reward Points");
								} else {
									echo $val['Recharge']['payment_method'];
								}
							?>
						</td>
						<td><?php echo $val['Recharge']['recharge_date']; ?></td>
						<td class="hidden-phone"><?php echo $val['Recharge']['points']; ?>
						</td>
						<td class="center">
							<?php
								if ($val['Recharge']['status'] == 0) {
									echo __("<span class='label label-warning'>Failed</span>");
								} else if ($val['Recharge']['status'] == 1) {
									echo __("<span class='label label-success'>Successful</span>");
								} else if ($val['Recharge']['status'] == 2) {
									echo __("<span class='label label-warning'>Replaced</span>");
								} else {
									echo $val['Recharge']['status'];
								}
							?>
						</td>
					</tr>	
					<?php
							}
					 	}	
					?>			
				</tbody>   
			</table>
			<div class="box-content">
				<?php
					if ($Admindata['Admin']['type'] == 3) {
						echo $this->Form->create(
							'',
							array(
								'url'   => array(
									'controller' => 'report',
									'action'     => 'export_transactions'
								),
								'name'  => 'frm_export',
								'class' => 'form-horizontal'
							)
						);
				?>
				<br/><br/>
				<input type="hidden" id="payment_method1" name="payment_method" value="<?php echo @$_REQUEST['payment_method']; ?>">&nbsp;
				<input type="hidden" id="input_date1" name="input_date" value="<?php echo @$_REQUEST['input_date']; ?>">&nbsp;
				<input type="hidden" id="reseller1" name="reseller" value="<?php echo @$_REQUEST['reseller']; ?>">&nbsp;
				<input type="hidden" id="username1" name="username" value="<?php echo @$_REQUEST['username']; ?>">&nbsp;
				<input type="hidden" id="status1" name="status" value="<?php echo @$_REQUEST['status']; ?>">&nbsp;
				<?php
						echo $this->Form->hidden(
							'Report.data',
							array(
								'value' => '',
								'id'    => 'info'
							)
						);
						echo $this->html->link(
							__('<i class="icon-download-alt icon-white"></i><span class="hidden-phone"> Export</span>'),
							'javascript:void(0);',
							array(
								'class'   => 'btn btn-primary',
								'escape'  => false,
								'onclick' => 'Export()'
							)
						);
					}
				?>
			</div>
		</div>
	</div>
</div>
