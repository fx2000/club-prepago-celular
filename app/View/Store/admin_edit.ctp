<?php
/**
 * Edit store view
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @package       app.View.Store
 * @since         Club Prepago Celular(tm) v 1.1.0
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
					__('View Stores'),
					array(
						'controller' => 'store',
						'action'     => 'index'
					)
				);
			?>
		</li>
	</ul>
</div>
<?php if ($this->Session->read('alert') != '') { ?>
	<div class="alert <?php echo ($this->Session->read('success') == 1) ? 'alert-success' : 'alert-error' ?>">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>
			<?php
				echo $this->Session->read('alert');
				$_SESSION['alert'] = '';
			?>
		</strong>
	</div>
<?php } ?>
<div class="row-fluid ">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __('Edit Store'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'store',
							'action'     => 'edit'
						),
						'class' => 'form-horizontal'
					)
				);
				echo $this->Form->hidden(
					'Store.id',
					array(
						'value' => base64_decode($this->request->params['pass'][0])
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Name'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Store.name',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'name',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Store Name')
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
					<label class="control-label"><?php echo __('Type'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio" name="data[Store][type]" id="type1" value="1"
								<?php echo ($this->data['Store']['type'] == 1) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 1);">
									<?php echo __('Restaurantes - Comidas - Bebidas'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[Store][type]" id="type2" value="2"
								<?php echo ($this->data['Store']['type'] == 2) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 2);">
									<?php echo __('Productos - Servicios'); ?>
						</label>
					</div>
				</div>
        <div class="control-group">
					<label class="control-label"><?php echo __('Address'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Store.address',
								array(
									'type'                => 'text',
									'class'               => 'input-large ',
									'id'                  => 'address',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 500,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Address')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('address');
							f1.add(Validate.Presence);
						</script>
					</div>
				</div>
        <div class="control-group">
					<label class="control-label"><?php echo __('Country'); ?></label>
					<div class="controls">
						<?php
							$countryoptions = $this->requestAction('setting/GetCountries');
							echo $this->Form->select(
								'Store.country_id',
								$countryoptions,
								array(
									'empty'               => __('Select a country'),
									'id'                  => 'country_id',
									'div'                 => false,
									'label'               => false,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Country')
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
					<label class="control-label"><?php echo __('Description'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Store.description',
								array(
									'type'                => 'text',
									'class'               => 'input-large ',
									'id'                  => 'description',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 200,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Description')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('description');
							f1.add(Validate.Presence);
						</script>
					</div>
				</div>
        <div class="control-group">
					<label class="control-label"><?php echo __('Email'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Store.email',
								array(
									'type'                => 'email',
									'class'               => 'input-large ',
									'id'                  => 'type',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 20,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Email')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('email');
							f1.add(Validate.Presence);
						</script>
					</div>
				</div>
        <div class="control-group">
					<label class="control-label"><?php echo __('Phone Number'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Store.phone_number',
								array(
									'type'                => 'text',
									'class'               => 'input-large ',
									'id'                  => 'type',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 8,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Type')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('type');
							f1.add(Validate.Presence);
						</script>
					</div>
				</div>
        <div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Store.status',
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
					<?php
						echo $this->Form->Submit(
							'Submit',
							array(
								'class' => 'btn btn-primary'
							)
						);
					?>
				</div>
			</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
