<?php
/**
 * Main staff view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Staff
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
			<h2><i class="icon-user"></i><?php echo __(' Staff Members'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Staffdatatable">
				<thead>
					<tr>
						<th><?php echo __('Name'); ?></th>
						<th class="hidden-phone"><?php echo __('Username'); ?></th>
						<th><?php echo __('Role'); ?></th>
						<th class="hidden-phone"><?php echo __('Recharger'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
			 	<tbody>
					<?php
						if (!empty($userdata)) {
							
							foreach ($userdata as $val) {
					?>
					<tr>
						<td><?php echo $val['Admin']['name']; ?></td>
						<td class="hidden-phone"><?php echo $val['Admin']['username']; ?></td>
						<td>
							<?php
								if ($val['Admin']['type'] == 1) {
									echo __("Support / Customer Service");
								} else if ($val['Admin']['type'] == 2) {
									echo __("Supervisor");
								} else if ($val['Admin']['type'] == 3) {
									echo __("Manager");
								}
							?>	
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Admin']['generate_recharge_access'] == 1) {
									echo __('Yes');
								} else {
									echo __('No');
								}
							?>
						</td>
						<td class="center">
							<?php
								echo $this->html->link(
									__('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span>'),
									array(
										'controller' => 'staff',
										'action'     => 'edit',
										base64_encode($val['Admin']['id'])
									),
									array(
										'class'      => 'btn btn-small',
										'escape'     => false
									)
								);
								
								if ($this->Session->read('admin_type') == 3) {
									echo $this->html->link(
										__('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">Delete</span>'),
										array(
											'controller' => 'staff',
											'action'     => 'delete',
											base64_encode($val['Admin']['id'])
										),
										array(
											'class'      => 'btn btn-small del_rec',
											'escape'     => false
										)
									);
								}
								echo $this->html->link(
									__('<i class="icon-edit icon-black"></i><span class="hidden-phone">Change Password</span>'),
									array(
										'controller' => 'staff',
										'action'     => 'change_password',
										base64_encode($val['Admin']['id'])
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
</div>
