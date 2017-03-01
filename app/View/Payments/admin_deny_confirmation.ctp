<?php
/**
 * Payment rejection view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Payments
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<style>
	textarea {
		resize:none;
	}
</style>
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
					__('Payment Notifications'),
					array(
						'controller' => 'Payments',
						'action'     => 'payment_notifications'
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Deny Payment Notification'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'payments',
							'action'     => 'deny/' . $id
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="textarea"><?php echo __('Reason For Rejection'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Payment.denial_reason',
								array(
									'type'        => 'textarea',
									'class'       => 'input-large',
									'id'          => 'denial_reason',
									'div'         => false,
									'label'       => false,
									'placeholder' => __('Enter a reason for rejecting the notification')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('denial_reason');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="form-actions">
					<?php echo $this->Form->Submit(__('Deny'), array('class' => 'btn btn-danger')); ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
