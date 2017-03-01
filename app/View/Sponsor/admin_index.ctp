<?php
/**
 * Sponsors view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Sponsor
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>

<!-- File export helper -->
<script>
	function getCookie(c_name) {

		var c_value = document.cookie;
		var c_start = c_value.indexOf(" " + c_name + "=");
		
		if (c_start == -1) {
			c_start = c_value.indexOf(c_name + "=");
		}
		
		if (c_start == -1) {
			c_value = null;
		} else {
			c_start = c_value.indexOf("=", c_start) + 1;
			var c_end = c_value.indexOf(";", c_start);
		
			if (c_end == -1) {
				c_end = c_value.length;
			}
			c_value = unescape(c_value.substring(c_start, c_end));
		}
		return c_value;
	}

	function Export() {

		var datatableInfo = getCookie('SpryMedia_DataTables_clubPrepago_user');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}
</script>

<!-- "Home" link -->
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

<!-- Check if session is valid -->
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

		<!-- Page icon and title -->
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Sponsors'); ?></h2>
		</div>

		<!-- Generate info table -->
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Sponsordatatable">
				<thead>
					<tr>
						<th><?php echo __('ID'); ?></th>
						<th><?php echo __('Name'); ?></th>
						<th class="hidden-phone "><?php echo __('Email'); ?></th>
						<th class="hidden-phone "><?php echo __('Registered'); ?></th>
						<th class="hidden-phone "><?php echo __('Status'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {
							
							foreach($userdata as $val) {
					?>
					<tr>
						<td>
							<?php 
								$remaining = 6 - strlen($val['Sponsor']['id']);
								$membershipId = '';
								
								for ($i = 0; $i < $remaining; $i++) {
									$membershipId .= '0';
								}
								$membershipId .= $val['Sponsor']['id'];
								echo $membershipId;
							?>
						</td>
						<td>
							<?php 
								if ($val['Sponsor']['delete_status'] == 0) {
									echo $this->Html->link(
										$val['Sponsor']['name'],
										array(
											'controller'=>'sponsor',
											'action'=>'view',
											base64_encode($val['Sponsor']['id'])
										)
									);
								}
								else {
									echo $val['Sponsor']['name'];
								}
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Sponsor']['email']; ?></td>								
						<td class="hidden-phone">
							<?php
								echo ($val['Sponsor']['registered'] != '0000-00-00 00:00:00') ? date('Y-m-d H:i:s', strtotime($val['Sponsor']['registered'])) : 'N/A';
							?>
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Sponsor']['status'] == 1) {
									echo __("<span class='label label-success'>Active</span>");
								} else {
									echo __("<span class='label label-warning'>Inactive</span>");
								}
							?>
						</td>
						<td class="center">
							<?php
								echo $this->html->link(
									__('<i class="icon-edit icon-black"></i><span class="hidden-phone"> Edit</span>'),
									array(
										'controller' => 'sponsor',
										'action'     => 'edit',
										base64_encode($val['Sponsor']['id'])),
									array(
										'class'      => 'btn btn-small',
										'escape'     => false
									)
								);
								echo $this->html->link(
									__('<i class="icon-trash icon-black"></i></i><span class="hidden-phone"> Delete</span>'),
									array(
										'controller' => 'sponsor',
										'action'     => 'delete',
										base64_encode($val['Sponsor']['id'])
									),
									array(
										'class'      => 'btn btn-small del_rec',
										'escape'     => false
									)
								);
								echo $this->html->link(
									__('<i class="icon-user icon-black"></i></i><span class="hidden-phone"> Resellers</span>'),
									array(
										'controller' => 'sponsor',
										'action'     => 'resellers',
										base64_encode($val['Sponsor']['id'])
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
			</table>
		</div>
	</div>
</div>
