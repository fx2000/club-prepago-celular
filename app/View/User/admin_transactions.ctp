<?php
/**
 * User Transactions view
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

<!-- File export helper -->
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
			c_value = unescape(c_value.substring(c_start, c_end));
		}
		return c_value;
	}
	
	function Export() {

		var datatableInfo = getCookie('ClubPrepago_<?php echo strtolower(urlencode($this->params['pass'][0])); ?>');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}
</script>

<!-- "Home" link -->
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

<!-- Check if session is valid -->
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
			<h2><i class="icon-user"></i><?php echo __(' Transactions'); ?></h2>
		</div>

		<!-- Generate info table -->
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Usertrxdatatable">
				<thead>
				 	<tr>
						<th class="hidden-phone"><?php echo __('Transaction ID'); ?></th>
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
							
							foreach ($userdata as $val) {
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
						<td class="hidden-phone"><?php echo $val['Operator']['name']; ?></td>
						<td><?php echo $val['Recharge']['phone_number']; ?></td>
						<td><?php echo 'Bs. ' . $val['Recharge']['amount']; ?></td>
						<td class="hidden-phone">
							<?php
								if ($val['Recharge']['payment_method'] == 1) {
									echo __('Prepaid Balance');
								} else if ($val['Recharge']['payment_method'] == 2) {
									echo __('Credit Card');
								} else if ($val['Recharge']['payment_method'] == 3) {
									echo __('Points');
								} else {
									echo $val['Recharge']['payment_method'];
								}
							?>
						</td>
						<td>
							<?php echo $val['Recharge']['recharge_date']; ?>
						</td>	
						<td class="hidden-phone">
							<?php echo $val['Recharge']['points']; ?>
						</td>
						<td class="center">
							<?php
								if ($val['Recharge']['status'] == 1) {
									echo __("<span class='label label-success'>Successful</span>");
								} else {
									echo __("<span class='label label-warning'>Failed</span>");								   
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

			<!-- Check for admin access and enable export button -->
			<?php
				if ($Admindata['Admin']['type'] == 3) {
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'user',
								'action'     => 'export_transactions'
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
			<!-- Pass user ID so only that user's info gets exported -->
			<input type="hidden"  id="user_id" name="user_id" value="<?php echo @$this->params->pass[0]; ?>">&nbsp;
			<?php
					echo $this->html->link(
						__('<i class="icon-download-alt icon-white"></i><spa> Export</span> '),
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
