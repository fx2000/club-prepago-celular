<?php
/**
 * Payment Notifications view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Payments
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
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
			<h2><i class="icon-user"></i><?php echo __(' Payment Notifications'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Paynotdatatable">
				<thead>
					 <tr>
						<th><?php echo __('Payment Number'); ?></th>
						<th class="hidden-phone"><?php echo __('User'); ?></th>
						<th class="hidden-phone"><?php echo __('Reseller'); ?></th>
						<th class="hidden-phone"><?php echo __('Reference'); ?></th>
						<th class="hidden-phone"><?php echo __('Payment Method'); ?></th>
						<th><?php echo __('Notification Date & Time'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th class="hidden-phone"><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($paymentdata)) {

							foreach ($paymentdata as $val) {
								$user = $this->requestAction(
									array(
									'controller' => 'User',
									'action'     => 'getUserByID',
									$val['Payment']['user_id']));
								$bank = $this->requestAction(
									array(
									'controller' => 'Banks',
									'action'     => 'getBankByID',
									$val['Payment']['bank_id']
								)
							);
					?>
					<tr>
						<td>
							<?php
								$payId = str_pad($val['Payment']['id'], 6, "0", STR_PAD_LEFT);
								echo $this->Html->link(
									$payId,
									array(
										'controller' => 'payments',
										'action'     => 'details',
										base64_encode($val['Payment']['id'])
									)
								);
							?>
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 1) {
									echo @$user['User']['name'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 2) {
									echo @$user['User']['name'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Payment']['reference_number']; ?></td>
						<td class="hidden-phone">
							<?php
								if ($val['Payment']['payment_method'] == 1) {
									echo __('Direct Deposit');
								} else if ($val['Payment']['payment_method'] == 2) {
									echo __('Credit Card');
								}
							?>
						</td>
						<td><?php echo $val['Payment']['notification_date']; ?></td>
						<td><?php echo 'Bs. ' . $val['Payment']['amount']; ?></td>
						<td class="hidden-phone">
							<?php
								echo $this->html->link(
									__('<i class="icon-ok icon-white"></i><span class="hidden-phone">&nbsp;Approve</span>'),
									array(
										'controller' => 'Payments',
										'action'     => 'approve',
										base64_encode($val['Payment']['id'])
									),
									array(
										'class'      => 'btn btn-small btn-success approve',
										'escape'     => false
									)
								);
							?>
							<?php
								echo $this->html->link(
									__('<i class="icon-remove icon-white"></i><span class="hidden-phone">&nbsp;Deny</span>'),
									array(
										'controller' => 'Payments',
										'action'     => 'deny_confirmation',
										base64_encode($val['Payment']['id'])
									),
									array(
										'class'      => 'btn btn-small btn-danger deny',
										'escape'     => false
									)
								);
							?>
						</td>
					</tr>
					<?php
							}
						}
					?>
				</tbody>
			<table>
		</div>
	</div>
</div>
