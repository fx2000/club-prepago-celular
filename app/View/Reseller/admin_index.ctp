<?php
/**
 * Main reseller view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Reseller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
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

		var datatableInfo = getCookie('ClubPrepago_<?php echo strtolower(urlencode($this->params['pass'][0])); ?>');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}
</script>
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
			<h2><i class="icon-user"></i><?php echo __(' Resellers'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Resellerdatatable">
				<thead>
			 		<tr>
						<th><?php echo __('ID'); ?></th>
						<th><?php echo __('Name'); ?></th>
						<th class="hidden-phone "><?php echo __('Email'); ?></th>
						<th class="hidden-phone "><?php echo __('Sponsor'); ?></th>
						<th class="hidden-phone "><?php echo __('Discount Rate'); ?></th>
						<th class="hidden-phone "><?php echo __('Registered'); ?></th>
						<th class="hidden-phone "><?php echo __('Status'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
		 		<tbody>
					<?php
						if (!empty($userdata)) {
							
							foreach ($userdata as $val) {
					?>
					<tr>
						<td>
							<?php 
								$remaining = 6 - strlen($val['Reseller']['id']);
								$membershipId = '';
								
								for ($i = 0; $i < $remaining; $i++) {
									$membershipId .= '0';
								}
								$membershipId .= $val['Reseller']['id'];
								echo $membershipId;
							?>
						</td>
						<td>
							<?php 
								if ($val['Reseller']['delete_status'] == 0) {
									echo $this->Html->link(
										$val['Reseller']['name'],
										array(
											'controller' => 'reseller',
											'action'     => 'view',
											base64_encode($val['Reseller']['id'])
										)
									);
								}
								else {
									echo $val['Reseller']['name'];
								}
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Reseller']['email']; ?></td>
						<td class="hidden-phone"><?php echo $val['Sponsor']['name']; ?></td>
						<td class="hidden-phone"><?php echo $val['Reseller']['discount_rate']; ?></td>
						<td class="hidden-phone">
							<?php
								echo ($val['Reseller']['registered'] != '0000-00-00 00:00:00') ? date('Y-m-d H:i:s', strtotime($val['Reseller']['registered'])) : 'N/A';
							?>
						</td>
						<td class="hidden-phone">
							<?php 
								if ($val['Reseller']['status'] == 1) {
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
										'controller' => 'reseller',
										'action'     => 'edit',
										base64_encode($val['Reseller']['id'])
									),
									array(
										'class'      => 'btn btn-small',
										'escape'     => false
									)
								);

								if ($Admindata['Admin']['type'] != 1) {
									echo $this->html->link(
										__('<i class="icon-trash icon-black"></i></i><span class="hidden-phone"> Delete</span>'),
										array(
											'controller' => 'reseller',
											'action'     => 'delete',
											base64_encode($val['Reseller']['id'])
										),
										array(
											'class'      => 'btn btn-small del_rec',
											'escape'     => false
										)
									);
								}

								if ($Admindata['Admin']['type'] == 2 || $Admindata['Admin']['type'] == 3) {
									echo $this->html->link(
										__('<i class="icon-user icon-black"></i></i><span class="hidden-phone"> Account</span>'),
										array(
											'controller' => 'reseller',
											'action'     => 'account',
											base64_encode($val['Reseller']['id'])
										),
										array(
											'class'      => 'btn btn-small',
											'escape'     => false
										)
									);
								}
								echo $this->html->link(
									__('<i class="icon-th icon-black"></i></i><span class="hidden-phone"> Transactions</span>'),
									array(
										'controller' => 'reseller',
										'action'     => 'transactions',
										base64_encode($val['Reseller']['id'])
									),
									array(
										'class'      => 'btn btn-small',
										'escape'     => false
									)
								);
								echo $this->html->link(
									__('<i class="icon icon-cart icon-black"></i></i><span class="hidden-phone"> Purchases</span>'),
									array(
										'controller' => 'reseller',
										'action'     => 'purchases',
										base64_encode($val['Reseller']['id'])
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
			<?php
				if ($Admindata['Admin']['type'] != 1) {
			?>
			<br/><br/>
			<div class="box-content">
				<?php
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'reseller',
								'action'     => 'export'
							),
							'name'  => 'frm_export',
							'class' => 'form-horizontal'
						)
					);
					echo $this->Form->hidden(
						'Reseller.data',
						array(
							'value' => '',
							'id'    => 'info'
						)
					);
					echo $this->html->link(
						__('<i class="icon-download-alt icon-white"></i><span> Export</span> '),
						'javascript:void(0);',
						array(
							'class'   => 'btn btn-primary',
							'escape'  => false,
							'onclick' => 'Export()'
						)
					);
				?>
			</div>
			<?php
				}
			?>
	</div>
</div>
</div>
