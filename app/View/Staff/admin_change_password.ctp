<?php
/**
 * Change staff password view
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
					__('View Staff'),
					array(
						'controller' => 'staff',
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
			<h2><i class="icon-list-alt"></i><?php echo __('Change Password'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'staff',
							'action'     => 'change_password',
							$this->request->params['pass'][0]
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('New Password'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.new_pwd',
								array(
									'type'        => 'password',
									'class'       => 'input-large',
									'id'          => 'new_pwd',
									'div'         => false,
									'label'       => false,
									'placeholder' => 'New Password'
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('new_pwd');

							f1.add( Validate.Presence);
							f1.add( Validate.passwordchange);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Confirm Password'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.confirm_pwd',
								array(
									'type'        => 'password',
									'class'       => 'input-large',
									'id'          => 'confirm_pwd',
									'div'         => false,
									'label'       => false,
									'placeholder' => 'Confirm Password'
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('confirm_pwd');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="form-actions">
					<?php echo $this->Form->Submit('Submit', array('class' => 'btn btn-primary')) ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
