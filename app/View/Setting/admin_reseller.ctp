<?php
/**
 * Fees and Discounts settings view
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
			<h2><i class="icon-cog"></i><?php echo __(' Fees and Discounts'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'setting',
							'action'     => 'reseller'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __(' Default Reseller Discount'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:left">
								<?php
									echo $this->Form->input(
										'Setting.discount_rate',
										array(
											'type'                => 'text',
											'class'               => 'input-small',
											'id'                  => 'discount_rate',
											'div'                 => false,
											'label'               => false,
											'maxlength'           => '10',
											'data-rel'            => 'tooltip',
											'data-original-title' => __('Default discount percentage for resellers')
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">%</span>
						</div>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('discount_rate');

							f1.add( Validate.Presence);
							f1.add( Validate.Percentagecheck);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><strong><?php echo __('Credit Card Fees'); ?></strong></label>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Percentage of Transaction'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:left">
								<?php
									echo $this->Form->input(
										'Setting.credit_card_fee_percent',
										array(
											'type'                => 'text',
											'class'               => 'input-small',
											'id'                  => 'credit_card_fee_percent',
											'div'                 => false,
											'label'               => false,
											'maxlength'           => '10',
											'data-rel'            => 'tooltip',
											'data-original-title' => __('Percentage of gross transaction value charged by the payment processor')
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">%</span>
						</div>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('credit_card_fee_percent');

							f1.add( Validate.Presence);
							f1.add( Validate.Percentagecheck);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Fixed Transaction Fee'); ?></label>
					<div class="controls">
						<div class="input-append">
							<div style="float:right">
								<?php
									echo $this->Form->input(
										'Setting.credit_card_fee_fixed',
										array(
											'type'                => 'text',
											'class'               => 'input-small',
											'id'                  => 'credit_card_fee_fixed',
											'div'                 => false,
											'label'               => false,
											'maxlength'           => '10',
											'data-rel'            => 'tooltip',
											'data-original-title' => __('Fixed fee charged by the payment processor for each credit card transaction')
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">Bs. </span>
						</div>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('credit_card_fee_fixed');

							f1.add( Validate.Presence);
							f1.add( Validate.Percentagecheck);
						</script>
					</div>
				</div>
				<div class="form-actions">
					<?php echo $this->Form->Submit(__('Submit'), array('class'=>'btn btn-primary')); ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
