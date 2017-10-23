<?php
/**
 * Failed recharges view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Recharge
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

		<!-- Page icon and title -->
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Failed Credit Card Recharges'); ?></h2>
		</div>

		<!-- Generate info table -->
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Faileddatatable">
				<thead>
					<tr>
						<th class="hidden-phone"><?php echo __('User or Reseller'); ?></th>
						<th align="center"><?php echo __('Transaction ID'); ?></th>
						<th class="hidden-phone"><?php echo __('Mobile Operator'); ?></th>
						<th class="hidden-phone"><?php echo __('Phone Number'); ?></th>
						<th align="center"><?php echo __('Amount'); ?></th>
						<th><?php echo __('Date & Time'); ?></th>
						<th class="hidden-phone"><?php echo __('Mobile Operator Response'); ?></th>
						<th><?php echo __('Action'); ?></th>
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
									if ($val['User']['delete_status'] == 0) {

										if ($val['User']['user_type'] == 1) {
											echo $this->Html->link(
												$val['User']['name'],
												array(
													'controller' => 'user',
													'action'     => 'view',
													base64_encode($val['User']['id'])
												)
											);
										} else if ($val['User']['user_type'] == 2) {
											echo $this->Html->link(
												$val['User']['name'],
												array(
													'controller' => 'reseller',
													'action'     => 'view',
													base64_encode($val['User']['id'])
												)
											);
										} else {
											echo $val['User']['name'];
										}
									} else {
										echo $val['User']['name'];
									}
								?>
							</td>
							<td align="center">
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
							<td class="hidden-phone"><?php echo $val['Recharge']['phone_number']; ?></td>
							<td align="center"><?php echo 'Bs. ' . $val['Recharge']['amount']; ?></td>
							<td>
								<?php
									echo $val['Recharge']['recharge_date'];
								?>
							</td>
							<td class="hidden-phone"><?php echo $val['Recharge']['response_message']; ?></td>
							<td>
								<?php
									if ($this->Session->read('admin_type') == 3 || $this->Session->read('admin_recharge') == 1) {
										echo $this->html->link(
											__('<i class="icon-edit icon-black"></i></i><span class="hidden-phone">Retry</span>'),
											array(
												'controller' => 'recharge',
												'action'     => 'retry',
												base64_encode($val['Recharge']['id'])
											),
											array(
												'class'      => 'btn btn-small',
												'escape'     => false
											)
										);
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
		</div>
	</div>
</div>
