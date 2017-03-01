<?php
/**
 * Tax settings view
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
			<h2><i class="icon-cog"></i><?php echo __('Taxes'); ?></h2>
		</div>
		<div class="box-content">
			<?php
				echo $this->Form->create(
					'',
					array(
						'url'   => array(
							'controller' => 'setting',
							'action'     => 'tax'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<fieldset>
				<?php
					if (!empty($this->request->data)) {
						foreach ($this->request->data as $Taxes) {
				?>      
				<div class="control-group">
					<label class="control-label"><?php echo __('ITBMS'); ?></label>
					<div class="controls"> 
						<div class="input-append">
							<div style="float:left">
								<?php
									echo $this->Form->input(
										'Country.tax' . $Taxes['Country']['id'],
										array(
											'type'                => 'text',
											'class'               => 'input-small',
											'id'                  => 'tax_' . $Taxes['Country']['id'],
											'div'                 => false,
											'label'               => false,
											'maxlength'           => '10',
											'data-rel'            => 'tooltip',
											'data-original-title' => __('ITBMS percentage'),
											'value'               => number_format($Taxes['Country']['tax'],2)
										)
									);
								?>
							</div>
							<span class="add-on" style="float:left">%</span>
						</div>
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('tax_<?php echo $Taxes['Country']['id']; ?>');
							f1.add( Validate.Presence);
							f1.add( Validate.Percentagecheck);
						</script>
					</div>
				</div>
				<?php
						}
					}
				?>
				<div class="form-actions">
					<?php
						echo $this->Form->Submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
