<?php
/**
 * Resellers assigned to sponsors view
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
		c_value = unescape(c_value.substring(c_start,c_end));
		}
		return c_value;
	}

	function Export() {

		var datatableInfo = getCookie('ClubPrepago_<?php echo strtolower(urlencode($this->params['pass'][0])); ?>');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}
</script>

<!-- "Home" links -->
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
					__('View Sponsors'),
					array(
						'controller' => 'sponsor',
						'action'     => 'index'
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
			<h2><i class="icon-user"></i><?php echo __(' Assigned Resellers'); ?></h2>
		</div>

		<!-- Generate info table -->
		<div style="clear:both"></div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Sponsorresdatatable">
				<thead>
					<tr>
						<th><?php echo __('ID'); ?></th>
						<th><?php echo __('Name'); ?></th>
						<th class="hidden-phone "><?php echo __('Email'); ?></th>
						<th class="hidden-phone "><?php echo __('City'); ?></th>
						<th class="hidden-phone "><?php echo __('Registered'); ?></th>
						<th class="hidden-phone "><?php echo __('Discount'); ?></th>
						<th class=""><?php echo __('Status'); ?></th>
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
								$remaining = 6 - strlen($val['User']['id']);
								$membershipId = '';
								
								for ($i = 0; $i < $remaining; $i++) {
									$membershipId .= '0';
								}
								$membershipId .= $val['User']['id'];
								echo $membershipId;
							?>
						</td>
						<td>
							<?php
								if ($val['User']['delete_status'] == 0) {
									echo $this->Html->link(
										$val['User']['name'],
										array(
											'controller' => 'reseller',
											'action'     => 'view',
											base64_encode($val['User']['id'])
										)
									);
								} else {
									echo $val['User']['name'];
								}
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['User']['email']; ?></td>	
						<td class="hidden-phone"><?php echo $val['User']['city']; ?></td>							
						<td class="hidden-phone">
							<?php
								echo ($val['User']['registered'] != '0000-00-00 00:00:00') ? date('Y-m-d H:i:s', strtotime($val['User']['registered'])) : 'N/A';
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['User']['discount_rate'] . ' %'; ?></td>
						<td class="">
							<?php
								if ($val['User']['status'] == 1) {
									echo __("<span class='label label-success'>Active</span>");
								}
								else {
									echo __("<span class='label label-warning'>Inactive</span>");
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
