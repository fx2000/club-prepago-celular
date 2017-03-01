<?php
/**
 * Edit reward view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Reward
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
					__('View Rewards'),
					array(
						'controller' => 'reward',
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
	<div class="box span6">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __(' Edit Reward'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'reward',
							'action'     => 'edit'
						),
						'class' => 'form-horizontal',
						'type'  => 'file'
					)
				);
				echo $this->Form->hidden('Reward.id', array('value'=>base64_decode(@$this->params['pass']['0']))); ?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Value'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reward.points',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'points',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 50,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Cost of the reward in points')
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
					<label class="control-label"><?php echo __('Recharge Amount'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reward.value',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'value',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Recharge amount')
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
								'Reward.description',
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
					<label class="control-label"><?php echo __('Image'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->file(
								'Reward.img',
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

							f1.add( Validate.ExtCheck);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Status'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Reward.status',
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
					echo $this->Html->image('rewards/' . $this->data['Reward']['image'], array('width' => '350'));
				?>
			</span>
		</div>
	</div>
</div>
