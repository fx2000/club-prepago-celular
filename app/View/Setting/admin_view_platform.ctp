<?php
/**
 * TrxEngine settings view
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
			<h2><i class="icon-user"></i><?php echo __('TrxEngine Settings'); ?></h2>
		</div>
	<div class="box-content">
		<table class="table table-striped table-bordered bootstrap-datatable Platformdatatable">
			<thead>
				<tr>
					<th><?php echo __('Mobile Operator'); ?></th>
					<th class="hidden-phone"><?php echo __('Product ID'); ?></th>
					<th class="hidden-phone"><?php echo __('Server'); ?></th>
					<th class="hidden-phone"><?php echo __('Port'); ?></th>
					<th class="hidden-phone"><?php echo __('Username'); ?></th>
					<th><?php echo __('Action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if (!empty($operators_credentials)) {
						
						foreach ($operators_credentials as $cred) {
				?>
				<tr>
					<td><?php echo $cred['Operator']['name'];?></td>
					<td class="hidden-phone">
						<?php
							echo $cred['OperatorCredential']['product_id'] != '' ? $cred['OperatorCredential']['product_id'] : __('N/A');
						?>
					</td>
					<td class="hidden-phone">
						<?php
							echo $cred['OperatorCredential']['ip_address'] != '' ? $cred['OperatorCredential']['ip_address'] : __('N/A');
						?>
					</td>
					<td class="hidden-phone">
						<?php
							echo $cred['OperatorCredential']['port'] != '' ? $cred['OperatorCredential']['port'] : __('N/A');
						?>
					</td>
					<td class="hidden-phone">
						<?php
							echo $cred['OperatorCredential']['username'] != '' ? $cred['OperatorCredential']['username'] : 'N/A';
						?>
					</td>
					<td class="center">
						<?php
							echo $this->html->link(
								__('<i class="icon-edit icon-black"></i><span>Edit</span>'),
								array(
									'controller' => 'Setting',
									'action'     => 'edit_platform',
									base64_encode($cred['Operator']['id'])
								),
								array(
									'class'      => 'btn btn-small',
									'escape'     => false
								)
							);
						?>
						<?php
							echo $this->html->link(
								__('<i class="icon-edit icon-black"></i><span>Change Password</span>'),
								array(
									'controller' => 'setting',
									'action'     => 'change_password',
									base64_encode($cred['Operator']['id'])
								),
								array(
									'class'      => 'btn btn-small',
									'escape'     => false
								)
							);
						?>
					</td>
				</tr>	
				<?php
						}
			  		}	
				?>			
			</tbody>   
		<table>
	</div>
</div>
