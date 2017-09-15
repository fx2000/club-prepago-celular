<?php
/**
 * Reseller sales report view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Report
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
$sale =
	$this->requestAction('report/total_reseller_sales/from:' .
		@$_REQUEST['from_date'] . '/to:' .
		@$_REQUEST['to_date'] . '/operator:' .
		@$_REQUEST['operator'] . '/sponsor:' .
		@$_REQUEST['sponsor'] . '/reseller:' .
		@$_REQUEST['reseller']
	);
$recharges =
	$this->requestAction('report/total_reseller_recharges/from:' .
		@$_REQUEST['from_date'] . '/to:' .
		@$_REQUEST['to_date'] . '/operator:' .
		@$_REQUEST['operator'] . '/sponsor:' .
		@$_REQUEST['sponsor'] . '/reseller:' .
		@$_REQUEST['reseller']
	);
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
			c_value = unescape(c_value.substring(c_start,c_end));
		}
		return c_value;
	}

	function Export() {

		var datatableInfo = getCookie('ClubPrepago_<?php echo strtolower(urlencode($this->params['pass'][0])); ?>');

		$('#info').val(datatableInfo);
		document.frm_export.submit();
	}

	function searchData(frmObj) {
		document.getElementById('reseller') . value ='';
		document.getElementById('from_date') . value ='';
		document.getElementById('to_date') . value ='';
		document.getElementById('operator') . value ='';
		frmObj.submit();
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

		<!-- Page icon and title -->
		<div class="box-header well" data-original-title>
			<h2><i class="icon-list-alt"></i><?php echo __(' Reseller Sales'); ?></h2>
		</div>

		<!-- Generate table -->
		<div class="box-content " style="margin-bottom:20px;">

			<!-- Generate summary -->
			<div style="float: left;">
				<span class="blue" style="font-size: 16px;font-weight: bold;line-height:30px;">
					<?php echo __('Total Sales: Bs. ') . (($sale != '') ? number_format($sale, 2) : '0.00'); ?>&nbsp;&nbsp;&nbsp;
				</span>
				<br/>
				<span class="blue" style="font-size: 16px;font-weight: bold;line-height:30px;">
					<?php echo __('Recharges: ') . (($recharges != '') ? $recharges : '0'); ?>&nbsp;&nbsp;&nbsp;
				</span>
				<br/>
			</div>

			<!-- Generate search tools -->
			<div class="pull-right">
				<form action="" class="form-horizontal" method="get" id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<select name="sponsor" class="input-medium" data-rel=tooltip data-original-title="<?php echo __('Select a Sponsor'); ?>" onchange="searchData(this.form);">
					<option value=""><?php echo __('All Sponsors'); ?></option>
					<?php
						$Sponsordata = $this->requestAction('sponsor/GetSponsor');

						foreach ($Sponsordata as $key => $sponsor) {
							$selected = ($key==$_REQUEST['sponsor']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $sponsor . "</option>";
						}
					?>
				</select>
				<select name="reseller" id="reseller" class="input-medium" data-rel=tooltip
					data-original-title="<?php echo __('Select a Reseller'); ?>" onchange="this.form.submit();">
					<option value=""><?php echo __('All Resellers'); ?></option>
					<?php
						$Resellerdata = $this->requestAction('reseller/GetReseller/' . $_REQUEST['sponsor']);

						foreach ($Resellerdata as $key=>$sponsor) {
							$selected = ($key == $_REQUEST['reseller']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $sponsor . "</option>";
						}
					?>
				</select>
				<input type="date" class="input-small datepicker" id="from_date"
					name="from_date" data-rel='tooltip' data-original-title="<?php echo __('From Date'); ?>"
					placeholder="From Date" value="<?php echo @$_REQUEST['from_date'];?>">&nbsp;
				<input type="date" class="input-small datepicker" id="to_date"
					name="to_date"  placeholder="To Date" data-rel='tooltip' data-original-title="<?php echo __('To Date'); ?>"'
					value="<?php echo @$_REQUEST['to_date'];?>">&nbsp;
				<select name="operator" id="operator"   class="input-small" data-rel=tooltip data-original-title="<?php echo __('Select a Mobile Operator'); ?>">
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
							'class' => 'btn btn-primary pull-right',
							'div'   => false
						)
					);
				?>

				<!-- Some blank space for the search button -->
				&nbsp;&nbsp;
				<?php
					echo $this->Form->end();
				?>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Represdatatable">
				<thead>
					<tr>
						<th><?php echo __('Sponsor'); ?></th>
						<th><?php echo __('Reseller'); ?></th>
						<th class="hidden-phone"><?php echo __('Mobile Operator'); ?></th>
						<th class="hidden-phone"><?php echo __('Phone Number'); ?></th>
						<th align="center"><?php echo __('Amount'); ?></th>
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
								if ($val['Sponsor']['delete_status'] == 0) {
									echo $this->Html->link(
										$val['Sponsor']['name'],
										array(
											'controller' => 'sponsor',
											'action'     => 'view',
											base64_encode($val['Sponsor']['id'])
										)
									);
								} else {
									echo $val['Sponsor']['name'];
								}
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
						<td class="hidden-phone"><?php echo $val['Operator']['name']; ?></td>
						<td class="hidden-phone"><?php echo $val['Recharge']['phone_number']; ?></td>
						<td align="center"><?php echo 'Bs. ' . $val['Recharge']['amount']; ?></td>
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
			<div class="box-content">
				<?php
					echo $this->Form->create(
						'',
						array(
							'url'   => array(
								'controller' => 'report',
								'action'     => 'export_reseller_sales'
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
				<br/><br/>
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
