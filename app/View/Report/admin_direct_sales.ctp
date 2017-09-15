<?php
/**
 * Direct sales report view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Report
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
$netSale =
	$this->requestAction('report/net_user_sales/from:' .
		@$_REQUEST['from_date'] . '/to:' .
		@$_REQUEST['to_date'] . '/operator:' .
		@$_REQUEST['operator'] . '/payment_method:' .
		@$_REQUEST['payment_method']
);
$grossSale =
	$this->requestAction('report/total_user_sales/from:' .
		@$_REQUEST['from_date'] . '/to:' .
		@$_REQUEST['to_date'] . '/operator:' .
		@$_REQUEST['operator'] . '/payment_method:' .
		@$_REQUEST['payment_method']
);
$recharges =
	$this->requestAction('report/total_user_recharges/from:' .
		@$_REQUEST['from_date'] . '/to:' .
		@$_REQUEST['to_date'] . '/operator:' .
		@$_REQUEST['operator'] . '/payment_method:' .
		@$_REQUEST['payment_method']
);
$pointAwarded =
	$this->requestAction('report/total_points_awarded/from:' .
		@$_REQUEST['from_date'] . '/to:' .
		@$_REQUEST['to_date'] . '/operator:' .
		@$_REQUEST['operator'] . '/payment_method:' .
		@$_REQUEST['payment_method']
);
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
			$_SESSION['alert']='';
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
			<h2><i class="icon-list-alt"></i><?php echo __(' Direct Sales'); ?></h2>
		</div>

		<!-- Generate table -->
		<div class="box-content " style="margin-bottom:20px;">

			<!-- Generate report summary -->
			<div style="float: left;">
				<span class="blue" style="font-size: 16px;font-weight: bold;line-height:30px;">
					<?php echo __('Gross Sales : Bs. ') . (($grossSale != '') ? number_format($grossSale, 2) : '0.00'); ?>
				</span>
				<br/>
				<span class="blue" style="font-size: 16px;font-weight: bold;line-height:30px;">
					<?php echo __('Net Sales : Bs. ') . (($netSale != '') ? number_format($netSale, 2) : '0.00'); ?>
				</span>
				<br/>
				<span class="blue" style="font-size: 16px;font-weight: bold;line-height:30px;">
					<?php echo __('Recharges : ') . (($recharges != '') ? $recharges : ''); ?>
				</span>
				<br/>
				<span class="blue" style="font-size: 16px;font-weight: bold;line-height:30px;">
					<?php echo __('Points Awarded : ') . (($pointAwarded != '') ? $pointAwarded : ''); ?>
				</span>
			</div>

			<!-- Generate search tools -->
			<div class="pull-right"><form action="" class="form-horizontal" method="get"
				id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<select name="payment_method" class="input-large" data-rel=tooltip data-original-title="<?php echo __('Select a Payment Method'); ?>">
					<option value="">
						<?php echo __('Payment method'); ?>
					</option>
					<option value='1' <?php echo ($_REQUEST['payment_method'] == '1') ? 'selected="selected"' : '';?>>
						<?php echo __('Prepaid Balance'); ?>
					</option>
					<option value='2' <?php echo ($_REQUEST['payment_method'] == '2') ? 'selected="selected"' : '';?>>
						<?php echo __('Credit Card'); ?>
					</option>
					<option value='3' <?php echo ($_REQUEST['payment_method'] == '3') ? 'selected="selected"' : '';?>>
						<?php echo __('Points'); ?>
					</option>
				</select>
				<input type="date" class="input-small datepicker" id="from_date" name="from_date"
					data-rel='tooltip' data-original-title='From date' placeholder="<?php echo __('From Date'); ?>"
					value="<?php echo @$_REQUEST['from_date'];?>">&nbsp;
				<input type="date" class="input-small datepicker" id="to_date" name="to_date"
					data-rel='tooltip' data-original-title='To date' placeholder="<?php echo __('To Date'); ?>"
					value="<?php echo @$_REQUEST['to_date'];?>">&nbsp;
				<select name="operator" class="input-small" data-rel=tooltip data-original-title="<?php echo __('Select a Mobile Operator'); ?>">
					<option value=""><?php echo __('Operator'); ?></option>
					<?php
						foreach ($Operatordata as $key => $operator) {
							$selected = ($key == $_REQUEST['operator']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $operator . "</option>";
						}
					?>
				</select>
				<?php
					echo $this->Form->Submit(
						__('Search'),
						array(
							'class'=>'btn btn-primary pull-right',
							'div'=>false
						)
					);
				?>

				<!-- Some blank space for the search button -->
				&nbsp;&nbsp;
				<?php echo $this->Form->end(); ?></div>
			</div>

			<!-- Generate info table -->
			<div style="clear:both"></div>
			<div class="box-content">
				<table class="table table-striped table-bordered bootstrap-datatable Repdirectdatatable">
					<thead>
						<tr>
							<th><?php echo __('User'); ?></th>
							<th class="hidden-phone "><?php echo __('Mobile Operator'); ?></th>
							<th class="hidden-phone "><?php echo __('Phone Number'); ?></th>
							<th class="hidden-phone "><?php echo __('Payment Method'); ?></th>
							<th align="center" ><?php echo __('Recharge Amount'); ?></th>
							<th align="center" class="hidden-phone"><?php echo __('Taxes'); ?></th>
							<th align="center" class="hidden-phone"><?php echo __('Total Amount'); ?></th>
							<th align="center" class="hidden-phone"><?php echo __('Points Awarded'); ?></th>
							<th><?php echo __('Date & Time'); ?></th>
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
									if ($val['User']['delete_status'] == 0) {
										echo $this->Html->link(
											$val['User']['name'],
											array(
												'controller' => 'user',
												'action'     => 'view',
												base64_encode($val['User']['id'])
											)
										);
									} else {
										echo $val['User']['name'];
									}
								?>
							</td>
							<td class="hidden-phone"><?php echo $val['Operator']['name']; ?></td>
							<td class="hidden-phone"><?php echo $val['Recharge']['phone_number']; ?></td>
							<td class="hidden-phone">
								<?php
									if ($val['Recharge']['payment_method'] == 1) {
										echo "Prepaid Balance";
									} else if ($val['Recharge']['payment_method'] == 2) {
										echo "Credit Card";
									} else if ($val['Recharge']['payment_method'] == 3) {
										echo "Reward Points";
									} else {
										echo $val['Recharge']['payment_method'];
									}
								?>
							</td>
							<td align="center" ><?php echo 'Bs. ' . $val['Recharge']['amount']; ?></td>
							<td align="center" class="hidden-phone"><?php echo 'Bs. ' . $val['Recharge']['tax_amount']; ?></td>
							<td align="center" class="hidden-phone"><?php echo 'Bs. ' . $val['Recharge']['total_amount']; ?></td>
							<td align="center" class="hidden-phone"><?php echo $val['Recharge']['points']; ?></td>
							<td>
								<?php
									echo $val['Recharge']['recharge_date'];
								?>
							</td>
						</tr>
						<?php
								}
							}
						?>
					</tbody>
				</table>

				<!-- Generate Export button -->
				<div class="box-content">
					<?php
						echo $this->Form->create(
							'',
							array(
								'url'   => array(
									'controller' => 'report',
									'action'     => 'export_direct_sales'
								),
								'name'  => 'frm_export',
								'class' => 'form-horizontal'
							)
						);
						echo $this->Form->hidden(
							'Report.data',
							array(
								'value' => '',
								'id'    => 'info'
							)
						);
					?>
					<input type="hidden" id="payment_method1" name="payment_method" value="<?php echo @$_REQUEST['payment_method']; ?>">&nbsp;
					<input type="hidden" id="from_date" name="from_date" value="<?php echo @$_REQUEST['from_date']; ?>">&nbsp;
					<input type="hidden" id="to_date" name="to_date" value="<?php echo @$_REQUEST['to_date']; ?>">&nbsp;
					<input type="hidden" id="operator" name="operator" value="<?php echo @$_REQUEST['operator']; ?>">&nbsp;
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
					?>
				</div>
			</div>
		</div>
	</div>
</div>
