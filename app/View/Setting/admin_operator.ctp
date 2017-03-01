<?php
/**
 * Mobile operator settings view
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
			<h2><i class="icon-user"></i><?php echo __(' Mobile Operator Status'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable">
				<thead>
					<tr>
						<th><?php echo __('Mobile Operator'); ?></th>
						<th><?php echo __('Status'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
			 	<tbody>
					<?php
						if (!empty($this->request->data)) {
							foreach ($this->request->data as $val) {
					?>
					<tr>
						<td><?php echo $val['Operator']['name']; ?></td>
						<td>
							<?php
								if($val['Operator']['status'] == 1) {
									echo __("<span class='label label-success'>ON</span>");
								} else {
									echo __("<span class='label label-warning'>OFF</span>");
								}
							?>
						</td>
						<td class="center">
							<?php
								if ($val['Operator']['status'] == 0) {
									echo $this->html->link(
										__('<i class="icon-edit icon-black"></i><span>ON</span>'),
										array(
											'controller' => 'setting',
											'action'     => 'operator_change',
											base64_encode($val['Operator']['id']),
											1
										),
										array(
											'class'      => 'btn btn-small',
											'escape'     => false
										)
									);
								}

								if ($val['Operator']['status'] == 1) {
									echo $this->html->link(
										__('<i class="icon-trash icon-black"></i></i><span>OFF</span>'),
										array(
											'controller' => 'setting',
											'action'     => 'operator_change',
											base64_encode($val['Operator']['id']),
											0
										),
										array(
											'class'      => 'btn btn-small',
											'escape'     => false
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
			</table>
		</div>
	</div>
</div>
