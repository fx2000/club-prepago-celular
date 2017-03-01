<?php
/**
 * Recharge status view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Recharge
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Check Recharge in Mobile Operator'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'recharge',
							'action'     => 'check_status',
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Transaction ID'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Recharge.merchant_txn_id',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'merchant_txn_id',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Transaction ID')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('merchant_txn_id');
							f1.add( Validate.Presence);
						</script>
					</div>
					<div class="form-actions">
					  <?php
					  	echo $this->Form->Submit(
					  		__('Submit'),
					  		array('class' => 'btn btn-primary')
						);
					?>
					</div>
				</div>
			</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>