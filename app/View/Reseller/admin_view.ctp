<?php
/**
 * View reseller view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Reseller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

// Load Google Maps Helper
$this->Html->script("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false);
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCgSi77LX23K8iN7LgslWWDvSOojLnWfNY', false);

// Get Longitude
if (!empty($this->data['Reseller']['x'])) {
	$longitude = $this->data['Reseller']['x'];
} else {
	$longitude = '0.000000';
}

// Get Latitude
if (!empty($this->data['Reseller']['y'])) {
	$latitude = $this->data['Reseller']['y'];
} else {
	$latitude = '0.000000';
}

$map_options = array(
	'id'           => 'reseller',
	'width'        => '350px',
	'height'       => '350px',
	'type'         => 'ROADMAP',
	'longitude'    => $longitude,
	'latitude'     => $latitude,
	'markerIcon'   => "https://www.google.com/mapfiles/marker.png",
	'markerShadow' => "https://www.google.com/mapfiles/shadow50.png",
	'localize'     => false,
	'zoom'         => 15,
);
?>

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
					__('View Resellers'),
					array(
						'controller' => 'reseller',
						'action'     => 'admin_index'
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
	<div class="box span6">

		<!-- Page icon and title -->
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __(' View Reseller'); ?></h2>
		</div>

		<!-- Generate Form -->
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'class'  => 'form-horizontal',
						'method' => 'POST'
					)
				);
				echo $this->Form->hidden(
					'Reseller.id',
					array('value' => base64_decode($this->request->params['pass'][0]))
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __(' ID'); ?></label>
					<div class="controls">
						<?php 
							$remaining = 6 - strlen($this->data['Reseller']['id']);
							$membershipId = '';
							for ($i = 0; $i < $remaining; $i++) {
								$membershipId .= '0';
							}
							$membershipId .= $this->data['Reseller']['id'];
							echo $this->Form->input(
								'Reseller.id_demo',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'id_demo',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => 'Membership Number',
									'value'               => $membershipId,
									'readonly'            => true
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('id_demo');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Name'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.name',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'name',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Name')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('name');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Cedula / Passport'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.tax_id',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'tax_id',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 25,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Cedula or Passport number')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('tax_id');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Email'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.email',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'email',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Email address')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('email');

							f1.add( Validate.Presence);
							f1.add( Validate.Email);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Address'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.address',
								array(
									'type'                => 'textarea',
									'class'               => 'input-large',
									'id'                  => 'address',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 255,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Address')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('address');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('City'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.city',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'city',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('City')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('city');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('State / Province'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.state',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'state',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('State or Province')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('state');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Country'); ?></label>
					<div class="controls">
						<?php 
							$countryoptions = $this->requestAction('setting/GetCountries');
							echo $this->Form->select(
								'Reseller.country_id',
								$countryoptions,
								array(
									'empty'               => 'Select',
									'id'                  => 'country_id',
									'div'                 => false,
									'label'               => false,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => 'Country',
									'value'               => $this->data['Reseller']['country_id']
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('country_id');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Phone Number'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.phone_number',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'phone_number',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Phone Number')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('phone_no');

							f1.add( Validate.Presence);
							f1.add( Validate.Phone_settingValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Sponsor'); ?></label>
					<div class="controls">
						<?php 
							$sponsoroptions = $this->requestAction('sponsor/getSponsor');
							echo $this->Form->select(
								'Reseller.sponsor_id',
								$sponsoroptions,
								array(
									'empty'               => 'Select',
									'id'                  => 'sponsor_id',
									'div'                 => false,
									'label'               => false,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Sponsor')
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Discount'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:left">
								<?php
									echo $this->Form->input(
										'Reseller.discount_rate',
										array(
											'type'                => 'text',
											'class'               => 'input-large',
											'id'                  => 'discount_rate',
											'div'                 => false,
											'label'               => false,
											'maxlength'           => 100,
											'readonly'            => true,
											'data-rel'            => 'tooltip',
											'data-original-title' => __('Reseller\'s discount percentage')
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">%</span>
						</div>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('discount_percentage');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Email Verification'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.email_verify',
								array(
									'type'            => 'checkbox',
									'class'           => 'iphone-toggle',
									'id'              => 'email_verify',
									'div'             => false,
									'label'           => false,
									'readonly'        => true,
									'data-no-uniform' => 'true',
									'checked'         => $this->data['Reseller']['email_verify']
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.status',
								array(
									'type'            => 'checkbox',
									'class'           => 'iphone-toggle',
									'id'              => 'status',
									'div'             => false,
									'label'           => false,
									'readonly'        => true,
									'data-no-uniform' => 'true',
									'checked'         => $this->data['Reseller']['status']
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Ban'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reseller.banned',
								array(
									'type'            => 'checkbox',
									'class'           => 'iphone-toggle',
									'id'              => 'banned',
									'div'             => false,
									'label'           => false,
									'readonly'        => true,
									'data-no-uniform' => 'true',
									'checked'         => $this->data['Reseller']['banned']
								)
							);
						?>
					</div>
				</div>
				<div class="form-actions">
					<?php
							echo $this->html->link(
								__('Account'),
								array(
									'controller' => 'reseller',
									'action'     => 'account',
									base64_encode($membershipId)
								),
								array(
									'class'      => 'btn btn-primary',
									'escape'     => false
								)
							);
					?>
				</div>
			</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
	<div class="box span6">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Image'); ?></h2>
		</div>
		<div>
			<span style="margin-top: 0px;float: center; vertical-align:middle;">
				<?php
					if (!empty($this->data['Reseller']['image'])) {
						echo $this->Html->image('resellers/' . $this->data['Reseller']['image'], array('width' => '350'));
					} else {
						echo $this->Html->image('resellers/default.jpg', array('width' => '350'));
					}
				?>
			</span>
		</div>
	</div>
	<div class="box span6">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Signup Location'); ?></h2>
		</div>
		<div>
			<span style="margin-top: 0px;float: center; vertical-align:middle;">
				<?= $this->GoogleMap->map($map_options); ?>
			</span>
		</div>
	</div>
</div>
