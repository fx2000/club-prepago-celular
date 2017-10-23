<?php
/**
 * Add reseller view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Reseller
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
			$_SESSION['alert'] ='';
		?>
	</strong>
</div>
<?php
	}
?>
<div class="row-fluid ">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __(' Add Reseller'); ?></h2>
		</div>
		<div class="box-content">
			<?php echo $this->Form->create('', array('class' => 'form-horizontal')); ?>
			<fieldset>
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
					<label class="control-label"><?php echo __('Cedula'); ?></label>
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
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Cédula')
								)
							);
						?>
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
									'type'=>'textarea',
									'class'=> 'input-large',
									'id'                  => 'address',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 255,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Address')
								)
							);
						?>
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
									'data-rel'            => 'tooltip',
									'data-original-title' => __('City')
								)
							);
						?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Estado'); ?></label>
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
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Estado')
								)
							);
						?>
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
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Country'),
									'value'               => 1
								)
							);
						?>
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
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Phone number')
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
							$sponsoroptions = $this->requestAction('sponsor/GetSponsor');
							echo $this->Form->select(
								'Reseller.sponsor_id',
								$sponsoroptions,
								array(
									'empty'               => 'Select',
									'id'                  => 'sponsor_id',
									'div'                 => false,
									'label'               => false,
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
										'Reseller.discount_percentage',
										array(
											'type'                => 'text',
											'class'               => 'input-large',
											'id'                  => 'discount_percentage',
											'div'                 => false,
											'label'               => false,
											'maxlength'           => 100,
											'data-rel'            => 'tooltip',
											'data-original-title' => __('Discount percentage if different from the default value')
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">%</span>
						</div>
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
									'data-no-uniform' => 'true',
									'checked'         => 1
								)
							);
						?>
					</div>
				</div>
				<div class="form-actions">
					<?php echo $this->Form->Submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
				</div>
			</fieldset>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
