<?php
/**
 * Add new coupon view
 *
 * @copyright     Club Prepago Celular(tm) Project
 * @package       app.View.Coupon
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
					__('View Coupons'),
					array(
						'controller' => 'coupon',
						'action'     => 'index'
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Add Coupon'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'coupon',
							'action'     => 'add'
						),
						'class' => 'form-horizontal',
						'type'  => 'file'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Store'); ?></label>
					<div class="controls">
						<?php
							$storeoptions = $this->requestAction('setting/getStores');
							echo $this->Form->select(
								'Coupon.store_id',
								$storeoptions,
								array(
									'empty'               => __('Select a store'),
									'id'                  => 'store_id',
									'div'                 => false,
									'label'               => false,
									'value'               => '1',
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Store')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('store_id');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Coupon Type'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio" name="data[Coupon][coupon_type]" id="type1" value="1"
								<?php echo ($this->data['Coupon']['coupon_type'] == 1) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 1);">
									<?php echo __('Restaurantes - Comidas - Bebidas'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[Coupon][coupon_type]" id="type2" value="2"
								<?php echo ($this->data['Coupon']['coupon_type'] == 2) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 2);">
									<?php echo __('Productos - Servicios'); ?>
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Points'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Coupon.points',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'points',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 50,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Cost of the coupon in points')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('points');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Amount'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Coupon.amount',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'value',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Amount')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('value');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValidFloat);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Description'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Coupon.description',
								array(
									'type'                => 'textarea',
									'class'               => 'input-large',
									'id'                  => 'description',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 255,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Description to show in app')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('description');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Cant'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Coupon.cant',
								array(
									'type'                => 'number',
									'class'               => 'input-large',
									'id'                  => 'cant',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 5,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Cant')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('cant');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Due Date'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Coupon.due_date',
								array(
									'type'                => 'date',
									'class'               => 'input-short',
									'id'                  => 'due_date',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 10,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Due Date')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('due_date');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Image'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->file(
								'Coupon.img',
								array(
									'class'               => 'input-large',
									'id'                  => 'img',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Upload an image')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('img');

							f1.add( Validate.Presence);
							f1.add( Validate.ExtCheck);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Coupon.status',
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
					<?php echo $this->Form->Submit(__('Submit'), array('class'=>'btn btn-primary')); ?>
				</div>
			</fieldset>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
