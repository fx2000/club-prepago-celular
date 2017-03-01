<?php
/**
 * Reward points settings view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Setting
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
			<h2><i class="icon-cog"></i><?php echo __(' Reward Points Settings'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'setting',
							'action'     => 'edit_points'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Points for Signup'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Setting.reward_signup',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'reward_signup',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 10,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Reward points granted for account signup')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('reward_signup');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Points for Refferals'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Setting.reward_referral',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'reward_referral',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 10,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Reward points granted for account referrals')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('reward_referral');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Points per B/. 1.00 Recharge'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Setting.reward_recharge',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'reward_recharge',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 10,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Reward points granted for each dollar successfuly recharged')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('reward_recharge');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Points for Shares Or Likes'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Setting.reward_social',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'reward_social',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 10,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Reward points granted for social media participation')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('reward_social');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid);
						</script>
					</div>
				</div>
				<div class="form-actions">
					<?php
						echo $this->Form->Submit(
							__('Submit'),
							array('class' => 'btn btn-primary')
						);
					?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
