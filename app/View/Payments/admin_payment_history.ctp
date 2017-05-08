<?php
/**
 * Payment history view
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
			<h2><i class="icon-user"></i><?php echo __(' Payment History'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Payhisdatatable">
				<thead>
					<tr>
						<th><?php echo __('Payment Number'); ?></th>
						<th class="hidden-phone"><?php echo __('User'); ?></th>
						<th class="hidden-phone"><?php echo __('Reseller'); ?></th>
						<th class="hidden-phone"><?php echo __('Type'); ?></th>
						<th class="hidden-phone"><?php echo __('Promo Number'); ?></th>
						<th class="hidden-phone"><?php echo __('Reference'); ?></th>
						<th class="hidden-phone"><?php echo __('Notification'); ?></th>
						<th><?php echo __('Status Change'); ?></th>
						<th><?php echo __('Payment Amount'); ?></th>
						<th class="hidden-phone"><?php echo __('Tax Deducted'); ?></th>
						<th class="hidden-phone"><?php echo __('Fees Paid'); ?></th>
						<th class="hidden-phone"><?php echo __('Discount Credit'); ?></th>
						<th class="hidden-phone"><?php echo __('Balance Added'); ?></th>
						<th><?php echo __('Status'); ?></th>
					</tr>
				</thead>
			<tbody>
				<?php
					if (!empty($paymentdata)) {
						
						foreach($paymentdata as $val) {
							$user = $this->requestAction(
								array(
									'controller' => 'User',
									'action'     => 'getUserByID',
									$val['Payment']['user_id']
								)
							);

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
									echo $user['User']['name'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td style="vertical-align:middle;" class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 2) {
									echo $user['User']['name'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo __('Bank Deposit'); ?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo @$val['Payment']['promo_number']; ?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo $val['Payment']['reference_number']; ?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo $val['Payment']['notification_date']; ?></td>
						<td style="vertical-align:middle;"><?php echo $val['Payment']['change_status_date']; ?></td>
						<td style="vertical-align:middle;"><?php echo 'B/. ' . $val['Payment']['amount']; ?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['tax']; ?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['fees']; ?></td>
						<td style="vertical-align:middle;" class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 2) {
									echo 'B/. ' . $val['Payment']['discount'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['amount_credited']; ?></td>
						<td style="vertical-align:middle;">
							<?php
								if ($val['Payment']['status'] == '1') {
							?>
							<span class="label label-success"><?php echo __(' Approved '); ?></span>
							<?php
								} else {
							?>
							<span class="label label-important"><?php echo __(' Denied '); ?></span>
							<?php
								}
							?>
						</td>
					</tr>
				<?php
							} else {
				?>
					<tr>
						<td style="vertical-align:middle;">
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
						<td style="vertical-align:middle;" class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 1) {
									echo $user['User']['name'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td style="vertical-align:middle;" class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 2) {
									echo $user['User']['name'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo __('Credit Card'); ?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo @$val['Payment']['promo_number'];?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo __('-');?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo $val['Payment']['notification_date'];?></td>
						<td style="vertical-align:middle;"><?php echo $val['Payment']['change_status_date'];?></td>
						<td style="vertical-align:middle;"><?php echo 'B/. ' . $val['Payment']['amount'];?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['tax'];?></td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['fees']; ?></td>
						<td style="vertical-align:middle;" class="hidden-phone">
							<?php
								if ($val['Payment']['user_type'] == 2) {
									echo 'B/. ' . $val['Payment']['discount'];
								} else {
									echo '-';
								}
							?>
						</td>
						<td style="vertical-align:middle;" class="hidden-phone"><?php echo 'B/. ' . $val['Payment']['amount_credited']; ?></td>
						<td style="vertical-align:middle;">
							<span class="label label-success"><?php echo __(' Approved '); ?></span>
						</td>
					</tr>
				<?php
							}
						}
					}
				?>
			</tbody>
			<table>
		</div>
	</div>
</div>
