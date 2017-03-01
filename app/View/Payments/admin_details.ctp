<?php
/**
 * Payment rejection details view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Payments
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

// Load Google Maps Helper
$this->Html->script("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false);
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCgSi77LX23K8iN7LgslWWDvSOojLnWfNY', false);

// Get Longitude
if (!empty($details['Payment']['x'])) {
	$longitude = $details['Payment']['x'];
} else {
	$longitude = '0.000000';
}

// Get Latitude
if (!empty($details['Payment']['y'])) {
	$latitude = $details['Payment']['y'];
} else {
	$latitude = '0.000000';
}

$map_options = array(
	'id'           => 'transaction',
	'width'        => '500px',
	'height'       => '470px',
	'type'         => 'ROADMAP',
	'longitude'    => $longitude,
	'latitude'     => $latitude,
	'markerIcon'   => "https://www.google.com/mapfiles/marker.png",
	'markerShadow' => "https://www.google.com/mapfiles/shadow50.png",
	'localize'     => false,
	'zoom'         => 15,
);

$bankName = $this->requestAction(
	array(
		'controller' => 'Banks',
		'action'     => 'getBankName',
		$details['Payment']['bank_id']
	)
);
$userName = $this->requestAction(
	array(
		'controller' => 'User',
		'action'     => 'getUserName',
		$details['Payment']['user_id']
	)
);

?>
<style>
	textarea {
	resize:none;
}
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
		<li>/</li>
		<li>
			<?php
				echo $this->Html->link(
					__('Payment History'),
					array(
						'controller' => 'Payments',
						'action'     => 'payment_history'
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
	<div class="box span6">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __('Payment Details'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Payment Number'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Payment.id',
								array(
									'type'     => 'text',
									'class'    => 'input-large',
									'id'       => 'id',
									'div'      => false,
									'label'    => false,
									'disabled' => true
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('User or Reseller'); ?></label>
					<div class="controls">
						<?php
							echo @$userName['User']['name'];
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Payment Method'); ?></label>
					<div class="controls">
						<?php
							if ($details['Payment']['payment_method'] == 1) {
								echo __('Bank Deposit');
							} else if ($details['Payment']['payment_method'] == 2) {
								echo __('Credit Card');
							} else {
								echo $details['Payment']['payment_method']; 
							}
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Bank'); ?></label>
					<div class="controls">
						<?php
							echo @$bankName['Bank']['bank_name'];
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Reference'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Payment.reference_number',
								array(
									'type'     => 'text',
									'class'    => 'input-large',
									'id'       => 'reference_number',
									'div'      => false,
									'label'    => false,
									'disabled' => true
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Notification Date'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Payment.notification_date',
								array(
									'type'     => 'text',
									'class'    => 'input-large',
									'id'       => 'notification_date',
									'div'      => false,
									'label'    => false,
									'disabled' => true
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Status Change'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Payment.change_status_date',
								array(
									'type'     => 'text',
									'class'    => 'input-large',
									'id'       => 'change_status_date',
									'div'      => false,
									'label'    => false,
									'disabled' => true
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Payment Amount'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:right">
								<?php
									echo $this->Form->input(
										'Payment.amount',
										array(
											'type'      => 'text',
											'class'     => 'input-small',
											'id'        => 'amount',
											'div'       => false,
											'label'     => false,
											'disabled'  => true
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">B/.</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Tax Deducted'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:right">
								<?php
									echo $this->Form->input(
										'Payment.tax',
										array(
											'type'     => 'text',
											'class'    => 'input-small',
											'id'       => 'tax',
											'div'      => false,
											'label'    => false,
											'disabled' => true
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">B/.</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Fees Paid'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:right">
								<?php
									echo $this->Form->input(
										'Payment.fees',
										array(
											'type'     => 'text',
											'class'    => 'input-small',
											'id'       => 'fees',
											'div'      => false,
											'label'    => false,
											'disabled' => true
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">B/.</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Discount Credit'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:right">
								<?php
									echo $this->Form->input(
										'Payment.discount',
										array(
											'type'     => 'text',
											'class'    => 'input-small',
											'id'       => 'discount',
											'div'      => false,
											'label'    => false,
											'disabled' => true
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">B/.</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Balance Added'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:right">
								<?php
									echo $this->Form->input(
										'Payment.amount_credited',
										array(
											'type'     => 'text',
											'class'    => 'input-small',
											'id'       => 'amount_credited',
											'div'      => false,
											'label'    => false,
											'disabled' => true
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">B/.</span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							if ($details['Payment']['status'] == 1) {
						?>
								<span class="label label-success"><?php echo __(' Approved '); ?></span>
						<?php
							} else if ($details['Payment']['status'] == 0) {
						?>
								<span class="label"><?php echo __(' Pending '); ?></span>
						<?php
							} else if ($details['Payment']['status'] == 2) {
						?>
								<span class="label label-important"><?php echo __(' Denied '); ?></span>
						<?php
							}
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea"><?php echo __('Reason for Denial'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Payment.denial_reason',
								array(
									'type'     => 'textarea',
									'class'    => 'input-large',
									'id'       => 'denial_reason',
									'div'      => false,
									'label'    => false,
									'disabled' => true
								)
							);
						?>
					</div>
				</div>
				<?php
					if ($details['Payment']['status'] == 0) {
				?>
				<div class="control-group">
					<label class="control-label"><?php echo __('Action'); ?></label>
					<div class="controls">
						<?php
							echo $this->html->link(
								__('<i class="icon-ok icon-white"></i><span class="hidden-phone">&nbsp;Approve</span>'),
								array(
									'controller' => 'Payments',
									'action'     => 'approve',
									base64_encode($details['Payment']['id'])
								),
								array(
									'class'      => 'btn btn-small btn-success approve',
									'escape'     => false
								)
							);
						?>
						&nbsp;&nbsp;
						<?php
							echo $this->html->link(
								__('<i class="icon-remove icon-white"></i><span class="hidden-phone">&nbsp;Deny</span>'),
								array(
									'controller' => 'Payments',
									'action'     => 'deny_confirmation',
									base64_encode($details['Payment']['id'])
								),
								array(
									'class'      => 'btn btn-small btn-danger deny',
									'escape'     => false
								)
							);
						?>
					</div>
				</div>
				<?php
					}
				?>
			</fieldset>
		</div>
	</div>
	<div class="box span6">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Location'); ?></h2>
		</div>
		<div>
			<span style="margin-top: 0px;float: center; vertical-align:middle;">
				<?= $this->GoogleMap->map($map_options); ?>
			</span>
		</div>
	</div>
</div>
