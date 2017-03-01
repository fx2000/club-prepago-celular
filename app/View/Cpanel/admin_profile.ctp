<?php
/**
 * Profile view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Cpanel
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
					__('Profile'),
					array(
						'controller' => 'cpanel',
						'action'     => 'profile'
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Profile'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'cpanel',
							'action'     => 'admin_profile'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Name'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.name',
								array(
									'type'        => 'text',
									'class'       => 'input-large ',
									'id'          => 'name',
									'div'         => false,
									'label'       => false,
									'placeholder' => __('Name')
								)
							);
						?>
						<script language = "javascript" type = "text/javascript">
							var f1 = new LiveValidation('name');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Username'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.username',
								array(
									'type'        => 'text',
									'class'       => 'input-large ',
									'id'          => 'username',
									'div'         => false,
									'label'       => false,
									'placeholder' => __('Username')
								)
							);
						?>
						<script language = "javascript" type = "text/javascript">
							var f1 = new LiveValidation('username');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="textarea2"><?php echo __('Email'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Admin.email',
								array(
									'type'        => 'text',
									'class'       => 'input-large',
									'id'          => 'email',
									'div'         => false,
									'label'       => false,
									'placeholder' => __('Email')
								)
							);
						?>
						<script language = "javascript" type = "text/javascript">
							var f1 = new LiveValidation('email');
							f1.add( Validate.Presence);
							f1.add( Validate.Email);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Language'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio"  name="data[Admin][language]" id="lang1" value="1"
								<?php echo ($this->data['Admin']['language'] == 1) ? 'checked=true' : '' ?>
									onclick="ShowField(this.checked,1);">
										<?php echo __('English'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio"  name="data[Admin][language]" id="lang2" value="2"
								<?php echo ($this->data['Admin']['language'] == 2) ? 'checked=true' : '' ?>
									onclick="ShowField(this.checked,2);">
										<?php echo __('Spanish'); ?>
						</label>
					</div>
				</div>
				<div class="form-actions">
					<?php
						echo $this->Form->Submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
