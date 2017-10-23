<?php
/**
 * User rewards view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.User
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
		
		var datatableInfo = getCookie('ClubPrepago_<?php echo strtolower(urlencode($this->params['pass'][0])); ?>');

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
		<li>/</li>
		<li>
			<?php
				echo $this->Html->link(
					__('View Users'),
					array(
						'controller' => 'user',
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
			<h2><i class="icon-user"></i><?php echo __(' Rewards'); ?></h2>
		</div>

		<!-- Generate info table -->
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Userrewdatatable">
				<thead>
					<tr>
						<th class="hidden-phone"><?php echo __('Reward ID'); ?></th>
						<th class="hidden-phone"><?php echo __('Reward Type'); ?></th>
						<th><?php echo __('Reward Value'); ?></th>
						<th><?php echo __('Points Spent'); ?></th>
						<th><?php echo __('Date & Time'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {
							
							foreach ($userdata as $val) {
					?>
					<tr>
						<td class="hidden-phone">
							<?php 
								$remaining = 6 - strlen($val['Redemption']['reward_id']);
								$membershipId = '';
								
								for ($i = 0; $i < $remaining; $i++) {
									$membershipId .= '0';
								}
								$membershipId .= $val['Redemption']['reward_id'];
								echo $membershipId;
							?>
						</td>
						<td class="hidden-phone">
							<?php
								if ($val['Redemption']['reward_type'] == 1) {
									echo "Recharge";
								} else if ($val['Redemption']['reward_type'] == 2) {
									echo "Customer Support";	
								} else if ($val['Redemption']['reward_type'] == 3) {
									echo "Download";
								}
							?>
						</td>
						<td><?php echo 'Bs. ' . $val['Reward']['value']; ?></td>
						<td><?php echo $val['Redemption']['points']; ?></td>
						<td><?php echo $val['Redemption']['redeem_date']; ?></td>
					</tr>	
					<?php
							}
				  		}	
					?>			
				</tbody>   
			</table>
			<?php
				if ($Admindata['Admin']['type'] != 1) {
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'user',
								'action'     => 'export_rewards'
							),
							'name'  => 'frm_export',
							'class' => 'form-horizontal'
						)
					);
					echo $this->Form->hidden(
						'User.data',
						array(
							'value' => '',
							'id'    => 'info'
						)
					);
			?>
			<br/><br/>
			<input type="hidden" id="user_id" name="user_id" value="<?php echo @$this->params->pass[0]; ?>">&nbsp;
			<?php
					echo $this->html->link(
						__('<i class="icon-download-alt icon-white"></i><span> Export</span>'),
						'javascript:void(0);',
						array(
							'class'   => 'btn btn-primary',
							'escape'  => false,
							'onclick' => 'Export()'
						)
					);
				}
			?>
		</div>
	</div>
</div>
