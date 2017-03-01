<?php 
/**
 * Bank index view
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
	</ul>
</div>
<?php
	if ($this->Session->read('alert') != '') { ?>
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
			<h2><i class="icon-user"></i><?php echo __(' Banks'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Banksdatatable">
				<thead>
					<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Account Number'); ?></th>
					<th><?php echo __('Account Type'); ?></th>
					<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($bankdata)) {
							
							foreach ($bankdata as $val) {
					?>
					<tr>
						<td><?php echo $val['Bank']['bank_name']; ?></td>
						<td><?php echo $val['Bank']['account_number']; ?></td>
						<td>
							<?php
								if ($val['Bank']['account_type'] == 1) {
									echo 'Corriente';
								} else if ($val['Bank']['account_type'] == 2) {
									echo 'Ahorros';
								} else {
									echo $val['Bank']['account_type'];
								}
							?>
						</td>
						<td class="center">
							<?php
								echo $this->html->link(
									__('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span>'),
									array(
										'controller' => 'banks',
										'action'     => 'edit',
										base64_encode($val['Bank']['id'])
									),
									array(
										'class'  => 'btn btn-small',
										'escape' => false
									)
								);
							?>
							<?php
								if($Admindata['Admin']['type'] != 1) {
									echo $this->html->link(
										__('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">Delete</span>'),
										array(
											'controller' => 'banks',
											'action'     => 'delete',
											base64_encode($val['Bank']['id'])
										),
										array(
											'class'  => 'btn btn-small del_rec',
											'escape' => false
										)
									);
								}
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
</div>
