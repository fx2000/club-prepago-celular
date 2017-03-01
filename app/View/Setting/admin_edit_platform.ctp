<?php
/**
 * Edit TrxEngine settings view
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
		<li>/</li>
		<li>
			<?php
				echo $this->Html->link(
					__('View TrxEngine Settings'),
					array(
						'controller' => 'setting',
						'action'     => 'view_platform'
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Edit TrxEngine Settings'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'=>array(
							'controller' => 'Setting',
							'action'     => 'edit_platform',
							$this->request->params['pass'][0]
						),
						'class'          => 'form-horizontal',
						'enctype'        => 'multipart/form-data'
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Mobile Operator'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Operator.name',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'readonly'            => 'readonly',
									'id'                  => 'operator',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Mobile Operator')
								)
							);
						?>
						<?php
							echo $this->Form->input(
								'OperatorCredential.operator_id',
								array(
									'type'                => 'hidden',
									'value'               => $this->request->data['Operator']['id'],
									'class'               => 'input-large',
									'readonly'            => 'readonly',
									'id'                  => 'operator_id',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Mobile Operator')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('operator');

							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Product ID'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'OperatorCredential.product_id',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'product_id',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Product ID')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('product_id');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Server'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'OperatorCredential.ip_address',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'ipaddress',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Server IP address')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('ipaddress');

							f1.add( Validate.Presence);
							f1.add(Validate.ValidIP)
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Port'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'OperatorCredential.port',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'port',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Port number')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('port');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValid)
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Merchant_ID'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'OperatorCredential.username',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'username',
									'div'                 => false,
									'label'               => false,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Merchant_ID value')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('username');
							
							f1.add( Validate.Presence);
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
