<?php
/**
 * Points redemtion report view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Report
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
$totalPoints = $this->requestAction('report/totalRedemptionPoint/' . @$_REQUEST['from_date'] . '/' . @$_REQUEST['to_date']);
?>
<script>

	function getCookie(c_name) {
		var c_value = document.cookie;//alert(c_value);
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
		var datatableInfo = getCookie('SpryMedia_DataTables_clubPrepago_redemption');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}
</script>
<div>
	<ul class="breadcrumb">
		<li>
			<?php
				echo $this->Html->link(
					'Home',
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
					'Point Redemption',
					array(
						'controller' => 'report',
						'action'     => 'redemption'
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
			$_SESSION['alert']='';
		?>
	</strong>
</div>
<?php
	}
?>
<div class="row-fluid ">	
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i> Point Redemption</h2>
		</div>
		<div class="box-content"  style="margin-bottom:20px;">
			<div style="float: left;">
				<span class="blue" style="font-size: 16px;font-weight: bold;">
					Total points redeemed for awards: <?php echo ($totalPoints!=null)?$totalPoints:'0'; ?>
				</span>
			</div>
			<div class="pull-right">
				<form action="" class="form-horizontal" method="get" id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<input type="date" class="input-small datepicker" id="from_date"
					name="from_date" data-rel='tooltip' data-original-title='From date'
					placeholder="From Date" value="<?php echo @$_REQUEST['from_date'];?>">&nbsp;
				<input type="date" class="input-small datepicker" id="to_date" name="to_date"
					placeholder="To Date" data-rel='tooltip' data-original-title='To date'
					value="<?php echo @$_REQUEST['to_date'];?>">&nbsp;
				<?php
					echo $this->Form->Submit(
						'Search',
						array(
							'class' => 'btn btn-primary pull-right',
							'div'   => false
						)
					);
				?>&nbsp;&nbsp;
				<?php
					echo $this->Form->end();
				?>
			</div>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Redemptiondatatable">
				<thead>
					<tr>
						<th>Username</th>
						<th >Mobile No.</th>
						<th class="hidden-phone ">Redeemed For</th>
						<th >Point</th>
						<th class="hidden-phone ">Redemption Code</th>
						<th class="hidden-phone ">Date</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {
						
							foreach($userdata as $val){
						
								if (!empty($val['Redemption']['redemption_code'])) {
									$recharge = $this->requestAction(
										array(
											'controller'=>'Recharge',
											'action'=>'getRechargeDetailById',
											$val['Redemption']['redemption_code']
										)
									);
								}
					?>
					<tr>
						<td><?php echo $val['User']['name']; ?></td>
						<td><?php echo $recharge['Recharge']['mobile_no']; ?></td>
						<td class="hidden-phone">
							<?php 
								if ($val['Redemption']['redeem_for'] == 1) {
									echo 'Recharge';
								} else if ($val['Redemption']['redeem_for'] == 2) {
									echo 'Customer Support';
								} else if ($val['Redemption']['redeem_for'] == 3) {
									echo 'Download';
								}
							?>
						</td>
						<td><?php echo $val['Redemption']['point']; ?></td>
						<td  class="hidden-phone">
							<?php 
								if ($val['Redemption']['redeem_for'] != 1) {
									echo '-';
								} else {
									echo $val['Redemption']['redemption_code'];
								}
							?>
						</td>
						<td class="hidden-phone">
							<?php 
								echo date('d M, Y', strtotime($val['Redemption']['datetime']));
							?>
						</td>	
					</tr>	
					<?php
							}
						}	
					?>	
				</tbody>
			</table>
			<div class="box-content">
				<?php
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'report',
								'action'     => 'exportredeem'
							),
							'name'  => 'frm_export',
							'class' => 'form-horizontal'
						)
					);
				?>
				<?php
					echo $this->Form->hidden(
						'Report.data',
						array(
							'value' => '',
							'id'    => 'info'
						)
					);
				?>
				<input type="hidden" id="from_date" name="from_date" value="<?php echo @$_REQUEST['from_date']; ?>">&nbsp;
				<input type="hidden" id="to_date" name="to_date" value="<?php echo @$_REQUEST['to_date']; ?>">&nbsp;
				<?php
					echo $this->html->link(
						'<i class="icon-download-alt icon-white"></i><span class="hidden-phone"> Export in CSV</span> ',
						'javascript:void(0);',
						array(
							'class'   => 'btn btn-primary',
							'escape'  => false,
							'onclick' => 'Export()'
						)
					);
				?>
			</div>
		</div>
	</div>
</div>
