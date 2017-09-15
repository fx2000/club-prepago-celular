<?php
/**
 * User Purchase History view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.User
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
		document.getElementById('reseller') . value = '';
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
		<li>/</li>
		<li>
			<?php
				echo $this->Html->link(
					__('View Users'),
					array(
						'controller' => 'user',
						'action'     => 'index'
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Purchase History'); ?></h2>
		</div>
		<div style="clear:both"></div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Userpurdatatable">
				<thead>
					<tr>							
						<th><?php echo __('Payment Number'); ?></th>
						<th><?php echo __('Type'); ?></th>
						<th class="hidden-phone"><?php echo __('Bank'); ?></th>
						<th class="hidden-phone"><?php echo __('Account Type'); ?></th>
						<th class="hidden-phone"><?php echo __('Account Number'); ?></th>
						<th class="hidden-phone"><?php echo __('Reference'); ?></th>
						<th class="hidden-phone"><?php echo __('Notification'); ?></th>
						<th><?php echo __('Status Change'); ?></th>
						<th><?php echo __('Payment Amount'); ?></th>
						<th class="hidden-phone"><?php echo __('Tax Deducted'); ?></th>
						<th class="hidden-phone"><?php echo __('Fees Paid'); ?></th>
						<th class="hidden-phone"><?php echo __('Balance Added'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {
							
							foreach ($userdata as $val) {
								
								if ($val['Payment']['payment_method'] == 1) {
									
									if ($val['Payment']['bank_id'] != '') {
										$bankName = $this->requestAction(
											array(
												'controller' => 'Banks',
												'action'     => 'getBankName',
												$val['Payment']['bank_id']
											)
										);
									}
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
						<td><?php echo $val['Payment']['payment_method'] == 1 ? 'Bank Deposit' : 'Credit card'; ?></td>
						<td align="center" class="hidden-phone" >
							<?php
									if (@is_array($bankName['Bank'])) {
											echo $bankName['Bank']['bank_name']; 
									} else {
										echo "-";
									}
							?>
						</td>
						<td align="center" class="hidden-phone">
							<?php
									if (@is_array($bankName['Bank'])) {

										if ($bankName['Bank']['account_type'] == 1) {
											echo __('Checking');
										} else if ($bankName['Bank']['account_type'] == 2) {
											echo __('Savings');
										} else{
											echo $bankName['Bank']['account_type']; 
										}
									} else {
										echo "-";
									}
							?>
						</td>
						<td align="center" class="hidden-phone">
							<?php
									if (@is_array($bankName['Bank'])) {
											echo $bankName['Bank']['account_number']; 
									} else {
										echo "-";
									}
							?>
						</td>
						<td align="center" class="hidden-phone"><?php echo $val['Payment']['reference_number']; ?></td>
						<td align="center" class="hidden-phone"><?php echo $val['Payment']['notification_date']; ?></td>
						<td><?php echo $val['Payment']['change_status_date']; ?></td>
						<td><?php echo 'Bs. ' . $val['Payment']['amount']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['tax']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['fees']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['amount_credited']; ?></td>
					</tr>	
					<?php
								} else {
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
						<td><?php echo $val['Payment']['payment_method'] == 1 ? 'Bank Deposit':'Credit card'; ?></td>
						<td align="center" class="hidden-phone"><?php echo __('-'); ?></td>
						<td align="center" class="hidden-phone"><?php echo __('-'); ?></td>
						<td align="center" class="hidden-phone"><?php echo __('-'); ?></td>
						<td align="center" class="hidden-phone"><?php echo $val['Payment']['reference_number']; ?></td>
						<td align="center" class="hidden-phone"><?php echo $val['Payment']['notification_date']; ?></td>
						<td><?php echo $val['Payment']['change_status_date']; ?></td>
						<td><?php echo 'Bs. ' . $val['Payment']['amount']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['tax']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['fees']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['amount_credited']; ?></td>
					</tr>
					<?php
								}
					
							}
						}	
					?>	
				</tbody>
			</table>
			<?php
				if ($Admindata['Admin']['type'] != 1) {
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'user',
								'action'     => 'export_purchases'
							),
							'name'  => 'frm_export',
							'class' => 'form-horizontal'
						)
					);
					echo $this->Form->hidden(
						'User.data',
						array(
							'value' => '',
							'id'    => 'info'
						)
					);
			?>
			<br/><br/>
			<input type="hidden" id="user_id" name="user_id" value="<?php echo @$this->params->pass[0]; ?>">&nbsp;
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
				}
			?>
		</div>
	</div>
</div>
