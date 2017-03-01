<?php
/**
 * Edit sponsors view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Sponsor
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
		<li>/</li>
		<li>
			<?php
				echo $this->Html->link(
					__('View Sponsors'),
					array(
						'controller' => 'sponsor',
						'action'     => 'admin_index'
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
			<h2><i class="icon-list-alt"></i><?php echo __(' View Sponsor'); ?></h2>
		</div>
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
					'Sponsor.id',
					array(
						'value' => base64_decode($this->request->params['pass'][0])
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Sponsor ID'); ?></label>
					<div class="controls">
						<?php 
							$remaining = 6 - strlen($this->data['Sponsor']['id']);
							$membershipId = '';
							
							for ($i = 0; $i < $remaining; $i++) {
								$membershipId .= '0';
							}
							$membershipId .= $this->data['Sponsor']['id'];
							echo $this->Form->input(
								'Sponsor.id_demo',
								array(
									'type'      => 'text',
									'class'     => 'input-large',
									'id'        => 'id_demo',
									'div'       => false,
									'label'     => false,
									'maxlength' => 100,
									'readonly'  => true,
									'value'     => $membershipId
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
								'Sponsor.name',
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
								'Sponsor.tax_id',
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
								'Sponsor.email',
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
								'Sponsor.address',
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
								'Sponsor.city',
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
								'Sponsor.state',
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
								'Sponsor.country_id',
								$countryoptions,
								array(
									'empty'               => __('Select a country'),
									'id'                  => 'country_id',
									'div'                 => false,
									'label'               => false,
									'readonly'            => true,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Country'),
									'value'               => $this->data['Sponsor']['country_id']
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
								'Sponsor.phone_number',
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
							var f1 = new LiveValidation('phone_number');

							f1.add( Validate.Presence);
							f1.add( Validate.Phone_settingValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Sponsor.status',
								array(
									'type'            => 'checkbox',
									'class'           => 'iphone-toggle',
									'id'              => 'status',
									'div'             => false,
									'label'           => false,
									'readonly'            => true,
									'data-no-uniform' => 'true',
									'checked'         => $this->data['Sponsor']['status']
								)
							);
						?>
					</div>
				</div>
			</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
