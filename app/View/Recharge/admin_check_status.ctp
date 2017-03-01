<?php
/**
 * Recharge status view (form)
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Recharge
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

// Load Google Maps Helper
$this->Html->script("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false);
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCgSi77LX23K8iN7LgslWWDvSOojLnWfNY', false);

// Get Longitude
if (!empty($rechageStatus['Recharge']['x'])) {
	$longitude = $rechageStatus['Recharge']['x'];
} else {
	$longitude = '0.000000';
}

// Get Latitude
if (!empty($rechageStatus['Recharge']['y'])) {
	$latitude = $rechageStatus['Recharge']['y'];
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
	<div class="box span6">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Recharge Status'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'recharge',
							'action'     => 'generateNewRecharge'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Transaction ID'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Recharge.merchant_txn_id',
								array(
									'type'     => 'text',
									'class'    => 'input-large',
									'id'       => 'merchant_txn_id',
									'div'      => false,
									'label'    => false,
									'disabled' => true
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Phone Number'); ?></label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" >
							<?php echo $rechageStatus['Recharge']['phone_number']; ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Mobile Operator'); ?></label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" >
							<?php echo $rechageStatus['Operator']['name']; ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">
						<?php
							if ($rechageStatus['Recharge']['user_type'] == 1) {
								echo __('User');
							} else if ($rechageStatus['Recharge']['user_type'] == 2) {
								echo __('Reseller');
							}
						?>
					</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" >
							<?php echo $rechageStatus['User']['name']; ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Amount'); ?></label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" >
							<?php echo $rechageStatus['Recharge']['amount']; ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Date & Time'); ?></label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" >
							<?php echo date('Y-m-d H:i:s', strtotime($rechageStatus['Recharge']['recharge_date'])); ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							if ($rechageStatus['Recharge']['status'] == 1) {
						?>
								<span class="label label-success"><?php echo __(' Successful '); ?></span>
						<?php
							} else if ($rechageStatus['Recharge']['status'] == 0) {
						?>
								<span class="label label-important"><?php echo __(' Failed '); ?></span>
						<?php
							} else if ($rechageStatus['Recharge']['status'] == 2) {
						?>
								<span class="label label-important"><?php echo __(' Replaced '); ?></span>
						<?php
							}
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Original Response message'); ?></label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" >
							<?php echo $rechageStatus['Recharge']['response_message']; ?>
						</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('TrxEngine Status'); ?></label>
					<div class="controls">
						<?php
							if ($trxStatus == 0) {
						?>
								<span class="label label-success"><?php echo __(' Successful '); ?></span>
						<?php
							} else {
						?>
								<span class="label label-important"><?php echo __(' Failed '); ?></span>
						<?php
							}
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('TrxEngine Response'); ?></label>
					<div class="controls">
						<span class="label">
							<?php
								echo $trxResponse;
							?>
						</span>
					</div>
				</div>
			</fieldset>
			<?php echo $this->Form->end(); ?>
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
