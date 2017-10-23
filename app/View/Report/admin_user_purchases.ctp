<?php
/**
 * User purchases view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Report
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
		document.getElementById('from_date') . value ='';
		document.getElementById('to_date') . value ='';
		document.getElementById('user') . value ='';
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
			<h2><i class="icon-list-alt"></i><?php echo __(' User Purchases'); ?></h2>
		</div>

		<!-- Generate table -->
		<div class="box-content">

			<!-- Generate search tools -->
			<div class="pull-right"><form action="" class="form-horizontal" method="get"
				id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<input type="date" class="input-small datepicker" id="from_date" name="from_date"
					data-rel='tooltip' data-original-title='From date' placeholder="<?php echo __('From Date'); ?>"
					value="<?php echo @$_REQUEST['from_date'];?>">&nbsp;
				<input type="date" class="input-small datepicker" id="to_date" name="to_date"
					data-rel='tooltip' data-original-title='To date' placeholder="<?php echo __('To Date'); ?>"
					value="<?php echo @$_REQUEST['to_date'];?>">&nbsp;
				<select name="user" class="input-medium" data-rel=tooltip data-original-title="<?php echo __('Select a User'); ?>" onchange="searchData(this.form);">
					<option value=""><?php echo __('All Users'); ?></option>
					<?php
						$userData = $this->requestAction('user/GetUser');

						foreach ($userData as $key => $user) {
							$selected = ($key == $_REQUEST['user']) ? 'selected="selected"' : '';
							echo "<option value='" . $key . "' " . $selected . ">" . $user . "</option>";
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
				<?php
					echo $this->Form->end();
				?>
			</div>

			<table class="table table-striped table-bordered bootstrap-datatable Repupurdatatable">
				<thead>
					<tr>
						<th><?php echo __('Payment Number'); ?></th>
						<th><?php echo __('User'); ?></th>
						<th class="hidden-phone"><?php echo __('Type'); ?></th>
						<th class="hidden-phone"><?php echo __('Bank'); ?></th>
						<th class="hidden-phone"><?php echo __('Reference'); ?></th>
						<th><?php echo __('Approval Date'); ?></th>
						<th><?php echo __('Payment Amount'); ?></th>
						<th class="hidden-phone"><?php echo __('Tax Deducted'); ?></th>
						<th class="hidden-phone"><?php echo __('Balance Added'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($userdata)) {

							foreach($userdata as $val) {

								if ($val['Payment']['payment_method'] == 1) {
									$bank = $this->requestAction(
										array(
											'controller' => 'Banks',
											'action'     => 'getBankName',
											$val['Payment']['bank_id']
										)
									);
					?>
					<tr>
						<td style="vertical-align:middle;">
							<?php
								if (strlen($val['Payment']['id']) < 6) {
									$filler = 6 - strlen($val['Payment']['id']);

									for ($i = 0; $i < $filler; $i++) {
										echo '0';
									}
									echo $val['Payment']['id'];
								} else {
									echo $val['Payment']['id'];
								}
							?>
						</td>
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
						<td class="hidden-phone">
							<?php
									echo $val['Payment']['payment_method'] == 1 ? __('Bank Deposit') : __('Credit card');
							?>
						</td>
						<td class="hidden-phone"><?php echo @$bank['Bank']['bank_name']; ?></td>
						<td class="hidden-phone"><?php echo $val['Payment']['reference_number']; ?></td>
						<td align="center">
							<?php
									echo $val['Payment']['payment_method'] == 1 ? $val['Payment']['change_status_date'] : '';
							?>
						</td>
						<td><?php echo 'Bs. ' . $val['Payment']['amount']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['tax']; ?></td>
						<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['amount_credited']; ?></td>
						</tr>
					<?php
								} else {
									$paymentDetail = $this->requestAction(
										'admin/payments/GetTransDetail/' . $val['Payment']['reference_number']);
					?>
						<tr>
							<td style="vertical-align:middle;">
								<?php
									if (strlen($val['Payment']['id']) < 6) {
										$filler = 6 - strlen($val['Payment']['id']);

										for ($i = 0; $i < $filler; $i++) {
											echo '0';
										}
										echo $val['Payment']['id'];
									} else {
										echo $val['Payment']['id'];
									}
								?>
							</td>
							<td>
								<?php
									if($val['User']['delete_status'] == 0) {
										echo $this->Html->link(
											$val['User']['name'],
											array(
												'controller' =>'user',
												'action'     =>'view',
												base64_encode($val['User']['id'])
											)
										);
									} else {
										echo $val['User']['name'];
									}
								?>
							</td>
							<td class="hidden-phone">
								<?php
									echo $val['Payment']['payment_method'] == 1 ? 'Bank Deposit' : 'Credit card';
								?>
							</td>
							<td class="hidden-phone"><?php echo '-'; ?></td>
							<td class="hidden-phone"><?php echo $paymentDetail['Transaction']['transaction_id']; ?></td>
							<td align="center"><?php echo $val['Payment']['change_status_date']; ?></td>
							<td><?php echo 'Bs. ' . $val['Payment']['amount']; ?></td>
							<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['tax']; ?></td>
							<td class="hidden-phone"><?php echo 'Bs. ' . $val['Payment']['amount_credited']; ?></td>
						</tr>
					<?php
								}

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
								'action'     => 'export_user_purchases'
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
				<input type="hidden" id="from_date" name="from_date" value="<?php echo @$_REQUEST['from_date']; ?>">&nbsp;
				<input type="hidden" id="to_date" name="to_date" value="<?php echo @$_REQUEST['to_date']; ?>">&nbsp;
				<input type="hidden" id="user" name="user" value="<?php echo @$_REQUEST['user']; ?>">&nbsp;
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
