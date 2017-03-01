<?php
/**
 * Add new staff view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Staff
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<script>
	function ShowField(checkVal,val) {
		if (checkVal == true && val == 1) {
			$('#ShowAccess1').show();
		}

		if (checkVal == true && val == 2) {
			$('#ShowAccess1').hide();
			$('#uniform-generate_recharge_access1 span').attr('class','checked');
			$('#uniform-generate_recharge_access2 span').attr('class','');
			document.getElementById('generate_recharge_access1').checked='true';
		}
	}
</script>
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
<div class="alert <?php echo ($this->Session->read('success') == 1) ? 'alert-success':'alert-error' ?>">
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Add Staff Member'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'staff',
							'action'     => 'add'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Name'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.name',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'fname',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Name')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('fname');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Email'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.email',
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
					<label class="control-label"><?php echo __('Username'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.username',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'username',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Username')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('username');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Password'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.password',
								array(
									'type'                => 'password',
									'class'               => 'input-large',
									'id'                  => 'password',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Password')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('password');

							f1.add( Validate.Presence);
							f1.add( Validate.len_password);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Confirm Password'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.confirm_password',
								array(
									'type'                => 'password',
									'class'               => 'input-large',
									'id'                  => 'confirm_password',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Confirm Password')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('confirm_password');

							f1.add( Validate.Presence);
							f1.add( Validate.password);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Role'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio" name="data[Admin][type]" id="type3" value="3"
								<?php echo ($this->data['Admin']['type'] == 3) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 2);">
									<?php echo __('Manager'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[Admin][type]" id="type2" value="2"
								<?php echo ($this->data['Admin']['type'] == 2) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 2);">
									<?php echo __('Supervisor'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[Admin][type]" id="type1" value="1"
								<?php echo ($this->data['Admin']['type'] == 1 || $this->data['Admin']['type'] == '') ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 1);">
									<?php echo __('Support / Customer Service'); ?>
						</label>
					</div>
				</div>
				<div class="control-group" id="ShowAccess1" style="
					<?php
						if ($this->data['Admin']['type'] == 1 || $this->data['Admin']['type'] == '') {
							echo "display:block";
						} else {
							echo "display:none";
						}
					?>
				">
					<div class="control-group">
						<label class="control-label"><?php echo __('Allowed to send recharges'); ?></label>
						<div class="controls">
							<?php
								echo $this->Form->input(
									'Admin.generate_recharge_access',
									array(
										'type'            => 'checkbox',
										'class'           => 'iphone-toggle',
										'id'              => 'generate_recharge_access',
										'div'             => false,
										'label'           => false,
										'data-no-uniform' => 'true',
										'checked'         => 0
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-actions">
					<?php echo $this->Form->Submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
