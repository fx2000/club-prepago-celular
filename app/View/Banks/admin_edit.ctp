<?php
/**
 * Edit banks view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Banks
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
					__('View Banks'),
					array(
						'controller' => 'banks',
						'action'     => 'index'
					)
				);
			?>
		</li>
	</ul>
</div>
<?php if ($this->Session->read('alert') != '') { ?>
	<div class="alert <?php echo ($this->Session->read('success') == 1) ? 'alert-success' : 'alert-error' ?>">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>
			<?php 
				echo $this->Session->read('alert');
				$_SESSION['alert'] = '';
			?>
		</strong>
	</div>
<?php } ?>
<div class="row-fluid ">	
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __('Edit Bank'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'banks',
							'action'     => 'edit'
						),
						'class' => 'form-horizontal'
					)
				);
				echo $this->Form->hidden(
					'Bank.id',
					array(
						'value' => base64_decode($this->request->params['pass'][0])
					)
				);
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label"><?php echo __('Name'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Bank.bank_name',
								array(
									'type'                => 'text',
									'class'               => 'input-large',
									'id'                  => 'bank_name',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Bank Name')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('name');
							f1.add( Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Account Number'); ?></label>
					<div class="controls">
						<?php
							echo $this->Form->input(
								'Bank.account_number',
								array(
									'type'                => 'text',
									'class'               => 'input-large ',
									'id'                  => 'account_number',
									'div'                 => false,
									'label'               => false,
									'maxlength'           => 100,
									'data-rel'            => 'tooltip',
									'data-original-title' => __('Account Number')
								)
							);
						?>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('name');
							f1.add(Validate.Presence);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Account Type'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio" name="data[Bank][account_type]" id="type1" value="1"
								<?php echo ($this->data['Bank']['account_type'] == 1) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 1);">
									<?php echo __('Corriente'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[Bank][account_type]" id="type2" value="2"
								<?php echo ($this->data['Bank']['account_type'] == 2) ? 'checked=true' : '' ?>
								onclick="ShowField(this.checked, 2);">
									<?php echo __('Ahorros'); ?>
						</label>
					</div>
				</div>
				<div class="form-actions">
					<?php
						echo $this->Form->Submit(
							'Submit',
							array(
								'class' => 'btn btn-primary'
							)
						);
					?>
				</div>
			</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>