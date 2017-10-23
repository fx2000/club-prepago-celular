<?php
/**
 * Inventory warnings view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Inventory
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
					__('View Inventory'),
					array(
						'controller' => 'inventory',
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
	<div class="alert <?php echo ($this->Session->read('success') == 1 )? 'alert-success' : 'alert-error' ?>">
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Inventory Warnings'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'inventory',
							'action'     => 'warning'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<?php
					if (!empty($this->request->data)) {

						foreach ($this->request->data as $Operator) {
				?>
					<div class="control-group">
						<label class="control-label"><?php echo $Operator['Operator']['name'] ?></label>
						<div class="controls">
							<div class="input-append">
								<div style="float:left">
									<?php
										echo $this->Form->input(
											'Operator.min_limit' . $Operator['Operator']['id'],
											array(
												'type'                => 'text',
												'class'               => 'input-large ',
												'id'                  => 'minLimit_' . $Operator['Operator']['id'],
												'div'                 => false,
												'label'               => false,
												'maxlength'           => '50',
												'data-rel'            => 'tooltip',
												'data-original-title' => 'Minimum amount for ' . $Operator['Operator']['name'],
												'value'               => str_replace(".",",",sprintf("%.2f", $Operator['Operator']['minimum_limit']))
											)
										);
									?>
								</div>
							</div>
							<script language="javascript" type="text/javascript">
								var f1 = new LiveValidation('minLimit_<?php echo $Operator['Operator']['id']; ?>');
								f1.add(Validate.Presence);
								f1.add(Validate.NumberValidFloat);
							</script>
						</div>
					</div>
				<?php
						}
					}
				?>
				<div class="form-actions">
					<?php
						echo $this->Form->Submit(__('Submit'), array('class'=>'btn btn-primary'));
					?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
